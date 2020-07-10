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


namespace Modulesgarden\Crm\Controllers\Api\Campaigns;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Helpers\TablesFieldViews;
use Modulesgarden\Crm\Repositories\Campaigns;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class CampaignsHelpers extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Campaigns();
    }

    /**
     * Give me list of all fields to generate create campaign filters
     *
     * @return array
     */
    public function getAllColumnsForFilters()
    {
        try {

            $available = TablesFieldViews::getAllColumnsForFilters();


        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($available);
    }

    /**
     * Check how many records will mach campaign filters
     * used in form for create campaign
     *
     * @return type
     */
    public function ResourcesTableQueryByFilters()
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();
            
            $result = $this->repository->ResourcesTableQueryByFilters($requestData);

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

    /**
     * Finally create campaign
     *
     * @return type
     */
    public function createCampaign()
    {
        $json = $this->app->request->getBody();
        $data = json_decode($json, true);

        try {
            $new = $this->repository->createCampaign($data);

            $return = array(
                'status' => 'success',
                'msg'    => 'New Campaign has been created',
                'new'    => $new->toArray(),
            );
        } catch (\Exception $ex) {
            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);

    }


    public function getCampaignList()
    {

        try {
            
            // api call
            if($this->app->config('isWhmcsAPIcall') === true) {
                return $this->returnData($this->repository->getCampaignsForAPI());
            }


            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $result = $this->repository->getCampaignListTableQuery($requestData);

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


    public function refreshCampaignAssignments($id)
    {
        try {

            $result = $this->repository->syncCampaignResourcesByFilters($id);

            if($result === true) 
            {
                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'Records synchronization with the campaign filters has been  completed',
                ));

            } else {
                throw new Exception('Something went wrong');
            }

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

    public function getCampaign($id)
    {
        try {

            $resultArray = $this->repository->getCampaign($id);

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


    public function updateCampaign($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $campaign = $this->repository->updateCampaign($id, $requestData);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Campaign has been updated',
                'new'    => $campaign->toArray(),
            ));

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

    public function deleteCampaign($id)
    {
        try {
            $new = $this->repository->deleteCampaign($id);

            $return = array(
                'status' => 'success',
                'msg'    => 'Campaign has been deleted',
            );
        } catch (\Exception $ex) {
            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }

}
