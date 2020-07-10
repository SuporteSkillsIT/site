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


namespace Modulesgarden\Crm\Repositories;


use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\PermissionRole;

/**
 * Repository pattern for Notifications
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class PermissionRoles extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\PermissionRole
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\PermissionRole';
    }


    /**
     * Wrapper to handle udpate only certain parameters for model
     * usefull when we dont want to push every single parameter
     * Find model, and trigger correct method
     *
     * @param type $id
     * @param type $data
     * @return \Modulesgarden\Crm\Models\PermissionRole
     * @throws Exception
     */
    public function updateSingleParamInModel($id, $data)
    {
        // get model
        $model =  $this->find($id);

        if( ! $model) {
            throw new Exception("Couldnt find role with id {$id}");
        }

        if( ! $model->updateSingleParam($data)) {
            throw new Exception("Something went wrong");
        }

        return $model;
    }


    /**
     * Each Permission role can be assigned to many WHMCS admin roles
     * so as we need obrain single CRM permission role by specified admin whmcs role id
     * lets do that
     *
     * @param type $roleID
     * @return \Modulesgarden\Crm\Models\PermissionRole or array()
     */
    public static function getForAdminRole($roleID)
    {
        $possible = PermissionRole::where('admin_groups', 'like', '%'.$roleID.'%' )->get();

        foreach ($possible as $p)
        {
            if( in_array($roleID, $p->admin_groups) ) {
                return $p;
            }
        }

        return array();
    }
}
