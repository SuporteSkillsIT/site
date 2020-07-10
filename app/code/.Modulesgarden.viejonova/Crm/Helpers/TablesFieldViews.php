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


namespace Modulesgarden\Crm\Helpers;


use \Illuminate\Database\Capsule\Manager as DB;
use Modulesgarden\Crm\Services\Language;
use \Modulesgarden\Crm\Integration\Slim\SlimApp;
use Modulesgarden\Crm\Models\Field;
use Modulesgarden\Crm\Models\FieldStatus;
use Modulesgarden\Crm\Models\FieldGroup;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\Setting;
use Modulesgarden\Crm\Models\ResourceType;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class TablesFieldViews
{
    protected static $namesMap = array(
        'lists.leads'          => 'lists.leads',
        'lists.potentials'     => 'lists.potentials',
        'dashboard'            => 'dashboard',
    );

    protected static $staticFields = array();

    

    /**
     * All Possible columns to render
     *
     * @return array
     */
    public static function allColumns()
    {
        $fields         = Field::all();
        $staticFields   = Resource::getStaticFields();

        $available = array(
            'fields' => $fields->toArray(),
            'static' => $staticFields,
        );


        return $available;
    }


    /**
     * All Possible fields with dependiences
     *
     * @return array
     */
    public static function getAllColumnsForFilters()
    {
        $fields         = FieldGroup::with(array('fields' => function($query) {
                                            $query->whereActive()->joinOptions()->orderBy('order', 'asc');
                                        }))->orderBy('order', 'asc')->get();
        
        $staticFields   = array();

        foreach (Resource::getStaticFields() as $f)
        {
            $tmp = array(
                'id'    => $f,
                'type'  => 'text',
                'name'  => Language::translate("campaigns.filters.static.{$f}"),
            );

            if(in_array($f, array('id', 'admin', 'client', 'ticket'))) {
                continue;
            }

            if($f == 'status') {
                $tmp['type'] = 'select';
                $tmp['options'] = FieldStatus::all()->toArray();
            } elseif($f == 'priority') {
                $tmp['type'] = 'select';
                $tmp['options'] = array(
                    array('id' => 1, 'name' => Language::translate("priority.low")),
                    array('id' => 2, 'name' => Language::translate("priority.medium")),
                    array('id' => 3, 'name' => Language::translate("priority.important")),
                    array('id' => 4, 'name' => Language::translate("priority.urgent")),
                );
            }

            $staticFields[] = $tmp;
        }

        $contactTypes = array();
        foreach (ResourceType::orderred()->onlyActive()->lists('name', 'id') as $id => $name) {
            $contactTypes[] = array(
                'id' => $id,
                'name' => $name,
            );
        }

        $staticFields[] = array(
            'id'        => 'type_id',
            'type'      => 'select',
            'name'      => Language::translate("campaigns.filters.static.type"),
            'options'   => $contactTypes,
        );

        $available = array(
            'dynamic' => $fields->toArray(),
            'static'  => $staticFields,
        );


        return $available;
    }

    /**
     * All Default columns to render
     *
     * @return array
     */
    public static function defaults()
    {
        $staticFields   = Resource::getStaticFields();

        $available = $staticFields;


        return $available;
    }

    public static function allForAdmin()
    {

        $settings = array();
        foreach (self::$namesMap as $ruleKey => $ruleName)
        {
            $settings[$ruleKey] = self::getSingleForAdmin($ruleName);
        }


        return $settings;
    }


    public static function getSingleForAdmin($rule)
    {
        if( !in_array($rule, self::$namesMap)) {
            throw new Exception('Invalid rule');
        }

        $adminID = SlimApp::getInstance()->currentAdmin->id;
        $setting = Setting::where('admin_id', '=', $adminID)->where('name', '=', $rule)->first();

        if( ! $setting || is_null($setting->value) || $setting->value == 'null' ) {
            return self::defaults();
        }

        $toReturn = $fieldIDs = array();
        foreach ($setting->value as $v)
        {
            $toReturn[] = $v;
            if(is_string($v)) {
                continue;
            }

            if(is_array($v) && array_get($v, 'id', null) != null) {
                $fieldIDs[] = $v['id'];
            }
        }

        if(!empty($fieldIDs))
        {
            $list = Field::whereIn('id', $fieldIDs)->joinOptions()->get()->toArray();
            $list = array_flip_keys_by($list, 'id');

            foreach ($toReturn as $k => $v) {
                if(is_array($v) && array_get($v, 'id', null) != null) {
                    $toReturn[$k] = array_get($list, $v['id']);
                }
            }
        }

        return $toReturn;
    }

    public static function updateForAdmin($rule, $data)
    {
        $adminID = SlimApp::getInstance()->currentAdmin->id;

        $data2 = self::filterGotFieldsForSave($data);
        $setting = Setting::where('admin_id', '=', $adminID)->where('name', '=', $rule)->first();

        if( ! $setting ) {
            $setting = new Setting();
            $setting->admin_id = $adminID;
            $setting->name     = $rule;
            $setting->value    = $data;
        }
            
        // update
        $setting->value = $data;

        return $setting->save();
    }


    public static function filterGotFieldsForSave($data)
    {
        return array_map(function($k){
            return (is_array($k) ? $k['id'] : $k);
        }, $data);
    }
}
