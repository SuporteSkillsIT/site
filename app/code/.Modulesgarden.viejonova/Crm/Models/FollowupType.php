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


namespace Modulesgarden\Crm\Models;


use Modulesgarden\Crm\Models\Source\AbstractModel;
use Exception;

class FollowupType extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_followup_types';

    protected $guarded = array('id');

    protected $fillable = array('name', 'color', 'order', 'active');

    /**
     * Just eloquent relation
     *
     * @return \Modulesgarden\Crm\Models\Followup
     */
    public function followups()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\Followup', 'type_id');
    }


    /**
     * Array of parameters to update
     * key needs to be same as parameter to uprade
     *
     * @param type $data
     * @return boolean
     * @throws Exception
     */
    public function updateSingleParam($data)
    {
        if( count($data) == 0 || empty($data) ) {
            throw new Exception('nothing to update');
        }

        foreach ($data as $param => $value)
        {
            $this->$param = $value;
        }

        if ($this->save()) {
            return true;
        }

        return false;
    }


    /**
     * Delete the model from the database.
     * Only if there is no fields assigned to this group!
     *
     * Add condition, then triger eloquent model delete method
     *
     * @throws Exception
     * @return bool|null
     */
    public function delete()
    {
        $count = $this->followups()->count();
        if ( $count > 0 )
        {
            throw new Exception("You can't delete group that have {$count} Records(s) assigned to this type.");
        }

        return parent::delete();
    }

    /**
     * Scope filter for only active groups
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeActiveGroups($query)
    {
        return $query->where('active', '=', 1);
    }

    /**
     * Scope join fields by active and orderred
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinFollowups($query)
    {
        return $query->with('followups');
    }

    /**
     * just wrap order
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOrderred($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * By default parse to integer
     */
    public function getOrderAttribute($value)
    {
        return intval($value);
    }

    /**
     * By default parse to integer
     */
    public function getActiveAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }




}
