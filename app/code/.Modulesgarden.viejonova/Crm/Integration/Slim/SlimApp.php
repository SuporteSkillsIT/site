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

// framework itself
use \Slim\Slim;
// exceptions handler that we want to use
use \Modulesgarden\Crm\Middleware\PrettyExceptions as NewPrettyExceptions;
use \Modulesgarden\Crm\Middleware\returnJson;

/**
 * Not significant changes
 * but we need to have ability, catch ours exceptions
 * whatever it is production or development mode, and standard behavior dont let us
 */
class SlimApp extends Slim
{

    public function customRun()
    {
        // register handlers
        set_error_handler(array('\Modulesgarden\Crm\Integration\Slim\SlimApp', 'handleErrors'));
        register_shutdown_function(array('\Modulesgarden\Crm\Integration\Slim\SlimApp', 'handleShutdown'));

        //   global $CRON;
        //Apply final outer middleware layers

        $this->view(new \Modulesgarden\Crm\Integration\Slim\JsonView());
//        $this->add(new ReturnJson());
        /**
         * We changed only this, oryginally it was:
         *     $this->add(new \Slim\Middleware\PrettyExceptions());
         */
        $this->add(new NewPrettyExceptions(array(
            // our pretty exception class will handle behavior acording to that settins
            'debug' => $this->config('debug'),
                //   'cron'  => $CRON,
        )));

        // assign handler to Slim error parser
        $this->error = function($e) {
            NewPrettyExceptions::handleError($e);
        };

        //Invoke middleware and application stack
        $this->middleware[0]->call();

        //Fetch status, header, and body
        list($status, $headers, $body) = $this->response->finalize();
        ob_clean();
//        echo "<pre>";
//        var_dump($this->view);
//        die();
        // Serialize cookies (with optional encryption)
        // \Slim\Http\Util::serializeCookies($headers, $this->response->cookies, $this->settings);
        //Send headers
        if (headers_sent() === false) {
            //Send status
            if (strpos(PHP_SAPI, 'cgi') === 0) {
                header(sprintf('Status: %s', \Slim\Http\Response::getMessageForCode($status)));
            } else {
                header(sprintf('HTTP/%s %s', $this->config('http.version'), \Slim\Http\Response::getMessageForCode($status)));
            }

            //Send headers
            foreach ($headers as $name => $value) {
                $hValues = explode("\n", $value);
                foreach ($hValues as $hVal) {
                    header("$name: $hVal", false);
                }
            }
        }

        //Send body, but only if it isn't a HEAD request
        if (!$this->request->isHead()) {
            echo $body;
        }

        $this->applyHook('slim.after');

        restore_error_handler();
        die();
    }

    /**
     * As we execute something from whmcs api to crm
     * we still want to parse this in our module, so this is done kinda tricky
     *
     * @return array
     */
    public function runInternalApi($route, $params = array())
    {
        // set up this! and dont change plz
        $this->config('isWhmcsAPIcall', true);



        // register handlers
        set_error_handler(array('\Modulesgarden\Crm\Integration\Slim\SlimApp', 'handleErrors'));
        register_shutdown_function(array('\Modulesgarden\Crm\Integration\Slim\SlimApp', 'handleShutdown'));

        // result in plain array
        // either way whmcs will encode that to json, so ;)
        $this->view(new \Modulesgarden\Crm\Integration\Slim\ArrayView());

        // Get route
        $requestedRoute = $this->router->getNamedRoute($route);

        if (is_null($requestedRoute)) {
            throw new \Exception(sprintf('Requested Action: "%s" not recognized!', $route));
        }

        // we are going to override this
        // to be able use parser in exactly the same wayt as from normal requests
        $this->environment = $this->environment->mock(array('slim.input' => json_encode($params)));


        // Set params for route, if not provided, or there are wrong, it have to be damm array,
        // otherwise we wont get response
        if (is_null($params) || !is_array($params)) {
            $params = array();
        }
        $requestedRoute->setParams($params);

        $this->config('isWhmcsAPIcall', true);

        // run requested route
        $requestedRoute->dispatch();

        return $this->view->all();
    }

    /**
     * Run
     *
     * This method invokes the middleware stack, including the core Slim application;
     * the result is an array of HTTP status, header, and body. These three items
     * are returned to the HTTP client.
     */
    public function run()
    {
        // register handlers
        set_error_handler(array('\Modulesgarden\Crm\Integration\Slim\SlimApp', 'handleErrors'));
        register_shutdown_function(array('\Modulesgarden\Crm\Integration\Slim\SlimApp', 'handleShutdown'));

        global $CRON;
        //Apply final outer middleware layers

        /**
         * We changed only this, oryginally it was:
         *     $this->add(new \Slim\Middleware\PrettyExceptions());
         */
        $this->add(new NewPrettyExceptions(array(
            // our pretty exception class will handle behavior acording to that settins
            'debug' => $this->config('debug'),
            'cron' => $CRON,
        )));

        // assign handler to Slim error parser
        $this->error = function($e) {
            NewPrettyExceptions::handleError($e);
        };
        
        //Invoke middleware and application stack
        $this->middleware[0]->call();

        //Fetch status, header, and body
        list($status, $headers, $body) = $this->response->finalize();

        // Serialize cookies (with optional encryption)
        \Slim\Http\Util::serializeCookies($headers, $this->response->cookies, $this->settings);

        //Send headers
        if (headers_sent() === false) {
            //Send status
            if (strpos(PHP_SAPI, 'cgi') === 0) {
                header(sprintf('Status: %s', \Slim\Http\Response::getMessageForCode($status)));
            } else {
                header(sprintf('HTTP/%s %s', $this->config('http.version'), \Slim\Http\Response::getMessageForCode($status)));
            }

            //Send headers
            foreach ($headers as $name => $value) {
                $hValues = explode("\n", $value);
                foreach ($hValues as $hVal) {
                    header("$name: $hVal", false);
                }
            }
        }

        //Send body, but only if it isn't a HEAD request
        if (!$this->request->isHead()) {
            echo $body;
        }

        $this->applyHook('slim.after');
        
        restore_error_handler();
    }

    /**
     * Convert errors into ErrorException objects
     *
     * This method catches PHP errors and converts them into \ErrorException objects;
     * these \ErrorException objects are then thrown and caught by Slim's
     * built-in or custom error handlers.
     *
     * @param  int            $errno   The numeric type of the Error
     * @param  string         $errstr  The error message
     * @param  string         $errfile The absolute path to the affected file
     * @param  int            $errline The line number of the error in the affected file
     * @return bool
     * @throws \ErrorException
     */
    public static function handleErrors($errno, $errstr = '', $errfile = '',
            $errline = '')
    {
        if (!($errno & error_reporting())) {
            return;
        }

        //  throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    }

    /**
     * Convert errors into ErrorException objects
     *
     * This method catches PHP errors and converts them into \ErrorException objects;
     * these \ErrorException objects are then thrown and caught by Slim's
     * built-in or custom error handlers.
     *
     * @param  int            $errno   The numeric type of the Error
     * @param  string         $errstr  The error message
     * @param  string         $errfile The absolute path to the affected file
     * @param  int            $errline The line number of the error in the affected file
     * @return bool
     * @throws \ErrorException
     */
    public static function handleShutdown()
    {
        $errfile = "unknown file";
        $errstr = "shutdown";
        $errno = E_CORE_ERROR;
        $errline = 0;

        $error = error_get_last();

        if ($error !== NULL) {
            $errno = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr = $error["message"];

            if ($errno & E_ERROR) { // fuck that, only core errors here
                try {
                    // just to trigger logginy by system
                    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
                } catch (\ErrorException $e) {
                    self::getInstance()->log->alert($e);
                }


                die(sprintf('%s in %s on line %s ', $errstr, $errfile, $errline));
            }
        }
    }

    /**
     * Call
     *
     * This method finds and iterates all route objects that match the current request URI.
     */
    public function call()
    {
        try {
            if (isset($this->environment['slim.flash'])) {
                $this->view()->setData('flash', $this->environment['slim.flash']);
            }
            $this->applyHook('slim.before');
            ob_start();
            $this->applyHook('slim.before.router');
            $dispatched = false;
            
            // oryginal:
            // $matchedRoutes = $this->router->getMatchedRoutes($this->request->getMethod(), $this->request->getResourceUri());

            /**
             * why ?
             * got case when
             *
             * was
             *       [SCRIPT_NAME] => string(42) "/admin/crm.php/api/notifications/mine/json"
             *       [PATH_INFO] => string(1) "/"
             * instead of
             *       [SCRIPT_NAME] => string(20) "/whmcs/admin/crm.php"
             *       [PATH_INFO] => string(26) "/api/settings/general/json"
             */
            // start
            
            if (strpos($this->request->getPath(), 'key/') !== false) {
                $routeString = substr($this->request->getPath(), strpos($this->request->getPath(), 'key/') + 4, strlen($this->request->getPath()));
            } else {
                $routeString = explode("/", $this->request->getPath());
                unset($routeString[0]);unset($routeString[1]);unset($routeString[2]);unset($routeString[3]);
                $routeString = implode("/", $routeString);
            }
            $routeString = substr($routeString, strpos($routeString, '/'));
            if (ends_with($routeString, '/')) {
                $routeString = substr($routeString, 0, -1);
            }
            
            // special occasion route mach
            $matchedRoutes = $this->router->getMatchedRoutes($this->request->getMethod(), $routeString);
            // end

            foreach ($matchedRoutes as $route) {
                try {
                    $this->applyHook('slim.before.dispatch');
                    $dispatched = $route->dispatch();
                    $this->applyHook('slim.after.dispatch');
                    if ($dispatched) {
                        break;
                    }
                } catch (\Slim\Exception\Pass $e) {
                    continue;
                }
            }
            if (!$dispatched) {
                $this->notFound();
            }
            $this->applyHook('slim.after.router');
            $this->stop();
        } catch (\Slim\Exception\Stop $e) {
            $this->response()->write(ob_get_clean());
        } catch (\Exception $e) {
            if ($this->config('debug')) {
                throw $e;
            } else {
                try {
                    $this->response()->write(ob_get_clean());
                    $this->error($e);
                } catch (\Slim\Exception\Stop $e) {
                    // Do nothing
                }
            }
        }
    }

}
