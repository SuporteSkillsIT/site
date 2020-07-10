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

use \Modulesgarden\Crm\Repositories\Settings;
use \Modulesgarden\Crm\Repositories\ResourceTypes;
use \Modulesgarden\Crm\Services\Monitor;
use \Modulesgarden\Crm\Helpers\MigrationHelper;
use \DOMElement;

class TemplateVariables extends \Slim\Middleware
{

    /**
     * Call
     */
    public function call()
    {
        // do not inject additional variables when output is json
        if (!($this->app->view instanceof \Modulesgarden\Crm\Integration\Slim\JsonView)) {
            $app = $this->app;
            $data = $this->getSharedData();

            $app->hook('slim.before.dispatch', function () use (&$app, &$data) {
                foreach ($data as $key => $value) {
                    $app->view()->set($key, $value);
                }
            }, 5); // this is damm priority
        }

        $this->next->call();
    }

    /**
     * much much much staff will be here
     *
     * @return array
     */
    protected function getSharedData()
    {
        $data = array();

        // This are global app settings
        $globalSettings = $this->app->container->settings;
        foreach ($globalSettings as $key => $value) {
            if (!is_object($value)) {
                array_set($data['settings'], $key, $value);
            }
        }

        // get configurations variable
        $configFileContainer = $this->app->container->get('configFile');
        $configFile = $configFileContainer->getItems();
        $configFile = reset($configFile);
        $data['configFile'] = $configFile;
        //echo $this->app->get('viewFileUrl');exit; 
        array_set($data['settings'], 'templates.rootDir', $this->app->config('viewFileUrl'));


        // integration with whmcs, we need to fix some html content first :D
        if ($this->app->config('skipWHMCS') === false) {



            //       $CRM_ADMINAREA_OUTPUT = str_replace('{{head}}', $headHtml->saveHTML($head), $CRM_ADMINAREA_OUTPUT);
        }


        // set global variables for template


        array_set($data['settings'], 'templates.renderStandalone', true);

        // some of template stuff
        //  array_set($data['settings'], 'templates.template', $configFileContainer->get('app.templates.template'));
        // merge frontend settings with one from config
        array_set($data['settings'], 'templates', array_merge($data['settings']['templates'], $configFileContainer->get('app.templates')));
//        array_set($data['settings'], 'templates.appFilename', $this->app->config('appFilename'));
        array_set($data['settings'], 'templates.apiURL', $this->app->config('apiUrl'));
        array_set($data['settings'], 'templates.customerCreateUrl', $this->app->config('customerCreateUrl'));
        array_set($data['settings'], 'templates.viewOrderUrl', $this->app->config('viewOrderUrl'));
        array_set($data['settings'], 'templates.viewCustomerUrl', $this->app->config('viewCustomerUrl'));
        array_set($data['settings'], 'templates.viewInvoiceUrl', $this->app->config('invoiceUrl'));
        array_set($data['settings'], 'templates.createOrderUrl', $this->app->config('createOrderUrl'));
//        array_set($data['settings'], 'templates.appAdminDir', $this->app->config('appAdminDir'));
        array_set($data['settings'], 'templates.appAdminUrl', substr($this->app->config('apiUrl'), 0, strpos($this->app->config('apiUrl'), 'crm/api/call')));
        array_set($data['settings'], 'templates.appDir', $this->app->config('appDir'));
        // array_set($data['settings'], 'templates.whmcsDir', $this->app->config('whmcsDir'));
        array_set($data['settings'], 'url_scheme', $this->app->environment['slim.url_scheme']);
        array_set($data['settings'], 'isSecured', ($this->app->environment['slim.url_scheme'] === 'https'));

        // push variables to render navigation
        array_set($data['settings'], 'dynamicTypes', ResourceTypes::getForNavigation());

        // bunch of confugurable settings
        array_set($data['settings'], 'templates.app', Settings::getGlobalsForTwig());
        // integration statuses for global access
        array_set($data['settings'], 'templates.integrations', Monitor::getInstance()->checkIntegrations());

        $data['lang'] = $this->app->lang;
        $data['acl'] = $this->app->acl;
        $data['currentAdmin'] = $this->app->currentAdmin->user_id;

        return $data;
    }

}
