<?php

namespace Modulesgarden\Crm\Services;

require_once dirname(__DIR__) . '/database/Connection.php';

use Modulesgarden\Crm\Integration\Slim\SlimApp;
use \Modulesgarden\Crm\database\Connection;
use \Modulesgarden\Crm\Middleware\TemplateVariables;
use \Modulesgarden\Crm\Middleware\ParseResponse;
use Modulesgarden\Crm\Models\Magento\Admin;

class SlimIntegration
{

    private $app;
    private $db;

    public function __construct($isApi, $lang = 'en_US', $adminId = 0,
            $urls = array())
    {
        $admin = $adminId;
        $configLoader = new \Illuminate\Config\FileLoader(new \Illuminate\Filesystem\Filesystem, dirname(__DIR__) . '/Config');
        //var_dump($configLoader->getFilesystem()->exists(dirname(__DIR__) . '/Config'));exit;
        $appConfig = new \Illuminate\Config\Repository($configLoader, 'development'); // unfortunatelly second parameter needs to be implemented, wont be used anyway
        $slimConfig = array(
            'debug' => false,
            'mode' => 'development',
            'appFilename' => 'crm',
            //   'appAdminDir' => substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], $appFilename)),
            // 'appAdminDirName' => basename(substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], $appFilename))),
            // 'appAdminUrl' => substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], $appFilename)) . $appFilename,
            'appDir' => dirname(__DIR__),
            'magentoDir' => dirname(dirname(dirname(dirname(dirname(__DIR__))))),
            'storage.path' => dirname(__DIR__) . '/Storage',
            'storage.files' => 'files', // in this directory nami at storage path there will be uploaded files for lead/etc
            'appInternalFile' => __FILE__,
            'appInternalAppDir' => dirname(__DIR__) . '/',
            'appInternalModuleDir' => dirname(dirname(__FILE__)) . '/',
            // logger
            'log.level' => \Slim\Log::ERROR,
            'log.enabled' => true,
            //  'log.writer' => $logWriter,
            // frontend settings
            'skipMagento' => false,
            'view' => new \Slim\Views\Twig(),
            'templates.path' => dirname(__DIR__) . '/view/adminhtml/web/',
            'templates.theme' => '/',
            'templates.renderStandalone' => false,
            // Versioning
//            'moduleVersion' => (isset($moduleVersion) ? $moduleVersion : '1.0.0'),
//            'moduleRevision' => (isset($moduleRevision) ? $moduleRevision : 'master'),
//            'moduleWikiUrl' => (isset($moduleWikiUrl) ? $moduleWikiUrl : 'http://www.docs.modulesgarden.com/CRM_For_WHMCS'),
            'viewFileUrl' => isset($urls['viewUrl']) ? $urls['viewUrl'] : null,
            'apiUrl' => isset($urls['apiUrl']) ? $urls['apiUrl'] . 'api' : null,
            'customerCreateUrl' => isset($urls['createCustomerUrl']) ? $urls['createCustomerUrl'] : null,
            'createOrderUrl' => isset($urls['createOrder']) ? $urls['createOrder'] . 'customer_id/' : null,
            'viewOrderUrl' => isset($urls['viewOrder']) ? $urls['viewOrder'] . 'order_id/' : null,
            'viewCustomerUrl' => isset($urls['viewCustomer']) ? $urls['viewCustomer'] . 'id/' : null,
            'invoiceUrl' => isset($urls['invoiceUrl']) ? $urls['invoiceUrl'] . 'invoice_id/' : null,
        );
//        echo '<pre>';
//        var_dump($slimConfig); exit;
        $this->app = new SlimApp($slimConfig);
        $this->app->view->parserOptions = array(
            // we are using cache from internals
            'cache' => dirname(__DIR__) . 'Storage/Cache',
        );
        $this->app->view->getEnvironment()->enableAutoReload();
        
        $this->app->view->parserExtensions = array(
            new \Slim\Views\TwigExtension(),
        );
        $this->app->view->getInstance()->addExtension(new \Twig_Extension_StringLoader());
        
// add function to debug things for twig
        $this->app->view->getInstance()->addFunction(new \Twig_SimpleFunction("debug", function($key, $type = 'print_r') {
            switch ($type) {
                case 'print_r': print_r($key);
                    break;
                case 'var_dump': var_dump($key);
                    break;
                case 'r': if (function_exists('\r'))
                        \r($key);
                    else
                        print_r($key);
                    break;
                default: print_r($key);
                    break;
            }
        }));
        $this->app->container->set('configFile', function() use ($appConfig) {
            return $appConfig;
        });
        $this->db = new Connection;
        $this->app->container->set('db', $this->db);
        $this->app->add(new TemplateVariables);
        $this->app->add(new ParseResponse);
        $this->app->container->set('locale', $lang);
//$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
        //$logger->info(print_r(\Modulesgarden\Crm\Services\ACL::getInstance(), true));
        $this->app->container->singleton('lang', function() {
            return new \Modulesgarden\Crm\Services\Language();
        });
        $this->app->container->singleton('currentAdmin', function() use ($admin) {
            return Admin::where('user_id', '=', $admin)->first(array('user_id', 'username', 'firstname', 'lastname', 'email'));
        });
        $this->app->container->set('acl', \Modulesgarden\Crm\Services\ACL::getInstance());
        
        require dirname(__DIR__) . '/routes.php';
        if ($isApi) {
            $this->app->customRun();
        } else {
            $this->app->run();
        }
    }

    public function getApp()
    {
        return $this->app;
    }

}
