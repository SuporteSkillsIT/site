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


namespace Modulesgarden\Crm\Controllers\Api\Files;

use Modulesgarden\Crm\Controllers\Source\AbstractController;

use Modulesgarden\Crm\Repositories\Files;

use \Exception;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class Manage extends AbstractController
{
    /**
     * constructor
     * set up repository model
     * @use \Modulesgarden\Crm\Repositories\Files
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository   = new Files();
    }

    /**
     * Handle file upload for certain resource
     *
     * @return array
     */
    public function uploadFile($id)
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

            $new = $this->repository->addFileForResource($id, $files, $request);

            if( $new ) {
                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'File has been added',
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
                'trace'    => $ex->getTrace(),
            ));
        }
    }



    /**
     * Parse followups for table
     *
     * @param type $id
     * @return type
     */
    public function parseForTable($id)
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
     * Prepare file for download and thrown it to browser
     *
     * @param type $id
     * @return type
     */
    public function getFile($id, $fileId)
    {
        try {

            $this->returnData($this->repository->getFile($id, $fileId));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(404);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    /**
     * remove file
     *
     * @param type $id
     * @return type
     */
    public function deleteFile($id, $fileId)
    {
        try {

            $this->repository->deleteFile($id, $fileId);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'File has been deleted',
            ));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(404);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

}
