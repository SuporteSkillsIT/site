<?php
namespace Magecomp\Geocurrencystore\Plugin\Store\Model;

use Magento\Directory\Model\Currency;
use Magento\Store\Model\Store;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magecomp\Geocurrencystore\Helper\Data as GeoHelperData;

class Geostore
{
    private $_currency;
    protected $scopeConfig;
    protected $geoHelperData;
	protected $_session;
	
    public function __construct(
        Currency $_currency,
        ScopeConfigInterface $scopeConfig,
		\Magento\Framework\Session\SessionManagerInterface $session,
        GeoHelperData $geoHelperData
    )
    {
        $this->_currency = $_currency;
        $this->scopeConfig = $scopeConfig;
        $this->geoHelperData = $geoHelperData;
		$this->_session = $session;
    }
	
	
	protected function _getSession() {
        if (!$this->_session->isSessionExists()) {
            $this->_session->setName('store_' . $this->getCode());
            $this->_session->start();
        }
        return $this->_session;
    }

    public function aftergetDefaultCurrencyCode(Store $store, $result)
    {
        try {
            $countryCode = $this->geoHelperData->getCountryByIp($_SERVER['REMOTE_ADDR']);
            $currencyCode = $this->geoHelperData->getCurrencyByCountry($countryCode);

            $result = $store->getConfig(Currency::XML_PATH_CURRENCY_DEFAULT);
            $allowedCurrencies = $this->_currency->getConfigAllowCurrencies();

            if (!in_array($currencyCode, $allowedCurrencies)) {
				$this->_getSession()->setCurrencyCode($result);
                return $result;
            } else {
				$this->_getSession()->setCurrencyCode($currencyCode);
                return $currencyCode;
            }
        }catch (\Exception $e)
        {
            return $result;
        }
    }
}