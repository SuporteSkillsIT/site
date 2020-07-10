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
use Modulesgarden\Crm\Services\Language;
use Modulesgarden\Crm\Repositories\Followups;
use Modulesgarden\Crm\Models\Source\AbstractModel;
use Modulesgarden\Crm\Models\Validators\Common as Validator;
use Modulesgarden\Crm\Models\Magento\Client;
use Modulesgarden\Crm\Models\Log;
use Modulesgarden\Crm\Models\Setting;
use Modulesgarden\Crm\Models\ResourceType;
use \Exception;
use Carbon\Carbon;

/**
 * Class Model for Resource
 * In frontend called "Contact"
 *
 * Main core of these module
 */
class Resource extends AbstractModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_resources';

    /**
     * Eloquent guarded parameters
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = array('name', 'email', 'phone', 'priority', 'type_id', 'status_id', 'admin_id', 'client_id');

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
     * This is used to map what fields are static, for table/forms/etc
     * to handle names syntax and other stuff
     *
     * Dont change this
     * @var array
     */
    protected static $staticFields = array(
        'id',
        'name',
        'status',
        'email',
        'phone',
        'priority',
        'client',
        // 'ticket',
        'admin',
    );

    /**
     * Defined that this particular fields
     * are easy to fill to model in DB, since they are 'strings'
     *
     * @var array
     */
    protected static $staticFieldsRelations = array(
        'name',
        'email',
        'phone',
        'priority',
    );

    /**
     * Defined that this particular in model
     * are responsible for store relations ID's
     *
     * based by that we will ASSIGN/ATTACH (only) certain model relations (not obtain!)
     *
     * @var array
     */
    protected static $staticFieldsFillable = array(
        'status_id' => 'status',
        'admin_id' => 'admin',
        'client_id' => 'client',
        //   'ticket_id' => 'ticket',
        'type_id' => 'type',
    );

    /**
     * Plain getter for static fields
     * used by table generations etc
     *
     * @return array
     */
    public static function getStaticFields()
    {
        return self::$staticFields;
    }

//is_potential
    /**
     * Function fo Fill static fields
     * only these one taht are strings
     *
     * @param array $data
     * @throws Exception
     */
    public function fillMainDetails(array $data = array())
    {
        $parsed = array();
        foreach (self::$staticFieldsRelations as $staticName) {
            $value = array_get($data, $staticName, null);

            // validations if so
            if ($staticName == 'name' && !$this->isValidateName($value)) {
                throw new Exception('Name must be filled');
            }

            if (!empty($value) && ($staticName == 'email' && !$this->isValidateEmail($value))) {
                throw new Exception('Email must be filled');
            }

            if ($staticName == 'type_id' && !Validator::isPositiveUnsignedNumber($value)) {
                ~rt($staticName);
                throw new Exception('Invalid Contact Type');
            }

            $parsed[$staticName] = $value;
        }

        $this->fill($parsed);
    }

    /**
     * Relation For Assigned Admin to this Resource
     *
     * relation: ONE TO ONE
     *
     * @return Modulesgarden\Crm\Models\Magento\Admin
     */
    public function admin()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\Admin', 'admin_id');
    }

    /**
     * Relation For Assigned Type to this Resource
     *
     * relation: ONE TO ONE
     *
     * @return \Modulesgarden\Crm\Models\ResourceType
     */
    public function type()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\ResourceType', 'type_id');
    }

    /**
     * Relation For Assigned Client to this Resource
     *
     * relation: ONE TO ONE
     *
     * @return Modulesgarden\Crm\Models\Magento\Client
     */
    public function client()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\Client', 'client_id');
    }

    /**
     * CRM statuses for leads relation
     *
     * @return Modulesgarden\Crm\Models\FieldStatus
     */
    public function status()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\FieldStatus', 'status_id');
    }

    /**
     * CRM statuses for notes relation
     *
     * @return Modulesgarden\Crm\Models\Note
     */
    public function notes()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\Note', 'resource_id');
    }

    public function fieldDatas()
    {
        return $this->hasMany('Modulesgarden\Crm\Models\FieldData', 'resource_id');
    }

    /**
     * relations to many campaigns, possible
     *
     * relation: MANY TO MANY
     * remember that this will return collection!
     *
     * @return collection of Modulesgarden\Crm\Models\Campaign
     */
    public function campaigns()
    {
        return $this->belongsToMany('Modulesgarden\Crm\Models\Campaign', 'crm_campaigns_resources', 'resource_id', 'campaign_id')->withTimestamps()->withTrashed();
    }

    /**
     * Helper scope that let me return data with Admin Object
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWithAdmin($query)
    {
        return $query->with('admin');
    }

    /**
     * Helper scope that let me return data with Client Object
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWithClient($query)
    {
        return $query->with('client');
    }

    /**
     * Helper scope that let me return data with Ticlet Object Notes
     *
     * @return Eloquent\Query\Builder
     */
//    public function scopeWithTicket($query)
//    {
//        return $query->with('ticket');
//    }

    /**
     * Helper scope that let me return data with Assigned Notes
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWithNotes($query)
    {
        return $query->with('notes');
    }

    /**
     * Helper scope that let me return data with Assigned Notes
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeWithType($query)
    {
        return $query->with('type');
    }

    /**
     * Helper just perform check if we have valid (not empty) name for this model
     *
     * @return bolen
     */
    public function isValidateName($val)
    {
        return Validator::isEmpty($val);
    }

    /**
     * Helper just perform check if we have valid (not empty) email
     *
     * @return bolen
     */
    public function isValidateEmail($val)
    {
        return Validator::isEmail($val);
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignAdmin(\Modulesgarden\Crm\Models\Magento\Admin $Model)
    {
        $this->admin()->associate($Model);
        $this->addLog('admin_id');
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignAdminByID($id)
    {
        $id = (int) $id;

        if (!Validator::isPositiveNumber($id)) {
            throw new Exception('Wrong Admin ID');
        }

        $this->admin_id = $id;
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignStatus(\Modulesgarden\Crm\Models\FieldStatus $Model)
    {
        $this->status()->associate($Model);
        $this->addLog('status_id');
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignStatusByID($id)
    {
        $id = (int) $id;

        if (!Validator::isPositiveNumber($id)) {
            throw new Exception('Wrong Admin ID');
        }

        $this->status_id = $id;
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignClient(\Modulesgarden\Crm\Models\Magento\Customer $Model)
    {
        $this->client()->associate($Model);
        $this->addLog('client_id');
    }

    /**
     * Trigger Eloquent Assign by model
     */
    public function assignClientByID($id)
    {
        $id = (int) $id;

        if (!Validator::isPositiveNumber($id)) {
            throw new Exception('Wrong Client ID');
        }

        $this->client_id = $id;
    }

    /**
     * Manually assign resource type by ID
     * 
     * @param int $id
     */
    public function assignType(\Modulesgarden\Crm\Models\ResourceType $Model)
    {
        $this->type()->associate($Model);
        $this->addLog('type_id');
    }

    /**
     * This is main function that handle events for leads
     * proiably based on strategy pattern
     *
     * @param string $eventName     system event name
     * @param type $enabled         we could define if it should be enabled or not here
     * @return boolean              mixed
     */
    public function pushEvent($eventName, $enabled = true)
    {
        if ($enabled !== true) {
            return false;
        }

        if ($eventName = 'CreateFollowupOnCreate') {
            Followups::createFollowupForResourceOnCreate($this);
            // trigger followup create in other classes
//            $followupTypeID = Setting::forGlobal()->whereSetting('followups_create_lead_followup_type')->first();
//
//            if($followupTypeID) {
//                $followupsRepo = new Followups();
//                $followupsRepo->createFollowup($this->id);
//            }
////            ~rt('damm motherfucker create followup for me');
//            ~rt($followupTypeID->toArray());
//            ~rt($followupTypeID);
//            die();
        }
    }

    public function updateSingleParam($data)
    {
        $parsed = array();

        $allowed = array_merge(array('status_id', 'admin_id', 'client_id', 'type_id'), self::$staticFieldsRelations);

        foreach ($data as $paramName => $paramVal) {
            if (!in_array($paramName, $allowed) && $this->{$paramName} != $paramVal) {
                continue;
            }

            if ($paramName == 'admin_id' && $paramVal === 0) {
                $this->{$paramName} = null;
            } else {
                $this->{$paramName} = $paramVal;
            }

            $this->addLog($paramName, $paramVal);
        }

        return $this->save();
    }

    /**
     * Used by email reminders
     * return just email from this record, but might be usefull
     * @return $this->email
     */
    public function getEmailForReminder()
    {
        return $this->email;
    }

    /**
     * Reassign ticket that is assigned to model
     *
     * @param type $ticketID
     */
//    public function reassignTicket($ticketID)
//    {
//        $this->assignTicket(Ticket::findOrFail($ticketID));
//        $this->save();
//        $this->addLog('ticket_id');
//    }

    /**
     * Reassign client that is assigned to model
     *
     * @param type $clientID
     */
    public function reassignClient($clientID)
    {
        $this->assignClient(Client::findOrFail($clientID));
        $this->save();
        $this->addLog('client_id');
    }

    /**
     * Reassign client that is assigned to model
     *
     * @param type $id
     */
    public function reassignassignType($id)
    {
        $this->assignType(ResourceType::findOrFail($id));
        $this->save();
        $this->addLog('client_id');
    }

    /**
     * unassign client that is assigned to model
     */
    public function unassignClient()
    {
        $this->client_id = null;
        $this->save();
        $this->addLog('client_unassign');
    }

    /**
     * unassign client that is assigned to model
     */
//    public function unassignTicket()
//    {
//        $this->ticket_id = null;
//        $this->save();
//        $this->addLog('ticket_unassign');
//    }

    /**
     * perform add log
     * 
     * @param type $what
     * @param type $data
     * @return boolean
     */
    public function addLog($what, $data = array())
    {
        if (!$this->id) {
            return false;
        }

        $event = 'Undefined';
        $message = '';

        // these are from static fields
        if ($what == 'priority') {
            $event = 'Priority Update';
            $message = Language::translate('log.reassign.priority', array('priority' => Language::translate("priority.{$this->priority}")));
        } elseif ($what == 'phone') {
            $event = 'Parameter Update';
            $message = Language::translate('log.reassign.phone', array('phone' => $this->phone));
        } elseif ($what == 'email') {
            $event = 'Parameter Update';
            $message = Language::translate('log.reassign.email', array('email' => $this->email));
        } elseif ($what == 'name') {
            $event = 'Parameter Update';
            $message = Language::translate('log.reassign.name', array('name' => $this->name));
        } elseif ($what == 'status_id') {
            $event = 'Status Change';
            $message = Language::translate('log.reassign.status', array('status' => $this->status->name));
        } elseif ($what == 'admin_id') {
            if ($paramVal === 0) {
                $event = 'Unassign Admin';
                $message = Language::translate('log.unassign.admin');
            }
            $event = 'Admin Reassign';
            $message = Language::translate('log.reassign.admin', array('admin' => $this->admin->fullName));
//        } elseif($what == 'ticket_id') {
//            $event   = 'Ticket Reassign';
//            $message = Language::translate('log.reassign.ticket', array('ticket' => $this->ticket->tid));
        } elseif ($what == 'client_id') {
            $event = 'Client Reassign';
            $message = Language::translate('log.reassign.client', array('client' => $this->client->fullName));
        }

        // these are dynamically
        elseif ($what == 'client_unassign') {
            $event = 'Client Unassign';
            $message = Language::translate('log.unassign.client');
//        } elseif($what == 'ticket_unassign') {
//            $event   = 'Ticket Unassign';
//            $message = Language::translate('log.unassign.ticket');
        }


        $log = new Log(array(
            'resource_id' => $this->id,
            'admin_id' => SlimApp::getInstance()->currentAdmin->user_id,
            'event' => $event,
            'date' => Carbon::now(),
            'message' => $message,
        ));
        $log->save();
    }

    /**
     * Reassign association to campaigns
     *
     * @param type $data
     * @return \Modulesgarden\Crm\Models\Resource
     */
    public function syncAssignedCampaigns($data)
    {
        $campaingsIDs = array_get($data, 'campaigns', array());
        if (is_array($campaingsIDs)) {
            $this->campaigns()->sync($campaingsIDs);
            $this->touch();
        }

        return $this;
    }

//    public function getTicketIdAttribute($value)
//    {
//        return $value === null ? null : intval($value);
//    }
    public function getStatusIdAttribute($value)
    {
        return $value === null ? null : intval($value);
    }

    public function getTypeIdAttribute($value)
    {
        return $value === null ? null : intval($value);
    }

    public function getClientIdAttribute($value)
    {
        return $value === null ? null : intval($value);
    }

    public function getPriorityAttribute($value)
    {
        return intval($value);
    }

}
