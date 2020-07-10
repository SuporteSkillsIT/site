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
use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

class FieldData extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_fields_data';

    protected $guarded = array('id');

    protected $fillable = array('field_id', 'data', 'resource_id');


    /**
     * for polymorphic eloquent
     * @var type
     */
    protected $morphClass = 'Modulesgarden\Crm\Models\FieldData';

    /**
     * Determinate which column handle storing class type for various field data types
     * @var type
     */
    protected $discriminatorTypeColumn = 'field_type';

    /**
     * Map, trigger what class based on type
     * @var type
     */
    protected static $fieldTypesMap = array(
        'text'     => 'Modulesgarden\Crm\Models\FieldTypes\Text',
        'textarea' => 'Modulesgarden\Crm\Models\FieldTypes\Textarea',
        'numeric'  => 'Modulesgarden\Crm\Models\FieldTypes\Numeric',
        'date'     => 'Modulesgarden\Crm\Models\FieldTypes\Date',
        'datetime' => 'Modulesgarden\Crm\Models\FieldTypes\Datetime',
        'checkbox' => 'Modulesgarden\Crm\Models\FieldTypes\Checkbox',
        'radio'    => 'Modulesgarden\Crm\Models\FieldTypes\Radio',
        'select'   => 'Modulesgarden\Crm\Models\FieldTypes\Select'
    );


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
     * @return collection of Modulesgarden\Crm\Models\FieldDataOption
     */
    public function options()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\FieldDataOption', 'field_data_id');
    }





    /*****************************************************************************
     * Single Table Inheritance Configuration
     * kind of polymorphic relations to map our models
     *****************************************************************************/


    /**
     * Use the inheritance map to determine the appropriate object type for a given Eloquent object
     *
     * @param array $attributes
     * @return mixed
     */
    public function mapData(array $attributes)
    {
        // Determine the type of entity specified by the discriminator column
        $entityType = isset($attributes[$this->discriminatorTypeColumn]) ? strtolower($attributes[$this->discriminatorTypeColumn]) : null;
        // Throw an exception if this entity type is not in the inheritance map
        if (!array_key_exists($entityType, self::$fieldTypesMap)) {
            throw new ModelNotFoundException(self::$fieldTypesMap[$entityType]);
        }

        // Get the appropriate class name from the inheritance map
        $class = self::$fieldTypesMap[$entityType];

        // Return a new instance of the specified class
        return new $class;
    }


    /**
     * Used globally to create new data classess and manipulate them
     *
     * @param string $entityType
     * @return mixed
     */
    public static function getMappedClass($entityType)
    {
        if (!array_key_exists($entityType, self::$fieldTypesMap)) {
            throw new ModelNotFoundException(self::$fieldTypesMap[$entityType]);
        }

        // Get the appropriate class name from the inheritance map
        return self::$fieldTypesMap[$entityType];
    }


    /**
     * Create a new model instance requested by the builder.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newFromBuilder($attributes = array())
    {
        // Create a new instance of the Entity Type Class
        $m = $this->mapData((array)$attributes)->newInstance(array(), true);

        // Hydrate the new instance with the table data
        $m->setRawAttributes((array)$attributes, true);

        // Return the assembled object
        return $m;
    }


    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder;
     */
    public function newRawQuery()
    {
        $builder = new Builder($this->newBaseQueryBuilder());
        // Once we have the query builders, we will set the model instances
        // so the builder can easily access any information it may need
        // from the model while it is constructing and executing various
        // queries against it.
        $builder->setModel($this)->with($this->with);
        return $builder;
    }


    /**
     * Get a new query builder for the model. Set any type of scope you want
     * on this builder in a child class, and it will keep applying
     * the scope on any read-queries on this model
     *
     * @return \Illuminate\Database\Eloquent\Builder;
     */
    public function newQuery($excludeDeleted = true)
    {
        $builder = $this->newRawQuery();
        if ($excludeDeleted and $this->softDelete) {
            $builder->whereNull($this->getQualifiedDeletedAtColumn());
        }
        return $builder;
    }


    /**
     * Save the model to the database.
     *
     * @return bool
     */
    public function save(array $options = array())
    {
        $query = $this->newRawQuery();
        // If the "saving" event returns false we'll bail out of the save
        // and return false, indicating that the save failed This gives
        // an opportunities to listeners to cancel save operations
        // if validations fail or whatever.
        if ($this->fireModelEvent('saving') === false) {
            return false;
        }
        // If the model already exists in the database we can just update
        // our record that is already in this database using the current
        // IDs in this "where" clause to only update this model.
        // Otherwise, we'll just insert them.
        if ($this->exists) {
            $saved = $this->performUpdate($query, $options);
        }
        // If the model is brand new, we'll insert it into our database
        // and set the ID attribute on the model to the value of the newly
        // inserted row's ID which is typically an auto-increment value
        // managed by the database.
        else {
            $saved = $this->performInsert($query, $options);
            $this->exists = $saved;
        }
        if ($saved) {
            $this->finishSave($options);
        }
        return $saved;
    }


    /**
     * Return the possible object types, as specified by the base class
     *
     * @return array
     */
    public function getBaseTypes()
    {
        return array_keys(self::$fieldTypesMap);
    }


    /**
     * Return a copy of this instance cast to the base class type.
     *
     * @return mixed
     */
    public function toBaseObject()
    {
        $object = app()->make($this->morphClass);
        $object->setRawAttributes((array)$this->getAttributes());
        return $object;
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
     * Join field that is assigned to that data, or vice versa -.- ;d
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinField($query)
    {
        return $query->with('field');
    }

    /**
     * Join field that is assigned to that data, or vice versa -.- ;d
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinFieldGoup($query)
    {
        return $query->with(array(
            'field' => function($query) {
                $query->JoinGroup();
            }));
    }

    /**
     * Join options aassigned to THIS data field
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinAssignedOptions($query)
    {
        return $query->with('options');
    }

    /**
     * Join options aassigned to THIS data field
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinAssignedOptionsAndOption($query)
    {
        return $query->with('options.option');
    }

    /**
     * Join field that is assigned to that data, or vice versa -.- ;d
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinFieldValidators($query)
    {
        return $query->with(array(
            'field' => function($query) {
                $query->joinValidators();
            }));
    }

    /**
     * Join field that is assigned to that data, or vice versa -.- ;d
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinFieldOptions($query)
    {
        return $query->with(array(
            'field' => function($query) {
                $query->joinOptions();
            }));
    }



    /**
     * Join field that is assigned to that data, or vice versa -.- ;d
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinFillStackField($query)
    {
        return $query->with(array(
            'field' => function($query) {
                $query->JoinGroup()->joinOptions()->joinValidators();
            }));
    }

    /**
     * Join field that is assigned to that data, or vice versa -.- ;d
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinFieldValidatorAndOptions($query)
    {
        return $query->with(array(
            'field' => function($query) {
                $query->joinOptions()->joinValidators();
            }));
    }

}
