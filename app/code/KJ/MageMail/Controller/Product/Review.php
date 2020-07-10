<?php
namespace KJ\MageMail\Controller\Product;

use KJ\MageMail\Controller\Product;

class Review extends Product
{
    protected $_pageFactory;

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
        \Magento\Framework\DataObjectFactory $dataObjectFactory, \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Filesystem\DirectoryList $dirManager, \Magento\Framework\App\CacheInterface $cacheManager,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Filesystem\Io\FileFactory $ioFileFactory)
    {
        parent::__construct($context, $customerCustomerFactory, $storeManager, $reviewReviewFactory, $reviewRatingFactory, $reviewRatingOptionVoteFactory, $mageMailHelper, $catalogProductFactory, $logger, $catalogImageHelper, $groupedProductProductTypeGroupedFactory, $configurableProductProductTypeConfigurableFactory, $dataObjectFactory, $jsonHelper, $dirManager, $cacheManager, $ioFileFactory);
        $this->_pageFactory = $pageFactory;
    }


    public function execute()
    {
        $page = $this->_pageFactory->create();
        $page->getConfig()->getTitle()->set('Product Review');
        return $page;
    }
}