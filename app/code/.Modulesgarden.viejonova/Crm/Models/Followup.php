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
use Modulesgarden\Crm\Models\Validators\Common;
use Carbon\Carbon;
use \Illuminate\Database\Capsule\Manager as DB;

class Followup extends AbstractModel
{
    public $timestamps = true;

    protected $table = 'crm_followups';

    protected $guarded = array('id');

    protected $fillable = array('resource_id', 'type_id', 'admin_id', 'date', 'description');

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array('date', 'deleted_at', 'updated_at', 'created_at');


    /**
     * Just eloquent relation
     *
     * @return \Modulesgarden\Crm\Models\Resources
     */
    public function resource()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Resource', 'resource_id')->withTrashed();
    }

    /**
     * Just eloquent relation
     *
     * @return \Modulesgarden\Crm\Models\FollowupType
     */
    public function type()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\FollowupType', 'type_id');
    }

    /**
     * Just eloquent relation
     *
     * @return \Modulesgarden\Crm\Models\Magento\Admin
     */
    public function admin()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\Admin', 'admin_id');
    }

    /**
     * Just eloquent relation
     *
     * @return collection of \Modulesgarden\Crm\Models\Reminder
     */
    public function reminders()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\Reminder', 'followup_id');
    }
    
    /**
     * Count Reminders
     * Usefull for tables
     * 
     * @return type
     */
    public function remindersCount()
    {
        return $this->hasOne('Modulesgarden\Crm\Models\Reminder')
                     ->selectRaw('followup_id, count(*) as count')
                     ->groupBy('followup_id');

    }

    /**
     * Modifier to return just count of reminders
     * @return type
     */
    public function getRemindersCountAttribute()
    {
        // if relation is not loaded remindersCount, let's do it first
        if ( ! array_key_exists('remindersCount', $this->relations)) {
            $this->load('remindersCount');
        }

        $related = $this->getRelation('remindersCount');

        // then return the count directly
        return ($related) ? (int) $related->count : 0;
    }

    /**
     * Join admin assigned to this followup
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinAdmin($query)
    {
        return $query->with('admin');
    }


    /**
     * Join admin with only relevant data
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinAdminRelevant($query)
    {
        return $query->with(array('admin' => function($query){
            $query->select('user_id', 'firstname', 'lastname', 'email');
        }));
    }



    /**
     * Join followup type assigned
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinType($query)
    {
        return $query->with('type');
    }


    /**
     * Join resource assigned to this
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinResource($query)
    {
        return $query->with('resource');
    }

    /**
     * Join assigned reminders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeJoinReminders($query)
    {
        return $query->with('reminders');
    }


    /**
     * Join assigned reminders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereResource($query, $id)
    {
        return $query->where('resource_id', '=', $id);
    }

    /**
     * Join assigned admin
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereAdmin($query, $id)
    {
        return $query->where('admin_id', '=', $id);
    }

    /**
     * Filter, by no admin assigned
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereNoAdmin($query)
    {
        return $query->whereNull('admin_id');
    }

    /**
     * Join assigned campaign
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereCampaign($query, $campaignID)
    {
        return $query->leftJoin('crm_campaigns_resources', function($join) use($campaignID) {
                        $join->on('crm_followups.resource_id', '=', 'crm_campaigns_resources.resource_id')
                             ->on('crm_campaigns_resources.campaign_id', '=', DB::raw($campaignID));
                    })->where('crm_campaigns_resources.campaign_id', '=', $campaignID);
    }

    /**
     * Filter, by no assigned campaign
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereNoCampaign($query)
    {
        return $query->leftJoin('crm_campaigns_resources', function($join) {
                        $join->on('crm_followups.resource_id', '=', 'crm_campaigns_resources.resource_id');
                    })->whereNull('crm_campaigns_resources.campaign_id');
    }

    /**
     * Join Resource with few params
     * used for calendar
     * 
     * @param type $query
     * @return type
     */
    public function scopeJoinLeadTypeAndName($query)
    {
        return $query->with(array('resource' => function($query){
            $query->withTrashed()
                  ->select('crm_resources.id', 'crm_resources.name', 'crm_resources.status_id', 'crm_resources_types.color as type_color', 'crm_resources_types.icon as type_icon', 'crm_resources_types.name as type_name')
                  ->leftJoin('crm_resources_types', 'crm_resources.type_id', '=', 'crm_resources_types.id');
        }));


    }

    /**
     * Just scope to to search by description content
     *
     * @param type $query
     * @param type $str     what we are looking for
     * @return type
     */
    public function scopeWithDescription($query, $str = '')
    {
        return $query->where('description', 'LIKE', "%{$str}%");
    }

    /**
     * Parse related IDs to integer
     */
    public function getResourceIdAttribute($value)
    {
        return intval($value);
    }
    /**
     * Parse related IDs to integer
     */
    public function getTypeIdAttribute($value)
    {
        return intval($value);
    }
    /**
     * Parse related IDs to integer
     */
    public function getAdminIdAttribute($value)
    {
        return intval($value);
    }


    /**
     * Force to delete followup
     */
    public function dropWithReminders()
    {
        // delete all associated reminders
        $this->reminders()->withTrashed()->forceDelete();
        // delete followup
        return parent::forceDelete();
    }

    /**
     * Reschedue followup with reminders
     * 
     * @param type $newDate
     */
    public function reschedueWithReminders(array $data = array())
    {
        // parse date
        $newDate         = Carbon::parse(array_get($data, 'date'));
//        $newDate         = Carbon::createFromTimestamp(array_get($data, 'date'));

        // updateReminders
        $updateReminders = array_get($data, 'updateReminders');
        if( ! Common::isBolean($updateReminders) ) {
            $updateReminders = false;
        }
        // get reason
        $reason = array_get($data, 'reason');


        $timeDiff   = $this->date->diffInMinutes($newDate);
        $plusToDate = $newDate->gt($this->date);

        if( $updateReminders === true )
        {
            // update each reminder date
            foreach ($this->reminders as $reminder)
            {
                $reminder->reschedue($plusToDate, $timeDiff);
            }
        }
        
        $this->date = $newDate;

        return $this->save();
    }


    /**
     * Scope
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWithResourceAndStatus($query)
    {
//        return $query->with('resource.status');
        return $query->with(array('resource' => function($query) {
            $query->withTrashed()->with('status');
        }));
    }
}
