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
use Modulesgarden\Crm\Models\Validators\Common as Validator;
use \Exception;

/**
 * Class Model for lead/potential
 */
class Log extends AbstractModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_logs';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = array('resource_id', 'admin_id', 'event', 'date', 'message');

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array('date');
    
    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = false;
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;



    /**
     * Relation For Assigned Admin to this Resource
     *
     * relation: ONE TO ONE
     *
     * @return Modulesgarden\Crm\Models\Magento\Admin
     */
    public function admin()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\Admin', 'admin_id');
    }

    
    /**
     * Helper scope that let me return data with Admin Object
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWithAdmin($query)
    {
        return $query->with(array('admin' => function($query){
            $query->select('user_id', 'username', 'firstname', 'lastname', 'email');
        }));
    }


    /**
     * Trigger Eloquent Assign by model
     */
    public function assignAdmin(\Modulesgarden\Crm\Models\Magento\Admin $Model)
    {
        $this->admin()->associate($Model);
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignAdminByID($id)
    {
        $id = (int)$id;

        if( ! Validator::isPositiveNumber($id)) {
            throw new Exception('Wrong Admin ID');
        }

        $this->admin_id = $id;
    }

    /**
     * Relation For Assigned Admin to this Resource
     *
     * relation: ONE TO ONE
     *
     * @return Modulesgarden\Crm\Models\Resource
     */
    public function resource()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Resource', 'resource_id')->withTrashed();
    }


    /**
     * Helper scope that let me return data with Admin Object
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeResource($query)
    {
        return $query->with('resource');
    }


    /**
     * Trigger Eloquent Assign by model
     */
    public function assignResource(\Modulesgarden\Crm\Models\Resource $Model)
    {
        $this->resource()->associate($Model);
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignResourceByID($id)
    {
        $id = (int)$id;

        if( ! Validator::isPositiveNumber($id)) {
            throw new Exception('Wrong Admin ID');
        }

        $this->resource_id = $id;
    }



    /**
     * Just scope to to search by message content
     *
     * @param type $query
     * @param type $str     what we are looking for
     * @return type
     */
    public function scopeWithMessage($query, $str = '')
    {
        return $query->where('message', 'LIKE', "%{$str}%");
    }

    /**
     * Find only for certain resources
     *
     * @param type $query
     * @param type $id      resource id
     * @return type
     */
    public function scopeWithResource($query, $id)
    {
        return $query->where('resource_id', '=', $id);
    }

    /**
     * Justr shortcut filter for admin
     *
     * @param type $query
     * @param type $str     what we are looking for
     * @return type
     */
    public function scopeWhereAdmin($query, $id)
    {
        return $query->where('admin_id', '=', $id);
    }

    /**
     * Justr shortcut filter for event type
     *
     * @param type $query
     * @param type $str     what we are looking for
     * @return type
     */
    public function scopeWhereEvent($query, $str = '')
    {
        return $query->where('event', 'LIKE', "%{$str}%");
    }

    /**
     * Justr shortcut filter for event type
     *
     * @param type $query
     * @param type $str     what we are looking for
     * @return type
     */
    public function scopeWhereDate($query, $str = '')
    {
        return $query->where('date', 'LIKE', "%{$str}%");
    }

}
