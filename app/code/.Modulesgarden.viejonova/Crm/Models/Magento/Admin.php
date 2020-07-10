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
use \Illuminate\Database\Capsule\Manager as DB;

class Admin extends AbstractModel
{

    public $timestamps = false;
    protected $table = 'admin_user';
    protected $primaryKey = 'user_id';
    protected $guarded = array('user_id');
    protected $fillable = array(
        'firstname',
        'lastname',
        'email',
        'username',
        'password',
        'created',
        'modified',
        'logdate',
        'lognum',
        'reload_zcl_flag',
        'is_active',
        'extra',
        'rp_token',
        'rp_token_created_at',
        'interface_locale',
        'failures_num',
        'first_failure',
        'lock_expires',
    );
    protected $appends = array(
        'full',
        'avatar',
        'roleid'
    );
    protected $visible = array('user_id', 'firstname', 'lastname', 'email');

    /**
     * Get the phone record associated with the user.
     */
    public function role()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\AdminRole', 'roleid')->select(array('role_id', 'name'));
        // return array('1' => 'Administrators');
    }

    public function isDisabled()
    {
        return ($this->disabled == 1);
    }

    public function scopeFilterIrrelevantParams($query)
    {
        return $query->select(array(
                    'user_id',
                    'firstname',
                    'lastname',
                    'email',
                    DB::raw("CONCAT(firstname, ' ', lastname) as full")
        ));
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getFullAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Helper scope that let me return data with Admin Object
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeJoinAvatar($query)
    {
        return $query->leftJoin('crm_settings', function($join) {
                    $join->on(DB::raw("( crm_settings.admin_id = admin_user.user_id AND crm_settings.name = 'avatar'  )"), DB::raw(''), DB::raw(''));
                })->addSelect('user_id', 'firstname', 'lastname', 'email', 'crm_settings.value as avatar');
    }

    public function getAvatarAttribute()
    {

        if (isset($this->attributes['avatar'])) {
            return $this->attributes['avatar'];
        } else {
            $query = DB::table('crm_settings')
                    ->where('crm_settings.name', '=', 'avatar')
                    ->where('crm_settings.admin_id', '=', $this->id)
                    ->select('value')
                    ->first();

            return !empty($query->value) ? $query->value : '';
        }
    }

    public function getRoleidAttribute()
    {
        if (isset($this->attributes['roleid'])) {
            return $this->attributes['roleid'];
        } else {
            $query = DB::table('authorization_role')->select('role_id', 'parent_id')->where('user_id', '=', $this->user_id)->first();

            if (intval($query['parent_id']) == 0) {
                return intval($query['parent_id']);
            }
            return intval($query['role_id']);
        }
    }

}
