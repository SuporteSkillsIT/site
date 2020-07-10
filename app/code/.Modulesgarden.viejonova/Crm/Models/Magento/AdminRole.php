<?php

/* * *************************************************************************************
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
 * @author      Paweł Tomczyk <pawel.tom@modulesgarden.com> /
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

namespace Modulesgarden\Crm\Models\Magento;

use Modulesgarden\Crm\Models\Source\AbstractModel;
use Modulesgarden\Crm\Services\ACL;

//use \Illuminate\Database\Capsule\Manager as DB;

class AdminRole extends AbstractModel
{

    public $timestamps = false;
    protected $table = 'authorization_role';
    protected $visible = array('role_id', 'role_name');
    protected $select = array('role_id', 'role_name');
    protected $fillable = array(
        'role_name',
        'role_type',
    );

    /**
     * Get the phone record associated with the user.
     */
    public function admins()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\Magento\Admin', 'roleid', 'id');
    }

    public function scopeByIds($query, array $ids)
    {
        return $query->whereIn('role_id', $ids);
    }

    public static function getAssignable()
    {
        $full = ACL::getInstance()->getFullAccessRoles();
        $possible = ACL::getInstance()->getAssignedAccessRoles();

        return array_diff($possible, $full);
    }

}
