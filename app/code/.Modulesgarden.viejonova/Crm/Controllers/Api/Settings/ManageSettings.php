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
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class ManageSettings extends AbstractController
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
     * Get statuses available for resources
     *
     * @return array
     */
    public function query()
    {
        try {
//            throw new Exception('Didnt found requested status');
            $statuses = $this->repository->get();

        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($statuses->toArray());
    }


    public function updateFieldsMap()
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);

            if(!empty($requestData))
            {

                $model = Setting::where('name', '=', 'fields_map')->where('admin_id', '=', '0')->first();

                if(is_null($model))
                {
                    $model = new Setting(array(
                        'name' => 'fields_map',
                        'admin_id' => 0,
                    ));
                }

                $model->value = $requestData;
                $model->save();

                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'Fields Map has been updated',
                ));

            } else {
                throw new Exception('Setting not found');
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

    public function getFieldsMap()
    {

        try {
            $mapped = Setting::where('name', '=', 'fields_map')->where('admin_id', '=', '0')->first();

            return $this->returnData(array(
                'status'  => 'success',
                'value'   => is_null($mapped) ? array() : $mapped->value,
            ));

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
