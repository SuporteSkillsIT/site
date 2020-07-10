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
use \Illuminate\Database\Capsule\Manager as DB;
use \Exception;

/**
 * Class Model for lead/potential
 */
class Note extends AbstractModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_notes';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = array('content', 'admin_id', 'resource_id');

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array('deleted_at', 'updated_at', 'created_at');
    
    /**
     * Indicates if the model should soft delete.
     *
     * @var bool
     */
    protected $softDelete = true;
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;



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
        return $query->with('admin');
    }

    /**
     * Helper scope that let me return data with Admin Object
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeJoinAdminAvatar($query)
    {
        return $query->with(array('admin'=>function($query) {

            return $query->joinAvatar();

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
     * scope for easy filter notes by resource
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereResource($query, $id)
    {
        return $query->where('resource_id', '=', $id);
    }


    public function getResourceIdAttribute($value)
    {
        return intval($value);
    }

    public function getAdminIdAttribute($value)
    {
        return intval($value);
    }
}
