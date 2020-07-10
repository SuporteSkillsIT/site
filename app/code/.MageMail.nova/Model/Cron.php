<?php
namespace KJ\MageMail\Model;

class Cron extends \Magento\Framework\DataObject
{
    const LAST_ORDER_CACHE_ID = 'kj_magemail_last_order_id';
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \KJ\MageMail\Helper\Data
     */
    protected $mageMailHelper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    protected $cacheManager;

    protected $cacheState;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $salesResourceModelOrderCollectionFactory;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $zendClientFactory;

    public function __construct(
        \KJ\MageMail\Helper\Data $mageMailHelper,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\Element\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesResourceModelOrderCollectionFactory,
        \Magento\Framework\HTTP\ZendClientFactory $zendClientFactory,
        array $data = []
    ) {
        $this->zendClientFactory = $zendClientFactory;
        $this->cacheState = $context->getCacheState();
        $this->logger = $context->getLogger();
        $this->mageMailHelper = $mageMailHelper;
        $this->request = $request;
        $this->cacheManager = $context->getCache();
        $this->salesResourceModelOrderCollectionFactory = $salesResourceModelOrderCollectionFactory;
        parent::__construct(
            $data
        );
    }

    public function sendOrderConfirmationEmail()
    {
        try {
            $this->_sendOrderConfirmationEmail();
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }

    /**
     * Pings MageMail server whenever there is order confirmation emails pending to be sent.
     * @return $this
     */
    protected function _sendOrderConfirmationEmail()
    {
        $shouldPingApi = $this->_shouldPingApi();
        if (! $shouldPingApi) {
            return $this;
        }

        $url = $this->_getMageMailOrderConfirmationUrl();
        $this->logger->info("Pinging $url");

        $client = $this->zendClientFactory->create();
        $client->setUri($url);

        $client->setParameterPost('api_key', $this->mageMailHelper->getApiKey());
        $client->setParameterPost('username', $this->mageMailHelper->getUsername());

        $result = $client->request('POST');
        $responseBody = $result->getBody();
        $this->logger->info("Result: $responseBody");

        $this->_cacheLastOrderId();

        return $this;
    }

    protected function _getMageMailOrderConfirmationUrl()
    {
        $protocol = $this->request->isSecure() ? "https" : "http";
        $url = $protocol . "://" . $this->mageMailHelper->getMageMailDomainWithoutTrailingSlash();
        $url .= "/api/send-order-confirmation";

        return $url;
    }

    protected function _shouldPingApi()
    {
        if (! $this->cacheState->isEnabled('config')) {
            $this->logger->info("Not going to ping API because cache is disabled");
            return false;
        }

        if (! $this->mageMailHelper->isOrderConfirmationEmailEnabled()) {
            $this->logger->info("Not going to ping API because order confirmation email isn't enabled");
            return false;
        }

        $lastOrderId = $this->cacheManager->load(self::LAST_ORDER_CACHE_ID);
        if (! $lastOrderId) {
            $this->logger->info("Will ping API b/c last order ID is empty");
            return true;
        }

        $newOrderCount = $this->salesResourceModelOrderCollectionFactory->create()
            ->addFieldToFilter('entity_id', array('gt' => $lastOrderId))
            ->count();

        if ($newOrderCount) {
            $this->logger->info("Pinging API b/c there are $newOrderCount new orders past last order ID $lastOrderId");
            return true;
        }

        $this->logger->info("Not pinging API b/c no new orders past $lastOrderId");
        return false;
    }

    protected function _cacheLastOrderId()
    {
        $lastOrder = $this->salesResourceModelOrderCollectionFactory->create()
            ->setOrder('entity_id', \Magento\Sales\Model\ResourceModel\Order\Collection::SORT_ORDER_DESC)
            ->setPageSize(1)
            ->getFirstItem();

        $lastOrderId = $lastOrder->getId();

        $this->logger->info("Caching last order ID (" . self::LAST_ORDER_CACHE_ID . "): $lastOrderId");
        $this->cacheManager->save($lastOrderId, self::LAST_ORDER_CACHE_ID, array('CONFIG_API'));

        return $this;
    }
}