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
    

return array(
    /**
     * turn on debug mode
     */
    'debug'    => false,
    'debugbar' => false,
    'mode'     => 'production',

    /**
     * Various configuration option related with frontend settings
     */
    'templates' => array
    (
        'template'       => 'ModulesGarden',
        'assetsDir'      => 'assets',
        /**
         * using this setting, we can override option configured in module and force this setting
         */
        // 'renderStandalone' => false,


        /**
         * Settings for JS
         * This will be included to ANGULAR/CRM UI settings
         */
        'scroolModule'          => false,    // if true, scrool to top will scrool to module header bar (so skip WHMCS one)
        'pageAutoScrollOnLoad'  => false,    // auto scroll to top on page load set false to disable. Works also for change url not only initial initialization
    ),

    /**
     * Section responsible for internal aplication logs system
     */
    'log' => array
    (
        /**
         * Enable or disable logging to file
         * path: app/Storage/logs
         */
        'enabled' => true,
        /**
         * Logging levels
         * You can setup here minimal priority of logs,
         * where logged will be only the one with higher priority
         *
         *     EMERGENCY = 1
         *     ALERT     = 2
         *     CRITICAL  = 3
         *     ERROR     = 4
         *     WARN      = 5
         *     NOTICE    = 6
         *     INFO      = 7
         *     DEBUG     = 8
         *
         * default level: ERROR
         */
        'level' => 8,
    ),

    /**
     * Section responsible for internal aplication logs system
     */
    'cron' => array
    (
        /**
         * Limitation for cton to send MAX this number or messages per iteration
         * usefull for low performance servers/mail/sms providers
         * This is particulary usefull for Mass Messages, when signitifical amount of messages we need to send
         * default 100
         */
        'messagesLimit' => 100,
    )
);