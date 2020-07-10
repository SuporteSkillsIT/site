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

use Modulesgarden\Crm\Integration\Slim\SlimApp;

use Modulesgarden\Crm\Services\Language;

use Modulesgarden\Crm\Repositories\Followups;

use Modulesgarden\Crm\Models\Source\AbstractModel;
use Modulesgarden\Crm\Models\Validators\Common as Validator;
use Modulesgarden\Crm\Models\Magento\Client;
use Modulesgarden\Crm\Models\Log;
use Modulesgarden\Crm\Models\Setting;
use \Exception;
use Carbon\Carbon;

/**
 * Class Model Resource types
 */
class ResourceType extends AbstractModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_resources_types';


    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');


    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = array('name', 'order', 'color', 'icon', 'active', 'show_in_nav', 'show_in_submenu', 'show_in_dashboard');


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
     * CRM statuses for notes relation
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function notes()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\Resource', 'type_id');
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
     * By default parse to integer
     */
    public function getShowInNavAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }


    /**
     * By default parse to integer
     */
    public function getShowInSubmenuAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }


    /**
     * By default parse to integer
     */
    public function getShowInDashboardAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
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

    public function isVisibleInNavbar()
    {
        return $this->show_in_nav === true;
    }

    public function isVisibleInNavbarSubmenu()
    {
        return $this->show_in_submenu === true;
    }

    public function isVisibleInDashboard()
    {
        return $this->show_in_dashboard === true;
    }

    public function isActive()
    {
        return ( $this->active === true && !$this->trashed() );
    }


    public function scopeOrderred($query)
    {
        return $query->orderBy('order', 'ASC');
    }

    public function scopeOnlyActive($query)
    {
        return $query->where('active', '=', '1');
    }

    public function toNavigationArray()
    {
        return array(
            'id'    => $this->id,
            'name'  => $this->name,
            'color' => $this->color,
            'icon'  => $this->icon,
        );
    }

    public function toRoutingArray()
    {
        // could improve some variables when need inside routing

        return array(
            'id'        => $this->id,
            'name'      => $this->name,
            'color'     => $this->color,
            'icon'      => $this->icon,
            'dashboard' => $this->isVisibleInDashboard(),
            'active'    => $this->isActive(),
        );
    }
}
