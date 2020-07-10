<?php
namespace KJ\MageMail\Controller;

/**
 * The Json API classes take a Json platform-independent request and returns the requested entities in Json format.
 * @package KJ\MageMail\Controller
 */
abstract class JsonApi extends Api
{
    /**
     * @var \KJ\MageMail\Model\Api\CategoryFactory
     */
    protected $mageMailApiCategoryFactory;

    /**
     * @var \KJ\MageMail\Model\Api\QuoteFactory
     */
    protected $mageMailApiQuoteFactory;

    /**
     * @var \KJ\MageMail\Model\Api\QuoteItemFactory
     */
    protected $mageMailApiQuoteItemFactory;

    /**
     * @var \KJ\MageMail\Model\Api\CouponFactory
     */
    protected $mageMailApiCouponFactory;

    /**
     * @var \KJ\MageMail\Model\Api\OrderFactory
     */
    protected $mageMailApiOrderFactory;

    /**
     * @var \KJ\MageMail\Model\Api\StockItemFactory
     */
    protected $mageMailApiStockItemFactory;

    /**
     * @var \KJ\MageMail\Model\Api\OrderItemFactory
     */
    protected $mageMailApiOrderItemFactory;

    /**
     * @var \KJ\MageMail\Model\Api\WishlistFactory
     */
    protected $mageMailApiWishlistFactory;

    /**
     * @var \KJ\MageMail\Model\Api\WishlistItemFactory
     */
    protected $mageMailApiWishlistItemFactory;

    /**
     * @var \KJ\MageMail\Model\Api\OrderStatusFactory
     */
    protected $mageMailApiOrderStatusFactory;

    /**
     * @var \KJ\MageMail\Model\Api\AheadworksSubscriptionFactory
     */
    protected $mageMailApiAheadworksSubscriptionFactory;

    /**
     * @var \KJ\MageMail\Model\Api\CustomerRewardsFactory
     */
    protected $mageMailApiCustomerRewardsFactory;

    /**
     * @var \KJ\MageMail\Model\Api\OrderRewardsFactory
     */
    protected $mageMailApiOrderRewardsFactory;

    /**
     * @var \KJ\MageMail\Model\Api\ProductFactory
     */
    protected $mageMailApiProductFactory;

    /**
     * @var \KJ\MageMail\Model\Api\StoreFactory
     */
    protected $mageMailApiStoreFactory;

    /**
     * @var \KJ\MageMail\Model\Api\CustomerFactory
     */
    protected $mageMailApiCustomerFactory;

    /**
     * @var \KJ\MageMail\Model\Api\SubscriberFactory
     */
    protected $mageMailApiSubscriberFactory;

    /**
     * @var \KJ\MageMail\Model\Api\CustomerGroupFactory
     */
    protected $mageMailApiCustomerGroupFactory;

    /**
     * @var \KJ\MageMail\Model\Api\SalesRuleFactory
     */
    protected $mageMailApiSalesRuleFactory;

    /**
     * @var \KJ\MageMail\Model\Api\RecommendationFactory
     */
    protected $mageMailApiRecommendationFactory;

    /**
     * @var \KJ\MageMail\Model\Api\PassbookLinkFactory
     */
    protected $mageMailApiPassbookLinkFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\SalesRule\Model\RuleFactory $salesRuleRuleFactory,
        \Magento\Framework\Data\Collection\AbstractDbFactory $collectionAbstractDbFactory,
        \Magento\Framework\Filesystem\Io\FileFactory $ioFileFactory,
        \KJ\MageMail\Model\Api\CategoryFactory $mageMailApiCategoryFactory,
        \KJ\MageMail\Model\Api\QuoteFactory $mageMailApiQuoteFactory,
        \KJ\MageMail\Model\Api\QuoteItemFactory $mageMailApiQuoteItemFactory,
        \KJ\MageMail\Model\Api\CouponFactory $mageMailApiCouponFactory,
        \KJ\MageMail\Model\Api\OrderFactory $mageMailApiOrderFactory,
        \KJ\MageMail\Model\Api\StockItemFactory $mageMailApiStockItemFactory,
        \KJ\MageMail\Model\Api\OrderItemFactory $mageMailApiOrderItemFactory,
        \KJ\MageMail\Model\Api\WishlistFactory $mageMailApiWishlistFactory,
        \KJ\MageMail\Model\Api\WishlistItemFactory $mageMailApiWishlistItemFactory,
        \KJ\MageMail\Model\Api\OrderStatusFactory $mageMailApiOrderStatusFactory,
        \KJ\MageMail\Model\Api\AheadworksSubscriptionFactory $mageMailApiAheadworksSubscriptionFactory,
        \KJ\MageMail\Model\Api\CustomerRewardsFactory $mageMailApiCustomerRewardsFactory,
        \KJ\MageMail\Model\Api\OrderRewardsFactory $mageMailApiOrderRewardsFactory,
        \KJ\MageMail\Model\Api\ProductFactory $mageMailApiProductFactory,
        \KJ\MageMail\Model\Api\StoreFactory $mageMailApiStoreFactory,
        \KJ\MageMail\Model\Api\CustomerFactory $mageMailApiCustomerFactory,
        \KJ\MageMail\Model\Api\SubscriberFactory $mageMailApiSubscriberFactory,
        \KJ\MageMail\Model\Api\PassbookLinkFactory $mageMailApiPassbookLinkFactory,
        \KJ\MageMail\Model\Api\CustomerGroupFactory $mageMailApiCustomerGroupFactory,
        \KJ\MageMail\Model\Api\SalesRuleFactory $mageMailApiSalesRuleFactory,
        \KJ\MageMail\Model\Api\RecommendationFactory $mageMailApiRecommendationFactory,
        \Magento\SalesRule\Model\Coupon\Massgenerator $massgenerator
    ) {
        $this->mageMailApiCategoryFactory = $mageMailApiCategoryFactory;
        $this->mageMailApiQuoteFactory = $mageMailApiQuoteFactory;
        $this->mageMailApiQuoteItemFactory = $mageMailApiQuoteItemFactory;
        $this->mageMailApiCouponFactory = $mageMailApiCouponFactory;
        $this->mageMailApiOrderFactory = $mageMailApiOrderFactory;
        $this->mageMailApiStockItemFactory = $mageMailApiStockItemFactory;
        $this->mageMailApiOrderItemFactory = $mageMailApiOrderItemFactory;
        $this->mageMailApiWishlistFactory = $mageMailApiWishlistFactory;
        $this->mageMailApiWishlistItemFactory = $mageMailApiWishlistItemFactory;
        $this->mageMailApiOrderStatusFactory = $mageMailApiOrderStatusFactory;
        $this->mageMailApiAheadworksSubscriptionFactory = $mageMailApiAheadworksSubscriptionFactory;
        $this->mageMailApiCustomerRewardsFactory = $mageMailApiCustomerRewardsFactory;
        $this->mageMailApiOrderRewardsFactory = $mageMailApiOrderRewardsFactory;
        $this->mageMailApiProductFactory = $mageMailApiProductFactory;
        $this->mageMailApiStoreFactory = $mageMailApiStoreFactory;
        $this->mageMailApiCustomerFactory = $mageMailApiCustomerFactory;
        $this->mageMailApiSubscriberFactory = $mageMailApiSubscriberFactory;
        $this->mageMailApiCustomerGroupFactory = $mageMailApiCustomerGroupFactory;
        $this->mageMailApiSalesRuleFactory = $mageMailApiSalesRuleFactory;
        $this->mageMailApiRecommendationFactory = $mageMailApiRecommendationFactory;
        $this->mageMailApiPassbookLinkFactory = $mageMailApiPassbookLinkFactory;

        parent::__construct($context, $mageMailHelper, $resourceConnection, $logger, $jsonHelper,
            $salesRuleRuleFactory, $collectionAbstractDbFactory, $ioFileFactory,
            $massgenerator);
    }

    /**
     * Accepts Json input in query parameter.
     * Returns Json array of results.
     * @return mixed
     */
    protected function _queryAction()
    {
        $before = microtime(true);
        if (! $this->_authenticate()) {
            return $this;
        }

        $query = $this->getRequest()->getParam('query');
        if (!$query) {
            throw new \Exception("Missing query parameter");
        }

        $batch = $this->getRequest()->getParam('batch');
        $query = json_decode($query, true);

        if (!array_key_exists('entity_type', $query)) {
            throw new \Exception('Missing entity_type');
        }

        $results = $this->_getImportApiModel($query['entity_type'])->setBatchValue($batch)->query($query);

        $response = array_merge(
            array(
                'success'           => true,
                'duration_seconds'  => number_format((microtime(true) - $before), 3),
            ),
            $results
        );

        $this->_jsonResponse($response);
    }

    protected function _getImportApiModel($entityType)
    {
        switch ($entityType) {
            case 'categories':
                return $this->mageMailApiCategoryFactory->create();
            case 'quotes':
                return $this->mageMailApiQuoteFactory->create();
            case 'quote_items':
                return $this->mageMailApiQuoteItemFactory->create();
            case 'coupons':
                return $this->mageMailApiCouponFactory->create();
            case 'orders':
                return $this->mageMailApiOrderFactory->create();
            case 'stock_items':
                return $this->mageMailApiStockItemFactory->create();
            case 'order_items':
                return $this->mageMailApiOrderItemFactory->create();
            case 'wishlists':
                return $this->mageMailApiWishlistFactory->create();
            case 'wishlist_items':
                return $this->mageMailApiWishlistItemFactory->create();
            case 'order_status':
                return $this->mageMailApiOrderStatusFactory->create();
            case 'aheadworks_subscriptions':
                return $this->mageMailApiAheadworksSubscriptionFactory->create();
            case 'customer_rewards':
                return $this->mageMailApiCustomerRewardsFactory->create();
            case 'order_rewards':
                return $this->mageMailApiOrderRewardsFactory->create();
            case 'products':
                return $this->mageMailApiProductFactory->create();
            case 'stores':
                return $this->mageMailApiStoreFactory->create();
            case 'customers':
                return $this->mageMailApiCustomerFactory->create();
            case 'subscribers':
                return $this->mageMailApiSubscriberFactory->create();
            case 'passbook_links':
                return $this->mageMailApiPassbookLinkFactory->create();
            case 'customer_groups':
                return $this->mageMailApiCustomerGroupFactory->create();
            case 'sales_rules':
                return $this->mageMailApiSalesRuleFactory->create();
            case 'up_sell_recommendations':
            case 'cross_sell_recommendations':
            case 'relation_recommendations':
                return $this->mageMailApiRecommendationFactory->create();
        }

        throw new \Exception('Invalid entity_type');
    }
}