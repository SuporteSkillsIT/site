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
class ResourcesList extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new FieldGroups();
    }


    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function query($query)
    {
        try {
            $resultArray = $this->repository->orderBy('order', 'ASC')->get();
            $resultArray = $resultArray->toArray();
//            ~r($resultArray);

//            $groups = $this->repository->all();

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }


    public function LeadsTableQuery()
    {

        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $repo   = new \Modulesgarden\Crm\Repositories\Resources();
            $result = $repo->parseLeadsForTable($requestData);

            return $this->returnData($result);

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function TrashedTableQuery()
    {

        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $repo   = new \Modulesgarden\Crm\Repositories\Resources();
            $result = $repo->parseTrashedForTable($requestData);

            return $this->returnData($result);

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function PotentialsTableQuery()
    {

        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $repo   = new \Modulesgarden\Crm\Repositories\Resources();
            $result = $repo->parsePotentialsForTable($requestData);

            return $this->returnData($result);

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function createLead()
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $resource = new \Modulesgarden\Crm\Repositories\Resources();
            $created = $resource->createNewLead($requestData);

            return $this->returnData(array(
                'status' => 'succes',
                'msg'    => 'Contact Has been created',
                'new'     => $created->toArray(),
            ));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    public function getCreateParams()
    {

        try {
            $resultArray = array();

            // need to get current admin
            $resultArray['currentAdmin'] = $this->app->currentAdmin->toArray();

            $admins = Admin::select(array(
                    'user_id',
                    'username',
                    'firstname',
                    'lastname',
                    'email',
                    DB::raw("CONCAT(firstname, ' ', lastname) as name")
                ))
               // ->whereIn('roleid', $this->app->acl->getAssignedAccessRoles())
                ->where('is_active', '=', 1)
                ->get();

            // need to get possible admin list to change
            $resultArray['admins'] = $admins->toArray();


            // need to get possible statuses
            $statussesRepository = new FieldStatuses();
            $statusses               = $statussesRepository->getActive();
            $resultArray['statuses'] = $statusses->toArray();

            // need to get fields groups with fields parsed by active and correct order
            // need to get field validators
            $repository = new FieldGroups();
            $fields     = $repository->withFieldsAndValidators();

            $resultArray['fieldGroups'] = $fields->toArray();


            return $this->returnData($resultArray);

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
