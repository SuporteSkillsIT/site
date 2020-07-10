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


use Modulesgarden\Crm\Integration\Slim\SlimApp;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\Log;
use Modulesgarden\Crm\Models\Validators\Common;
use Carbon\Carbon;

/**
 * Just container for Logs
 * as repository pattern
 */
class Logs extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return Log
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\Log';
    }

    /**
     * Handle Smart table requests
     *
     * @param type  $resourceID related resource
     * @param array $data       params sended by smart table
     * @return type             array for smart table format later parsed to json
     */
    public function parseForTable($resourceID, array $data = array())
    {
        // limit
        $limit      = array_get($data, 'params.pagination.number', 10);
        $ofset      = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy    = array_get($data, 'params.sort.predicate', 'date');
        $orderDesc  = array_get($data, 'params.sort.reverse', true);
        $orderDesc  = ($orderDesc === true) ? 'DESC' : 'ASC';
        // search
        $message    = array_get($data, 'params.search.predicateObject.message', false);
        $date       = array_get($data, 'params.search.predicateObject.date', false);
        $admin_id   = array_get($data, 'params.search.predicateObject.admin_id', false);
        $event      = array_get($data, 'params.search.predicateObject.event', false);

        // prepare base query with no conditions for count all elements
        $queryTotal = Log::withResource($resourceID);
        // base query with limits etc to obrain what we need
        $query = $this->getModel()
                    ->withAdmin()
                    ->withResource($resourceID)
                    ->orderBy($orderBy, $orderDesc)
                    ->take($limit)
                    ->offset($ofset);

        // trigger search
        if(!empty($message) && $message !== false) {
            $query      = $query->withMessage($message);
            $queryTotal = $query->withMessage($message);
        }
        // find by date
        if(!empty($date) && $date !== false) {
            $query      = $query->whereDate($date);
            $queryTotal = $query->whereDate($date);
        }
        // find by date
        if(!empty($event) && $event !== false) {
            $query      = $query->whereEvent($event);
            $queryTotal = $query->whereEvent($event);
        }
        // find by date
        if($admin_id !== false && is_numeric($admin_id)) {
            $query      = $query->whereAdmin($admin_id);
            $queryTotal = $query->whereAdmin($admin_id);
        }

        
        // run this damm queries
        $results = $query->get();
        $count   = $queryTotal->count();

        // gather to data format for smart table
        $return = array(
            'data'  => $results->toArray(),
            'total' => $count,
        );

        return $return;
    }


    /**
     * Handle Smart table requests
     * for DASHBOARD
     *
     * @param array $data       params sended by smart table
     * @return type             array for smart table format later parsed to json
     */
    public function parseForDashboardTable($data = array())
    {
        // limit
        $limit      = array_get($data, 'params.pagination.number', 10);
        $ofset      = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy    = array_get($data, 'params.sort.predicate', 'date');
        $orderDesc  = array_get($data, 'params.sort.reverse', true);
        $orderDesc  = ($orderDesc === true) ? 'DESC' : 'ASC';
        // search

        $search         = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal   = array_pull($search, '$', false);

        // descr
        $sDate        = array_get($data, 'params.search.predicateObject.date', false);
        $sName        = array_get($data, 'params.search.predicateObject.lead_name', false);
        $sEvent       = array_get($data, 'params.search.predicateObject.event', false);
        $sMessage     = array_get($data, 'params.search.predicateObject.message', false);
        $sAdmin       = array_get($data, 'params.search.predicateObject.admin_id', false);


        $orders = array(
            'date'      => 'crm_logs.date',
            'lead_name' => 'crm_resources.name',
            'event'     => 'crm_logs.event',
            'message'   => 'crm_logs.message',
        );

        // base query with limits etc to obrain what we need
        $query = $this->getModel()
                      ->withAdmin()
                      ->select(array('crm_logs.*', 'crm_resources.name', 'crm_resources_types.icon'))
                      ->resource()
                      ->leftJoin('crm_resources', 'crm_resources.id', '=', 'crm_logs.resource_id')
                      ->leftJoin('crm_resources_types', 'crm_resources.type_id', '=', 'crm_resources_types.id');

        // requested admin
        if( Common::isPositiveUnsignedNumber($sAdmin) && $sAdmin !== false ) {
            $query = $query->where('crm_logs.admin_id', '=', $sAdmin);
        } elseif( $sAdmin == '' ) {
            // special case, dont add filter
        } elseif( $sAdmin === 0 ) {
            $query = $query->whereNull('crm_logs.admin_id');
        } else {
            $query = $query->where('crm_logs.admin_id', '=', SlimApp::getInstance()->currentAdmin->id);
        }

        // trigger search
        if(!empty($sMessage)) {
            $query      = $query->withMessage($sMessage);
        }
        if(!empty($sDate)) {
            $query      = $query->whereDate($sDate);
        }
        if(!empty($sEvent)) {
            $query      = $query->whereEvent($sEvent);
        }
        if(!empty($sName)) {
            $query      = $query->where('crm_resources.name', 'LIKE', "%{$sName}%");
        }
        if(!empty($searchGlobal) && $searchGlobal !== false) {
            $query      = $query->where(function($query) use ($searchGlobal) {
                $query->orWhere('crm_logs.event', 'LIKE', "%{$searchGlobal}%")
                             ->orWhere('crm_logs.date', 'LIKE', "%{$searchGlobal}%")
                             ->orWhere('crm_resources.name', 'LIKE', "%{$searchGlobal}%")
                             ->orWhere('crm_logs.message', 'LIKE', "%{$searchGlobal}%");
            });
        }

        $total = $query->count();

        // run this damm queries
        $results = $query->orderBy($orders[$orderBy], $orderDesc)->take($limit)->offset($ofset)->get();

        $now = Carbon::now();

        $resultsArray = $results->toArray();

        foreach ($results as $k => $r) {
            // nice formated date diff based on Carbon lib
            $resultsArray[$k]['date'] = $r->date->diffForHumans($now);
        }

        // gather to data format for smart table
        $return = array(
            'data'  => $resultsArray,
            'total' => $total,
        );

        return $return;
    }
}
