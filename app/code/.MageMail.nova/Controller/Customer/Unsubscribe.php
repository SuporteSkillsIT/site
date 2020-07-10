<?php
namespace KJ\MageMail\Controller\Customer;

use KJ\MageMail\Controller\Customer;

class Unsubscribe extends Customer
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Model\QuoteFactory $quoteQuoteFactory,
        \Magento\Checkout\Model\Cart $checkoutCart,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        \Magento\Catalog\Model\ProductFactory $catalogProductFactory,
        \Magento\SalesRule\Model\RuleFactory $salesRuleRuleFactory,
        \Magento\SalesRule\Model\CouponFactory $salesRuleCouponFactory,
        \Magento\Newsletter\Model\SubscriberFactory $newsletterSubscriberFactory,
        \Magento\Sales\Model\OrderFactory $salesOrderFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\CustomerFactory $customerCustomerFactory,
        \Magento\Store\Model\StoreFactory $storeStoreFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Catalog\Helper\Image $catalogImageHelper,
        \Psr\Log\LoggerInterface $logger)
    {
        parent::__construct($context, $checkoutSession, $quoteQuoteFactory, $checkoutCart, $mageMailHelper, $catalogProductFactory, $salesRuleRuleFactory, $salesRuleCouponFactory, $newsletterSubscriberFactory, $salesOrderFactory, $customerSession, $customerCustomerFactory, $storeStoreFactory, $jsonHelper, $quoteRepository, $catalogImageHelper, $logger);
        $this->_pageFactory = $pageFactory;
    }

    public function execute()
    {
        $page = $this->_pageFactory->create();
        $page->getConfig()->getTitle()->set('Unsubscribed');
        return $page;
    }
}