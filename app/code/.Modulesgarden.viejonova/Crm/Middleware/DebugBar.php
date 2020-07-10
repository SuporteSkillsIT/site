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

use \Modulesgarden\Crm\Integration\DebugBar\DataCollector\DataCollectorInterface;
use \Modulesgarden\Crm\Integration\DebugBar\OpenHandler;
use \Modulesgarden\Crm\Integration\DebugBar\SlimDebugBar;
use \Modulesgarden\Crm\Integration\DebugBar\SlimHttpDriver;
use \Modulesgarden\Crm\Integration\DebugBar\Storage\FileStorage;
use \Modulesgarden\Crm\Integration\DebugBar\Storage\StorageInterface;
use \Slim\Middleware;

class DebugBar extends Middleware
{

    /**
     * Slim Application instance
     *
     * @var \Slim\Slim
     */
    protected $app;

    /**
     * Debugbar instance
     *
     * @var \DebugBar\SlimDebugBar
     */
    protected $debugbar;

    /**
     * keep reference to instance of this class
     *
     * @var Modulesgarden\Crm\Middleware\DebugBar
     */
    private static $instance = false;

    /**
     * Debugbar instance
     *
     * @static
     */
    public static function getInstance()
    {
        if (!is_object(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    /**
     * @var HttpDriverInterface
     */
    protected $httpDriver;

    public function __construct($HttpDriver = null)
    {
        $this->httpDriver = $HttpDriver;
        // $this->debugbar = new SlimDebugBar();
        $this->debugbar = SlimDebugBar::getInstance();
    }

    /**
     * @param DataCollectorInterface $collector
     * @throws \DebugBar\DebugBarException
     */
    public function addCollector(DataCollectorInterface $collector)
    {
        $this->debugbar->addCollector($collector);
    }

    /**
     * @return SlimDebugBar
     */
    public function getDebugBar()
    {
        return $this->debugbar;
    }

    /**
     * @return Slim
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param \DebugBar\DebugBar $debugbar
     */
    public function setDebugBar(\DebugBar\DebugBar $debugbar)
    {
        $this->debugbar = $debugbar;
    }

    public function call()
    {
        $this->prepareDebugBar();

        $httpDriver = $this->httpDriver ? : new SlimHttpDriver($this->app);
        $this->debugbar->setHttpDriver($httpDriver);

        $this->setAssetsRoute();

        $this->next->call();

        if ($this->isAssetsRoute()) {
            return;
        }

        if ($this->app->request->isAjax()) {
            if ($this->debugbar->getStorage()) {
                $this->debugbar->sendDataInHeaders($useOpenHandler = true);
            }
            return;
        }

        if (!$this->isModifiable()) {
            return;
        }

        if ($this->app->config('turnOffDebugBar')) {
            return;
        }

        $html = $this->app->response->body();
        $this->app->response->body($this->modifyResponse($html));
    }

    public function isModifiable()
    {
        if ($this->app->response->isRedirect()) {
            if ($this->debugbar->getHttpDriver()->isSessionStarted()) {
                $this->debugbar->stackData();
            }
            return false;
        }

        if (!$this->isHtmlResponse()) {
            return false;
        }

        return true;
    }

    public function isHtmlResponse()
    {
        $content_type = $this->app->response->header('Content-Type');

        return (stripos($content_type, 'html') !== false);
    }

    /**
     * @param string $html
     * @return string
     */
    public function modifyResponse($html)
    {
        $debug_html = $this->getDebugHtml();
        $pos = mb_strripos($html, '</body>');
        if ($pos === false) {
            $html .= $debug_html;
        } else {
            $html = mb_substr($html, 0, $pos) . $debug_html . mb_substr($html, $pos);
        }

        return $html;
    }

    public function getDebugHtml()
    {
        $renderer = $this->debugbar->getJavascriptRenderer();
        if ($this->debugbar->getStorage()) {
            $renderer->setOpenHandlerUrl($this->app->router->urlFor('debugbar.openhandler'));
        }

        $html = $this->getAssetsHtml();
        if ($renderer->isJqueryNoConflictEnabled()) {
            $html .= "\n" . '<script type="text/javascript">jQuery.noConflict(true);</script>';
        }

        return $html . "\n" . $renderer->render();
    }

    public function getAssetsHtml()
    {
        // $root = $this->app->request()->getScriptName();
        // return '<script type="text/javascript" src="' . $root . '/_debugbar/resources/dump.js"></script>' .
        //     '<link rel="stylesheet" type="text/css" href="' . $root . '/_debugbar/resources/dump.css">';

        return '<script type="text/javascript" src="' . $_SERVER['SCRIPT_NAME'] . '/_debugbar/resources/dump.js"></script>' .
                '<link rel="stylesheet" type="text/css" href="' . $_SERVER['SCRIPT_NAME'] . '/_debugbar/resources/dump.css">';
    }

    protected function prepareDebugBar()
    {
        if ($this->debugbar instanceof SlimDebugBar) {
            $this->debugbar->initCollectors($this->app);
        }
        $storage = $this->app->config('debugbar.storage');
        if ($storage instanceof StorageInterface) {
            $this->debugbar->setStorage($storage);
        }
        // add debugbar to Slim IoC container
        $this->app->container->set('debugbar', $this->debugbar);
    }

    protected function setAssetsRoute()
    {
        $renderer = $this->debugbar->getJavascriptRenderer();
        $this->app->get('/_debugbar/fonts/:file', function($file) use ($renderer) {
            $thisInstance = \Modulesgarden\Crm\Integration\Slim\SlimApp::getInstance();

            // e.g. $file = fontawesome-webfont.woff?v=4.0.3
            $files = explode('?', $file);
            $file = reset($files);
            $path = $renderer->getBasePath() . '/vendor/font-awesome/fonts/' . $file;
            if (file_exists($path)) {
                $thisInstance->response->header('Content-Type', mime_content_type($path));
                echo file_get_contents($path);
            } else {
                // font-awesome.css referencing fontawesome-webfont.woff2 but not include in the php-debugbar.
                // It is not slim-debugbar bug.
                $thisInstance->notFound();
            }
        })->name('debugbar.fonts');
        $this->app->get('/_debugbar/resources/:file', function($file) use ($renderer) {
            $thisInstance = \Modulesgarden\Crm\Integration\Slim\SlimApp::getInstance();

            $files = explode('.', $file);
            $ext = end($files);
            if ($ext === 'css') {
                $thisInstance->response->header('Content-Type', 'text/css');
                $renderer->dumpCssAssets();
            } elseif ($ext === 'js') {
                $thisInstance->response->header('Content-Type', 'text/javascript');
                $renderer->dumpJsAssets();
            }
        })->name('debugbar.resources');
        $this->app->get('/_debugbar/openhandler', function() {
            $thisDebugBarInstance = \Modulesgarden\Crm\Middleware\DebugBar::getInstance();
            $thisInstance = \Modulesgarden\Crm\Integration\Slim\SlimApp::getInstance();

            $openHandler = new OpenHandler($thisDebugBarInstance->getDebugBar());
            $data = $openHandler->handle($request = null, $echo = false, $sendHeader = false);

            $thisInstance->response->header('Content-Type', 'application/json');
            $thisInstance->response->setBody($data);
        })->name('debugbar.openhandler');
    }

    protected function isAssetsRoute()
    {
        $route = $this->app->router->getCurrentRoute();

        if ($route) {
            $name = $route->getName();
            $return = explode('.', $name);
            return ($return[0] === 'debugbar');
        }

        return false;
    }

}
