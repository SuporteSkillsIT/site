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

namespace Modulesgarden\Crm\Services;

use \Modulesgarden\Crm\Integration\Slim\SlimApp;
use \Illuminate\Database\Capsule\Manager as DB;
use \Modulesgarden\Crm\Repositories\PermissionRoles;
use \Exception;
use \Carbon\Carbon;

/**
 * Monitor Class
 * based on singletron
 * used to obtain various data regardless on integration with other modules
 *
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> < >
 */
class Monitor
{

    /**
     * Define from what version SMS center is compatible with CRM
     * @var string version in compatible format for version_compare function
     */
    const COMPATIBLE_SMS_CENTER = '1.3.0';

    /**
     * Define from what version Asterisl Voip Center is compatible with CRM
     * @var string version in compatible format for version_compare function
     */
    const COMPATIBLE_ASTERISK = '1.0.0';

    /**
     * base possibility
     *
     * @var array
     */
    private static $config = array(
        'integrations' => array(
            'asterisk' => null,
            'sms_center' => null,
            'quotes_automation' => null,
        ),
        'cron' => array(
            'last_run' => null,
            'interval' => null,
            'path' => null,
            'url' => null,
        ),
        'emails' => array(
            'count' => null,
        ),
    );

    /**
     * Keep single instance of translation object
     * We do not want to create many many translators object's
     *
     * @var Lang instance
     */
    private static $instance;

    /**
     * Constuct
     */
    private function __construct()
    {
        self::$instance = $this;
    }

    /**
     * Disable clones
     */
    private function __clone()
    {
        
    }

    /**
     * Keep Singletron pattern
     *
     * @return Lang object
     */
    public static function getInstance()
    {
        // singletron!
        if (empty(self::$instance)) {
            $class = get_called_class();
            self::$instance = new $class();
        }
        return self::$instance;
    }

    /**
     * Return bolean if admin is logged in
     *
     */
    public function checkIntegrations()
    {
        foreach (self::$config['integrations'] as $what => $is) {
            self::checkIntegration($what);
        }
        return self::$config['integrations'];
    }

    /**
     * Return bolean if admin is logged in
     *
     */
    public function checkIntegration($what)
    {
        if (!array_key_exists($what, self::$config['integrations'])) {
            return false;
        }

        return array_get(self::$config, "integrations.{$what}", false);
    }

    /**
     * how many email templates are assigned to module
     *
     * @return int
     */
    public function getEmailTemplatesNum()
    {
        if (array_get(self::$config, 'emails.count', null) !== null) {
            return 0;
        }

        $count = DB::table('email_template')->where('template_code', 'LIKE', 'crm_%')->count();

        array_set(self::$config, 'emails.count', $count);

        return $count;
    }

    /**
     * Return cron statictics
     *
     * interval is in number of minutes!
     * @return type
     */
    public function checkCronStatus()
    {
        $previousRun = DB::table('cron_schedule')->where('job_code', '=', 'modulesgarden_crm_handle_reminders')
                        ->orWhere('job_code', '=', 'modulesgarden_crm_handle_mass_messages')->select('executed_at')
                ->orderBy('executed_at', 'DESC')->take(2)->first();
        $lastRun = DB::table('cron_schedule')->where('job_code', '=', 'modulesgarden_crm_handle_reminders')
                        ->orWhere('job_code', '=', 'modulesgarden_crm_handle_mass_messages')->select('executed_at')
                        ->orderBy('executed_at', 'DESC')->take(1)->first();

        $lastRunTimestamp = strtotime(array_get($lastRun, 'executed_at', false));
        $previousRunTimestamp = strtotime(array_get($previousRun, 'executed_at', false));

        $last = false;
        $dateDiff = false;

        if ($lastRunTimestamp) {
            $last = Carbon::createFromTimestamp($lastRunTimestamp);
            $now = Carbon::now();
            array_set(self::$config, 'cron.last_run', $last->toDateTimeString());
            array_set(self::$config, 'cron.interval', $now->diffInMinutes($last));
        } else {
            array_set(self::$config, 'cron.interval', false);
        }

//        array_set(self::$config, 'cron.path', SlimApp::getInstance()->config('appInternalModuleDir') . 'cron.php');
//        array_set(self::$config, 'cron.url', SlimApp::getInstance()->whmcs->getSystemUrl() . 'modules/addons/Modulesgarden\Crm/cron.php');

        return self::$config['cron'];
    }

}
