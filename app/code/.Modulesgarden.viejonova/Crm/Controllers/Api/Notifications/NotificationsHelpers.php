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


namespace Modulesgarden\Crm\Controllers\Api\Notifications;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Repositories\Notifications;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class NotificationsHelpers extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Notifications();
    }

    /**
     * Finally create campaign
     *
     * @return type
     */
    public function createNotification()
    {
        $json = $this->app->request->getBody();
        $data = json_decode($json, true);

        try {
            $new = $this->repository->createNotification($data);

            $return = array(
                'status' => 'success',
                'msg'    => 'New Notification has been created',
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

    public function getNotificationList()
    {

        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $result = $this->repository->getNorificationsListTableQuery($requestData);

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

    public function getMineNotification()
    {

        try {
            // if its from API, we require provided admin ID
            if($this->app->config('isWhmcsAPIcall') === true)
            {
                $requestData = json_decode($this->app->request->getBody(), true);
                $adminID     = array_get($requestData, 'adminID');
            } else {
                $adminID = $this->app->currentAdmin->user_id;
            }

            
            $result = $this->repository->getMineNotificationList($adminID);

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

    public function deleteNotification($id)
    {
        try {
            $new = $this->repository->deleteNotification($id);

            $return = array(
                'status' => 'success',
                'msg'    => 'Notification has been deleted',
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

    public function acceptNotification($id)
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $this->repository->acceptNotification($requestData, $this->app->currentAdmin->user_id);

            $return = array(
                'status' => 'success',
                'msg'    => 'Notification has been accepted',
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



    public function getNotification($id)
    {
        try {

            $resultArray = $this->repository->getNotification($id);

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


    public function updateNotification($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $edited = $this->repository->updateNotification($id, $requestData);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Notification has been updated',
                'new'    => $edited->toArray(),
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

}
