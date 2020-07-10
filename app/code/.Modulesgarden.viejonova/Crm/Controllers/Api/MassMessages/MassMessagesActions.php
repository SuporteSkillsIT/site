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


namespace Modulesgarden\Crm\Controllers\Api\MassMessages;

use Modulesgarden\Crm\Controllers\Source\AbstractController;

use Modulesgarden\Crm\Repositories\MassMessageConfigs;

class MassMessagesActions extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new MassMessageConfigs();
    }


    /**
     * Get fields
     *
     * @return array
     */
    public function addConfig()
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $new = $this->repository->addMassMessageConfig($requestData);

            if( $new ) {
                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'New Mass Message config has been created',
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
     * Parse for table
     *
     * @param type $id
     * @return type
     */
    public function getForTable()
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) {
                $requestData = array();
            }

            $this->returnData($this->repository->parseForTable($requestData));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

    public function updateConfig($id)
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            $requestData = is_array($requestData) ? $requestData : array();

            $model = $this->repository->updateMassMessagesConfig($id, $requestData);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Mass Message has been updated',
                'new'    => $model->toArray(),
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

    public function getSingle($id)
    {
        try {

            return $this->returnData($this->repository->getMassMessagesConfig($id));

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


    public function deleteConfig($id)
    {
        try {
            $this->repository->deleteMassMessagesConfig($id);

            $return = array(
                'status' => 'success',
                'msg'    => 'Mass Message configuration has been deleted',
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
