<?php
namespace KJ\MageMail\Helper;


use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\UrlInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cacheInterface;

    /**
     * @var \Magento\Framework\Filesystem\Io\FileFactory
     */
    protected $ioFileFactory;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $zendClientFactory;

    private $_deploymentConfig;

    private $_dirManager;

    protected $urlHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        DeploymentConfig $deploymentConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\CacheInterface $cacheInterface,
        \Magento\Framework\Filesystem\Io\FileFactory $ioFileFactory,
        \Magento\Framework\Filesystem\DirectoryList $dirManager,
        \Magento\Framework\Url $urlHelper,
        \Magento\Framework\HTTP\ZendClientFactory $zendClientFactory
    ) {
        $this->ioFileFactory = $ioFileFactory;
        $this->urlHelper = $urlHelper;
        $this->_dirManager = $dirManager;
        $this->zendClientFactory = $zendClientFactory;
        $this->_deploymentConfig = $deploymentConfig;
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->cacheInterface = $cacheInterface;
        parent::__construct(
            $context
        );
    }

    public function getCryptKey()
    {
        return (string)$this->_deploymentConfig->get(Encryptor::PARAM_CRYPT_KEY);
    }

    public function getGeneralEmail()
    {
        return $this->scopeConfig->getValue('trans_email/ident_general/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getProductSuffix()
    {
        return $this->scopeConfig->getValue('catalog/seo/product_url_suffix', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isApiEnabled()
    {
        return $this->scopeConfig->getValue('kj_magemail/settings/enable_api', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isApiWriteAccessEnabled()
    {
        return $this->scopeConfig->getValue('kj_magemail/settings/enable_api_write_access', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isConnected()
    {
        return $this->scopeConfig->getValue('kj_magemail/settings/is_connected', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getApiKey()
    {
        return $this->scopeConfig->getValue('kj_magemail/advanced/api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getUsername()
    {
        return $this->scopeConfig->getValue('kj_magemail/settings/web_username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getApiRoute()
    {
        return $this->scopeConfig->getValue('kj_magemail/advanced/api_route', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getMageMailDomain()
    {
        return $this->scopeConfig->getValue('kj_magemail/advanced/magemail_domain', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isIpAddressWhitelistEnabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/enable_ip_address_whitelist', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function useXForwardedFor()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/use_x_forwarded_for', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getWhiteListedIpAddresses()
    {
        $ips = $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/whitelisted_ip_addresses', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
        return array_map('trim', explode(',', $ips));
    }

    public function getQueryBlackList()
    {
        $keywords = $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/query_blacklist', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
        if (! $keywords) {
            return array();
        }

        return array_map('trim', explode(',', $keywords));
    }

    public function getAdditionalHtml()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/additional_html', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getLogLevel()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/log_level', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getJavascriptLoggingDisabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/disable_javascript_logging', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function autoLoginEnabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/enable_auto_login', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function autoLoginIpRestricted()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/auto_login_ip_restricted', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function shouldCaptureEmail()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/capture_email_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isSqlApiDisabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/disable_sql_api', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isAddProductEventDispatchDisabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/disable_add_product_event_dispatch', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isExitModalEnabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/enable_exit_modal', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isNewsletterExitModalDisabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/newsletter_exit_modal_disabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function isOrderConfirmationEmailEnabled()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/enable_order_confirmation_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function logEverything()
    {
        return ($this->getLogLevel() == \KJ\MageMail\Model\System\Config\Source\LogLevel::LEVEL_EVERYTHING);
    }

    public function currentIpAddress()
    {
        if ($this->useXForwardedFor()) {
            return $this->_getRequest()->getServer('HTTP_X_FORWARDED_FOR');
        }

        return $this->_remoteAddress->getRemoteAddress();
    }

    public function isCurrentIpAddressWhiteListed()
    {
        $currentIp = $this->currentIpAddress();

        if (strpos($currentIp, ",") !== false) {
            $currentIpsCsv = $currentIp;
            $currentIpsArray = array_map('trim', explode(',', $currentIpsCsv));
            foreach ($currentIpsArray as $currentIp) {
                if (in_array($currentIp, $this->getWhiteListedIpAddresses())) {
                    return true;
                }
            }
        }

        return in_array($currentIp, $this->getWhiteListedIpAddresses());
    }

    public function getMageMailDomainWithoutTrailingSlash()
    {
        $url = $this->getMageMailDomain();
        if (substr($url, -1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }

    public function getMageMailBaseUrl()
    {
        $protocol = $this->request->isSecure() ? "https" : "http";
        $url = $protocol . "://" . $this->getMageMailDomainWithoutTrailingSlash();

        return $url;
    }

    public function getMageMailJavascriptUrl()
    {
        $protocol = $this->request->isSecure() ? "https" : "http";
        $url = $protocol . "://" . $this->getMageMailDomainWithoutTrailingSlash() . '/skin/js/magento.js';

        return $url;
    }

    public function getJqueryRemodalJsUrl()
    {
        return "null";
    }

    public function getJqueryRemodalCssUrl()
    {
        return "null";
    }

    public function getOuibounceJslUrl()
    {
        return "null";
    }

    public function getExitModalCssUrl()
    {
        return "null";
    }

    public function getBaseUrlOverride()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/base_url_override', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    /**
     * Strip out the http:// so it can be used in protocol-netural ways.
     * This should probably renamed as a domain getter not a url getter
     *
     * @return string
     */
    public function getMagentoBaseUrl()
    {
        if ($this->getBaseUrlOverride()) {
            return $this->getBaseUrlOverride();
        }

        $url = $this->scopeConfig->getValue('web/unsecure/base_link_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
        $url = substr($url, 7);

        if (substr($url, -1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }

    /**
     * Strip out the http:// so it can be used in protocol-netural ways.
     * This should probably renamed as a domain getter not a url getter
     *
     * @return string
     */
    public function getMagentoSecureBaseUrl()
    {
        if ($this->getBaseUrlOverride()) {
            return $this->getBaseUrlOverride();
        }

        $url = $this->scopeConfig->getValue('web/secure/base_link_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
        $parts = explode("://", $url);
        if (! isset($parts[1])) {
            return "error_finding_url";
        }

        $url = $parts[1];
        if (substr($url, -1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }

    /**
     * Have to force secure on this because otherwise it will grab the
     * secure setting for the admin, which may or may not be the
     * same as the frontend setting.
     *
     * @return string
     */
    public function getSecureApiUrl()
    {
        $this->urlHelper->setData('secure_is_forced', true);
        $url = $this->urlHelper->getUrl('magemail/jsonApi', array('_nosid' => true, '_secure' => true));

        return $url;
    }

    public function getUrl($route)
    {
        return $this->storeManager->getStore()->getUrl($route);
    }

    public function isQueryBlacklisted($query)
    {
        $blacklistedKeywords = $this->getQueryBlackList();
        foreach ($blacklistedKeywords as $keyword) {
            if (strpos($query, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Language
     */
    public function getCouponBarMessageNotActivated()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/coupon_bar_message_not_activated', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getCouponBarMessageActivated()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/coupon_bar_message_activated', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getMessageUpdatingCart()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/message_updating_cart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getMessageReloadingForCoupon()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/message_reloading_for_coupon', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getMessageReloadingForCart()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/message_reloading_for_cart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getMessageClose()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/message_close', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getMessageHoursSymbol()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/message_hours_symbol', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function langReviewProduct()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/product_review', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function langUnknownProblem()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/unknown_problem', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function langThanksReviewComplete()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/thanks_review_complete', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function langSubmittingReview()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/submitting_review', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function langThereWasProblem()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/there_was_problem', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function langAuthenticationFail()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/authentication_fail', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function langInvalidEmail()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/invalid_email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getLanguageKeys()
    {
        return array(
            'before_you_leave',
            'enter_email_to_have_cart_emailed',
            'enter_email_to_receive_newsletter',
            'invalid_email',
            'loading',
            'network_problem_couldnt_save_email',
            'no_thanks',
            'save_exit_modal_button',
            'thanks_you_will_receive_email',
            'unknown_problem',
        );
    }

    public function getLanguageValue($langKey)
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_language/' . $langKey, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    // Zend_Validate for emails is all lies so I'm rolling a super dumbed down
    // one.  Zend_Validate has a false negative for name.5@netzero.com
    public function validateEmailFormat($string)
    {
        if (strpos($string, '@') !== false && strpos($string, '.') !== false) {
            return true;
        }

        return false;
    }

    public function dontRedirectImages()
    {
        return $this->scopeConfig->getValue('newsletter/kj_magemail_advanced/dont_redirect_images', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }

    public function getManageStock()
    {
        return $this->scopeConfig->getValue('cataloginventory/item_options/manage_stock', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore());
    }
}