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

//use Modulesgarden\Crm\Integration\Slim\SlimApp;

use Modulesgarden\Crm\Models\Source\AbstractModel;
use Modulesgarden\Crm\Models\Validators\Common as Validator;
use Modulesgarden\Crm\Repositories\FieldDatas;
use Modulesgarden\Crm\Services\Mailer;
use Modulesgarden\Crm\Services\SenderSms;
use \Illuminate\Database\Capsule\Manager as DB;
use \Exception;

/**
 * Class For Mass Mail Config records
 */
class MassMessagePending extends AbstractModel
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'crm_mass_message_pendings';

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
        'mass_message_config_id',
        'client_id',
        'resource_id',
        'message_content',
        'message_title',
        'message_type',
    );

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
    public $timestamps = false;

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
     * Set type of messate and in case of not allowed throw exception
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
     * Relation For Assigned Client to this Model
     * relation: ONE TO ONE
     *
     * @return Modulesgarden\Crm\Models\Magento\Client
     */
    public function client()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\Client', 'client_id');
    }

    public function scopeFix($query)
    {
        return $query;
        return $query->with(array('client' => function($query) {
                        $query->selectAllColumns();
                    }));
    }

    public function toArray()
    {
        if ($this->client) {
            $this->client->markAllColumnsVisible();
        }

        $array = parent::toArray();

        return $array;
    }

    /**
     * Relation For Resources to this Model
     * relation: ONE TO ONE
     *
     * @return \Modulesgarden\Crm\Models\Resources
     */
    public function resource()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Resource', 'resource_id')->withTrashed();
    }

    /**
     * From what confit it was generated
     * relation: ONE TO ONE
     *
     * @return Modulesgarden\Crm\Models\MassMessageConfig
     */
    public function messageConfig()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\MassMessageConfig', 'mass_message_config_id');
    }

    /**
     * Main function to perform sent
     * @return bolean
     */
    public function sent()
    {
        if ($this->message_type == 'sms') {
            return $this->sentAsSms();
        } elseif ($this->message_type == 'email') {
            return $this->sentAsEmail();
        }

        return false;
    }

    protected function sentAsEmail()
    {
        try {
            $clientEmail = $this->client->email;
            $client_id = null;

            //
            if (!empty($this->client->email)) {
                $targetEmail = $this->client->email;
                $client_id = $this->client->entity_id;
            } elseif (!empty($this->resource->client->email)) {
                $targetEmail = $this->resource->client->email;
                $client_id = $this->resource->client->entity_id;
            } elseif (!empty($this->resource->email)) {
                $targetEmail = $this->resource->email;
            }

//        test case
//        $targetEmail = 'piotr.sa@modulesgarden.com';
            // validate
            if (!Validator::isValidEmail($targetEmail)) {
                throw new Exception(sprintf('Email (%s) is not valid', $targetEmail));
            }

            // get smarty variables
            $smartyVariables = array();


            array_set($smartyVariables, 'config', $this->message_config->toArray());

            if ($this->client->entity_id) {
                array_set($smartyVariables, 'client', $this->client->markAllColumnsVisible()->toArray());
            }

//        if ($this->resource_id) {
//            $customFieldRepo = new FieldDatas();
//            $fieldsData = $customFieldRepo->getOnlyFieldsForResourceSummary($this->resource_id);
//
//            array_set($smartyVariables, 'contact', $this->resource->toArray());
//            $smartyVariables['contact']['fields'] = $fieldsData;
//
//            if ($this->resource->client->entity_id) {
//                array_set($smartyVariables, 'contact.client', $this->resource->client->markAllColumnsVisible()->toArray());
//            }
//        }
            $result = Mailer::getInstance()->sentRawEmail(
                    array($targetEmail), // single recipment
                    array(), // dont set reply to, will be used default from we sent
                    array(), // no cc
                    null, // from email - loaded from global settings
                    $this->message_title, // subject as plain text
                    $this->message_content, // subject as plain text
                    array(), // $files if any
                    $smartyVariables, // passed variables to use
                    array(
                'resource_id' => $this->resource_id,
                'client_id' => $client_id,
                    )
            );


            $logArray = array(
                'id' => $this->id,
                'cid' => $this->mass_message_config_id,
                'email' => $targetEmail,
            );

            if ($result == true) {
                //  SlimApp::getInstance()->log->notice('CRON: Mass Message (id:{id} from config #{cid}) has been send to {email}', $logArray);
                $this->forceDelete();

                return true;
            } else {
                //  SlimApp::getInstance()->log->notice('CRON: Mass Message (id:{id} from config #{cid}) Unable to sent to {email}', $logArray);
            }
        } catch (Exception $e) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/massMessageSendFailLogs.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info(print_r($e->getMessage(), true));
        }
        return false;
    }

    /**
     * This currenyly use only send raw message for clients ;)
     * 
     * @return boolean
     * @throws Exception
     */
    protected function sentAsSms()
    {
        $clientId = $this->client->id;
        $resourceClientId = $this->resource->client->id;
        $resourcePhone = $this->resource->phone;

        $targetClientID = $targetPhone = null;
        if ($clientId) {
            $targetClientID = $clientId;
        } elseif ($resourceClientId) {
            $targetClientID = $resourceClientId;
        } elseif (!empty($resourcePhone)) {
            $targetPhone = $resourcePhone;
        }

        // validate
        if (!($targetClientID || $targetPhone)) {
            throw new Exception(sprintf('No Target phone provided to sent sms'));
        }

        // get smarty variables
        $smartyVariables = array();


        array_set($smartyVariables, 'config', $this->message_config->toArray());

        if ($this->client->id) {
            array_set($smartyVariables, 'client', $this->client->markAllColumnsVisible()->toArray());
        }

        if ($this->resource->id) {
            $customFieldRepo = new FieldDatas();
            $fieldsData = $customFieldRepo->getOnlyFieldsForResourceSummary($this->resource->id);

            array_set($smartyVariables, 'contact', $this->resource->toArray());
            array_add($smartyVariables['contact'], 'fields', $fieldsData);

            if ($this->resource->client->id) {
                array_set($smartyVariables, 'contact.client', $this->resource->client->markAllColumnsVisible()->toArray());
            }
        }

        $result = SenderSms::getInstance()->sentRawSMSForClient(
                $targetClientID, $targetPhone, $this->message_content, $smartyVariables, // passed variables to use
                array(
            'resource_id' => $this->resource_id,
            'client_id' => $this->client_id,
                )
        );


        $logArray = array(
            'id' => $this->id,
            'cid' => $this->mass_message_config_id,
            'msg' => $result['message'],
        );

        if ($result['result'] == 'success') {
//            SlimApp::getInstance()->log->notice('CRON: SMS Mass Message (id:{id} from config #{cid}) has been added to SMS Center. Message: {msg}', $logArray);
            $this->forceDelete();

            return true;
        } else {
//            SlimApp::getInstance()->log->error('CRON: SMS Mass Message (id:{id} from config #{cid}) Unable to sent: {msg}', $logArray);
        }

        return false;
    }

}
