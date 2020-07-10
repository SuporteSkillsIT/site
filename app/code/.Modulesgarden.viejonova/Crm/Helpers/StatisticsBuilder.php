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

namespace Modulesgarden\Crm\Helpers;

use \Illuminate\Database\Capsule\Manager as DB;
use \Modulesgarden\Crm\Integration\Slim\SlimApp;
use Modulesgarden\Crm\Models\Validators\Common;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\ResourceType;
use \Exception;
use Carbon\Carbon;

/**
 * Class to maintain actions for single lead instance
 */
class StatisticsBuilder
{

    protected $adminID = false;

    public function __construct($adminID)
    {
        $this->adminID = $adminID;
        //  $this->adminID = 1;
    }

    public function getResourcesPerStatus($data)
    {
        $query = DB::table('crm_resources')
                ->select('status_id', 'type_id', DB::raw('count(*) as total'))
                ->whereNull('deleted_at')
                ->groupBy('status_id')
                ->groupBy('type_id');

        if (Common::isPositiveNumber($this->adminID)) {
            $query = $query->where('admin_id', '=', $this->adminID);
        }

        $return = array();
        $types = ResourceType::orderred()->onlyActive()->get();
        $result = $query->get();

        foreach ($types as $type) {
            if ($type->isActive()) {
                $tmp = array(
                    'type' => $type->toArray(),
                    'data' => array()
                );

                foreach ($result as $r) {
                    if ($type->id == (int) $r['type_id']) {
                        $r['total'] = intval($r['total']);
                        $r['status_id'] = intval($r['status_id']);
                        $tmp['data'][] = $r;
                    }
                }

                $return[] = $tmp;
            }
        }

        return $return;
    }

    public function getLastTenRecords($data)
    {
        $query = Resource::whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->with(array('admin' => function($query) {
                        $query->select('user_id', 'username', 'firstname', 'lastname', 'email');
                    }))
                ->with('status')
                ->limit(10);

        if (Common::isPositiveNumber($this->adminID)) {
            $query = $query->where('admin_id', '=', $this->adminID);
        }


        $return = array();
        $types = ResourceType::orderred()->onlyActive()->get();

        foreach ($types as $type) {
            if ($type->isActive()) {
                $tmpQuery = clone $query;

                $tmp = array(
                    'type' => $type->toArray(),
                    'data' => $tmpQuery->where('type_id', '=', $type->id)->get()->toArray(),
                );

                unset($tmpQuery);

                $return[] = $tmp;
            }
        }

        return $return;
    }

    public function getTotalRecordsPerAdmin($data)
    {

        $admins = DB::table('admin_user')->select('user_id', 'firstname', 'lastname')->get();

        $return = array();
        $types = ResourceType::orderred()->onlyActive()->get();


        foreach ($types as $type) {
            if ($type->isActive()) {
                $tmp = array(
                    'type' => $type->toArray(),
                    'data' => array(),
                );

                foreach ($admins as &$admin) {
                    if ($type->isActive()) {
                        $count = DB::table('crm_resources')
                                ->select(DB::raw('count(*) as total'))
                                ->whereNull('deleted_at')
                                ->where('admin_id', '=', $admin['user_id'])
                                ->where('type_id', '=', $type->id)
                                ->first();
                        $tmp['data'][] = intval($count['total']);
                    }
                }


                $return[] = $tmp;
            }
        }

        return array_merge(array('types' => $return), array('admins' => $admins));
    }

    public function getTotalRecordsPerYear($year, $data)
    {

        $query = DB::table('crm_resources')
                ->select(DB::raw('count(*) as total'), DB::raw("DATE_FORMAT(created_at, '%m') as month"))
                ->whereNull('deleted_at')
                ->where(DB::raw("DATE_FORMAT(created_at, '%Y')"), '=', $year)
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m')"));

        if (Common::isPositiveNumber($this->adminID)) {
            $query = $query->where('admin_id', '=', $this->adminID);
        }


        $return = array();
        $types = ResourceType::orderred()->onlyActive()->get();

        foreach ($types as $type) {
            if ($type->isActive()) {
                $tmp = array(
                    'type' => $type->toArray(),
                    'month' => array(),
                );

                $tmpQuery = clone $query;
                $months = $tmpQuery->where('type_id', '=', $type->id)->get();

                // fill holes
                for ($i = 0; $i < 12; $i++) {
                    array_set($tmp, "month.$i", array_get($tmp, "month.$i", 0));
                }
                foreach ($months as $l) {
                    array_set($tmp, "month." . intval(--$l['month']), intval($l['total']));
                }


                unset($tmpQuery);

                $return[] = $tmp;
            }
        }

        return $return;
    }

    public function getTotalRecordsInMonth($year, $month, $data)
    {

        $query = DB::table('crm_resources')
                ->select(DB::raw('count(*) as total'), DB::raw("DATE_FORMAT(created_at, '%d') as day"))
                ->whereNull('deleted_at')
                ->where(DB::raw("DATE_FORMAT(created_at, '%Y')"), '=', $year)
                ->where(DB::raw("DATE_FORMAT(created_at, '%m')"), '=', $month)
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d')"));

        if (Common::isPositiveNumber($this->adminID)) {
            $query = $query->where('admin_id', '=', $this->adminID);
        }

        $return = array();
        $types = ResourceType::orderred()->onlyActive()->get();
        $dt = Carbon::parse("{$year}-{$month}");

        foreach ($types as $type) {
            if ($type->isActive()) {
                $tmp = array(
                    'type' => $type->toArray(),
                    'month' => array(),
                );

                $days = DB::table('crm_resources')
                        ->select(DB::raw('count(*) as total'), DB::raw("DATE_FORMAT(created_at, '%d') as day"))
                        ->whereNull('deleted_at')
                        ->whereRaw('type_id =' . intval($type->id));
                if (Common::isPositiveNumber($this->adminID)) {
                    $days = $days->whereRaw('admin_id =' . intval($this->adminID));
                }
                $days = $days->whereRaw("DATE_FORMAT(created_at, '%Y') =" . intval($year))
                        ->whereRaw("DATE_FORMAT(created_at, '%m') =" . intval($month))
                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d')"))
                        ->get();

                // Fill Blanks for Days
                for ($i = 0; $i < $dt->daysInMonth; $i++) {
                    array_set($tmp, "month.$i", 0);
                }
                foreach ($days as $l) {
                    array_set($tmp, "month." . intval(--$l['day']), intval($l['total']));
                }

                $return[] = $tmp;
            }
        }

        return $return;
    }

}
