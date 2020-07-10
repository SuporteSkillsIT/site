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

class Language
{

    /**
     * Detected language based by logged in admin
     *
     * @var string
     */
    protected static $lang = null;

    /**
     * Default language in case that wont be able to determinate which one use
     *
     * @var string
     */
    protected static $defaultLang = 'en_US';

    /**
     * Keep array of all module translations
     *
     * @var array
     */
    protected static $translations = array();

    /**
     * Keep single instance of translation object
     * We do not want to create many many translators object's
     *
     * @var Lang instance
     */
    protected static $instance;

    /**
     * Constuct and initialize paths/current user lang etc
     */
    public function __construct()
    {
        // load file with translations and store to this object
        $this->loadLanguageTranslations();
        self::$instance = $this;
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
     * Load file with translations
     *
     * @return bolean
     * @throw \Exception
     */
    private function loadLanguageTranslations()
    {
        if (!empty(self::$translations)) {
            return true;
        }

        // get APP instance
        $app = SlimApp::getInstance();
        self::$lang = $app->container->get('locale');
        // get directory for translations
        $langDir = $app->config('appInternalModuleDir') . "lang/";
        //    echo $langDir;exit;
        // include lang
        if (file_exists($langDir . self::$lang . '.php')) {
            require_once $langDir . self::$lang . '.php';
        } elseif (file_exists($langDir . self::$defaultLang . '.php')) {
            require_once $langDir . self::$defaultLang . '.php';
        } else {
            throw new \Exception("Invalid Module Language");
        }

        // Put translations to object :D:D
        if (isset($_LANG) && is_array($_LANG)) {
            self::$translations = $_LANG;
        }

        return true;
    }

    /**
     * Get all translations loaded from lang file
     *
     * @return array
     */
    public function getTranslations()
    {
        return self::$translations;
    }

    /**
     * Obtain detected Language
     *
     * @return string
     */
    public function getLang()
    {
        return self::$lang;
    }

    /**
     * Perform Translation by key
     *
     * @param  string $key key that needs to be translated
     * @return array      possible variables to append in translation
     */
    public function _($key)
    {
        $replace = array();


        if (func_num_args() > 1) {
            for ($i = 1; $i < func_num_args(); $i++) {
                $tmp = func_get_arg($i);
                if (is_array($tmp)) {
                    foreach ($tmp as $k => $v) {
                        $replace[':' . $k] = $v;
                    }
                } else {
                    $replace[':a' . $i] = func_get_arg($i);
                }
            }
        }

        $return = array_get(self::$translations, $key, $key);

        if (!empty($replace)) {
            $return = $this->strReplaceAssoc($replace, $return);
        }

        // =====================
        //  Generate LANG
        // =====================
        //
        // old code, should be rewrited for slim, but i dont think ill use lang generation, f*ck that
        //
        // if (self::$generateLang === true)
        // {
        //     if (!isset($_LANG[$key]) && File::exists( self::$langFile ) && File::isWritablePath( self::$langFile ) )
        //     {
        //         // append new line for lang :D
        //         File::append(self::$langFile, "\r\n".'$_LANG["'.$key.'"] = "'.$key.'";', false);
        //         // we do not need to update this->lang
        //         // since either way translation will be the same as just generated
        //         // no point of loading/reloading everything each every not found translation
        //         // include self::$langFile;
        //         // self::$lang = $_LANG;
        //     }
        // }
        // =====================
        //  Generate LANG END
        // =====================

        return $return;
    }

    /**
     * Access to '_' metgod as alias via 't'
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function t($key)
    {
        return call_user_func_array(array($this, '_'), func_get_args());
    }

    /**
     * Access to '_' metgod from global scope as static method
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public static function translate($key)
    {
        return call_user_func_array(array(self::getInstance(), '_'), func_get_args());
    }

    /**
     * Repleace mapped strings by array keys
     *
     * @param  array  $replace what to repleade (as array with keys to search)
     * @param  string $subject text that need to be searched for occurence to relpleace
     * @return string
     */
    private function strReplaceAssoc(array $replace, $subject)
    {
        return str_replace(array_keys($replace), array_values($replace), $subject);
    }

}
