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

// namespace DebugBar\DataCollector;

use \Slim\Slim;
use \DebugBar\DataCollector\DataCollector;
use \DebugBar\DataCollector\Renderable;

class SlimEnvCollector extends DataCollector implements Renderable
{

    /**
     * @var \Slim\Slim
     */
    protected $slim;

    public function __construct(Slim $slim)
    {
        $this->slim = $slim;
    }

    public function collect()
    {
        return $this->slim->getMode();
    }

    public function getName()
    {
        return 'slim';
    }

    public function getWidgets()
    {
        $slim_version = Slim::VERSION;
        $php_version = PHP_VERSION;

        return array(
            'mode' => array(
                'icon' => 'info',
                'tooltip' => "Slim {$slim_version} | PHP {$php_version}",
                'map' => 'slim',
                'default' => '',
            )
        );
    }

}
