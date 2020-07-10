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

use Modulesgarden\Crm\Services\Language;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\MassMessageConfig;
use Modulesgarden\Crm\Models\Log;

use \Exception;
use Carbon\Carbon;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Just container for model
 * as repository pattern
 */
class MassMessageConfigs extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return MassMessageConfig
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\MassMessageConfig';
    }


    public function addMassMessageConfig($data)
    {
        $toFill = array();
        $toFill = array_add($toFill, 'description',     array_get($data, 'description', null));
        $toFill = array_add($toFill, 'message_content', array_get($data, 'message_content', null));
        $toFill = array_add($toFill, 'message_title',   array_get($data, 'message_title', null));
        $toFill = array_add($toFill, 'message_type',    array_get($data, 'message_type', null));
        $toFill = array_add($toFill, 'target_type',     array_get($data, 'target_type', null));
        $toFill = array_add($toFill, 'target_ids',      array_get($data, 'target_ids', array()));

        // or invalid date
        $date           = Carbon::parse(array_get($data, 'date'));
        $toFill         = array_add($toFill, 'date', $date);


        $new = $this->model->create($toFill);
        $new->save();

        return $new;
    }

    public function updateMassMessagesConfig($id, $data)
    {
        $model = $this->getModel()->find($id);

        if(is_null($model)) {
            throw new Exception(sprintf("Couldn't find Mass Mail Configuration #%d", $id));
        }

        $toFill = array();
        $toFill = array_add($toFill, 'description',     array_get($data, 'description', null));
        $toFill = array_add($toFill, 'message_content', array_get($data, 'message_content', null));
        $toFill = array_add($toFill, 'message_title',   array_get($data, 'message_title', null));
        $toFill = array_add($toFill, 'message_type',    array_get($data, 'message_type', null));
        $toFill = array_add($toFill, 'target_type',     array_get($data, 'target_type', null));
        $toFill = array_add($toFill, 'target_ids',      array_get($data, 'target_ids', array()));

        // or invalid date
        $date           = Carbon::parse(array_get($data, 'date'));
        $toFill         = array_add($toFill, 'date', $date);


        $model->fill($toFill);
        $model->save();

        return $model;
    }


    /**
     * Simple Enough, obrain requested Campaign
     *
     * @param int   $id
     * @return \Modulesgarden\Crm\Models\Campaign
     */
    public function getMassMessagesConfig($id)
    {
        $model = $this->getModel()->find($id);

        if(is_null($model)) {
            throw new Exception(sprintf("Couldn't find Mass Mail Configuration #%d", $id));
        }

        return $model->toArray();
    }


    public function deleteMassMessagesConfig($id)
    {
        $found = $this->getModel()->withTrashed()->find($id);

        if(is_null($found)) {
            throw new Exception(sprintf("Couldn't find Mass Mail Configuration #%d", $id));
        }

        return $found->forceDelete();

        throw new Exception(sprintf("Something went wrong at delete Mass Mail Configuration #%d", $id));
    }

    public function parseForTable(array $data = array())
    {
        // limit
        $limit      = array_get($data, 'params.pagination.number', 10);
        $ofset      = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy    = array_get($data, 'params.sort.predicate', 'date');
        $orderDesc  = array_get($data, 'params.sort.reverse', true);
        $orderDesc  = ($orderDesc === true) ? 'DESC' : 'ASC';

        // global search
        $search         = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal   = array_pull($search, '$', false);

        // search
        $description    = array_get($data, 'params.search.predicateObject.description', false);
        $messageContent = array_get($data, 'params.search.predicateObject.message_content', false);
        $messageTitle   = array_get($data, 'params.search.predicateObject.message_title', false);
        $messageType    = array_get($data, 'params.search.predicateObject.message_type', false);
        $targetType     = array_get($data, 'params.search.predicateObject.target_type', false);
        $generated      = array_get($data, 'params.search.predicateObject.generated', false);
        $date           = array_get($data, 'params.search.predicateObject.date', false);


        // define possibles columns to order
        $orderableColumn = array(
            'id'                => 'crm_mass_message_configs.id',
            'description'       => 'crm_mass_message_configs.description',
            'message_content'   => 'crm_mass_message_configs.message_content',
            'message_title'     => 'crm_mass_message_configs.message_title',
            'message_type'      => 'crm_mass_message_configs.message_type',
            'target_type'       => 'crm_mass_message_configs.target_type',
            'generated'         => 'crm_mass_message_configs.generated',
            'date'              => 'crm_mass_message_configs.date',
            'messages_count'    => 'messages_count',
        );

        if(isset($orderableColumn[$orderBy])) {
            $orderBy = $orderableColumn[$orderBy];
        } else {
            $orderBy = $orderableColumn['date'];
        }

        // base query with limits etc to obrain what we need
        $query = MassMessageConfig::withMessagesCount();

        // filters
        if(!empty($description)) {
            $query = $query->whereDescription($description);
        }
        if(!empty($messageContent)) {
            $query = $query->whereMessageContent($messageContent);
        }
        if(!empty($messageTitle)) {
            $query = $query->whereMessageTitle($messageTitle);
        }
        if($messageType == 'email') {
            $query = $query->whereMessageEmailType();
        } elseif($messageType == 'sms') {
            $query = $query->whereTargetUserGroups();
        }
        if($targetType == 'users') {
            $query = $query->whereTargetUsers();
        } elseif($targetType == 'usergroups') {
            $query = $query->whereTargetUserGroups();
        } elseif($targetType == 'campaigns') {
            $query = $query->whereTargetCampaigns();
        }
        if($generated === '1') {
            $query = $query->whereAlreadyGenerated();
        } elseif($generated === '0') {
            $query = $query->whereNotGenerated();
        }
        if(!empty($date)) {
            $query = $query->where('crm_mass_message_configs.date', 'like', "%{$date}%");
        }


        if(!empty($searchGlobal)) {
            $query = $query->where(function($query) use($searchGlobal) {
                $query = $query->orWhere('crm_mass_message_configs.message_content', 'like', "%{$searchGlobal}%")
                               ->orWhere('crm_mass_message_configs.message_title', 'like', "%{$searchGlobal}%")
                               ->orWhere('crm_mass_message_configs.description', 'like', "%{$searchGlobal}%");
            });
        }
        // basically the same query but no orderby/limit/select
        $totalq  = clone $query;
        $total   = count($totalq->select(DB::raw('Count(*) as total'))->lists('total'));

        // run this damm queries
        $results = $query->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->get();

        // gather to data format for smart table
        $return = array(
            'data'  => $results->toArray(),
            'total' => $total,
        );

        return $return;
    }



    public function generatePendingMassMessages()
    {
        //SlimApp::getInstance()->log->notice('CRON: Generate Messages to send - START');

        $collection = $this->getModel()->whereNotGenerated()->get();

        if(count($collection) > 0) {
        //    SlimApp::getInstance()->log->notice('CRON: Found {num} Mass Message configuration that date passed', array('num' => count($collection)));
        } else {
         //   SlimApp::getInstance()->log->notice('CRON: Nothing To Generate');
        }
        
        $collection = $collection->map(function ($item, $key) {
            return $item->generatePendingMessages();
        });

       // SlimApp::getInstance()->log->notice('CRON: Generate Messages to send - END');
    }
}
