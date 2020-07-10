<?php

/* * *************************************************************************************
 *
 *
 *                  ██████╗██████╗ ███╗   ███╗         Customer
 *                 ██╔════╝██╔══██╗████╗ ████║         Relations
 *                 ██║     ██████╔╝██╔████╔██║         Manager
 *                 ██║     ██╔══██╗██║╚██╔╝██║
 *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For Magento
 *                  ╚═════╝╚═╝  ╚═╝╚═╝     ╚═╝
 *
 *
 * @author      Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 *
 *
 * @link        http://www.docs.modulesgarden.com/CRM_For_WHMCS for documenation
 * @link        http://modulesgarden.com ModulesGarden
 *              Top Quality Custom Software Development
 * @copyright   Copyright (c) ModulesGarden, INBS Group Brand,
 *              All Rights Reserved (http://modulesgarden.com)
 *
 * This software is furnished under a license and mxay be used and copied only  in
 * accordance  with  the  terms  of such  license and with the inclusion of the above
 * copyright notice.  This software  or any other copies thereof may not be provided
 * or otherwise made available to any other person.  No title to and  ownership of
 * the  software is hereby transferred.
 *
 * ************************************************************************************ */

namespace Modulesgarden\Crm\Integration\DebugBar;

use \Slim\Slim;
use \DebugBar\DataCollector\ConfigCollector;
use \DebugBar\DataCollector\MemoryCollector;
use \DebugBar\DataCollector\RequestDataCollector;
use \DebugBar\DataCollector\TimeDataCollector;
use \Modulesgarden\Crm\Integration\DebugBar\DataCollector\SlimEnvCollector;
use \Modulesgarden\Crm\Integration\DebugBar\DataCollector\SlimLogCollector;
use \Modulesgarden\Crm\Integration\DebugBar\DataCollector\SlimResponseCollector;
use \Modulesgarden\Crm\Integration\DebugBar\DataCollector\SlimRouteCollector;
use \Modulesgarden\Crm\Integration\DebugBar\DataCollector\SlimViewCollector;

class SlimDebugBar extends \DebugBar\DebugBar
{

    /**
     * keep reference to instance of this class
     *
     * @var object
     */
    private static $instance = false;

    /**
     * get instance
     *
     * @static
     */
    public static function getInstance()
    {
        if (!is_object(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c();

            self::$instance->addCollector(new TimeDataCollector());
            self::$instance->addCollector(new RequestDataCollector());
            self::$instance->addCollector(new MemoryCollector());
        }
        return self::$instance;
    }

    public function __construct()
    {
        // $this->addCollector(new TimeDataCollector());
        // $this->addCollector(new RequestDataCollector());
        // $this->addCollector(new MemoryCollector());
    }

    public function initCollectors(Slim $slim)
    {
        $this->addCollector(new SlimLogCollector($slim));
        $this->addCollector(new SlimEnvCollector($slim));

        $slim->hook('slim.after.router', function() use ($slim) {
            // kinda compicated, we cant use direct reverence $this, due to compatibility with PHP 5.3
            // $thisDebugbar = $slim->debugbar->getDebugBar();
            $thisDebugbar = \Modulesgarden\Crm\Integration\DebugBar\SlimDebugBar::getInstance();

            $setting = $thisDebugbar->prepareRenderData($slim->container['settings']);
            $data = $thisDebugbar->prepareRenderData($slim->view->all());

            $thisDebugbar->addCollector(new SlimResponseCollector($slim->response));
            $thisDebugbar->addCollector(new ConfigCollector($setting));
            $thisDebugbar->addCollector(new SlimViewCollector($data));
            $thisDebugbar->addCollector(new SlimRouteCollector($slim));
        });
    }

    public function prepareRenderData(array $data = array())
    {
        $tmp = array();
        foreach ($data as $key => $val) {
            if (is_object($val)) {
                $val = "Object (" . get_class($val) . ")";
            }
            $tmp[$key] = $val;
        }
        return $tmp;
    }

}
