<?php

namespace BoostMyShop\Supplier\Helper;

class Invoice {

    const XML_PATH_ALLOW_MAEHODS = 'supplier/invoice/allowed_payment_methods';
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getAllowMethods(){
        $options = array();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $allowdMehtods = $this->scopeConfig->getValue(self::XML_PATH_ALLOW_MAEHODS, $storeScope);
        
        if($allowdMehtods && $allowdMehtods != ''){
            $methods = explode(",", $allowdMehtods);
            if(count($methods) > 0){
                foreach($methods as $code){
                    $options[] = array('value' => $code, 'label' => $code);
                }
            }
        } else {
            $options = array(
                'Bank Transfer' => 'Bank Transfer Payment', 
                'Cash On Delivery' => 'Cash On Delivery',
                'Check / Money order' => 'Check / Money order',
                'Credit Card' => 'Credit Card'
            );
        }
         
        return $options;
    }

}