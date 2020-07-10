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
use Modulesgarden\Crm\Repositories\Reminders;
use Modulesgarden\Crm\Models\Magento\Admin;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class ResourcesFollowupReminders extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Reminders();
    }


    /**
     * Get reminders
     *
     * @param type $id          resource id (lead/potential)
     * @param type $followupID  followup id
     * @return type             eloquent collection
     * @throws Exception
     */
    public function get($id, $followupID)
    {
        try {

            return $this->returnData($this->repository->getForResourceAndFollowup($id, $followupID)->toArray());

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
     * Get single specified reminder
     *
     * @param type $id          resource id (lead/potential)
     * @param type $followupID  followup id
     * @return type             eloquent collection
     * @throws Exception
     */
    public function getSingle($id, $followupID, $reminderID)
    {
        try {

            $reminder = $this->returnData($this->repository->getForResourceAndFollowupAndReminder($id, $followupID, $reminderID)->toArray());

            if( ! $reminder ) {
                $this->app->response->setStatus(404);
                return $this->returnData(array(
                    'status' => 'error',
                    'msg'    => 'Not Found',
                ));
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
     * Attempt to manually create single Reminder
     *
     * @param type $id
     * @param type $followupID
     * @return type
     * @throws Exception
     */
    public function createSingle($id, $followupID)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $new = $this->repository->createSingleReminder($id, $followupID, $requestData);

            if( $new ) {
                return $this->returnData($new->toArray());
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
     * Attempt to manually update single Reminder parameters
     *
     * @param type $id
     * @param type $followupID
     * @return type
     * @throws Exception
     */
    public function updateSingle($id, $followupID, $reminderID)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $model = $this->repository->updateSingleReminder($id, $followupID, $reminderID, $requestData);

            if( $model ) {
                return $this->returnData($model->toArray());
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
     * Attempt to deletesingle Reminder parameters
     *
     * @param type $id
     * @param type $followupID
     * @return type
     * @throws Exception
     */
    public function deleteSingle($id, $followupID, $reminderID)
    {
        try {

            $model = $this->repository->deleteSingle($id, $followupID, $reminderID);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Reminder has been deleted',
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
