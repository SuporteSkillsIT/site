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
        

namespace Modulesgarden\Crm\Models\FieldTypes\Source;

use Modulesgarden\Crm\Models\Source\AbstractModel;

/**
 * Basic standard abstract class for each of possible field type
 * since cod has to be DRY
 */
abstract class FieldTypeAbstract extends AbstractModel
{
    /**
     * Disable timestamps for model
     * @var bolean
     */
    public $timestamps = false;

    /**
     * Set up table
     * @var type
     */
    protected $table = 'crm_fields_data';

    /**
     * This valuea are not changable directly
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Hidden
     * @var array
     */
//    protected $hidden = array('field_type');

    /**
     * These we can fill
     * @var type
     */
    protected $fillable = array('field_id', 'resource_id', 'data', 'field_type');




    /**
     * Container for Value
     * @var type
     */
    protected $value  = null;

    /**
     * Keep field config here
     * @var type
     */
    protected $config = array();

    /**
     * Plain flag for multiple values that could be assigned for this field
     * @var bolean
     */
    protected $multiple = false;

    /**
     * We want to keep validators here
     * @var array
     */
    protected $possibleValidators = array();

    /**
     * container for invalid messages collector
     * @var array
     */
    protected $invalids = array();


    /**
     * Just eloquent relation
     * ONE TO MANY, one field(definition of fielf type) type have many datas (keeps data for certain fields type)
     *
     * @return \Modulesgarden\Crm\Models\Field
     */
    public function field()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Field', 'field_id');
    }


    /**
     * Just eloquent relation
     * ONE TO MANY, one lead/resource type have many datas
     *
     * @return \Modulesgarden\Crm\Models\Resources
     */
    public function resource()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Resource', 'resource_id')->withTrashed();
    }


    /**
     * Just read about hasManyThrough
     *
     * @return collection of \Modulesgarden\Crm\Models\FieldOption
     */
    public function options()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\FieldDataOption', 'field_data_id');
    }


    /**
     * Set up validation rules
     *
     * array \Modulesgarden\Crm\Models\FieldValidatorConfig
     */
    public function setValidators(array $validators = array())
    {
        foreach ($validators as $v)
        {
            if(in_array($v['type'], $this->possibleValidators)) {
                $this->config[$v['type']] = $v;
            }
        }
    }

    /**
     * Run each configured validator and return error or etc
     *
     * @return bolean
     */
    public function isValid()
    {
        $this->invalids = array();

        foreach ($this->config as $v)
        {
            $method      = 'validate' . ucfirst($v['type']);

            if( ! method_exists($this, $method) ) {
                continue;
            }

            if( ! $this->{$method} ) {
                $this->invalids[] = ($v['error'] ? $v['error'] : sprintf('Field is not valid by validator #%s, type: %s.', $v['id'], $v['type']));
            }
        }

        return empty($this->invalids);
    }

    /**
     * return any errors
     * @return mixed
     */
    public function getInvalids()
    {
        return $this->invalids;
    }

    /**
     * force implementation in main class that extend this one
     */
    abstract function setData($data);


    /**
     * Handle relation attach
     *
     * @param \Modulesgarden\Crm\Models\Resource $resource
     */
    public function attachToResource(\Modulesgarden\Crm\Models\Resource &$resource)
    {
        return $this->resource()->associate($resource);
    }

    /**
     * By default parse to integer resource id
     */
    public function getResourceIdAttribute($value)
    {
        return intval($value);
    }

    /**
     * By default parse to integer
     */
    public function getFieldIdAttribute($value)
    {
        return intval($value);
    }

    /**
     * Is this field have assigned options
     * @return bolean
     */
    public function isMultiple()
    {
        return $this->multiple;
    }
}
