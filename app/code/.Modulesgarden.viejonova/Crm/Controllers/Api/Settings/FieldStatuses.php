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
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class FieldStatuses extends AbstractController
{
    /**
     * Constructor
     * set repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new \Modulesgarden\Crm\Repositories\FieldStatuses();
    }

    /**
     * Get statuses available for resources
     *
     * @return array
     */
    public function query()
    {
        try {
//            throw new Exception('Didnt found requested status');
            $statuses = $this->repository->orderBy('order', 'ASC')->get();

        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($statuses->toArray());
    }


    /**
     * Get statuses available for resources
     * with Counter. Used in dashboard
     *
     * @return array
     */
    public function queryDashboard($id)
    {
        try {
            
            $statuses = $this->repository->getWithCounter($id);

            return $this->returnData($statuses);

        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


    }


    /**
     * Create New Status for resource
     *
     * @return array
     */
    public function add()
    {
        $json = $this->app->request->getBody();
        $data = json_decode($json, true);

        try {
            $newStatus = $this->repository->create($data);

            $return = array(
                'status' => 'success',
                'msg'    => 'New Status has been created',
                'new'    => $newStatus->toArray(),
            );
        } catch (\Exception $ex) {

            // some logging errors mechanism

            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        

        return $this->returnData($return);
    }



    /**
     * Create New Status for resource
     *
     * @return array
     */
    public function delete($id)
    {

        $status = $this->repository->find($id);

        try {

            if(! $status) {
                throw new Exception('Didnt found requested status');
            }

//            $status->forceDelete();
            $status->safeDelete();

            $return = array(
                'status' => 'success',
                'msg'    => 'Status has been deleted',
            );
        } catch (Exception $e) {
            $this->app->response->setStatus(404);

            // some logging errors mechanism

            $return = array(
                'status' => 'error',
                'msg'    => $e->getMessage(),
            );
        }



        return $this->returnData($return);
    }


    /**
     * Get statuses available for resources
     *
     * @return array
     */
    public function massUpdate()
    {
        try 
        {
            // get from request
            $data = json_decode($this->app->request->getBody(), true);

            // flip keys
            $toUpdate = array_flip_keys_by($data, 'id');

            if ( ! $this->repository->massUpdate($toUpdate) ) {
                Throw new Exception('Something Went Wrong');
            }

            $return = array(
                'status' => 'success',
                'msg'    => 'Statuses has been updated',
            );

        } catch (Exception $e) {

            // some logging errors mechanism would be nice later
            // @todo

            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $e->getMessage(),
            );
        }

        return $this->returnData($return);
    }



    /**
     * Reorder all statuses
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
                    'msg'    => 'Order Has has been updated',
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
     * Get statuses available for resources.
     * Return resource statuses
     * counters, how many resource status got
     *
     * Usate: requested for certain "admin"(or all), and resource type
     *
     * @return array
     */
    public function generateSummary($id)
    {
        try {

            $json = $this->app->request->getBody();
            $data = json_decode($json, true);

            $statuses = $this->repository->generateSummaryWithCounters($data);

            return $this->returnData($statuses);

        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


    }
}
