<?php

/* * *************************************************************************************
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
 * ************************************************************************************ */

namespace Modulesgarden\Crm\Models;

use Modulesgarden\Crm\Integration\Slim\SlimApp;
use Modulesgarden\Crm\Models\MassMessagePending;
use Modulesgarden\Crm\Models\Source\AbstractModel;
use Modulesgarden\Crm\Models\Validators\Common as Validator;
use \Illuminate\Database\Capsule\Manager as DB;
use \Exception;
use \Carbon\Carbon;

/**
 * Class For Mass Mail Config records
 */
class MassMessageConfig extends AbstractModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_mass_message_configs';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = array(
        'description',
        'message_content',
        'message_title',
        'message_type',
        'target_ids',
        'target_type',
        'generated',
        'total',
        'date',
    );

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array('deleted_at', 'updated_at', 'created_at', 'date');

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
     * Basically possible messages types for validation
     * as protection layer
     *
     * @var type
     */
    protected static $allowedMessageTypes = array(
        'email',
        'sms',
    );

    /**
     * Basically possible messages types for validation
     * as protection layer
     *
     * @var type
     */
    protected static $allowedTargetTypes = array(
        'users',
        'usergroups',
        'campaigns',
    );

    public function getGeneratedAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function getTotalAttribute($value)
    {
        return intval($value);
    }

    public function getTargetIdsAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Set permissions roles
     *
     * @param  string  $value
     */
    public function setTargetIdsAttribute($value)
    {
        if (empty($value) || !is_array($value) || is_null($value)) {
            $toSave = array();
        } else {
            $toSave = $value;
        }

        $this->attributes['target_ids'] = json_encode($toSave, true);
    }

    /**
     * Set permissions roles
     *
     * @param  string  $value
     */
    public function setMessageTypeAttribute($value)
    {
        if (!in_array($value, self::$allowedMessageTypes)) {
            throw new Exception('Invalid Message Type');
        }

        $this->attributes['message_type'] = $value;
    }

    /**
     * Set permissions roles
     *
     * @param  string  $value
     */
    public function setTargetTypeAttribute($value)
    {
        if (!in_array($value, self::$allowedTargetTypes)) {
            throw new Exception('Invalid Message Type');
        }

        $this->attributes['target_type'] = $value;
    }

    /**
     * Just eloquent relation
     *
     * @return collection of \Modulesgarden\Crm\Models\Reminder
     */
    public function messages()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\MassMessagePending', 'mass_message_config_id');
    }

    /**
     * Parse date to day format, not with time
     *
     * @return type
     */
    public function getDateAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->toDateString() . ' ' . $date->toTimeString();
    }

    /**
     * Counters For Campaigns resources
     * this is just a scope for helper query builred
     *
     * @return type
     */
    public function scopeWithMessagesCount($query)
    {
        return $query->leftJoin('crm_mass_message_pendings', 'crm_mass_message_pendings.mass_message_config_id', '=', 'crm_mass_message_configs.id')
                        ->addSelect(DB::raw('crm_mass_message_configs.*, count(crm_mass_message_pendings.id) as messages_count'))
                        ->groupBy('crm_mass_message_configs.id');
    }

    /**
     * Parse atribute to integer
     *
     * @return type
     */
    public function getMessagesCountAttribute()
    {
        return (isset($this->attributes['messages_count'])) ? (int) $this->attributes['messages_count'] : 0;
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereDescription($query, $str)
    {
        return $query->where('crm_mass_message_configs.description', 'like', "%{$str}%");
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereMessageTitle($query, $str)
    {
        return $query->where('crm_mass_message_configs.message_title', 'like', "%{$str}%");
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereMessageContent($query, $str)
    {
        return $query->where('crm_mass_message_configs.message_content', 'like', "%{$str}%");
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereMessageEmailType($query)
    {
        return $query->where('crm_mass_message_configs.message_type', '=', 'email');
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereMessageSmsType($query)
    {
        return $query->where('crm_mass_message_configs.message_type', '=', 'sms');
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereTargetUsers($query)
    {
        return $query->where('crm_mass_message_configs.target_type', '=', 'users');
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereTargetUserGroups($query)
    {
        return $query->where('crm_mass_message_configs.target_type', '=', 'usergroups');
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereTargetCampaigns($query)
    {
        return $query->where('crm_mass_message_configs.target_type', '=', 'campaigns');
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereAlreadyGenerated($query)
    {
        return $query->where('crm_mass_message_configs.generated', '=', '1');
    }

    /**
     * scope for easy filter by campaign description
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWhereNotGenerated($query)
    {
        return $query->where('crm_mass_message_configs.generated', '=', '0');
    }

    public function generatePendingMessages()
    {
        // determinate ralation type
        // and target
        if ($this->target_type == 'usergroups') {
            $relObjects = DB::table('customer_entity')->where('is_active', '=', '1')->whereIn('group_id', $this->target_ids)->lists('entity_id');
            $relType = 'client_id';
        } elseif ($this->target_type == 'users') {
            $relObjects = DB::table('customer_entity')->where('is_active', '=', '1')->lists('entity_id');
            $relType = 'client_id';
        } elseif ($this->target_type == 'campaigns') {
            $relType = 'resource_id';
            $relObjects = DB::table('crm_campaigns')
                            ->select(array('crm_campaigns_resources.campaign_id', 'crm_campaigns_resources.resource_id'))
                            ->whereIn('crm_campaigns.id', $this->target_ids)
                            ->whereNull('crm_campaigns_resources.deleted_at')
                            ->whereNull('crm_campaigns.deleted_at')
                            ->leftJoin('crm_campaigns_resources', function($join) {
                                $join->on('crm_campaigns.id', '=', 'crm_campaigns_resources.campaign_id');
                            })->lists('resource_id');
        }

//        SlimApp::getInstance()->log->notice('CRON: Generating Messages for #{id} that will generate {num} records to send', array('id' => $this->id, 'num' => count($relObjects)));
        // just in case that nothing match
        if (count($relObjects) > 0) {
            // for performance mater dont use model for this since it will generate single insert per single item
            // we want one query to create all
            $message = $this->generateSingleMessageArrayData();

            $messagesArray = array();
            foreach ($relObjects as $relID) {
                $messagesArray[] = new MassMessagePending(array_merge($message, array($relType => $relID)));
            }

            $this->messages()->saveMany($messagesArray);
        }


        // mark config as generated, so next cron run wont re-generate messages to send
        $this->generated = true;
        $this->total = count($relObjects);
        $this->save();

//        SlimApp::getInstance()->log->notice('CRON: Marked Mass Messages Configuration #{id} as generated', array('id' => $this->id));


        return $this;
    }

    public function generateSingleMessageArrayData()
    {
        return array(
//            'mass_message_config_id'=> $this->id,             // we could make it manually
//            'client_id'             => $this->client_id,      // but eloquent will handle that ;)
//            'resource_id'           => $this->resource_id,    // without a scratch

            'message_content' => $this->message_content,
            'message_title' => $this->message_title,
            'message_type' => $this->message_type,
        );
    }

}
