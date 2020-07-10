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

class PermissionRole extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_permissions';

    protected $guarded = array('id');

    protected $fillable = array('name', 'admin_groups', 'description', 'allowed');

    protected $parsedRules = null;

    public function getAdminGroupsAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Set permissions roles
     *
     * @param  string  $value
     */
    public function setAdminGroupsAttribute($value)
    {
        if ( empty($value) || ! is_array($value) || is_null($value)) {
            $toSave = array();
        } else {
            $toSave = array_filter($value);
        }

        $this->attributes['admin_groups'] = json_encode($toSave, true);
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
     * Set the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getAllowedAttribute($value)
    {
        if ( isset($this->parsedRules) ) {
            return $this->parsedRules;
        }


        $this->parsedRules = json_decode($value, true);

        return $this->parsedRules;
    }


    /**
     * Set permissions roles
     *
     * @param  string  $value
     */
    public function setAllowedAttribute($value)
    {
        if ( empty($value) || ! is_array($value) || is_null($value)) {
            $toSave = array();
        } else {
            $toSave = array_filter_recursive($value);
        }

        $this->attributes['allowed'] = json_encode($toSave, true);
    }


}
