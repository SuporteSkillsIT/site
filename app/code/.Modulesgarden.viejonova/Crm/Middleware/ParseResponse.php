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

namespace Modulesgarden\Crm\Middleware;

class ParseResponse extends \Slim\Middleware
{

    /**
     * Call
     */
    public function call()
    {
        $requestedResponse = explode('/', $this->app->request->getPath());

        // fix in some cases it contain wrong enviroment values
        // dammit
        if (ends_with($this->app->request->getPath(), '/')) {
            $requestedResponse = strtolower($requestedResponse[count($requestedResponse) - 2]);
        } else {
            $requestedResponse = strtolower(last($requestedResponse));
        }

        if ($requestedResponse == 'json' || $this->app->request->isAjax()) {
            $this->prepareJsonView();
        }

        // $this->app->getLog()->warning('ParseResponse before call');
        $this->next->call();
        // $this->app->getLog()->warning('ParseResponse after call');
    }

    protected function prepareJsonView()
    {
        $app = &$this->app;
        $app->view(new \Modulesgarden\Crm\Integration\Slim\JsonView());

        // on error
        // this works only for production mode (when debug is off) Slim fucked up parsing :D
        // so we are handle this in  PrettyExceptions middleware
        // not found
        $app->notFound(function() use ($app) {
            $return = array(
                'error' => 'The page you are looking for could not be found. Check the address bar to ensure your URL is spelled correctly',
                'status' => '404',
            );
            $app->view->display('kurwa.twig', $return); //since i had to put some file name :D

            $app->halt(404, ob_get_clean());
        });
    }

}
