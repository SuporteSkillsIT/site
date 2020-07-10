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


namespace Modulesgarden\Crm\Controllers\Api;

use Modulesgarden\Crm\Controllers\Source\AbstractController;

/**
 * Simple enough
 * render main pages that generate rest of angular app
 * depending of type app (standalone or integrated with WHMCS) change basic template file
 */
class Home extends AbstractController
{
    /**
     * Generate main index file for app (whole frontend)
     * @return twig template to render
     */
    public function index()
    {
        // if you want we can assign additional variables
        $variables = array();

        // so this is one and only one place where we define either integrate or not
        $template = ('standalone');

        // $template = 'test';
        return $this->app->render("{$template}.twig", $variables);
    }


    /**
     * test call
     * @return array
     */
    public function test()
    {
        var_dump(
            $this->app->acl
                );
        return $this->returnData(array('status' => 'ok'));
    }


    /**
     * Sample function that show way to generate logs
     * kind of educational to operate in this module
     *
     * for list of log levels see: Modulesgarden\Crm\Integration\Slim\Logger;
     */
    public function generateTestLogs()
    {

        //only the one with higher priority
        //         *
        //         *     EMERGENCY = 1
        //         *     ALERT     = 2
        //         *     CRITICAL  = 3
        //         *     ERROR     = 4
        //         *     WARN      = 5
        //         *     NOTICE    = 6
        //         *     INFO      = 7
        //         *     DEBUG     = 8

        // check log level
        $level = $this->app->log->getLevel();
        // this can be set in modules/addons/Modulesgarden\Crm/app/Config/app.php
        // variable log.level
        // configure minimum level that generate records to file
        // so if level is 4, there will be no debug/info/notice/warn messages in logs

        // generate each message to log file
        // simple enough to add plain message
        $this->app->log->emergency('Emergency message');
        $this->app->log->alert('Alert message');
        $this->app->log->critical('Critical message');
        $this->app->log->error('Error message');
        $this->app->log->warning('Warning message');
        $this->app->log->notice('Notice message');
        $this->app->log->info('Info message');
        $this->app->log->debug('Debug message');

        // more complicated push string with variable
        // also second parameter can be object that have __toString method
        // or it can be Exception object (Exceptions are logged here anyway)
        $this->app->log->debug('Got 2 messages for you: #1 error: {error}, #2 msg: {msg}', array('error' => 'typo in variable name', 'msg' => 'you made it wrong man'));

        // for more information
        // read
        // \Slim\Log
        //
        // $this->app->log contain \Slim\Log object  (just saying)
    }


    /**
     * playground for various cases
     * @return array
     */
    public function debug()
    {

        var_dump($this->app->settings);
        var_dump($this->app->config('storage.path'));
        var_dump($this->app->config('storage.files'));


        die();
        return $this->returnData(array('status' => 'ok'));
    }

}