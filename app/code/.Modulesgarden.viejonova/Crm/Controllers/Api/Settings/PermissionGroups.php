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
use Modulesgarden\Crm\Models\Magento\AdminRole;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class PermissionGroups extends AbstractController
{
    /**
     * Constructor
     * set repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new \Modulesgarden\Crm\Repositories\PermissionRoles();
    }

    /**
     * get available to configure admin roles
     * keep on mind that it wont return not allowed groups and full admins
     *
     * @return array
     */
    public function getMagentoAdminRoles()
    {
        try {

            $ids = AdminRole::getAssignable();
            if( empty($ids) ){
                return $this->returnData($ids);
            }

            $roles = AdminRole::ByIds($ids)->get();

        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($roles->toArray());
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        try {

            $statuses = $this->repository->all();

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
     * add new role
     *
     * @return array
     */
    public function addRole()
    {
        $json = $this->app->request->getBody();
        $data = json_decode($json, true);

        try {
            $newStatus = $this->repository->create($data);

            $return = array(
                'status' => 'success',
                'msg'    => 'New Role has been created',
                'new'    => $newStatus->toArray(),
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
     * Update single atribute to role
     *
     * @param type $id
     * @return type
     */
    public function updateRole($id)
    {

        // get from request
        $data  = json_decode($this->app->request->getBody(), true);

        try {
            // perform uprate
            $result  =  $this->repository->updateSingleParamInModel($id, $data);

            $return = array(
                'status' => 'success',
                'msg'    => 'Role has been updated',
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
     * Simple enough, triger delete from DB
     *
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function deleteRole($id)
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
     * Get from ACL all possibe rules
     * just in case we may need this
     *
     * @return type
     */
    public function getParsedRules()
    {
        return $this->returnData($this->app->acl->getRules());
    }


    /**
     * Get from ACL all possibe rules by config format
     * usefull for generating this
     *
     * @return type
     */
    public function getRulesConfig()
    {
        return $this->returnData($this->app->acl->getRulesConfig());
    }

    /**
     * Obrain array with actual user permisions
     *
     */
    public function getMinePermissions()
    {
        $r = $this->app->acl->getCurrentAdminRules();

//        r(array_flat($r));
//        ~r($r);

//        r($this->app->acl->hasAccess('settings.general'));
//        r($this->app->acl->getRuleName('settings.general'));

        return $this->returnData($r);
//        return $this->returnData($this->app->acl->getMinePermissions());
    }
}
