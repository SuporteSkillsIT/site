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


namespace Modulesgarden\Crm\Controllers\Api\Settings;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Models\Setting;
use Modulesgarden\Crm\Models\Magento\EmailTemplates;
use Modulesgarden\Crm\Services\Monitor;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class GeneralSettings extends AbstractController
{
    /**
     * Constructor
     * set repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new \Modulesgarden\Crm\Repositories\Settings();
    }

    /**
     * Get global app settings
     *
     * @return array
     */
    public function get()
    {
        try {

            $configs = $this->repository->getGlobal();
            return $this->returnData($configs);

        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    /**
     * Get global app settings
     * with app integration status
     * cron etc
     *
     *
     * @return array
     */
    public function getWithStatus()
    {
        try {

            $monitor = Monitor::getInstance();

            $return = array(
                'global'    => $this->repository->getGlobal(),
                'templates' => EmailTemplates::forSelect()->onlyCrmType()->get()->toArray(),
                'status'    => array(
                   // 'integrations'  => $monitor->checkIntegrations(),
                    'emails'        => $monitor->getEmailTemplatesNum(),
                    'cron'          => $monitor->checkCronStatus(),
                ),
            );

            return $this->returnData($return);

        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

    /**
     * Trigger update global settings in repository
     *
     * @return type
     * @throws Exception
     */
    public function update()
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);

            if(!empty($requestData))
            {
                $this->repository->updateGlobals($requestData);

                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'Global Settings have been updated',
                ));

            } else {
                throw new Exception('Empty Data');
            }
        } catch (\Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
        
    }


}
