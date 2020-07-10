<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */


namespace Amasty\Finder\Helper;

use Magento\Store\Model\ScopeInterface;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param $path
     * @return string|bool|int
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue('amfinder/' . $path, ScopeInterface::SCOPE_STORE);
    }
}
