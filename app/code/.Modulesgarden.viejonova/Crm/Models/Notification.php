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
class Notification extends AbstractModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_notifications';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = array('class', 'type', 'content', 'confirmation', 'hide_after_confirm', 'date_start', 'date_end');

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
    protected $softDelete = false;

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
    public function admins()
    {
        return $this->belongsToMany('Modulesgarden\Crm\Models\Magento\Admin', 'crm_notifications_admins', 'notification_id', 'admin_id')->withPivot('accepted_at')->withTimestamps();
    }

    public function scopeWithAdmins($query)
    {
        return $query->with('admins');
    }


    /**
     * scope for easy filter by campaign name
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereContent($query, $str)
    {
        return $query->where('content', 'like', "%{$str}%");
    }

    /**
     * Return plain true/false depending on dates range for today
     * Basically its indicator if campaign is active based by date start/end
     *
     * @return bolean
     */
    public function getActiveAttribute($value)
    {
        if($this->type == 'permanent') {
            return true;
        }

        return Carbon::now()->between(Carbon::parse($this->attributes['date_start']), Carbon::parse($this->attributes['date_end']));
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

    public function getAdminWhoAcceptted()
    {
        $return = array();
        foreach ($this->admins as $a) {
            if(!is_null($a->pivot->accepted_at)) {
                $return[] = $a->toArray();
            }
        }

        return $return;
    }

    /**
     * By default parse to bolean
     */
    public function getConfirmationAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * By default parse to bolean
     */
    public function getHideAfterConfirmAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

}
