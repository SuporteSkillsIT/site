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

namespace Modulesgarden\Crm\Integration\DebugBar\DataCollector;

use \Slim\Slim;
use \DebugBar\DataCollector\ConfigCollector;

class SlimRouteCollector extends ConfigCollector
{

    /**
     * @var \Slim\Slim
     */
    protected $slim;

    /**
     * @param Slim $slim
     */
    public function __construct(Slim $slim)
    {
        $this->slim = $slim;
        $this->setData($this->getRouteInfo());
    }

    public function getName()
    {
        return 'route';
    }

    public function getRouteInfo()
    {
        // if slim.after.router fired, route is not null
        $route = $this->slim->router->getCurrentRoute();
        $method = $this->slim->request->getMethod();
        $path = $this->slim->request->getPathInfo();
        $uri = $method . ' ' . $path;

        return array(
            'uri' => $uri,
            'pattern' => $route->getPattern(),
            'params' => $route->getParams() ? : '-',
            'name' => $route->getName() ? : '-',
            'conditions' => $route->getConditions() ? : '-',
        );
    }

    public function getWidgets()
    {
        $name = $this->getName();
        $data = parent::getWidgets();

        $data['currentroute'] = array(
            'icon' => 'share',
            'tooltip' => 'Route',
            'map' => "$name.uri",
            'default' => '{}',
        );
        return $data;
    }

}
