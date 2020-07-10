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
use \Modulesgarden\Crm\Repositories\PermissionRoles;
use \Illuminate\Database\Capsule\Manager as DB;
use \Exception;

/**
 * Access Controll List
 *
 * Kind of tricky class, works in conjuction with WHMCS
 * Based on app configuration permisions. Each WHMCS admin have certain role (whmcs one).
 * And there are permissions granted to this role, and configured directly in WHMCS.
 *
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> < >
 */
class ACL
{

    /**
     * Detected language based by logged in admin
     *
     * @var string
     */
    private static $config = null;

    /**
     * Default language in case that wont be able to determinate which one use
     *
     * @var string
     */
    private static $rules = array();

    /**
     * reference to current admin model
     *
     * @var Model\Magento\Admin
     */
    private static $admin = null;

    /**
     * Array assugned role
     *
     * @var Modulesgarden\Crm\Models\PermissionRole
     */
    private static $permissionRole = null;

    /**
     * container, keep full access admin roles
     *
     * @var array
     */
    private static $fullAccessRoles = null;

    /**
     * container, keep admin roles that are assigned by whmcs to use module
     *
     * @var array
     */
    private static $assignedAccessRoles = null;

    /**
     * determinate if admin is full accessed everywhere
     *
     * @var bolean
     */
    private static $isRoot = false;

    /**
     * Most important here
     * Keep parsed rules for current admin
     * contain All available rules with flag true/false
     * 
     * @var array
     */
    private static $currentAdminRules = array();

    /**
     * Keep single instance of translation object
     * We do not want to create many many translators object's
     *
     * @var Lang instance
     */
    private static $instance;

    /**
     * Constuct and initialize paths/current user lang etc
     */
    public function __construct()
    {
        // load file with translations and store to this object
        $this->loadPermissionsConfig();
        $this->parseConfigRules();
        $this->assignAdmin();
        self::$instance = $this;
    }

    /**
     * Disable clones
     */
    private function __clone()
    {
        
    }

    /**
     * Load file with translations
     *
     * @return bolean
     * @throw \Exception
     */
    private function loadPermissionsConfig()
    {
        if (!empty(self::$config)) {
            return true;
        }

        self::$config = SlimApp::getInstance()->configFile->get('permissions');

        if (empty(self::$config)) {
            Throw new Exception('Unable to read permissions config');
        }

        return true;
    }

    /**
     * Load file with translations
     *
     * @return bolean
     * @throw \Exception
     */
    private function assignAdmin()
    {
        if (!empty(self::$admin)) {
            return true;
        }

        // plain assign to this instance currently logged admin
        self::$admin = SlimApp::getInstance()->currentAdmin;
        // check if maybe this admin hase full access
        $this->checkForFullAccess();
        // parse that admin permissions by assigned role in CRM
        $this->getPermissionRoleForAdmin();

        return true;
    }

    /**
     * Load file with translations
     *
     * @return bolean
     * @throw \Exception
     */
    private function getPermissionRoleForAdmin()
    {
        if (!empty(self::$permissionRole)) {
            return self::$permissionRole;
        }

        // determinate role assignment from CRM module
        self::$permissionRole = PermissionRoles::getForAdminRole($this->getAdminRole());

        // recheck and mark permisions
        $this->storeCurrentAdminRules(self::$permissionRole);

        return true;
    }

    /**
     * generate array wyth possible roules with all other possible
     * and store it
     *
     * @param type $allowed
     * @return array
     */
    private function storeCurrentAdminRules($permissionObj)
    {
        if ($permissionObj instanceof \Modulesgarden\Crm\Models\PermissionRole) {
            $allowed = $permissionObj->allowed;
        } else {
            $allowed = $permissionObj;
        }

        // get all
        $all = $this->getRules();

        // make it flat
        $flattenAll = array_flat($all);
        $flattenAllowed = array_flat($allowed);

        if ($this->isFullAdmin()) {
            $defaultSet = true;
        } else {
            $defaultSet = false;
        }

        foreach ($flattenAll as $ruteKey => $ruteVal) {
            array_set($all, $ruteKey, array_get($flattenAllowed, $ruteKey, $defaultSet));
        }

        // assign
        self::$currentAdminRules = $all;

        return self::$currentAdminRules;
    }

    /**
     * Check if currently logged admin have full access
     *
     * @return bolean
     */
    private function checkForFullAccess()
    {
        if (in_array($this->getAdminRole(), $this->getFullAccessRoles())) {
            self::$isRoot = true;
        }

        return self::$isRoot;
    }

    /**
     * Load file with translations
     *
     * @return bolean
     * @throw \Exception
     */
    private function parseConfigRules()
    {
        self::$rules = $this->parseConfigRulesRecursivley(self::$config);
    }

    /**
     * Recursivley parse rules from config
     * return flatten roules array to use
     *
     * @param array $array
     * @return mixed
     */
    private function parseConfigRulesRecursivley(array $array = array())
    {
        $tmp = array();

        foreach ($array as $group) {
            if (empty($group['rule']) || empty($group['name'])) {
                continue;
            }

//            $v = true;
            $v = $group['name'];

            if (isset($group['children']) && is_array($group['children'])) {
                $a = $this->parseConfigRulesRecursivley($group['children']);

                if (is_array($a)) {
                    $v = $a;
                }
            }

            $tmp[$group['rule']] = $v;
        }

        return empty($tmp) ? false : $tmp;
    }

    /**
     * Keep Singletron pattern
     *
     * @return Lang object
     */
    public static function getInstance()
    {
        // singletron!
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Return bolean if admin is logged in
     *
     * @return bolean
     */
    public function isAdminLoggedIn()
    {
        return true; //(isset(self::$admin));
    }

    /**
     * Return bolean if admin is configuret aw WHMCS that he can use module
     *
     * @return bolean
     */
    public function isAdminHassAccessToModule()
    {
        return true; //( in_array($this->getAdminRole(), $this->getAssignedAccessRoles()) );
    }

    /**
     * Whenever current admin have full access
     *
     * @return boolean
     */
    public function isFullAdmin()
    {
        if (self::$isRoot === true) {
            return true;
        }
    }

    /**
     * Obtain from admin assigned to ACL
     * role id, its WHMCS ID
     *
     * @return int
     */
    public function getAdminRole()
    {
//        if (is_object(self::$admin)) {
            $adminRole = DB::table('authorization_role')
                    ->select('role_id', 'parent_id')
                    ->where('user_id', '=', self::$admin->user_id)
                    ->first();
            if (intval($adminRole['parent_id']) != 0) {
                return intval($adminRole['parent_id']);
            }
            return intval($adminRole['role_id']);
//        } else {
//            $this->assignAdmin();
//            $this->getAdminRole();
//        }
    }

    /**
     * get Full Access Admin roles
     *
     * @return array
     * @throw \Exception
     */
    public function getFullAccessRoles()
    {
        if (isset(self::$fullAccessRoles)) {
            return self::$fullAccessRoles;
        }

        $full = DB::table('authorization_rule')->select('role_id')->where('resource_id', '=', 'Magento_Backend::all')->where('permission', '=', 'allow')->get();
        if (empty($full[0])) {
            self::$fullAccessRoles = array();
        } else {
            foreach ($full as $role) {
                self::$fullAccessRoles[] = $role['role_id'];
            }
        }

        return self::$fullAccessRoles;
    }

    /**
     * get Assigned to module Admin roles
     *
     * @return array
     * @throw \Exception
     */
    public function getAssignedAccessRoles()
    {
        if (isset(self::$assignedAccessRoles)) {
            return self::$assignedAccessRoles;
        }

        $allowed = DB::table('authorization_rule')->select('role_id')->where('resource_id', '=', 'Modulesgarden_Crm::index')->where('permission', '=', 'allow')->get();

        if (empty($allowed[0])) {
            self::$assignedAccessRoles = array();
        } else {
            foreach ($allowed as $role) {
                self::$assignedAccessRoles[] = $role['role_id'];
            }
        }

        return self::$assignedAccessRoles;
    }

    /**
     * Check if currently logged admin have certain permission
     *
     * @param type $rule
     * @return boolean
     */
    public function hasAccess($rule)
    {
        if (self::$admin->isDisabled()) {
            return false;
        }

        if ($this->isFullAdmin() === true) {
            return true;
        }

        return array_get($this->getCurrentAdminRules(), $rule, false);
    }

    /**
     * Obtain rule name for use
     *
     * @param type $rule
     * @return string
     */
    public function getRuleName($rule)
    {
        if (self::$admin->isDisabled()) {
            return false;
        }

        if ($this->isFullAdmin() === true) {
            return true;
        }

        return array_get($this->getRules(), $rule, 'Name has not been set');
    }

    /**
     * get all allowed roles for logged admin
     *
     * @return array
     */
    public function getCurrentAdminRules()
    {
        return self::$currentAdminRules;
    }

    /**
     * wrapper for getCurrentAdminRules
     * return single dimention array with rules (flatten array)
     *
     * key is roule, value is bolean, if admin have or not access
     *
     * @return array
     */
    public function getCurrentAdminRulesFlat()
    {
        return array_flat(self::$currentAdminRules);
    }

    /**
     * wrapper for getCurrentAdminRules
     * return single dimention array with rules (flatten array)
     *
     * return only allowed rules by keys (not all of them)
     *
     * @return array
     */
    public function getCurrentAdminRulesFlatRules()
    {
        $rules = array();
        foreach (array_flat(self::$currentAdminRules) as $rule => $isAllowed) {
            if ($isAllowed === true) {
                $rules[] = $rule;
            }
        }


        return $rules;
    }

    /**
     * Get parsed rules in friendly format for backend
     *
     * @return array
     */
    public function getRules()
    {
        return self::$rules;
    }

    /**
     * Get parsed rules in flatten format for backend
     *
     * @return array
     */
    public function getRulesFlat()
    {
        return array_flat($this->getRules());
    }

    /**
     * Return oryginal config with available permisions
     * usefull for listing, etc
     *
     * @return array
     */
    public function getRulesConfig()
    {
        return self::$config;
    }

    /**
     * Check if rule even exist
     *
     * @param type string to array access in dot format
     * @return bolean
     */
    public function isValid($rule)
    {
        return array_get($this->getRules(), $rule, false);
    }

}
