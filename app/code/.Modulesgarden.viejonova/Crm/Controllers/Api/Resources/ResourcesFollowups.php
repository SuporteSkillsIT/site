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

use Modulesgarden\Crm\Repositories\Logs;
use Modulesgarden\Crm\Repositories\Followups;
use Modulesgarden\Crm\Models\Magento\Admin;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class ResourcesFollowups extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Followups();
    }


    /**
     * Get fields
     *
     * @return array
     */
    public function addWithReminders($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $new = $this->repository->createWithReminders($id, $requestData);

            if( $new ) {
                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'New Follow-up has been created',
                    'new'    => $new->toArray(),
                ));
            } else {
                throw new Exception('Something went wrong.');
            }

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }

    }


    /**
     * Get fields
     *
     * @return array
     */
    public function addWithoutReminders($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $new = $this->repository->createFollowup($id, $requestData);

            if( $new ) {
                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'New Follow-up has been created',
                    'new'    => $new->toArray(),
                ));
            } else {
                throw new Exception('Something went wrong.');
            }

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }

    }

    /**
     * Parse followups for table
     *
     * @param type $id
     * @return type
     */
    public function getForTable($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) {
                $requestData = array();
            }

            $this->returnData($this->repository->parseForTable($id, $requestData));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

    /**
     * Obtain single followup record
     *
     * @param type $id
     * @param type $followupID
     * @return type
     */
    public function getSingleFollowup($id, $followupID)
    {
        try {

            $this->returnData($this->repository->getSingle($id, $followupID)->toArray());

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    /**
     * Obtain single followup record
     * with reminders attached
     *
     * @param type $id
     * @param type $followupID
     * @return type
     */
    public function getSingleFollowupWithReminders($id, $followupID)
    {
        try {

            $this->returnData($this->repository->getSingleFollowupWithReminders($id, $followupID)->toArray());

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    /**
     * Update followup details
     *
     * @param type $id
     * @param type $followupID
     * @return type
     * @throws Exception
     */
    public function updateSingleFollowup($id, $followupID)
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $followup = $this->repository->updateSingleFollowup($id, $followupID, $requestData);

            if( $followup ) {
                return $this->returnData($followup->toArray());
            } else {
                throw new Exception('Something went wrong.');
            }

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

    
    /**
     * Drop followup with all reminders 
     * 
     * @param type $id
     * @param type $followupID
     * @return type
     * @throws Exception
     */
    public function deleteSingleFollowup($id, $followupID)
    {
        try {

            $followup = $this->repository->deleteSingleFollowup($id, $followupID);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Follow-up has been deleted',
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


    /**
     * Reschedue followup
     *
     * @param type $id
     * @param type $followupID
     * @return type
     * @throws Exception
     */
    public function reschedueFollowup($id, $followupID)
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $this->repository->reschedueFollowup($id, $followupID, $requestData);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Follow-up has been reschedued',
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
}
