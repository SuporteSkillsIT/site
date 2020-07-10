<?php
namespace KJ\MageMail\Controller;

/**
 * Magento web services triggered by https://v2.magemail.co/skin/js/magento.js functionality.
 * @package KJ\MageMail\Controller
 */
abstract class Customer extends \Magento\Framework\App\Action\Action
{
    const TEST_EMAIL = 'kalen+test@magemail.co';

    protected $_couponModel;

    /** @var  \Magento\SalesRule\Model\Rule */
    protected $_salesRule;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteQuoteFactory;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $checkoutCart;

    /**
     * @var \KJ\MageMail\Helper\Data
     */
    protected $mageMailHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $catalogProductFactory;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    protected $salesRuleRuleFactory;

    /**
     * @var \Magento\SalesRule\Model\CouponFactory
     */
    protected $salesRuleCouponFactory;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $newsletterSubscriberFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $salesOrderFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerCustomerFactory;

    /**
     * @var \Magento\Store\Model\StoreFactory
     */
    protected $storeStoreFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    protected $_jsonHelper;

    protected $quoteRepository;

    protected $catalogImageHelper;

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
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Catalog\Helper\Image $catalogImageHelper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteQuoteFactory = $quoteQuoteFactory;
        $this->checkoutCart = $checkoutCart;
        $this->mageMailHelper = $mageMailHelper;
        $this->catalogProductFactory = $catalogProductFactory;
        $this->salesRuleRuleFactory = $salesRuleRuleFactory;
        $this->salesRuleCouponFactory = $salesRuleCouponFactory;
        $this->newsletterSubscriberFactory = $newsletterSubscriberFactory;
        $this->salesOrderFactory = $salesOrderFactory;
        $this->customerSession = $customerSession;
        $this->customerCustomerFactory = $customerCustomerFactory;
        $this->storeStoreFactory = $storeStoreFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->quoteRepository = $quoteRepository;
        $this->catalogImageHelper = $catalogImageHelper;
        parent::__construct(
            $context
        );
    }

    protected function _getQuote()
    {
        /** @var \Magento\Checkout\Model\Session $session */
        $session = $this->checkoutSession;
        return $session->getQuote();
    }

    protected function _applyCouponAction()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Origin','*');

        $coupon = $this->getRequest()->getParam('coupon');
        $this->_validateCoupon();
        $quote = $this->_getQuote();

        if ($quote->getCouponCode()) {
            $this->_couponAlreadyApplied();
        }
        $quote->setTotalsCollectedFlag(false);
        $quote->setCouponCode($coupon)->collectTotals();
        $this->quoteRepository->save($quote);

        $discountAmount = $quote->getSubtotal() - $quote->getSubtotalWithDiscount();
        $this->_log(var_export($quote->getTotals(), true));
        if ($discountAmount == 0) {
            $this->_jsonResponse(array(
                'success'               => true,
                'is_applied'            => false,
                'discount_amount'       => 0,
                'should_save_in_cookie' => true,
                'coupon'                => $coupon,
                'rule_label'            => $this->_getSalesRuleLabel(),
                'message'               => "Coupon code could not be applied yet.",
            ));

            return $this;
        }

        $this->_jsonResponse(array(
            'success'               => true,
            'is_applied'            => true,
            'discount_amount'       => $discountAmount,
            'should_save_in_cookie' => true,
            'rule_label'            => $this->_getSalesRuleLabel(),
            'coupon'                => $quote->getCouponCode(),
            'message'               => "Applied coupon: " . $quote->getCouponCode(),
        ));

        return $this;
    }

    protected function _getDiscountAmount($quote)
    {
        $totals = $quote->getTotals();
        if (array_key_exists('discount', $totals)
            && $totals["discount"] instanceof \Magento\Quote\Model\Quote\Address\Total) {
            $discountAmount = $totals["discount"]->getValue();
        } else {
            $discountAmount = 0;
        }

        return $discountAmount;
    }

    protected function _restoreCartAction()
    {
        $exceptions = array();
        $this->getResponse()->setHeader('Access-Control-Allow-Origin','*');

        $newQuoteId = (int)$this->getRequest()->getParam('mm_entity');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteQuoteFactory->create()->load($newQuoteId);
        if (! $quote->getId()) {
            $this->_handleResponse(true, "Wasn't able to load quote by ID: " . $quote->getId());
            return $this;
        }

        $customer = $this->_loadCustomerFromEntity($quote);
        $loggedIn = $customer->getId() ? $this->_loginCustomer($quote) : false;
        if ($loggedIn) {
            $message = "Logged in customer - ID: " . $customer->getId() . " (Logged In: $loggedIn)";
        } else {
            list($addedSkus, $exceptions) = $this->_addQuoteItemsToCart($quote);
            $addedSkusCsv = (empty($addedSkus)) ? "(no items)" : implode(", ", $addedSkus);
            $message = "Restored items for quote ({$quote->getId()}): " . $addedSkusCsv;
        }

        $this->_handleResponse(true, $message, array('exceptions' => $exceptions));

        return $this;
    }

    /**
     * @param $success
     * @param $message
     * @param $customer Mage_Customer_Model_Customer
     */
    protected function _handleResponse($success, $message, $params = array())
    {
        if ($this->_getRedirectUrl()) {
            $this->getResponse()->setHeader("location", $this->_getRedirectUrl())
                ->sendHeaders();
        }

        $response = array(
            "success"           => $success,
            "message"           => $message,
        );
        if (!empty($params)) {
            $response += $params;
        }

        $this->_jsonResponse($response);

        return $this;
    }

    protected function _getRedirectUrl()
    {
        $redirectUrl = $this->getRequest()->getParam('mm_redirect', null);
        if (! $redirectUrl) {
            return null;
        }

        // Don't allow redirects to external sites
        if (strpos($redirectUrl, "http:") !== false) {
            return null;
        }

        return $redirectUrl;
    }

    protected function _getExistingItemSkus()
    {
        $itemSkus = array();
        $cart = $this->checkoutCart;
        $items = $cart->getItems();

        /** @var $item Mage_Sales_Model_Quote_Item */
        foreach ($items as $item) {
            $itemSkus[] = $item->getSku();
        }

        return $itemSkus;
    }

    /**
     * @param $quote Mage_Sales_Model_Quote
     */
    protected function _addQuoteItemsToCart($quote)
    {
        $help = $this->mageMailHelper;
        $cart = $this->checkoutCart;
        $existingItemsBySku = $this->_getExistingItemSkus();
        $exceptions = array();
        $addedSkus = array();

        /** @var $item Mage_Sales_Model_Quote_Item */
        foreach ($quote->getAllItems() as $item) {
            if (! in_array($item->getSku(), $existingItemsBySku)) {
                if (! $item->getParentItemId()) {
                    try {
                        $product = $this->catalogProductFactory->create()->load($item->getProductId());
                        $cart->addProduct($product, $item->getBuyRequest());
                        if (! $help->isAddProductEventDispatchDisabled()) {
                            $this->_eventManager->dispatch('checkout_cart_add_product_complete',
                                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                            );
                        }
                        $addedSkus[] = $item->getSku();
                    } catch (Exception $e) {
                        $exceptions[] = $item->getSku() . ": " .  $e->getMessage();
                    }
                }
            }
        }

        $cart->save();

        return array($addedSkus, $exceptions);
    }

    protected function _getSalesRuleLabel()
    {
        $salesRule = $this->_getSalesRule();
        $label = $salesRule->getStoreLabel();
        return (string)$label;
    }

    /**
     * @return \Magento\SalesRule\Model\Rule
     */
    protected function _getSalesRule()
    {
        if (isset($this->_salesRule)) {
            return $this->_salesRule;
        }

        $salesRule = $this->salesRuleRuleFactory->create()->load($this->_getCouponModel()->getRuleId());

        $this->_salesRule = $salesRule;
        return $this->_salesRule;
    }

    protected function _couponAlreadyApplied()
    {
        $coupon = $this->getRequest()->getParam('coupon');
        $quote = $this->checkoutSession->getQuote();
        $requestSource = $this->getRequest()->getParam('source');

        if ($quote->getCouponCode() == $coupon) {
            $this->_jsonResponse(array(
                'success'               => true,
                'is_applied'            => true,
                'should_save_in_cookie' => true,
                'coupon'                => $quote->getCouponCode(),
                'rule_label'            => $this->_getSalesRuleLabel(),
                'message'               => "The coupon is already applied: " . $quote->getCouponCode(),
            ));
        } elseif ($requestSource == 'cookie') {
            $this->_jsonResponse(array(
                'success'               => true,
                'should_save_in_cookie' => false,
                'should_destroy_cookie' => true,
                'is_applied'            => true,
                'coupon'                => $quote->getCouponCode(),
                'message'               => "Another coupon was already applied: " . $quote->getCouponCode(),
            ));
        }
    }

    protected function _cancelCouponAction()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Origin','*');

        $quote = $this->checkoutSession->getQuote();

        if (! $quote->getCouponCode()) {
            $this->_jsonResponse(array(
                'success'               => true,
                'coupon'                => null,
                'is_applied'            => false,
                'should_destroy_cookie' => true,
                'message'               => "There wasn't any coupon code applied to cancel",
            ));
        }

        $originalCouponCode = $quote->getCouponCode();
        $quote->setCouponCode(null)->collectTotals()->save();
        $this->_jsonResponse(array(
            'success'               => true,
            'coupon'                => $originalCouponCode,
            'is_applied'            => false,
            'should_destroy_cookie' => false,
            'message'               => "Cancelled coupon code: " . $originalCouponCode,
        ));
    }

    protected function _getCouponModel()
    {
        if (isset($this->_couponModel)) {
            return $this->_couponModel;
        }

        $couponCode = $this->getRequest()->getParam('coupon');
        $couponModel = $this->salesRuleCouponFactory->create()->loadByCode($couponCode);

        $this->_couponModel = $couponModel;
        return $this->_couponModel;
    }

    protected function _validateCoupon()
    {
        $couponCode = $this->getRequest()->getParam('coupon');
        if (!$couponCode) {
            throw new \Exception("No coupon code was supplied");
        }

        $coupon = $this->salesRuleCouponFactory->create()->loadByCode($couponCode);
        if (! $coupon->getId()) {
            throw new \Exception("The coupon code doesn't exist: " . $couponCode);
        }

        if ($coupon->getUsageLimit() && $coupon->getTimesUsed() >= $coupon->getUsageLimit()) {
            throw new \Exception("The coupon has already been used");
        }

        $ruleId = $coupon->getRuleId();

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $this->salesRuleRuleFactory->create()->load($ruleId);
        if (! $rule->getId()) {
            throw new \Exception("Unable to load rule by ID: $ruleId");
        }

        if (! $rule->getIsActive()) {
            throw new \Exception("Rule is not active");
        }

        if ($rule->getToDate() && date("Y-m-d") >= $rule->getToDate() ) {
            throw new \Exception("Rule expired: " . $rule->getToDate());
        }

        return $this;
    }

    protected function _jsonResponse($data)
    {
        $this->getResponse()->setHeader('Content-Type', 'application/json')
            ->setBody($this->_jsonHelper->jsonEncode($data))
            ->sendHeaders();

        return $this;
    }



    protected function _saveCartAction()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Origin','*');

        $email = $this->getRequest()->getParam('email');
        if (! $this->mageMailHelper->validateEmailFormat($email)) {
            throw new \Exception($this->mageMailHelper->langInvalidEmail() . $email);
        }

        $quote = $this->checkoutSession->getQuote();
        $quote->setCustomerEmail($email);

        $this->quoteRepository->save($quote);

        $quoteId = $quote->getId();
        $message = \Zend_Validate::is($email, 'EmailAddress') ? "Saved email $email to quote ID $quoteId" : "Email seems invalid - might not have saved";

        $this->_jsonResponse(array(
            'success' => true,
            'message' => $message,
        ));

        return $this;
    }

    protected function _saveNewsletterAction()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Origin','*');

        //Check if the honeypot field is filled out.
        if ($this->getRequest()->getParam('firstname')) {
            throw new \Exception('It appears that you are a bot.');
        }

        $email = $this->getRequest()->getParam('email');
        if (! $this->mageMailHelper->validateEmailFormat($email)) {
            throw new \Exception($this->mageMailHelper->langInvalidEmail() . $email);
        }

        $existingSubscriberId = null;
        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        $subscriber = $this->newsletterSubscriberFactory->create()->loadByEmail($email);
        if (! $subscriber->getId()) {
            $status = $this->newsletterSubscriberFactory->create()->subscribe($email);
            $subscriber = $this->newsletterSubscriberFactory->create()->loadByEmail($email);
        } else {
            $status = $subscriber->getStatus();
            $existingSubscriberId = $subscriber->getId();
        }

        if ($this->getRequest()->getParam('source')) {
            $subscriber->setData('magemail_source', $this->getRequest()->getParam('source'))->save();
        }

        if ($existingSubscriberId) {
            $message = __("It looks like you were already subscribed to the newsletter.");
        } else {
            // Not actually using this message on the frontend - they'll be able to control
            // the response message with the rest of MageMail content translation.

            if ($status == \Magento\Newsletter\Model\Subscriber::STATUS_NOT_ACTIVE) {
                $message = __('Confirmation request has been sent.');
            }
            else {
                $message = __('Thank you for your subscription.');
            }
        }

        $this->_jsonResponse(array(
            'success'                   => true,
            'message'                   => $message,
            'notes'                     => "Saved email $email as newsletter subscriber.  Status: $status",
            'source'                    => $subscriber->getData('magemail_source'),
            'existing_subscriber_id'    => $existingSubscriberId,
        ));

        return $this;
    }

    protected function _getCartAction()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Origin','*');

        $quote = $this->checkoutSession->getQuote();

        $cartResponse = array();
        foreach ($quote->getAllVisibleItems() as $item) {
            $cartResponse[] = array(
                'item_id'   => $item->getId(),
                'qty'       => $item->getQty(),
                'row_total' => $item->getRowTotal(),
                'name'      => $item->getName(),
                'image_url' => $this->catalogImageHelper->init($item->getProduct(), 'product_small_image')->resize(200, 200)->getUrl()
            );
        }

        $this->_jsonResponse(array(
            'success'   => true,
            'quote_id'  => $quote->getId(),
            'email'     => $quote->getCustomerEmail(),
            'cart'      => $cartResponse,
        ));

        return $this;
    }

    protected function _autoLoginAction()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Origin','*');

        $entityId = $this->getRequest()->getParam('mm_entity');
        $entity = $this->_loadEntity($entityId);
        if (! $entity) {
            $this->_handleResponse(true, "Wasn't able to load entity by ID and email: $entityId", array(
                'just_logged_in'        => false,
                'customer_firstname'    => null,
            ));
            return $this;
        }

        $customerId = $entity->getCustomerId();
        $justLoggedIn = $this->_loginCustomer($entity);
        $this->_handleResponse(true, "Auto login attempt for customer $customerId", array(
            'just_logged_in'        => $justLoggedIn,
            'customer_firstname'    => $entity->getCustomerFirstname(),
        ));

        return $this;
    }

    /**
     * @param $entityId
     * @return \Magento\Sales\Model\Order|\Magento\Quote\Model\Quote
     */
    protected function _loadEntity($entityId)
    {
        $email = $this->getRequest()->getParam('mm_recipient');

        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->salesOrderFactory->create()->load($entityId);
        if ($order->getId() && $order->getCustomerEmail() == $email) {
            return $order;
        }

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteQuoteFactory->create()->load($entityId);
        if ($quote->getId() && $quote->getCustomerEmail() == $email) {
            return null; // Quote cannot be returned b/c it double adds items when restoring cart
        }

        $this->_log("Unable to load order or quote by entity ID ($entityId) with a non-null customer ID and with email set to $email ");
        return null;
    }

    /**
     * @return \KJ\MageMail\Helper\Data
     */
    protected function help()
    {
        return $this->mageMailHelper;
    }

    /**
     * @param $entity Mage_Sales_Model_Order|Mage_Sales_Model_Quote
     */
    protected function _loginCustomer($entity)
    {
        if (! $this->mageMailHelper->autoLoginEnabled()) {
            $this->_log("Auto-login isn't enabled");
            return false;
        }

        if ($this->customerSession->isLoggedIn()) {
            $customerId = $this->customerSession->getCustomerId();
            $this->_log("Already logged in as customer $customerId");
            return false;
        }

        $entityIpAddress = $entity->getRemoteIp();
        $requestIpAddress = $this->help()->currentIpAddress();

        if ($this->mageMailHelper->autoLoginIpRestricted() && $requestIpAddress != $entityIpAddress) {
            $this->_log("Request IP Address ($requestIpAddress) doesn't match IP address on entity ($entityIpAddress) - not logging in");
            return false;
        }

        if (! $this->_isAutoLoginTokenValid()) {
            $this->_log("Auto-login token is invalid: " . $this->getRequest()->getParam('mm_token'));
            return false;
        }

        $customer = $this->_loadCustomerFromEntity($entity);
        if (!$customer->getId()) {
            return false;
        }

        $this->_log("Successful auto-login for customer: {$customer->getId()}");
        $this->customerSession->setCustomerAsLoggedIn($customer);

        return true;
    }

    protected function _isAutoLoginTokenValid()
    {
        $token = $this->getRequest()->getParam('mm_token');
        $email = $this->getRequest()->getParam('mm_recipient');
        $apiKey = $this->mageMailHelper->getApiKey();
        $tokenShouldBe = substr(md5($email . $apiKey), 0, 10);

        return ($token == $tokenShouldBe);
    }

    /**
     * @param $entity Mage_Sales_Model_Order|Mage_Sales_Model_Quote
     */
    protected function _loadCustomerFromEntity($entity)
    {
        $customer = $this->customerCustomerFactory->create();

        if ($entity->getCustomerId()) {
            $customer->load($entity->getCustomerId());
            if ($customer->getId()) {
                return $customer;
            }
        }

        $email = $entity->getCustomerEmail();
        if ($email) {
            $websiteId = $this->_getWebsiteId($entity);
            if ($websiteId) {
                $customer->setData('website_id', $websiteId);
                $customer->loadByEmail($email);
            }
        }

        if ($customer->getId()) {
            return $customer;
        }

        $this->_log("Wasn't able to load customer by ID ({$entity->getId()}) or email ($email) - not going to auto-login");
        return $customer; // This customer has no getId()
    }

    /**
     * @param $entity Mage_Sales_Model_Order|Mage_Sales_Model_Quote
     */
    protected function _getWebsiteId($entity)
    {
        $storeId = $entity->getStoreId();
        if (! $storeId) {
            return null;
        }

        $store = $this->storeStoreFactory->create()->load($storeId);
        if (! $store->getId()) {
            return null;
        }

        return $store->getWebsiteId();
    }

    protected function _log($message)
    {
        if ($this->mageMailHelper->logEverything()) {
            $this->logger->info($message);
        }
    }

    protected function _createTestQuoteAction()
    {
        $quote = $this->checkoutSession->getQuote();
        $quote->setCustomerEmail(self::TEST_EMAIL)->save();

        $this->_jsonResponse(array(
            'success'               => true,
            'quote_id'              => $quote->getId(),
            'created_at'            => $quote->getCreatedAt(),
        ));

        return $this;
    }
}