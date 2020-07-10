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
use \Illuminate\Database\Capsule\Manager as DB;
use Modulesgarden\Crm\Models\Validators\Common;
use \Exception;

class FieldStatus extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_resources_statuses';

    protected $guarded = array('id');

    protected $fillable = array('name', 'color', 'order', 'active');

    
    /**
     * St up relation for resources
     * 
     * @return type
     */
    public function resources()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\Resource', 'status_id')->withTrashed();
    }


    public function scopeWithActive($query)
    {
        return $query->where('active', '=', 1);
    }

    public function getOrderAttribute($value)
    {
        return intval($value);
    }

    public function getActiveAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    // Resource model
    public function resourcesCount()
    {
      return $this->hasOne('Modulesgarden\Crm\Models\Resource', 'status_id')
                  ->selectRaw('status_id, count(*) as aggregate')
                  ->groupBy('status_id');
    }

    public function getResourcesCountAttribute()
    {
        // if relation is not loaded already, let's do it first
        if ( ! array_key_exists('resourcesCount', $this->relations)) {
            $this->load('resourcesCount');
        }

        $related = $this->getRelation('resourcesCount');

        // then return the count directly
        return ($related) ? (int) $related->aggregate : 0;
    }

    public function countRecordsForAdmin($adminID)
    {
        $query = DB::table('crm_resources')
                    ->select(DB::raw('count(crm_resources.id) as total'))
                    ->where('crm_resources.status_id', '=', $this->id);


        if( Common::isPositiveNumber($adminID) && $adminID != 0 ) {
            $query = $query->where('crm_resources.admin_id', '=', $adminID);
        }

        $return = $query->lists('total');
        return intval($return[0]);
    }

    public function safeDelete()
    {
        $count = $this->resources()->count();
        if( $count > 0 ) {
            throw new Exception(sprintf('Cannot delete, since this status is assigned to %s element(s).', $count));
        }

        return $this->forceDelete();
    }


    /**
     * Count resources based by status and other conditions
     *
     * @param type $type_id
     * @param type $adminID
     * @param type $campaignID
     * @return type
     */
    public function countRecordsFor($type_id, $adminID, $campaignID = null)
    {
        $query = DB::table('crm_resources')
                    ->select(DB::raw('count(crm_resources.id) as total'))
                    ->whereNull('crm_resources.deleted_at')
                    ->where('crm_resources.status_id', '=', $this->id);

        if( Common::isPositiveUnsignedNumber($type_id) ) {
            $query = $query->where('crm_resources.type_id', '=', $type_id);
        }

        if( Common::isPositiveUnsignedNumber($adminID) ) {
            $query = $query->where('crm_resources.admin_id', '=', $adminID);
        } elseif($campaignID === 0) {
            $query = $query->whereNull('crm_resources.admin_id');
            // filter by NO ADMIN ASSIGNED
        }

        if( Common::isPositiveUnsignedNumber($campaignID) ) {
            // filter by campaign
            $query = $query->leftJoin('crm_campaigns_resources', function($join) use($campaignID) {
                        $join->on('crm_resources.id', '=', 'crm_campaigns_resources.resource_id')
                             ->on('crm_campaigns_resources.campaign_id', '=', DB::raw($campaignID));
                    });
            $query = $query->where('crm_campaigns_resources.campaign_id', '=', $campaignID);
        } elseif($campaignID === 0) {
            // filter by NO CAMPAIGN ASSIGNED
            $query = $query->leftJoin('crm_campaigns_resources', function($join) {
                        $join->on('crm_resources.id', '=', 'crm_campaigns_resources.resource_id');
                    });
            $query = $query->whereNull('crm_campaigns_resources.campaign_id');
        }

        $return = $query->lists('total');
        return intval($return[0]);
    }
}
