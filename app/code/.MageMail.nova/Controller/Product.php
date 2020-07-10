<?php
namespace KJ\MageMail\Controller;

use Magento\Translation\Model\Inline\CacheManager;

/**
 * Magento web services triggered by https://v2.magemail.co/skin/js/magento.js functionality.
 * @package KJ\MageMail\Controller
 */
abstract class Product extends \Magento\Framework\App\Action\Action
{
    protected $_customer;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerCustomerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $reviewReviewFactory;

    /**
     * @var \Magento\Review\Model\RatingFactory
     */
    protected $reviewRatingFactory;

    /**
     * @var \Magento\Review\Model\Rating\Option\VoteFactory
     */
    protected $reviewRatingOptionVoteFactory;

    /**
     * @var \KJ\MageMail\Helper\Data
     */
    protected $mageMailHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $catalogProductFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $catalogImageHelper;

    /**
     * @var \Magento\GroupedProduct\Model\Product\Type\GroupedFactory
     */
    protected $groupedProductProductTypeGroupedFactory;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory
     */
    protected $configurableProductProductTypeConfigurableFactory;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @var \Magento\Framework\Filesystem\Io\FileFactory
     */
    protected $ioFileFactory;

    protected $_jsonHelper;

    protected $_dirManager;

    protected $_cacheManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerCustomerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Review\Model\ReviewFactory $reviewReviewFactory,
        \Magento\Review\Model\RatingFactory $reviewRatingFactory,
        \Magento\Review\Model\Rating\Option\VoteFactory $reviewRatingOptionVoteFactory,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        \Magento\Catalog\Model\ProductFactory $catalogProductFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Helper\Image $catalogImageHelper,
        \Magento\GroupedProduct\Model\Product\Type\GroupedFactory $groupedProductProductTypeGroupedFactory,
        \Magento\ConfigurableProduct\Model\Product\Type\ConfigurableFactory $configurableProductProductTypeConfigurableFactory,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Filesystem\DirectoryList $dirManager,
        \Magento\Framework\App\CacheInterface $cacheManager,
        \Magento\Framework\Filesystem\Io\FileFactory $ioFileFactory
    ) {
        $this->_cacheManager = $cacheManager;
        $this->_dirManager = $dirManager;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->ioFileFactory = $ioFileFactory;
        $this->customerCustomerFactory = $customerCustomerFactory;
        $this->storeManager = $storeManager;
        $this->reviewReviewFactory = $reviewReviewFactory;
        $this->reviewRatingFactory = $reviewRatingFactory;
        $this->reviewRatingOptionVoteFactory = $reviewRatingOptionVoteFactory;
        $this->mageMailHelper = $mageMailHelper;
        $this->_jsonHelper = $jsonHelper;
        $this->catalogProductFactory = $catalogProductFactory;
        $this->logger = $logger;
        $this->catalogImageHelper = $catalogImageHelper;
        $this->groupedProductProductTypeGroupedFactory = $groupedProductProductTypeGroupedFactory;
        $this->configurableProductProductTypeConfigurableFactory = $configurableProductProductTypeConfigurableFactory;
        parent::__construct(
            $context
        );
    }


    /**
     * @return \Magento\Customer\Model\Customer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getCustomer()
    {
        if (isset($this->_customer)) {
            return $this->_customer;
        }

        $email = $this->getRequest()->getParam('email');

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->customerCustomerFactory->create();
        $customer->setData('website_id', $this->storeManager->getWebsite()->getId());
        $customer->loadByEmail($email);

        $this->_customer = $customer;
        return $this->_customer;
    }

    protected function _saveReviewAction()
    {
        $this->_validateBeforeSavingReview();
        $product = $this->_initProduct();
        $reviewData = $this->_buildReviewData();

        /** @var \Magento\Review\Model\Review $review */
        $review = $this->reviewReviewFactory->create();
        if ($this->getRequest()->getParam('review_id')) {
            $review->load($this->getRequest()->getParam('review_id'));
        }

        foreach ($reviewData as $key => $val) {
            $review->setData($key, $val);
        }

        $customer = $this->_getCustomer();

        $review->setEntityId($review->getEntityIdByCode(\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE))
            ->setEntityPkValue($product->getId())
            ->setStatusId(\Magento\Review\Model\Review::STATUS_PENDING)
            ->setData('customer_id', $customer->getId())
            ->setData('store_id', $this->storeManager->getStore()->getId())
            ->setData('stores', array($this->storeManager->getStore()->getId()));

        $review->save();
        $this->_saveRatings($customer, $product, $review);
        $review->aggregate();

        $this->_jsonResponse(array(
            "success"   => true,
            "review_id" => $review->getId(),
        ));
    }

    /**
     * @param $customer Mage_Customer_Model_Customer
     * @param $product Mage_Catalog_Model_Product
     * @param $review Mage_Review_Model_Review
     */
    protected function _saveRatings($customer, $product, $review)
    {
        // Have to delete them so that the addOptionVote below won't create dups
        if ($this->getRequest()->getParam('review_id')) {
            $this->_deleteExistingRatingVotes($review);
        }

        $ratingsData = $this->_buildRatingsData();
        foreach ($ratingsData as $ratingId => $optionId) {
            /** @var \Magento\Review\Model\Rating $rating */
            $rating = $this->reviewRatingFactory->create();
            $rating->setData('rating_id', $ratingId)
                ->setData('review_id', $review->getId())
                ->setData('customer_id', $customer->getId());

            $rating->addOptionVote($optionId, $product->getId());
        }
    }

    /**
     * @param $review Mage_Review_Model_Review
     * @throws \Exception
     */
    protected function _deleteExistingRatingVotes($review)
    {
        $ratingCollection = $this->reviewRatingOptionVoteFactory->create()
            ->getResourceCollection()
            ->setReviewFilter($review->getId())
            ->addOptionInfo()
            ->load()
            ->addRatingOptions();

        /** @var \Magento\Review\Model\Rating\Option\Vote $ratingOptionVote */
        foreach ($ratingCollection as $ratingOptionVote) {
            $ratingOptionVote->delete();
        }
    }

    /**
     * @return \KJ\MageMail\Helper\Data
     */
    protected function help()
    {
        return $this->mageMailHelper;
    }

    protected function _validateBeforeSavingReview()
    {
        if (! $this->_isReviewTokenValid()) {
            throw new \Exception($this->help()->langAuthenticationFail());
        }

        if (! $this->getRequest()->getParam('nickname')) {
            throw new \Exception("Nickname shouldn't be empty");
        }

        if (! $this->getRequest()->getParam('product_id')) {
            throw new \Exception("Missing product_id");
        }
    }

    protected function _isReviewTokenValid()
    {
        $token = $this->getRequest()->getPost('token');
        $email = $this->getRequest()->getParam('email');
        $apiKey = $this->help()->getApiKey();
        $tokenShouldBe = substr(md5("review_request" . $email . $apiKey), 0, 10);

        return ($token == $tokenShouldBe);
    }

    protected function _initProduct()
    {
        // Borrowing this from Mage_Review_ProductController::_initProduct()
        $this->_eventManager->dispatch('review_controller_product_init_before', array('controller_action'=>$this));

        $productId = $this->getRequest()->getPost('product_id');

        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->catalogProductFactory->create();
        $product->setData('store_id', $this->storeManager->getStore()->getId());
        $product->load($productId);

        // Borrowing this from Mage_Review_ProductController::_initProduct()
        try {
            $this->_eventManager->dispatch('review_controller_product_init', array('product'=>$product));
            $this->_eventManager->dispatch('review_controller_product_init_after', array(
                'product'           => $product,
                'controller_action' => $this
            ));
        } catch (Mage_Core_Exception $e) {
            $this->logger->critical($e);
            return false;
        }

        return $product;
    }

    protected function _buildReviewData()
    {
        $comment = $this->getRequest()->getParam('comment') ? $this->getRequest()->getParam('comment') : "No comment";
        $title = $this->getRequest()->getParam('title') ? $this->getRequest()->getParam('title') : "No comment";

        return array(
            "detail"    => $comment,
            "title"     => $title,
            "nickname"  => $this->getRequest()->getPost('nickname'),
        );
    }

    protected function _buildRatingsData()
    {
        $ratingValue = $this->getRequest()->getPost('rating');

        $ratingCollection = $this->reviewRatingFactory->create()
            ->getResourceCollection()
            ->addEntityFilter('product')
            ->setPositionOrder()
            ->setStoreFilter($this->storeManager->getStore()->getId())
            ->addRatingPerStoreName($this->storeManager->getStore()->getId())
            ->load();

        $data = array();
        /** @var \Magento\Review\Model\Rating $rating */
        foreach ($ratingCollection as $rating) {
            $options = $rating->getOptions();

            foreach ($options as $option) {
                /** @var \Magento\Review\Model\Rating\Option $option */

                if ($option->getValue() == $ratingValue) {
                    $data[$rating->getData('rating_id')] = $option->getData('option_id');
                }
            }

        }

        return $data;
    }



    protected function _imageAction()
    {
        $imagePath = $this->_getImagePath();
        if ($this->help()->dontRedirectImages()) {
            $typeCode = exif_imagetype($imagePath);
            if ($typeCode == IMAGETYPE_GIF) {
                $type = 'gif';
            } elseif ($typeCode == IMAGETYPE_BMP) {
                $type = 'bmp';
            } elseif ($typeCode == IMAGETYPE_PNG) {
                $type = 'png';
            } else {
                $type = 'jpeg';
            }
            $imageContents = file_get_contents($imagePath);
            $this->getResponse()->setHeader('Content-type', "image/$type");
            $this->getResponse()->setBody($imageContents);
            return $this;
        }

        $this->getResponse()->setRedirect($imagePath);

        return $this;

    }

    protected function _placeholderImagePath()
    {
        /** @var \Magento\Catalog\Model\Product $model */
        $model = $this->catalogProductFactory->create();
        return $model->getSmallImageUrl($this->_getWidth(), $this->_getHeight());
    }

    protected function _getImagePath()
    {
        $cached = $this->_cacheManager->load($this->_getCacheKey());
        if ($cached && $this->_fileExists($cached)) {
            return $cached;
        }

        $imageUrl = $this->_fetchImageUrl();
        $this->_cacheManager->save($imageUrl, $this->_getCacheKey(), array('config'));

        return $imageUrl;
    }

    protected function _getCacheKey()
    {
        return "magemail_product_" . $this->_getProductId() . "_image_" . $this->_getImageType() . "_path_" . $this->_getWidth() . "x" . $this->_getHeight() . "_option" . $this->getRequest()->getParam('option');
    }

    protected function _getProductId()
    {
        $productId = (int)$this->getRequest()->getParam('product_id');
        if (! $productId) {
            throw new \Exception("Missing product_id");
        }

        return $productId;
    }

    protected function _getImageType()
    {
        $imageType = $this->getRequest()->getParam('image_type');
        $imageType = preg_replace("/[^a-zA-Z0-9_]+/", "", $imageType);
        if (! $imageType) {
            return "small_image";
        }

        return 'product_' . $imageType;
    }

    protected function _getWidth()
    {
        return ($this->getRequest()->getParam('width')) ? (int)$this->getRequest()->getParam('width') : 100;
    }

    protected function _getHeight()
    {
        return ($this->getRequest()->getParam('height')) ? (int)$this->getRequest()->getParam('height') : 100;
    }

    protected function _fetchImageUrl()
    {
        $product = $this->catalogProductFactory->create()->load($this->_getProductId());
        if (! $product->getData('small_image') || $product->getData('small_image') == 'no_selection') {
            $parent = $this->_getParentProduct($product);
            if ($parent) {
                $product = $parent;
            }
        }

        $helper = $this->catalogImageHelper;
        $helper->init($product, $this->_getImageType())->resize($this->_getWidth(), $this->_getHeight());
        $imageUrl = $helper->getUrl();

        $data = $this->dataObjectFactory->create();
        $data->setData('image_url', $imageUrl);

        $smallImageUrl = $helper->init($product, $this->_getImageType())->getUrl();

        $data->setData('unresized_small_image_url', $smallImageUrl);

        $this->_eventManager->dispatch('kj_magemail_product_image_fetch_after', array(
            'data'                  => $data,
            'product'               => $product,
            'requested_product_id'  => $this->_getProductId(),
        ));

        return $data->getData('image_url');
    }

    /**
     * @param $child Mage_Catalog_Model_Product
     */
    protected function _getParentProduct($child)
    {
        if ( $child->getTypeId() != "simple") {
            return null;
        }

        /** @var \Magento\GroupedProduct\Model\Product\Type\Grouped $model */
        $model = $this->groupedProductProductTypeGroupedFactory->create();
        $parentIds = $model->getParentIdsByChild($child->getId());
        if ($parentIds) {
            $firstParentId = $parentIds[0];
            return $this->catalogProductFactory->create()->load($firstParentId);
        }

        /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable $model */
        $model = $this->configurableProductProductTypeConfigurableFactory->create();
        $parentIds = $model->getParentIdsByChild($child->getId());
        if ($parentIds) {
            $firstParentId = $parentIds[0];
            return $this->catalogProductFactory->create()->load($firstParentId);
        }

        return null;
    }

    protected function _fileExists($imageUrl)
    {
        $parts = explode("/media/", $imageUrl);
        if (! isset($parts[1])) {
            return false;
        }

        $imagePath = $this->_dirManager->getPath('media') . "/" . $parts[1];

        $io = $this->ioFileFactory->create();
        if ($io->fileExists($imagePath)) {
            return true;
        }

        return false;
    }

    protected function _jsonResponse($data)
    {
        $response = $this->getResponse();

        $response->clearHeaders();
        $response->setHeader("Content-type", "application/json");

        $response->setBody($this->_jsonHelper->jsonEncode($data));
    }
}