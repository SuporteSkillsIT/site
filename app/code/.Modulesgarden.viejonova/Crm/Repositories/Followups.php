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
use Modulesgarden\Crm\Repositories\Reminders;

use Modulesgarden\Crm\Models\FollowupType;
use Modulesgarden\Crm\Models\Followup;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\Setting;
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Models\Validators\Common;

use \Carbon\Carbon;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Repository pattern for Campaign
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class Followups extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return Followup
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\Followup';
    }


    /**
     * Create followup & multiple reminders based by $data
     *
     * @param type $resourceID  followup needs to be assigned to some lead/potential
     * @param array $data
     */
    public function createWithReminders($resourceID, array $data = array())
    {
        $followup = $this->createFollowup($resourceID, $data);


        $reminders = array();
        // target -> admin
        $admin_reminders = array_get($data, 'admin_reminders', array());
        foreach( $admin_reminders as $remind => $what )
        {
            if(array_get($what, 'enable', false) == false) {
                continue;
            }

            // email
            if(array_get($what, 'email.enable', false) == true) {
                $reminders[] = Reminders::createAdminEmailRemidner($followup, $remind, array_get($what, 'email', array()));
            }

            // sms
            if(array_get($what, 'sms.enable', false) == true) {
                $reminders[] = Reminders::createAdminSmsRemidner($followup, $remind, array_get($what, 'sms', array()));
            }
        }

        // target -> client
        $client_reminders = array_get($data, 'client_reminders', array());
        foreach( $client_reminders as $remind => $what )
        {
            if(array_get($what, 'enable', false) == false) {
                continue;
            }

            // email
            if(array_get($what, 'email.enable', false) == true) {
                $reminders[] = Reminders::createClientEmailRemidner($followup, $remind, array_get($what, 'email', array()));
            }

            // sms
            if(array_get($what, 'sms.enable', false) == true) {
                $reminders[] = Reminders::createClientSmsRemidner($followup, $remind, array_get($what, 'sms', array()));
            }
        }

        // in case of any fails during create
        // remove falses from array
        // since errors are silentlly escaped
        $reminders = array_filter($reminders, function($value) {
            return ($value !== false && $value instanceof \Modulesgarden\Crm\Models\Reminder);
        });

        // magic happend here
        // now all created Reminders will be saved to DB
        $followup->reminders()->saveMany($reminders);
        // then attach to followup model
        $followup->load('reminders');

        return $followup;
    }


    /**
     * Create new Followup :)
     *
     * @param type $resourceID
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Followup
     */
    public function createFollowup($resourceID, array $data = array())
    {
        // fail on not existing related resources
        $resource       = Resource::findOrFail($resourceID);
        $followupType   = FollowupType::findOrFail(array_get($data, 'type'));
        $admin          = Admin::findOrFail(array_get($data, 'admin'));

        // or invalid date
        $date           = Carbon::parse(array_get($data, 'date'));
        
        $followup = new Followup(array(
            'resource_id'   => $resource->id,
            'type_id'       => $followupType->id,
            'admin_id'      => $admin->user_id,
            'description'   => array_get($data, 'description', ''),
            'date'          => $date,
        ));

        $followup->save();

        return $followup;
    }


    /**
     * Create new Followup :)
     *
     * @param type $resourceID
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Followup
     */
    public static function createFollowupForResourceOnCreate(Resource $resource, array $data = array())
    {
        $followupTypeID = Setting::forGlobal()->whereSetting('followups_create_lead_followup_type')->first();

        // fail on not existing related resources
        $followupType   = FollowupType::find($followupTypeID->value);
        if(!$followupType) {
            $followupType = FollowupType::first();
        }
        $admin          = Admin::find(array_get($data, 'admin'));
        if(!$admin) {
            if($resource->admin->user_id) {
                $admin = $resource->admin;
            } else {
                $admin = Admin::find(SlimApp::getInstance()->currentAdmin->user_id);;
            }
        }

        // or invalid date
        if(isset($data['date'])) {
            $date           = Carbon::parse(array_get($data, 'date'));
        } else {
            $date           = Carbon::now();
        }

        $followup = new Followup(array(
            'resource_id'   => $resource->id,
            'type_id'       => $followupType->id,
            'admin_id'      => $admin->user_id,
            'description'   => array_get($data, 'description', 'After Create'),
            'date'          => $date,
        ));

        $followup->save();

        return $followup;
    }


    /**
     * Handle Smart Table requests
     *
     * @param type $resourceID
     * @param array $data
     * @return type
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
        $description        = array_get($data, 'params.search.predicateObject.description', false);
        $typeId             = array_get($data, 'params.search.predicateObject.type_id', false);
        $admin_id           = array_get($data, 'params.search.predicateObject.admin_id', false);
        $search_id          = array_get($data, 'params.search.predicateObject.id', false);
        $search_date        = array_get($data, 'params.search.predicateObject.date', false);


        if($orderBy == 'admin') {
            $orderBy = 'admin_id';
        } elseif($orderBy == 'type') {
            $orderBy = 'type_id';
        }

        // prepare base query with no conditions for count all elements
        $queryTotal = Followup::whereResource($resourceID);
        // base query with limits etc to obrain what we need
        $query = $this->getModel()
                      ->whereResource($resourceID)
                      ->joinAdminRelevant()
                      ->joinType()
                      ->orderBy($orderBy, $orderDesc)
                      ->with('remindersCount')
                      ->with('reminders')
                      ->take($limit)
                      ->offset($ofset);



        // trigger search
        if(!empty($description) && $description !== false) {
            $query      = $query->withDescription($description);
            $queryTotal = $queryTotal->withDescription($description);
        }

        // trigger $typeId
        if(!empty($typeId) && $typeId !== false) {
            $query      = $query->where('type_id', '=', $typeId);
            $queryTotal = $queryTotal->where('type_id', '=', $typeId);
        }
        // trigger $typeId
        if(!empty($admin_id) && $admin_id !== false) {
            $query      = $query->where('admin_id', '=', $admin_id);
            $queryTotal = $queryTotal->where('admin_id', '=', $admin_id);
        }
        if(!empty($search_id) && $search_id !== false) {
            $query      = $query->where('id', '=', $search_id);
            $queryTotal = $queryTotal->where('id', '=', $search_id);
        }
        if(!empty($search_date) && $search_date !== false) {
            $query      = $query->where('date', 'like', "%{$search_date}%");
            $queryTotal = $queryTotal->where('id', 'like', "%{$search_date}%");
        }

        // run this damm queries
        $results = $query->get();
        $count   = $queryTotal->count();

        $parsed = array();
        foreach ($results as $r)
        {
            $tmp = $r->toArray();
            $tmp['reminders_count'] = $r->remindersCount;

            $parsed[] = $tmp;
        }

        // gather to data format for smart table
        $return = array(
            'data'  => $parsed,
            'total' => $count,
        );

        return $return;
    }


    /**
     * Handle Smart table requests
     * For DASHBOARD !
     *
     * @param type $resourceID
     * @param array $data
     * @return type
     */
    public function parseForDashboardTable(array $data = array(), $adminID = false, $forAPI = false)
    {
        // limit
        $limit      = array_get($data, 'params.pagination.number', 5);
        $ofset      = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy    = array_get($data, 'params.sort.predicate', 'date');
        $orderDesc  = array_get($data, 'params.sort.reverse', true);
        $orderDesc  = ($orderDesc === true) ? 'DESC' : 'ASC';

        $search         = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal   = array_pull($search, '$', false);

        // descr
        $description        = array_get($data, 'params.search.predicateObject.description', false);
        $typeId             = array_get($data, 'params.search.predicateObject.type_id', false);
        $reminders          = array_get($data, 'params.search.predicateObject.reminders', false);
        $leadStatusId       = array_get($data, 'params.search.predicateObject.lead_status_id', false);
        $leadPriorityId     = array_get($data, 'params.search.predicateObject.lead_priority_id', false);
        $leadName           = array_get($data, 'params.search.predicateObject.lead_name', false);




        $day        = array_get($data, 'params.search.predicateObject.day', false);
        if( $day == false ) {
            $dayObj = Carbon::now();
        } else {
            $dayObj = Carbon::parse($day);
        }

        if($orderBy == 'lead_name') {
            $orderBy = 'crm_resources.name';
        } elseif($orderBy == 'date') {
            $orderBy = 'crm_followups.date';
        } elseif($orderBy == 'type') {
            $orderBy = 'crm_followup_types.order';
        } elseif($orderBy == 'status') {
            $orderBy = 'crm_resources_statuses.order';
        } elseif($orderBy == 'priority') {
            $orderBy = 'crm_resources.priority';
        } elseif($orderBy == 'description') {
            $orderBy = 'crm_followups.description';
        }


        // base query with limits etc to obrain what we need
        $query = $this->getModel()
                      ->select(array('crm_followups.*', 'crm_resources_types.icon'))
                      ->joinAdminRelevant()
                      ->joinType()
                      ->withResourceAndStatus()
                      ->orderBy($orderBy, $orderDesc)
                      ->with('remindersCount')
                      ->leftJoin('crm_followup_types', 'crm_followup_types.id', '=', 'crm_followups.type_id')
                      ->leftJoin('crm_resources', 'crm_resources.id', '=', 'crm_followups.resource_id')
                      ->leftJoin('crm_resources_statuses', 'crm_resources_statuses.id', '=', 'crm_resources.status_id')
                      ->leftJoin('crm_resources_types', 'crm_resources.type_id', '=', 'crm_resources_types.id');

        // requested admin
        if( Common::isPositiveUnsignedNumber($adminID) && $adminID !== false ) {
            $query = $query->where('crm_followups.admin_id', '=', $adminID);
        } elseif( $adminID == '' ) {
            // special case, dont add filter
        } elseif( $adminID === 0 ) {
            $query = $query->whereNull('crm_followups.admin_id');
        } else {
            $query = $query->where('crm_followups.admin_id', '=', SlimApp::getInstance()->currentAdmin->id);
        }

        $campaignID = array_get($data, 'campaign');
        if( Common::isPositiveUnsignedNumber($campaignID) ) {
            $query = $query->whereCampaign($campaignID);
        } elseif($campaignID === 0) {
            $query = $query->whereNoCampaign();
        }
        
        // set days filter
        $query      = $query->where(DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"), '=', $dayObj->format('Y-m-d'));

        // global search - special for this there is so much joins ..... damm it
        if(!empty($searchGlobal) && $searchGlobal !== false) {
            $query = $query->where(function ($query) use($searchGlobal) {
                        $query->where('crm_followups.description', 'LIKE', DB::raw("'%{$searchGlobal}%'"))->orWhere('crm_resources.name', 'LIKE', DB::raw("'%{$searchGlobal}%'"));
                    });
        }

        // trigger search
        if(!empty($description) && $description !== false) {
            $query = $query->withDescription($description);
        }

        // trigger $typeId
        if(!empty($typeId) && $typeId !== false) {
            $query = $query->where('crm_followups.type_id', '=', $typeId);
        }


        // run this damm queries
        $results = $query->get();

        $parsed = array();
        foreach ($results as $r)
        {
            $tmp = $r->toArray();
            $tmp['reminders_count'] = $r->remindersCount;

            $conditions = true;

            // trigger num reminders
            if(!empty($reminders) && $reminders !== false) {
                if( $r->remindersCount != $reminders ) {
                    $conditions = false;
                }
            }

            // trigger status
            if(!empty($leadStatusId) && $leadStatusId !== false) {
                if( $r->resource->status->id != $leadStatusId ) {
                    $conditions = false;
                }
            }

            // trigger priority
            if(!empty($leadPriorityId) && $leadPriorityId !== false) {
                if( $r->resource->priority != $leadPriorityId ) {
                    $conditions = false;
                }
            }

            // trigger name
            if(!empty($leadName) && $leadName !== false) {
                if (strpos($r->resource->name, $leadName) === false) {
                    $conditions = false;
                }
            }

            if( $conditions ) {
                $parsed[] = $tmp;
            }
        }

        if($forAPI === true)
        {
            return $parsed;
        }

        $cut = array();
        for ($i = $ofset; ($i < count($parsed) ); $i++ ){
            if( $limit <= 0) {
                break;
            }
            $cut[] = $parsed[$i];
            $limit--;
        }


        // gather to data format for smart table
        $return = array(
            'data'  => $cut,
            'total' => count($parsed),
        );

        return $return;
    }


    /**
     * Used to obtain single followup record
     * for example for edit page
     *
     * @param type $resourceID
     * @param type $followupID
     * @return Modulesgarden\Crm\Models\Followup
     */
    public function getSingleWithReminders($resourceID, $followupID)
    {
        return $this->getModel()->whereResource($resourceID)
                      ->joinAdminRelevant()
                      ->joinType()
                      ->joinReminders()
                      ->findOrFail($followupID);
    }


    /**
     * Used to obtain single followup record
     * for example for edit page
     *
     * @param type $resourceID
     * @param type $followupID
     * @return Modulesgarden\Crm\Models\Followup
     */
    public function getSingle($resourceID, $followupID)
    {
        return $this->getModel()->whereResource($resourceID)
                      ->joinAdminRelevant()
                      ->joinType()
                      ->findOrFail($followupID);
    }

    /**
     * Used to update main data for specific followup
     * (we dont mess with reminders for followup data here)
     *
     * @param type $resourceID
     * @param type $followupID
     * @param type $requestData
     * @return Modulesgarden\Crm\Models\Followup
     */
    public function updateSingleFollowup($resourceID, $followupID, $requestData)
    {
        $followup = $this->getModel()
                         ->whereResource($resourceID)
                         ->findOrFail($followupID);

        // fail on not existing related resources
        $followupType   = FollowupType::findOrFail(array_get($requestData, 'type_id'));
        $admin          = Admin::findOrFail(array_get($requestData, 'admin_id'));
        // or invalid date
        $newDate           = Carbon::parse(array_get($requestData, 'date'));
//        $newDate           = Carbon::createFromTimestamp(array_get($requestData, 'date'));
        // or description
        $description    = array_get($requestData, 'description', '');

        // make copy
        $oldDate = $followup->date->copy();

        $followup->fill(array(
            'resource_id'   => $followup->resource_id,
            'date'          => $newDate->timestamp,
            'description'   => $description,
            'admin_id'      => $admin->id,
            'type_id'       => $followupType->id,
        ));

        if ( ! $followup->save() ) {
            throw new Exception('Something went wrong');
        }

        if( array_get($requestData, 'updateReminders', false) === true ) {

            // calculate time diference
            $timeDiff   = $oldDate->diffInMinutes($newDate);
            $plusToDate = $newDate->gt($oldDate);

            // update each reminder date
            foreach ($followup->reminders as $reminder)
            {
                $reminder->reschedue($plusToDate, $timeDiff);
            }
        }
        
        return $followup;
    }


    /**
     * Return followups as events for calendar
     *
     * @param type $adminID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function queryCalendarForAdmin($adminID)
    {
        return $this->getModel()
                    ->whereAdmin($adminID)
                    ->joinLeadTypeAndName()
                    ->with('remindersCount')
                    ->get();
    }

    /**
     * Return followups as events for calendar
     *
     * @param type $adminID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function queryCalendarForAllAdmins()
    {
        return $this->getModel()
                    ->joinLeadTypeAndName()
                    ->with('remindersCount')
                    ->get();
    }

    /**
     * Drop followup from DB
     * with all reminders
     *
     * @param type $id
     * @param type $followupID
     * @return bolean
     */
    public function deleteSingleFollowup($resourceID, $followupID)
    {
        $followup = $this->getModel()
                         ->whereResource($resourceID)
                         ->findOrFail($followupID);
        $followup->dropWithReminders();
    }

    /**
     * Drop followup from DB
     * with all reminders
     *
     * @param type $id
     * @param type $followupID
     * @return bolean
     */
    public function reschedueFollowup($resourceID, $followupID, array $data = array())
    {
        $followup = $this->getModel()
                         ->whereResource($resourceID)
                         ->joinReminders()
                         ->findOrFail($followupID);

        

        return $followup->reschedueWithReminders($data);
    }

    /**
     * Calculate Followup Counters for whole month
     * Used in dashboard calendar
     *
     * @param type $year
     * @param type $month
     * @param type $adminID
     * @param type $campaign
     * @return array
     */
    public function getCountersForCalendar($year, $month, $adminID, $campaign = null)
    {
        $query = $this->getModel()
                ->select(DB::raw('count(*) as total'), DB::raw("DATE_FORMAT(crm_followups.date, '%d') as day"))
                ->whereNull('crm_followups.deleted_at')
                ->where(DB::raw("DATE_FORMAT(crm_followups.date, '%Y')"), '=', $year)
                ->where(DB::raw("DATE_FORMAT(crm_followups.date, '%m')"), '=', $month)
                ->groupBy(DB::raw("DATE_FORMAT(crm_followups.date, '%d')"));

        if( Common::isPositiveUnsignedNumber($adminID) ) {
            $query = $query->whereAdmin($adminID);
        } elseif($adminID === 0) {
            $query = $query->whereNoAdmin();
        }
        if( Common::isPositiveUnsignedNumber($campaign) ) {
            $query = $query->whereCampaign($campaign);
        } elseif($campaign === 0) {
            $query = $query->whereNoCampaign();
        }

        $counter = $query->get()->toArray();

        $return = array();

        // Fill Blanks for Days
        $dt = Carbon::parse("{$year}-{$month}");
        for($i=0; $i < $dt->daysInMonth; $i++)
        {
            $return[]      = 0;
        }

        // Fill With Correct Values
        foreach ($counter as $c) {
            array_set($return, intval(--$c['day']), intval($c['total']));
        }

        return $return;
    }
}
