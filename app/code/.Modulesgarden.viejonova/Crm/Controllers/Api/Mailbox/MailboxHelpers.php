<?php

/* * *************************************************************************************
 *
 *
 *                  ██████╗██████╗ ███╗   ███╗         Customer
 *                 ██╔════╝██╔══██╗████╗ ████║         Relations
 *                 ██║     ██████╔╝██╔████╔██║         Manager
 *                 ██║     ██╔══██╗██║╚██╔╝██║
 *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For WHMCS
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

namespace Modulesgarden\Crm\Controllers\Api\Mailbox;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Helpers\TablesFieldViews;
use Modulesgarden\Crm\Repositories\Mailboxes;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class MailboxHelpers extends AbstractController
{

    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Mailboxes();
    }

    /**
     * Finally create mailbox
     *
     * @return type
     */
    public function createMailbox()
    {
        $json = $this->app->request->getBody();
        $data = json_decode($json, true);

        try {
            $new = $this->repository->createMailbox($data);

            $return = array(
                'status' => 'success',
                'msg' => 'New Mailbox has been created',
                'new' => $new->toArray(),
            );
        } catch (\Exception $ex) {
            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg' => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }

    public function getMailboxList()
    {

        try {


            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $result = $this->repository->getMailboxListTableQuery($requestData);

            return $this->returnData($result);
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

    public function getMailbox($id)
    {
        try {

            $resultArray = $this->repository->getMailbox($id);
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

    public function updateMailbox($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $mailbox = $this->repository->updateMailbox($id, $requestData);

            return $this->returnData(array(
                        'status' => 'success',
                        'msg' => 'Mailbox has been updated',
                        'new' => $mailbox->toArray(),
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

    public function deleteMailbox($id)
    {
        try {
            $new = $this->repository->deleteMailbox($id);

            $return = array(
                'status' => 'success',
                'msg' => 'Mailbox has been deleted',
            );
        } catch (\Exception $ex) {
            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg' => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }

}
