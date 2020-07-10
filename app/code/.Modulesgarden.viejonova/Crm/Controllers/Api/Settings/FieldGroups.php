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


namespace Modulesgarden\Crm\Controllers\Api\Settings;

use Modulesgarden\Crm\Controllers\Source\AbstractController;

use Modulesgarden\Crm\Repositories\FieldGroups as FieldsGroupRepository;
use Modulesgarden\Crm\Models\FieldGroup;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class FieldGroups extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new FieldsGroupRepository();
    }


    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function query()
    {
        try {

            $groups = $this->repository->orderBy('order', 'ASC')->get();
//            $groups = $this->repository->all();

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($groups->toArray());
    }


    /**
     * Reorder all groups
     *
     * @return array
     */
    public function reorder()
    {
        try {

            $json = $this->app->request->getBody();
            $data = json_decode($json, true);

            $newOrder = array_flip(array_get($data, 'order', array()));

            if( $this->repository->reorder($newOrder) ) {
                $return = array(
                    'status' => 'success',
                    'msg'    => 'Order has been updated',
                );
            } else {
                throw new Exception('Something went wrong.');
            }


        } catch (Exception $e) {

            // some logging errors mechanism

            $this->app->response->setStatus(404);
            $return = array(
                'status' => 'error',
                'msg'    => $e->getMessage(),
            );
        }


        return $this->returnData($return);
    }


    /**
     * add new fields group
     *
     * @return array
     */
    public function addGroup()
    {
        $json = $this->app->request->getBody();
        $data = json_decode($json, true);

        try {
            $new = $this->repository->create($data);

            if( $new ) {
                $return = array(
                    'status' => 'success',
                    'msg'    => 'New Group has been created',
                    'new'    => $new->toArray(),
                );
            } else {
                throw new Exception('Something went wrong.');
            }


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

    
    /**
     * Simple enough, triger delete from DB
     *
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function deleteGroup($id)
    {
        try {

            $result  =  $this->repository->delete($id);

            if( ! $result) {
                throw new Exception('Something Went Wrong. Could not deleted Role.');
            }

            $return = array(
                'status' => 'success',
                'msg'    => 'Role has been deleted',
            );
        } catch (Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }

    
    /**
     * Update single atribute to role
     *
     * @param type $id
     * @return type
     */
    public function updateGroup($id)
    {
        // get from request
        $data  = json_decode($this->app->request->getBody(), true);

        try {
            // perform uprate
            $result  =  $this->repository->updateSingleParamInModel($id, $data);

            $return = array(
                'status' => 'success',
                'msg'    => 'Group has been updated',
                'new'    => $result->toArray(),
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


    /**
     * Obtain fields groups with associated to them fields
     *
     * @return type
     */
    public function queryFields()
    {
        try {

            $groups = $this->repository->getModel()->orderBy('order', 'ASC')->with('fieldsOrderred')->get();


        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($groups->toArray());
    }
}
