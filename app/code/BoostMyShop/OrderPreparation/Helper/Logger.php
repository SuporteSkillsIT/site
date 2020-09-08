<?php

namespace BoostMyShop\OrderPreparation\Helper;

class Logger
{

    const kLogGeneral = 'general';
    const kLogEditor = 'editor';

    public function log($msg, $type = self::kLogGeneral)
    {
        # 2020-09-08 Dmitry Fedyuk https://www.upwork.com/fl/mage2pro
        # 1) "Prevent logging to `orderpreparation_registry.log`": https://github.com/dxmoto/site/issues/47
        # 2) "Prevent logging to `orderpreparation_general.log`": https://github.com/dxmoto/site/issues/48
    	if (false) {
			$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/orderpreparation_'.$type.'.log');
			$logger = new \Zend\Log\Logger();
			$logger->addWriter($writer);
			$logger->info($msg);
		}
    }

    public function logException($exception, $type = self::kLogGeneral)
    {
        $msg= $exception->getMessage().' : '.$exception->getTraceAsString();
        $this->log($msg, $type);
    }

}