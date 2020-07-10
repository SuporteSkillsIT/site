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
use \Carbon\Carbon;


/**
 * Class Model for Campaign
 */
class Campaign extends AbstractModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_campaigns';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = array('name', 'description', 'date_start', 'date_end', 'filters', 'color');

    /**
     * Eloquent additional parameters that will be recieved
     * @var array
     */
    protected $appends = array(
        'active',
    );

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array('date_start', 'date_end');

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
     * Related resources
     *
     * relation: MANY TO MANY
     * remember that this will return collection!
     *
     * @return Modulesgarden\Crm\Models\Resource
     */
    public function resources()
    {
        return $this->belongsToMany('Modulesgarden\Crm\Models\Resource', 'crm_campaigns_resources', 'campaign_id', 'resource_id')->withTimestamps()->withTrashed();
    }

    /**
     * Related resources
     *
     * relation: MANY TO MANY
     * remember that this will return collection!
     *
     * @return Modulesgarden\Crm\Models\Resource
     */
    public function admins()
    {
        return $this->belongsToMany('Modulesgarden\Crm\Models\Magento\Admin', 'crm_campaigns_admins', 'campaign_id', 'admin_id')->withTimestamps();
    }

    public function scopeWithAdmins($query)
    {
        return $query->with('admins');
    }

    public function filterMainDataFilters()
    {
        $return = $this->toArray();
        unset($return['description']);
        unset($return['filters']);
        unset($return['created_at']);
        unset($return['updated_at']);
        unset($return['deleted_at']);
        unset($return['pivot']);

        return $return;
    }


    /**
     * Decode from saved
     *
     * @param  string  $value
     * @return string
     */
    public function getFiltersAttribute($value)
    {
        if ( isset($this->parsedRules) ) {
            return $this->parsedRules;
        }

        $this->parsedFilters = json_decode($value, true);

        return $this->parsedFilters;
    }


    /**
     * Save as encoded string to DB
     *
     * @param  string  $value
     */
    public function setFiltersAttribute($value)
    {
        if ( empty($value) || ! is_array($value) || is_null($value)) {
            $toSave = array();
        } else {
            $toSave = $value;

            foreach ($value as $gk => $group) {

                foreach ($group as $vk => $v) {
                    if($v['enabled'] !== true) {
                        unset($value[$gk]);
                        break;
                    }
                }

            }
        }

        $this->attributes['filters'] = json_encode($toSave);
    }

    /**
     * scope for easy filter by campaign name
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereName($query, $str)
    {
        return $query->where('name', 'like', "%{$str}%");
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereDescription($query, $str)
    {
        return $query->where('description', 'like', "%{$str}%");
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereNameOrDescription($query, $str)
    {
        return $query->where(function($query) use($str) {
            return $query->orWhere('description', 'like', "%{$str}%")->orWhere('name', 'like', "%{$str}%");
        });
//        return $query->where('description', 'like', "%{$str}%")->orWhere('name', 'like', "%{$str}%");
    }



    /**
     * Counters For Campaigns resources
     * this is just a scope for helper query builred
     *
     * @return type
     */
    public function scopeWithResourcesCount($query)
    {
        return $query->leftJoin('crm_campaigns_resources', 'crm_campaigns_resources.campaign_id', '=', 'crm_campaigns.id')
                     ->addSelect(DB::raw('crm_campaigns.*, count(crm_campaigns_resources.id) as resources_count'))
                     ->groupBy('crm_campaigns.id');
    }

    /**
     * Return plain true/false depending on dates range for today
     * Basically its indicator if campaign is active based by date start/end
     *
     * @return bolean
     */
    public function getActiveAttribute($value)
    {
        return Carbon::now()->between(Carbon::parse($this->date_start), Carbon::parse($this->date_end));
    }

    /**
     * Parse atribute to integer
     *
     * @return type
     */
    public function getResourcesCountAttribute()
    {
        return (isset($this->attributes['resources_count'])) ? (int) $this->attributes['resources_count'] : 0;
    }


    /**
     * Filter campaigns by allowed assignements (admin one)
     *
     * @param type $query
     * @param type $id
     * @return type
     */
    public function scopeForAdmin($query, $id)
    {
        return $query->whereHas('admins', function($query) use($id)
        {
            $query->where('admin_user.user_id', '=', $id );
        });
    }

    /**
     * Filter campaigns by allowed assignements (admin one)
     *
     * @param type $query
     * @param type $id
     * @return type
     */
    public function scopeActiveForNow($query)
    {
        return $query->where('date_start', '<=', Carbon::now())
                     ->where('date_end',   '>=', Carbon::now());
    }



    /**
     * Parse date to day format, not with time
     *
     * @return type
     */
    public function getDateStartAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
    }

    /**
     * Parse date to day format, not with time
     *
     * @return type
     */
    public function getDateEndAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
    }

    /**
     * Assigngn additional variable to object to inform if current admin can reasign record to this campaign
     * @param type $adminID
     */
    public function markAvailableOrNotFor($adminID)
    {
        $this->canAssign = (in_array($adminID, $this->admins->lists('user_id')) && $this->active) ? true : false;
    }
}
