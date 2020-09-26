<?php

namespace BoostMyShop\UltimateReport\Helper;

class Logger
{

    const kLogGeneral = 'general';


    public function log($msg, $type = self::kLogGeneral)
    {
    	# 2020-09-26 Dmitry Fedyuk https://www.upwork.com/fl/mage2pro
		# "Disable `ultimatereport_general.log`": https://github.com/dxmoto/site/issues/106
    	return;
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/ultimatereport_'.$type.'.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($msg);
    }

}