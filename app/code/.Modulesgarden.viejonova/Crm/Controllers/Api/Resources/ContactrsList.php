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


namespace Modulesgarden\Crm\Controllers\Api\Resources;

use Modulesgarden\Crm\Controllers\Source\AbstractController;

use Modulesgarden\Crm\Repositories\FieldGroups;
use Modulesgarden\Crm\Repositories\FieldStatuses;

use Modulesgarden\Crm\Models\FieldGroup;
use Modulesgarden\Crm\Models\Validators\Common;
use Modulesgarden\Crm\Models\Field;
use Modulesgarden\Crm\Models\Magento\Admin;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class ContactrsList extends AbstractController
{
    public function queryTable($id)
    {

        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $repo   = new \Modulesgarden\Crm\Repositories\Resources();

            // if its from API, modify some request body for incompatibility
            if($this->app->config('isWhmcsAPIcall') === true)
            {
                if(array_get($requestData, 'status') != null) {
                    array_set($requestData, 'params.search.predicateObject.status_id', array_get($requestData, 'status'));
                }
            }

            return $this->returnData($repo->parseRepositoriesForTable($requestData, false, $this->app->config('isWhmcsAPIcall')));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

    public function queryArchiveTable()
    {

        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $repo   = new \Modulesgarden\Crm\Repositories\Resources();

            return $this->returnData($repo->parseRepositoriesForTable($requestData, true));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

}
