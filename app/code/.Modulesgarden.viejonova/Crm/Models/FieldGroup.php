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
use \Exception;

class FieldGroup extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_fields_groups';

    protected $guarded = array('id');

    protected $fillable = array('name', 'color', 'order', 'active');


    /**
     * Delcare eloquent relation type
     *
     * @return type
     */
    public function fields()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\Field', 'group_id');
    }

    /**
     * Again related fields
     * this time extended by order added to it
     *
     * @return type
     */
    public function fieldsOrderred()
    {
        return $this->fields()->orderBy('order', 'ASC');
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
        $count = $this->fields()->count();
        if ( $count > 0 )
        {
            throw new Exception("You can't delete group that have {$count} field(s) assigned to.");
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
    public function scopeJoinActiveFields($query)
    {
        return $query->with(array('fields' => function($query) {
            $query->where('active', '=', 1)->orderBy('order', 'asc');
        }));
    }

    /**
     * Scope join fields by active and orderred
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinFieldsDataFor($query, $resourceID)
    {
        return  $query->with(array('fields' => function($query) use($resourceID) {
                    $query->with(array('data' => function($query) use($resourceID) {
                        $query->withResource($resourceID);
                    }));
            }));
    }

    /**
     * Scope join fields validators to query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinValidators($query)
    {
        return $query->with('fields.validators');
    }

    /**
     * just wrap order
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinOptions($query)
    {
        return $query->with('fields.options');
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
