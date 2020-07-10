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

namespace Modulesgarden\Crm\Controllers\Api\Resources;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Models\Resource;
use \Exception;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class ResourceSingle extends AbstractController
{

    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function getMainDetails($id)
    {
        try {

            $resource = Resource::withTrashed()
                    ->with(array('admin', 'status', 'client', 'campaigns'))
//                                            ->with(array('status', 'ticket', 'client'))
//                                            ->with(array('campaigns' => function($query){
//                                                return $query->select('crm_campaigns.id');
//                                            }))
                    ->find($id);

            // dont return fat campaigns, just ID's
            $resultArray = $resource->toArray();
            array_set($resultArray, 'campaigns', $resource->campaigns->lists('id'));
        } catch (Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function syncAssignedCampaigns($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if (empty($requestData))
                $requestData = array();

            $resource = Resource::withTrashed()->find($id);
            $resource->syncAssignedCampaigns($requestData);

            return $this->returnData(array(
                        'status' => 'success',
                        'msg' => 'Campaigns has been updated',
                        'updated_at' => $resource->updated_at->toDateTimeString(),
            ));
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function updateSingleParam($id)
    {

        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if (empty($requestData))
                $requestData = array();

            $resource = Resource::withTrashed()->find($id);
            $resource->updateSingleParam($requestData);

            return $this->returnData(array(
                        'status' => 'success',
                        'msg' => 'Parameter has been updated',
                        'updated_at' => $resource->updated_at->toDateTimeString(),
            ));
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function reassignClient($id)
    {

        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            $clientID = array_get($requestData, 'client_id', false);
            $unassign = array_get($requestData, 'unassign', false);

            $resource = Resource::withTrashed()->find($id);

            if ($unassign === true) {
                $resource->unassignClient();
                $return = array(
                    'status' => 'success',
                    'msg' => 'Client has been unassigned',
                    'updated_at' => $resource->updated_at->toDateTimeString(),
                    'client_id' => $resource->client_id,
                    'client' => false,
                );
            } else {
                $resource->reassignClient($clientID);

                $return = array(
                    'status' => 'success',
                    'msg' => 'Client has been assigned',
                    'updated_at' => $resource->updated_at->toDateTimeString(),
                    'client_id' => $resource->client_id,
                    'client' => $resource->client->toArray(),
                );
            }


            return $this->returnData($return);
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function softDelete($id)
    {

        try {

            $requestData = json_decode($this->app->request->getBody(), true);

            $resource = Resource::find($id);
            $resource->delete();

            return $this->returnData(array(
                        'status' => 'success',
                        'msg' => 'Contact has been sent to Archive',
                        'updated_at' => $resource->updated_at->toDateTimeString(),
                        'deleted_at' => $resource->deleted_at->toDateTimeString(),
            ));
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function restoreSoftDeleted($id)
    {

        try {

            $requestData = json_decode($this->app->request->getBody(), true);

            $resource = Resource::withTrashed()->find($id);
            $resource->restore();

            return $this->returnData(array(
                        'status' => 'success',
                        'msg' => 'Contact has been restored from Archive',
                        'updated_at' => $resource->updated_at->toDateTimeString(),
                        'deleted_at' => null,
            ));
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    public function setResourceSessionId()
    {
        session_start();
        $requestData = json_decode($this->app->request->getBody(), true);
        $_SESSION['modulesgarden_crm_resource_id'] = $requestData['query'];
        return $this->returnData(array(
                    'status' => 'success',
                    'msg' => 'Resource Session id set succesfully',
        ));
    }

}
