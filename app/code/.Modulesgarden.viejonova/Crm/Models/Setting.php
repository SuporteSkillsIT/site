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

class Setting extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_settings';

//    protected $guarded = array('id');

    protected $fillable = array('name', 'admin_id', 'value');
    
    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
//    protected $primaryKey = null;
    protected $primaryKey = array('name', 'admin_id');

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $parsedValue = null;


    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getValueAttribute($value)
    {
        if ( isset($this->parsedValue) ) {
            return $this->parsedValue;
        }

        $decoded = json_decode($value, true);

        if( ! is_null($decoded) ) {
            $this->parsedValue = $decoded;
        } else {
            $this->parsedValue = $value;
        }

        return $this->parsedValue;
    }


    /**
     * Set permissions roles
     *
     * @param  string  $value
     */
    public function setValueAttribute($value)
    {
        $encode = true;

        if( is_array($value) ) {
            $this->attributes['value'] = json_encode($value, true);
        } else {
            $this->attributes['value'] = $value;
        }
        
    }

    /**
     * Set the keys for a save update query.
     * This is a fix for tables with composite keys
     * TODO: Investigate this later on
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
       if (is_array($this->primaryKey)) {
           foreach ($this->primaryKey as $pk) {
               $query->where($pk, '=', $this->original[$pk]);
           }
           return $query;
       }else{
           return parent::setKeysForSaveQuery($query);
       }
    }
    
    /**
     * Additional scope to filter personal settings
     * 
     * @param type $query
     * @param type $id  admin id
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAdmin($query, $id)
    {
        return $query->where('admin_id', '=', $id);
    }

    /**
     * Additional scope to filter Global settings settings
     *
     * @param type $query
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForGlobal($query)
    {
        return $query->where('admin_id', '=', 0);
    }

    /**
     * Additional scope to filter Global settings settings
     *
     * @param type $query
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereSetting($query, $setting)
    {
        return $query->where('name', '=', $setting);
    }

}
