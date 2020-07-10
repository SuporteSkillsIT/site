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

namespace Modulesgarden\Crm\Repositories;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;
use Modulesgarden\Crm\Models\Followup;
use Modulesgarden\Crm\Models\Reminder;
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Models\Magento\EmailTemplates;
use Carbon\Carbon;
use \Exception;

/**
 * Repository pattern for Reminders
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class Reminders extends AbstractRepository implements RepositoryInterface
{

    /**
     * Determinate model used by this Repository
     *
     * @return Reminder
     */
    function determinateModel()
    {
        return 'Modulesgarden\Crm\Models\Reminder';
    }

    /**
     * Usefull handler allow me to ofset date by certain reminder configuration
     *
     * @todo KURWA NAPRAWIĆ TO
     *
     * @param type $when
     * @param Carbon $followupDate
     * @return type
     */
    public static function recalculateDateByType($when,
            \Carbon\Carbon $followupDate, $data = array())
    {
        // bring me damm date
        if ($when == 'for_date') {
            $date = $followupDate;
        } elseif ($when == 'before_date') {
            // calculate diference between two dates
            $date = $followupDate;
            $ofset = intval(array_get($data, 'amount', 0));

            if (array_get($data, 'before') == 'days') {
                $date->subDays($ofset);
            } elseif (array_get($data, 'before') == 'hours') {
                $date->subHours($ofset);
            } else {
                $date->subMinutes($ofset);
            }
        } else {
            $date = Carbon::now();
        }

        return $date;
    }

    /**
     * Wrapper to create Admin Email Reminders
     *
     * @param Followup $followup
     * @param type $when
     * @param array $data
     * @return boolean
     */
    public static function createAdminEmailRemidner(\Modulesgarden\Crm\Models\Followup $followup,
            $when, array $data = array())
    {
        return self::createEmailRemidner($followup, 'admin', 'email', $when, $data);
    }

    /**
     * Wrapper to create Admin SMS Reminders
     *
     * @param Followup $followup
     * @param type $when
     * @param array $data
     * @return boolean
     */
    public static function createAdminSmsRemidner(\Modulesgarden\Crm\Models\Followup $followup,
            $when, array $data = array())
    {
        return self::createEmailRemidner($followup, 'admin', 'sms', $when, $data);
    }

    /**
     * Wrapper to create Client Email Reminders
     *
     * @param Followup $followup
     * @param type $when
     * @param array $data
     * @return boolean
     */
    public static function createClientEmailRemidner(\Modulesgarden\Crm\Models\Followup $followup,
            $when, array $data = array())
    {
        return self::createEmailRemidner($followup, 'client', 'email', $when, $data);
    }

    /**
     * Wrapper to create Client SMS Reminders
     *
     * @param Followup $followup
     * @param type $when
     * @param array $data
     * @return boolean
     */
    public static function createClientSmsRemidner(\Modulesgarden\Crm\Models\Followup $followup,
            $when, array $data = array())
    {
        return self::createEmailRemidner($followup, 'client', 'sms', $when, $data);
    }

    /**
     * Create Reminder entry + trigger various confiruration
     *
     * @param Followup $followup
     * @param type $targetType
     * @param type $reminderType
     * @param type $when
     * @param array $data
     * @param Carbon $customData
     * @return boolean|Reminder
     */
    public static function createEmailRemidner(
    \Modulesgarden\Crm\Models\Followup $followup, $targetType = 'admin',
            $reminderType = 'email', $when, array $data = array(),
            $customData = false)
    {
        try {
            $template = EmailTemplates::findOrFail(array_get($data, 'template'));

            // determinate relation type
            if ($targetType == 'client') { // basicaly it will be sent based by resource (lead/potential data)
                // fail on not existing related resources
                $targetID = $followup->resource_id;
                $targetCheckedType = 'resource';
            } else {
                // fail on not existing related resources
                $targetID = Admin::findOrFail(array_get($data, 'admin'))->user_id;
                $targetCheckedType = 'admin';
            }

            $cc = array_get($data, 'cc', null);
            if ($customData !== false && $customData instanceof \Carbon\Carbon) {
                $date = self::recalculateDateByType($when, $customData, $data);
            } else {
                $date = self::recalculateDateByType($when, $followup->date, $data);
            }

            $reminder = new Reminder(array(
                'followup_id' => $followup->id,
                'template_id' => $template->template_id,
                'target' => $targetCheckedType,
                'target_id' => $targetID,
                'date' => $date->timestamp,
                'cc' => array_get($data, 'cc', null),
                'reply' => array_get($data, 'reply', null),
                'type' => ($reminderType == 'sms' ? 'sms' : 'email'),
                'status' => 'pending',
            ));

            return $reminder;

            $reminder->save();
        } catch (Exception $e) {
            // silent escape. but we can create logs entry
            SlimApp::getInstance()->log->error($e);
            return false;
        }
    }

    /**
     * Find Reminders for certain resource and followup
     *
     * @param type $resourceID
     * @param type $followupID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForResourceAndFollowup($resourceID, $followupID)
    {
        return $this->getModel()
                        ->forFollowupResource($resourceID, $followupID)
                        ->get();
    }

    /**
     * Find Reminders for certain resource and followup
     *
     * @param type $resourceID
     * @param type $followupID
     * @param type $reminderID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForResourceAndFollowupAndReminder($resourceID,
            $followupID, $reminderID)
    {
        return $this->getModel()
                        ->where('id', '=', $reminderID)
                        ->forFollowupResource($resourceID, $followupID)
                        ->get();
    }

    /**
     * Manually create single instance of reminder for certain followup
     *
     * @param type $resourceID
     * @param type $followupID
     * @param type $requestData
     * @return Modulesgarden\Crm\Models\Reminder
     * @throws Exception
     */
    public function createSingleReminder($resourceID, $followupID, $requestData)
    {
        // new created reminder will be assigned to this damm followup
        $followup = Followup::findOrFail($followupID);

        if ($followup->resource_id != (int) $resourceID) {
            throw new Exception(sprintf('Follow-up does not belong to resource #%s', $resourceID));
        }

        // containder for data to send
        $data = array();

        // remindewr type
        $reminderType = array_get($requestData, 'type', 'email');

        // determinate reminder target
        if (in_array(array_get($requestData, 'for', 'admin'), array('admin', 'client'))) {
            $targetType = array_get($requestData, 'for', 'admin');
            array_set($data, 'admin', array_get($requestData, 'target_id', null));
        } else {
            throw new Exception('Invalid Reminder Target');
        }

        // set additional parameters for emails
        if ($reminderType == 'email') {
            if (array_get($requestData, 'email.cc', null) != null) {
                array_set($data, 'cc', array_get($requestData, 'email.cc', null));
            }
            if (array_get($requestData, 'email.reply', null) != null) {
                array_set($data, 'reply', array_get($requestData, 'email.reply', null));
            }
        }

        // set up temilate id
        array_set($data, 'template', array_get($requestData, 'template_id', null));

        // parse configured date from form
        $configuredDate = Carbon::parse(array_get($requestData, 'date', null));


        // attach this
        $reminder = self::createEmailRemidner($followup, $targetType, $reminderType, 'for_date', $data, $configuredDate);

        // magic happend here
        $followup->reminders()->save($reminder);

        return $reminder;
    }

    /**
     * Update reminder!
     *
     * @param type $resourceID
     * @param type $followupID
     * @param type $reminderID
     * @param type $requestData
     * @return Modulesgarden\Crm\Models\Reminder
     * @throws Exception
     */
    public function updateSingleReminder($resourceID, $followupID, $reminderID,
            $requestData)
    {
        // and finally get reminder object
        $reminder = Reminder::forFollowupResource($resourceID, $followupID)->whereId($reminderID)->first();

        if (is_null($reminder)) {
            throw new Exception(sprintf('Follow-up does noe belong to resource #%s', $resourceID));
        }

        // containder for data to send
        $data = array();

        // set up temilate id
        $template = EmailTemplates::findOrFail(array_get($requestData, 'template_id'));
        array_set($data, 'template_id', $template->template_id);

        // remindewr type
        array_set($data, 'type', array_get($requestData, 'type', null));

        // determinate reminder target
        $targetType = array_get($requestData, 'target', 'admin');
        if (in_array($targetType, array('admin', 'resource'))) {
            array_set($data, 'target', $targetType);
            array_set($data, 'target_id', array_get($requestData, 'target_id', null));
        } else {
            throw new Exception('Invalid Reminder Target');
        }

        array_set($data, 'cc', array_get($requestData, 'cc', null));
        array_set($data, 'reply', array_get($requestData, 'reply', null));

        // parse configured date from form
        $configuredDate = Carbon::parse(array_get($requestData, 'date', null));
        array_set($data, 'date', $configuredDate->__toString());


        $reminder->fill($data)->save();

        return $reminder;
    }

    /**
     * Used by calendar
     * Obtain reminders assigned to followup
     *
     * @param type $followupID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFollowupReminders($followupID)
    {
        return $this->getModel()
                        ->forFollowup($followupID)
                        ->get();
    }

    /**
     * Obtain reminders that are pending and scheduled for time before now
     * simple, cron needs it to send that reminderd -/-
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function cronGetRemindersToSent()
    {
        return $this->getModel()
                        ->pendingOrError()
                        ->beforeNow()
                        ->get();
    }

    /**
     * remove reminder from DB
     *
     * @param type $reminderID
     * @param type $followupID
     * @return bolean
     */
    public function deleteSingle($reminderID, $followupID)
    {
        $reminder = $this->getModel()->findOrFail($reminderID);
        return $reminder->forceDelete();
    }

}
