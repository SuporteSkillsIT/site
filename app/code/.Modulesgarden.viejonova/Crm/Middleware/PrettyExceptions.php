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

namespace Modulesgarden\Crm\Middleware;

use \Modulesgarden\Crm\Integration\Slim\SlimApp;
use \Exception;
use \ErrorException;

/**
 * Pretty Exceptions
 *
 * This middleware catches any Exception thrown by the surrounded
 * application and displays a developer-friendly diagnostic screen.
 *
 * Improved standard Slim PrettyExceptions by our modifications for our needs
 *
 * @author      Josh Lockhart
 * @modified    Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 */
class PrettyExceptions extends \Slim\Middleware
{

    /**
     * @var array
     */
    protected $settings;

    /**
     * Constructor
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $this->settings = $settings;
    }

    /**
     * Call for middleware callstac
     */
    public function call()
    {
        try {
            // basically do not check ACL while it is CRON request
            if (array_get($this->settings, 'cron', false) === false) {
                // basic ACL checks
                if (/*$this->app->acl->isAdminLoggedIn()*/ true === false) {
                    throw new Exception('Log in to system first', 1);
                }
                if (/*$this->app->acl->isAdminHassAccessToModule()*/ true === false) {
                    throw new Exception('Access Denied. Access has not been given for your admin role group to access this addon module. You can grant access in Setup > Addon Modules', 2);
                }
            }

            // $this->app->getLog()->warning('PrettyExceptions before call');
            $this->next->call();

            // $this->app->getLog()->warning('PrettyExceptions after call');
        } catch (Exception $e) {
            self::handleError($e);
        }
    }

    /**
     * Allow to handle errors when sporred
     *
     * @param type $e
     */
    public static function handleError($e)
    {
        $app = SlimApp::getInstance();

        // log to file
        if ($app->config('debug') || $app->config('log.enabled')) {
            $app->log->error($e);
        }

        $requestedResponse = explode('/', $app->request->getPath());
        $requestedResponse = strtolower(last($requestedResponse));

        // json
        if ($requestedResponse == 'json' || $app->request->isAjax()) {
            $return = array(
                'status' => 'error',
                'msg' => $e->getMessage(),
            );

            if ($app->config('debug')) {
                $return['_debug'] = array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                );
            }

            $app->response->header('Content-Type', 'application/json');
            $app->response()->status(500);
            $app->response()->body(json_encode($return));
        } else {
            $app->contentType('text/html');
            $app->response()->status(500);
            $app->response()->body(self::renderExceptionBody($app->config('debug'), $e));
        }
    }

    /**
     * Nice error renderring
     *
     * @param type $debug
     * @param type $exception
     * @return type
     */
    public static function renderExceptionBody($debug, $exception)
    {
        $html = sprintf('<h1>Error</h1>', $title);
        $html .= sprintf('<h2>%s</h2>', $exception->getMessage());

        if ($debug) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $trace = str_replace(array('#', "\n"), array('<div>#', '</div>'), $exception->getTraceAsString());

            if ($code) {
                $html .= sprintf('<div><strong>Code:</strong> %s</div>', $code);
            }
            if ($message) {
                $html .= sprintf('<div><strong>Message:</strong> %s</div>', $message);
            }
            if ($file) {
                $html .= sprintf('<div><strong>File:</strong> %s</div>', $file);
            }
            if ($line) {
                $html .= sprintf('<div><strong>Line:</strong> %s</div>', $line);
            }
            if ($trace) {
                $html .= '<h2>Trace</h2>';
                $html .= sprintf('<pre>%s</pre>', $trace);
            }
        }

        return sprintf("<html><head><title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{display:inline-block;width:65px;}</style></head><body>%s</body></html>", $title, $html);
    }

}
