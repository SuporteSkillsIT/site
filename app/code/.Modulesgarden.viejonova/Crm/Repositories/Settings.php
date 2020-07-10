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


namespace Modulesgarden\Crm\Repositories;


use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\Setting;

/**
 * Just container for Settings
 * as repository pattern
 */
class Settings extends AbstractRepository implements RepositoryInterface
{
    /**
     * Basically set default values for general settings
     *
     * @var type 
     */
    protected static $generalDefaults = array(
        'quotations_enable'             => true,
        'potentials_enable'             => true,
        'assign_admin'                  => true,
        'followups_per_day'             => false,
        'followups_reminder_template'   => false,
        'followups_reschedule_template' => false,
    );


    /**
     * Determinate model used by this Repository
     *
     * @return Setting
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\Setting';
    }


    /**
     * Return with settings assigned to specific admin
     *
     * @param type $adminID
     * @return type
     * @throws Exception
     */
    public function getAdminPersonalizedSettings($adminID)
    {
        if(!is_numeric($adminID)) {
            throw new Exception('Invalid Admin ID');
        }

        return $this->getModel()->forAdmin($adminID)->lists('value', 'name');
    }


    /**
     * Handle update settings for admin
     *
     * @param type $adminID
     * @param type $data
     * @return boolean
     * @throws Exception
     */
    public function updateManuForAdmin($adminID, $data)
    {
        if(empty($data)) {
            throw new Exception('Nothing to update');
        }
        if(!is_numeric($adminID)) {
            throw new Exception('Invalid Admin ID');
        }
        foreach ($data as $settingName => $settingValue)
        {
            $model = Setting::where('name', '=', $settingName)->forAdmin($adminID)->first();

            if(is_null($model))
            {
                $model = new Setting(array(
                    'name'      => $settingName,
                    'admin_id'  => intval($adminID),
                ));
            }

            $model->value = $settingValue;
            $model->save();
        }

        return true;
    }


    /**
     * Get Global settings For CRM app
     *
     * @return type
     */
    public function getGlobal()
    {
        $globals = $this->getModel()->forGlobal()->lists('value', 'name');
        unset($globals['fields_map']);
        foreach ($globals as $k => $v) {
            if($v == 1) {
                $globals[$k] = true;
            } elseif ($v == 0) {
                $globals[$k] = false;
            }
        }
        return $globals;
    }


    /**
     * This is very important to return evry
     * self::$generalDefaults
     *
     * since Frontend relay on this variables, so if do not found in DB, use damm default from this class
     *
     *
     * @return boolean
     */
    public static function getGlobalsForTwig()
    {
        $globals = Setting::forGlobal()->lists('value', 'name');
        unset($globals['fields_map']);
        foreach ($globals as $k => $v)
        {
            if($v == 1) {
                $globals[$k] = true;
            } elseif ($v == 0) {
                $globals[$k] = false;
            }
        }

        foreach (self::$generalDefaults as $key => $default)
        {
            if( ! array_key_exists($key,$globals) ) {
                array_set($globals, $key, $default);
            }
        }

        return $globals;
    }


    /**
     * Just plain return wanted setting by key from DB
     *
     * @param type $what
     * @return type
     */
    public static function getSingleParameter($what)
    {
        return Setting::forGlobal()->whereSetting($what)->lists('value', 'name');
    }


    /**
     * Handle update settings for admin
     *
     * @param type $data
     * @return boolean
     * @throws Exception
     */
    public function updateGlobals($data)
    {
        if(empty($data)) {
            throw new Exception('Nothing to update');
        }
        foreach ($data as $settingName => $settingValue)
        {
            $model = Setting::where('name', '=', $settingName)->forGlobal()->first();

            if(is_null($model))
            {
                $model = new Setting(array(
                    'name'      => $settingName,
                    'admin_id'  => 0,
                ));
            }

            $model->value = $settingValue;
            $model->save();
        }

        return true;
    }
}
