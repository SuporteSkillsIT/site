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

namespace Modulesgarden\Crm\Controllers;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/loader.php';
$loader = new \Modulesgarden\Crm\loader();

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Repositories\Reminders;
use Modulesgarden\Crm\Repositories\MassMessageConfigs;
use Modulesgarden\Crm\database\Connection;
use Modulesgarden\Crm\Models\MassMessagePending;
use Modulesgarden\Crm\Models\Validators\Common;
use \Illuminate\Database\Capsule\Manager as DB;
use \Carbon\Carbon;
use \Exception;

//function globalExceptionHandler($e)
//{
//    $file = 'kurwa.log';
//    file_put_contents($file, var_dump($e));
////    error_log($e);
//}
//
//function globalErrorHandler($errno, $errstr, $errfile, $errline)
//{
//    ini_set("log_errors", E_ALL);
//    ini_set("error_log", "/tmp/php-error.log");
//    switch ($errno) {
//        case E_NOTICE:
//        case E_USER_NOTICE:
//            $errors = "Notice";
//            break;
//        case E_WARNING:
//        case E_USER_WARNING:
//            $errors = "Warning";
//            break;
//        case E_ERROR:
//        case E_USER_ERROR:
//            $errors = "Fatal Error";
//            break;
//        default:
//            $errors = "Unknown Error";
//            break;
//    }
//
//    error_log(sprintf("PHP %s:  %s in %s on line %d", $errors, $errstr, $errfile, $errline));
//    $msg = "ERROR: [$errno] $errstr\r\n" .
//            "$errors on line $errline in file $errfile\r\n";
//    $file = 'kurwa.log';
//    file_put_contents($file, var_dump($msg));
//
//    exit($msg);
//}
//set_exception_handler('globalExceptionHandler');
//set_error_handler('globalErrorHandler', E_ALL);
/**
 * Note that it seems to be controller
 * but WHO CARE
 *
 * All Cron Logic is here, so ITS not really Controller or any MVC
 *
 */
class Cron extends AbstractController
{

    // just keep messages to send in outpout
    protected $messages = array();
    // counter, how many messages (smail/sms) send via single cron run
    // this will be decrementing to zero then stop
    // this limit can be overrided in app/Config/app.php, as setting "cron.messagesLimit"
    protected $limit = 100;

    const LIMIT_MESSAGES_PER_RUN = 100;

    public function __construct()
    {

        parent::__construct();

//        $this->updateCronStart();
        $this->setNewLimits();


//        $messagesLimit = $this->app->configFile->get('app.cron.messagesLimit');
//        if (Common::isPositiveUnsignedNumber($messagesLimit)) {
//            $this->limit = $messagesLimit;
//            unset($messagesLimit);
//        }
//
//        ob_clean();
    }

    public function setNewLimits()
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);
    }

    public function isInCLIMode()
    {
        return php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR']);
    }

    public function run()
    {
        $this->addMessage('Cron Start');

        $this->handleReminders();
        $this->handleMassMessages();

        $this->addMessage('Cron Stop');
        $this->output();
    }

    protected function addMessage($msg)
    {
        //just in cas add to container

        $this->messages[] = $msg;

        if ($this->isInCLIMode()) {
            echo $msg . "\r\n";
        } else {
            echo $msg, '<br />';
        }
    }

    protected function output()
    {
        // possibly log everything to file, but who care right now
//        $this->messages
        die();
    }

    /**
     * Update Times in DB to calculate various data
     */
//    protected function updateCronStart()
//    {
//        $previous   = DB::table('tblconfiguration')->where('setting', '=', 'Modulesgarden\Crm_cron_previous')->select('value')->first();
//        $last       = DB::table('tblconfiguration')->where('setting', '=', 'Modulesgarden\Crm_cron_last')->select('value')->first();
//
//        $previousTimeStamp  = intval(array_get($last, 'value', 0));
//        $lastTimeStamp      = time();
//
//        if( is_null($previous) ) {
//            DB::insert('insert into tblconfiguration (value, setting) values (?, ?)', array($previousTimeStamp, 'Modulesgarden\Crm_cron_previous'));
//        } else {
//            DB::update('update tblconfiguration set value = ? where setting = ?', array($previousTimeStamp, 'Modulesgarden\Crm_cron_previous'));
//        }
//
//        if( is_null($last) ) {
//            DB::insert('insert into tblconfiguration (value, setting) values (?, ?)', array($lastTimeStamp, 'Modulesgarden\Crm_cron_last'));
//        } else {
//            DB::update('update tblconfiguration set value = ? where setting = ?', array($lastTimeStamp, 'Modulesgarden\Crm_cron_last'));
//        }
//    }
    // handle reminders
    public function handleReminders()
    {
        try {
            $dbConnection = new Connection;
            $remindersRepository = new Reminders();
            $remindersToSent = $remindersRepository->cronGetRemindersToSent();
            //$this->addMessage(sprintf('%s Reminders to sent', count($remindersToSent)));
            $counter = 0;

            foreach ($remindersToSent as $reminder) {
                if ($this->limit <= 0) {
                    break;
                }

                try {
                    if ($reminder->sent()) {
                        $counter++;
                        $this->limit--;
                    }
                } catch (Exception $e) {
                    $this->addMessage(sprintf('Unable to sent reminder #%s: %s', $reminder->id, $e->getMessage()));
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        //exit(var_dump('kurwa'));
        //  $this->addMessage(sprintf('%s of %s has been sent', $counter, count($remindersToSent)));
    }

    public function handleMassMessages()
    {
        try {
            $dbConnection = new Connection;
            // first lets check for messages that should be generated
            $repo = new MassMessageConfigs();
            $repo->generatePendingMassMessages();

            // now we can send messages that are scheduled
            $collection = MassMessagePending::with(array('resource', 'resource.status', 'resource.type', 'resource.admin', 'resource.ticket', 'messageConfig'))->fix()->take($this->limit)->get();

            // $this->addMessage(sprintf('%s Messages to sent', count($collection)));
            $counter = 0;
            $counterTotal = MassMessagePending::count();
            foreach ($collection as $message) {
                // take limit under consideration
                if ($this->limit <= 0) {
                    break;
                }

                try {
                    // send!
                    if ($message->sent()) {
                        $counter++;
                        $this->limit--;
                    }
                } catch (Exception $e) {
                        $this->addMessage(sprintf('Unable to sent Messages #%s: %s', $message->id, $e->getMessage()));
//                    $this->app->log->error('CRON: Unable to sent Messages #{id} for configuration {cid}. Error: {error}', array('id' => $message->id, 'cid' => $message->mass_message_config_id, 'error' => $e->getMessage()));
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        // $this->addMessage(sprintf('Message %s of %s has been sent', $counter, $counterTotal));
    }

}
