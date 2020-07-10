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

namespace Modulesgarden\Crm\Integration\Slim;

/**
 * Just wrapper, maybe one day we are override default logger functionality
 *
 * List of levels with names
 *
 *    EMERGENCY = 1;
 *    ALERT     = 2;
 *    CRITICAL  = 3;
 *    ERROR     = 4;
 *    WARN      = 5;
 *    NOTICE    = 6;
 *    INFO      = 7;
 *    DEBUG     = 8;
 *
 */
class Logger extends \Slim\Log
{

    public static function getLevelType($level)
    {
        if (isset(self::$levels[$level])) {
            return self::$levels[$level];
        } else {
            return 'Log Level ' . $level;
        }
    }

}
