<?php
/***************************************************************************************
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
 **************************************************************************************/


namespace Modulesgarden\Crm\Controllers\Source;

use \Modulesgarden\Crm\Integration\Slim\SlimApp;

abstract class AbstractController
{
    /**
     * Container for Slim instance
     *
     * @var type
     */
    protected $app = null;

    /**
     * Just assign App instance to container
     */
    public function __construct()
    {
        $this->app = SlimApp::getInstance();
    }

    /**
     * Kind of tricky way to return data, but surprising this work
     * This is handled by middleware, either you want json object or debug page with data inserted here
     *
     * @param  $data     to show as response
     * @return View instance
     */
    protected function returnData($data)
    {
//        if($this->app->config('isWhmcsAPIcall') === true) {
//            $data = $this->reFilterOutputForAPI($data);
//        }

        return $this->app->render("responseDebug.twig", $data);
    }

    /**
     * we could convert output for some different format for whmcs api calls
     * but i have no idea to what other format, so lets skip this for now
     *
     * @param type $data
     * @return type
     */
    protected function reFilterOutputForAPI($data)
    {
        return $data;
    }
}