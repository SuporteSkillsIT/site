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


use \Illuminate\Database\Capsule\Manager as DB;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\Notification;

use \Exception;
use \Carbon\Carbon;

/**
 * Repository pattern for Notifications
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class Notifications extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\Notification
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\Notification';
    }


    /**
     * Basic things to create Norification
     *
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Notification
     */
    public function createNotification(array $data = array())
    {
        $startDate  = Carbon::parse(array_get($data, 'date_start'));
        $endDate    = Carbon::parse(array_get($data, 'date_end'));

        // force fix start date to beginning of a day
        $startDate->hour   = $endDate->minute = $endDate->second = 0;

        // force fix ending date to end of the day
        $endDate->hour   = 23;
        $endDate->minute = $endDate->second = 59;


        $parsed = array(
            'class'             => array_get($data, 'class', ''),
            'type'              => array_get($data, 'type', ''),
            'content'           => array_get($data, 'content', ''),
            'date_start'        => $startDate,
            'date_end'          => $endDate,
            'confirmation'      => (array_get($data, 'confirmation', false) === true) ? true : false,
            'hide_after_confirm'=> (array_get($data, 'hide_after_confirm', false) === true) ? true : false,
        );

        $new = new Notification($parsed);
        $new->save();

        $adminsIDs  = array_get($data, 'admins', array());
        if(!empty($adminsIDs)) {
            $new->admins()->attach($adminsIDs);
        }

        return $new;
    }


    /**
     * Update single Notification
     *
     * @param type $id
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Notification
     * @throws Exception
     */
    public function updateNotification($id, array $data = array())
    {
        $notification = $this->getModel()->find($id);

        if(is_null($notification)) {
            throw new Exception('Campaign not found');
        }

        $startDate  = Carbon::parse(array_get($data, 'date_start'));
        $endDate    = Carbon::parse(array_get($data, 'date_end'));

        // force fix start date to beginning of a day
        $startDate->hour   = $endDate->minute = $endDate->second = 0;

        // force fix ending date to end of the day
        $endDate->hour   = 23;
        $endDate->minute = $endDate->second = 59;

        $parsed = array(
            'class'             => array_get($data, 'class', ''),
            'type'              => array_get($data, 'type', ''),
            'content'           => array_get($data, 'content', ''),
            'date_start'        => $startDate,
            'date_end'          => $endDate,
            'confirmation'      => (array_get($data, 'confirmation', false) === true) ? true : false,
            'hide_after_confirm'=> (array_get($data, 'hide_after_confirm', false) === true) ? true : false,
        );

        $notification->fill($parsed);
        $notification->save();

        // sync assigned admins
        $adminsIDs  = array_get($data, 'admins', array());
        if(!empty($adminsIDs)) {
            $notification->admins()->sync($adminsIDs);
        }

        return $notification;
    }


    /**
     * List Norifications for SmartTable
     *
     * @param array $data
     */
    public function getNorificationsListTableQuery($data)
    {
        // limit
        $limit      = array_get($data, 'params.pagination.number', 10);
        $ofset      = array_get($data, 'params.pagination.start', 0);

        // order
        $orderBy    = array_get($data, 'params.sort.predicate', 'date_start');
        $orderDesc  = array_get($data, 'params.sort.reverse', true);
        $orderDesc  = ($orderDesc === true) ? 'DESC' : 'ASC';

        // search
        $name           = array_get($data, 'params.search.predicateObject.name', false);
        $description    = array_get($data, 'params.search.predicateObject.description', false);

        // global search
        $search         = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal   = array_pull($search, '$', false);

        // base query with limits etc to obrain what we need
        $query = $this->getModel()->withAdmins();

        if(!empty($searchGlobal)) {
            $query = $query->whereContent($searchGlobal);
        }

        // basically the same query but no orderby/limit/select
        $total = $query->count();

        // run this damm queries
        $results = $query->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->get();

        $return = array();
        foreach ($results as $r) {
            $tmp = $r->toArray();
            $tmp['accepted'] = $r->getAdminWhoAcceptted();
            $return[] = $tmp;
        }

        // gather to data format for smart table
        $return = array(
            'data'  => $return,
            'total' => $total,
        );

        return $return;
    }


    /**
     * Obtain Notifications assigned to requested Admin
     * Parsed to array, and frontend friently parameters
     *
     * @param type $adminID
     * @return type
     */
    public function getMineNotificationList($adminID)
    {
        $return = array();

        $notifications = Notification::whereHas('admins', function($q) use($adminID) {
            $q->where('crm_notifications_admins.admin_id', '=', DB::raw($adminID));
        })->get();

        $return = array();
        foreach ($notifications as $n)
        {
            $tmp = $n->toArray();

            if(count($n->admins)) {
                $searchedAdmin = $n->admins->filter(function($item) use($adminID) {
                    return $item->user_id == $adminID;
                })->first();
                $tmp['accepted_at'] = $searchedAdmin->pivot->accepted_at;
                $tmp['class'] = sprintf('note-%s', $n->class);
            }
            
            if($n->active === true )
            {
                if( ! ($n->confirmation === true && $n->hide_after_confirm === true && $tmp['accepted_at'] !== null) ) {
                    $return[] = $tmp;
                }
            }
        }

        return $return;
    }


    /**
     * Delete Notification record
     * 
     * @note  force delete from DB
     * @param type $id
     * @return bolean
     * @throws Exception
     */
    public function deleteNotification($id)
    {
        $found = $this->getModel()->withTrashed()->where('id', '=', $id)->first();

        if(is_null($found)) {
            throw new Exception(sprintf("Couldn't find Notification #%d", $id));
        }

        return $found->forceDelete();
    }


    /**
     * Mark Notification as Accepted in pivot table
     *
     * @param type $requestData
     * @param type $adminID
     * @return boolean
     * @throws Exception
     */
    public function acceptNotification($requestData, $adminID)
    {
        $noteID = array_get($requestData, 'note');

        $found = $this->getModel()->find($noteID);

        if($found->active === false ) {
            throw new Exception(sprintf("Notification #%d is inactive", $found->id));
        }

        if(is_null($found)) {
            throw new Exception(sprintf("Couldn't find Notification #%d", $id));
        }

        $now = Carbon::now();
        DB::table('crm_notifications_admins')
                    ->where('crm_notifications_admins.notification_id', '=', $noteID)
                    ->where('crm_notifications_admins.admin_id', '=', $adminID)
                    ->update(array('accepted_at' => $now));

        return true;
    }


    /**
     * Get requested notification
     *
     * @param type $id
     * @return \Modulesgarden\Crm\Models\Notification
     * @throws Exception
     */
    public function getNotification($id)
    {
        $notification = $this->getModel()->find($id);

        if(is_null($notification)) {
            throw new Exception('Notification not found');
        }

        $return = $notification->toArray();
        $return['admins'] = $notification->admins->lists('id');

        return $return;
    }
}
