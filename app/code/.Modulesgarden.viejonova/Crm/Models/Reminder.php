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
use Modulesgarden\Crm\Models\Source\AbstractModel;
use Modulesgarden\Crm\Services\Mailer;
use Modulesgarden\Crm\Services\SenderSms;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Repositories\FieldDatas;
use Modulesgarden\Crm\Models\Validators\Common;
use Modulesgarden\Crm\Models\Magento\Admin;
use Carbon\Carbon;
use \Exception;

class Reminder extends AbstractModel
{

    public $timestamps = true;
    protected $table = 'crm_followup_reminders';
    protected $guarded = array('id');
    protected $fillable = array(
        'followup_id', // int(10)
        'template_id', // int(10)
        'type', // enum('sms', 'email')
        'status', // enum('sent', 'pending', 'error')
        'target', // enum('resource', 'admin')
        'target_id', // int(10)
        'date', // timestamp
        'cc', // text     -- json decoded array with Admin ID's to which set up cc
        'reply', // text     -- single admin id t to which set up reply email
    );

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = array('date', 'deleted_at', 'updated_at', 'created_at');

    /**
     * Just eloquent relation
     *
     * @return \Modulesgarden\Crm\Models\Followup
     */
    public function followup()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Followup', 'followup_id');
    }

    /**
     * Just eloquent relation
     *
     * @return \Modulesgarden\Crm\Models\Magento\EmailTemplates
     */
    public function template()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\EmailTemplates', 'template_id');
    }

    /**
     * Parse related IDs to integer
     */
    public function getFollowupIdAttribute($value)
    {
        return intval($value);
    }

    /**
     * Parse related IDs to integer
     */
    public function getTargetIdAttribute($value)
    {
        return intval($value);
    }

    /**
     * Parse related IDs to integer
     */
    public function getTemplateIdAttribute($value)
    {
        return intval($value);
    }

    /**
     * Parse related IDs to integer
     */
    public function getReplyAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }

        return intval($value);
    }

    public function scopeSearchOld($query)
    {
        //todo
    }

    public function scopeSearchByTwoDates($query)
    {
        //todo
    }

    public function scopeSearchToSend($query)
    {
        //todo
    }

    /**
     * Parse related IDs to integer
     */
    public function getCcAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Parse related IDs to integer
     */
    public function setCcAttribute($value)
    {
        $this->attributes['cc'] = json_encode($value);
    }

    /**
     * Join assigned reminders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeForFollowupResource($query, $resourceID, $followupID)
    {
        return $query->forFollowup($followupID)->with(array(
                    'followup' => function($query) use ($resourceID) {
                        $query->where('resource_id', '=', $resourceID);
                    }
        ));
    }

    /**
     * Join assigned reminders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeForFollowup($query, $followupID)
    {
        return $query->where('followup_id', '=', $followupID);
    }

    /**
     * Join assigned reminders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereId($query, $id)
    {
        return $query->where('id', '=', $id);
    }

    /**
     * plain reschedue
     *
     * @param bolean $plusToDate    euther of add minutes or remove
     * @param integet $timeDiff     diff in number of minutes
     * @return type
     */
    public function reschedue($plusToDate, $timeDiff)
    {
        $newDate = $this->date->copy();

        if ($plusToDate === true) {
            $this->date = $this->date->addMinutes($timeDiff);
        } else {
            $this->date = $this->date->subMinutes($timeDiff);
        }


        return $this->save();
    }

    /**
     * Join assigned reminders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePendingOrError($query)
    {
        return $query->whereIn('status', array('pending', 'error'));
    }

    /**
     * Join assigned reminders
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeBeforeNow($query)
    {
        return $query->where('date', '<=', Carbon::now());
    }

    /**
     * Main function to perform sent
     * @return bolean
     */
    public function sent()
    {
        if ($this->type == 'sms') {
            return $this->sentSmsReminder();
        } else {
            return $this->sentEmailReminder();
        }

        return false;
    }

    protected function sentEmailReminder()
    {
        // this is what we need to fill
        $recipients = array();
        $cc = array();
        $replyTo = array();
        $smartyVariables = array();
        $emailTemplate = $this->template_id;

        // determinate target
        if ($this->target == 'resource') {
            // get email
            $targetEmail = $this->followup->resource->getEmailForReminder();

            // validate
            if (!Common::isValidEmail($targetEmail)) {
                throw new Exception(sprintf('Email (%s) is not valid', $targetEmail));
            }

            // push
            $recipients[] = $targetEmail;
        } elseif ($this->target == 'admin') {

            // get email
            $target = Admin::filterIrrelevantParams()->findOrFail($this->target_id);

            // validate
            if (!Common::isValidEmail($target->email)) {
                throw new Exception(sprintf('Email (%s) is not valid', $target->email));
            }

            // push
            $recipients[] = $target->email;
        } else {
            throw new Exception(sprintf('Invalid Reminder target type (%s).', $this->target));
        }

        // check for reply to admin
        if (Common::isPositiveNumber($this->reply) && !is_null($this->reply)) {
            // by design reply to is addressed to some admin so..
            $adminObj = Admin::filterIrrelevantParams()->findOrFail($this->reply);
            // set up reply to email address
            $replyTo = array(
                'email' => $adminObj->email,
                'name' => $adminObj->full,
            );
        }


        // check for cc to admin
        if (is_array($this->cc) && !empty($this->cc)) {
            // by design reply to is addressed to some admin so..
            $admins = Admin::filterIrrelevantParams()->whereIn('user_id', $this->cc)->get();

            foreach ($admins as $a) {
                // just to be sure
                if (Common::isValidEmail($a->email)) {
                    $cc[] = array(
                        'email' => $a->email,
                        'name' => $a->full,
                    );
                }
            }
        }

        // get smarty variables
        $customFieldRepo = new FieldDatas();
        $fieldsData = $customFieldRepo->getOnlyFieldsForResourceSummary($this->followup->resource_id);

        $this->followup->resource->load('status', 'client');

        array_set($smartyVariables, 'contact', $this->followup->resource->toArray());
        array_set($smartyVariables, 'followup', $this->followup->toArray());
        $smartyVariables['contact']['fields'] = $fieldsData;

        if ($this->followup->resource->client->id) {
            array_set($smartyVariables, 'contact.client', $this->followup->resource->client->markAllColumnsVisible()->toArray());
        }
        // its time to send
        $result = null;
        try {
            $result = Mailer::getInstance()->sentReminderEmailFromTemplate($emailTemplate, $recipients, $replyTo, $cc, $smartyVariables, array(
                'resource_id' => $this->followup->resource->id,
                'followup_id' => $this->followup->id,
                'reminder_id' => $this->id,
                'smtp' => null
            ));
        } catch (Exception $e) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/reminderSendFailLogs.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info(print_r($e->getMessage(), true));
        }


        if ($result == true) {
            $this->status = 'sent';
//            SlimApp::getInstance()->log->notice('Reminder #{id} has been sent', array('id', $this->id));
        } else {
            $this->status = 'error';
//            SlimApp::getInstance()->log->error('Unable to send Reminder #{id}', array('id', $this->id));
        }

        $this->save();


        return $result;
    }

    protected function sentSmsReminder()
    {
        // determinate target
        if ($this->target != 'admin') {
            throw new Exception(sprintf('Invalid Reminder target type (%s).', $this->target));
        }

        // get smarty variables
        $customFieldRepo = new FieldDatas();
        $fieldsData = $customFieldRepo->getOnlyFieldsForResourceSummary($this->followup->resource_id);

        $this->followup->resource->load('status', 'client', 'ticket');

        array_set($smartyVariables, 'contact', $this->followup->resource->toArray());
        array_set($smartyVariables, 'followup', $this->followup->toArray());
        array_add($smartyVariables['contact'], 'fields', $fieldsData);

        if ($this->followup->resource->client->id) {
            array_set($smartyVariables, 'contact.client', $this->followup->resource->client->markAllColumnsVisible()->toArray());
        }

        // its time to send
        $result = SenderSms::getInstance()->sentReminderSMSFromTemplate(
                $this->target_id, $this->template_id, $smartyVariables
        );


        if ($result['result'] == 'success') {
            $this->status = 'sent';
            $return = true;
            SlimApp::getInstance()->log->notice('SMS Reminder #{id} has been sent. {msg}', array('id', $this->id, 'msg' => $result['message']));
        } else {
            $this->status = 'error';
            $return = false;
            SlimApp::getInstance()->log->error('Unable to send SMS Reminder #{id} Unable to sent: {msg}', array('id', $this->id, 'msg' => $result['message']));
        }

        $this->save();

        return $return;
    }

}
