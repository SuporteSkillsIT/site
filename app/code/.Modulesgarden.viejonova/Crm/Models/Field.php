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
use Modulesgarden\Crm\Models\FieldData;

class Field extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_fields';

    protected $guarded = array('id');

    protected $fillable = array('group_id', 'type', 'name', 'description', 'order', 'active');

    protected $possibleTypes = array(
        'text',
        'textarea',
        'select',
        'radio',
        'date',
        'datetime',
        'checkbox',
        'numeric',
    );

    /**
     * Just eloquent relation
     *
     * @return type
     */
    public function group()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\FieldGroup', 'group_id');
    }

    public function validators()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\FieldValidatorConfig', 'field_id');
    }

    public function options()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\FieldOption', 'field_id');
    }

    public function data()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\FieldData', 'field_id');
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

    public function scopeActiveFields($query)
    {
        return $query
            ->select('crm_fields.*')
            ->leftJoin('crm_fields_groups', 'crm_fields.group_id', '=', 'crm_fields_groups.id')
            ->where('crm_fields_groups.active', '=', 1)
            ->where('crm_fields.active', '=', 1);
    }

    public function scopeOrderred($query)
    {
        return $query->orderBy('order', 'ASC');
    }

    public function scopeWhereActive($query)
    {
        return $query->where('active', '=', 1);
    }

    public function scopeWithValidators($query)
    {
        return $query->with('validators');
    }


    public function createNewData(\Modulesgarden\Crm\Models\Resource &$resource, $data)
    {
        $fieldDataClass = FieldData::getMappedClass($this->type);
        $fieldData      = new $fieldDataClass(array(
            'resource_id'   => $resource->id,
            'field_id'      => $this->id,
            'field_type'    => $this->type,
        ));

        $fieldData->setValidators($this->validators()->get()->toArray());
        $fieldData->setData($data);

        return $fieldData;
    }


    public function createNewDataFromMigrationData(\Modulesgarden\Crm\Models\Resource &$resource, $data)
    {
        $data = rtrim($data);
        if(empty($data)) {
            return true;
        }


        $fieldDataClass = FieldData::getMappedClass($this->type);
        $fieldData      = new $fieldDataClass(array(
            'resource_id'   => $resource->id,
            'field_id'      => $this->id,
            'field_type'    => $this->type,
        ));

        $fieldData->setDataFromMigration($this, $data);

        return $fieldData;

    }

    /**
     * Join field group that is assigned to that data, or vice versa -.- ;d
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinGroup($query)
    {
        return $query->with('group');
    }


    /**
     * Join field validators that are assigned to
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinValidators($query)
    {
        return $query->with('validators');
    }


    /**
     * Join field possible options
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinOptions($query)
    {
        return $query->with('options');
    }

    /**
     * By default parse to integer
     */
    public function getGroupIdAttribute($value)
    {
        return intval($value);
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

    /**
     * Is this field have assigned options
     * @return bolean
     */
    public function isMultiple()
    {
        return in_array($this->type, array(
            'select',
            'radio',
            'checkbox',
        ));
    }

    /**
     * Used bu update/assign/obtain
     * FieldData object, search for id if exist, or create new for some resource
     * in case when someone will add new field, and then resource doesnt have data record for this particular field
     *
     * @param type $id
     * @param type $resourceID
     * @return \FieldData - instance depending on type
     */
    public function getFieldDataOrNew($id, $resourceID)
    {
        $fieldData = FieldData::find($id);
        if( is_null($fieldData) )
        {
            $class     = FieldData::getMappedClass($this->type);
            $fieldData = new $class();
        }
        
        $fieldData->fill(array(
            'resource_id'   => $resourceID,
            'field_id'      => $this->id,
            'field_type'    => $this->type,
        ));

        return $fieldData;
    }
}
