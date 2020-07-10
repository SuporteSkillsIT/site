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

use Modulesgarden\Crm\Repositories\EmailLogs;
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Models\Log;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class ResourcesLogEmails extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new EmailLogs();
    }


    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function get($id)
    {
        try {
            $resultArray = $this->repository->orderBy('date', 'DESC')->withResource($id)->get();
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


    public function parseForTable($id)
    {

        try {
            $requestData = json_decode($this->app->request->getBody(), true);

            $result = $this->repository->parseForTable($id, $requestData);

            return $this->returnData($result);


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
     * This request is an a exception
     * In order to process files this is standard form send by POST method
     * no json encoded request body
     *
     * @param type $id  resource ID
     * @return type
     */
    public function sendEmail($id)
    {
        try {

            if( is_array($_FILES) && !empty($_FILES) ) {
                $files   = $_FILES;
            } else {
                $files   = array();
            }

            if( is_array($_REQUEST) && !empty($_REQUEST) ) {
                $request   = $_REQUEST;
            } else {
                $request   = array();
            }

            $result = $this->repository->sendRawEmail($id, $request, $files);

            if( $result ) {
                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'Emai has been sent',
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
                'trace'  => $ex->getTrace(),
            ));
        }


    }

}
