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
use \Illuminate\Database\Query\Expression as Expression;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\Campaign;
use Modulesgarden\Crm\Models\Field;

use \Exception;
use \Carbon\Carbon;

/**
 * Repository pattern for Campaign
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class Campaigns extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return Modulesgarden\Crm\Models\Campaign
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\Campaign';
    }


    /**
     * Return campaigns list with additional parameter :D
     * personalized by logged in admin. We want to make sure that each campaign is available for certain admin
     *
     * Point is here, that Admin want to se all campaign, even id he does not have permission to see some
     * That is purpose of this
     *
     * @param type $adminID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function obtainAllCampaignsListForAdmin($adminID)
    {
        $campaigns = Campaign::withAdmins()->get();

        $campaigns->map(function ($c) use ($adminID) {
            return $c->markAvailableOrNotFor($adminID);
        });

        // im gonna leave this code here, it do exactly the same, but is not closure ;p
        // foreach ($campaigns as $c) {
        //     $c->markAvailableOrNotFor($adminID);
        // }

        return $campaigns;
    }


    /**
     * Return me a list of campaign that requested admin has access for
     *
     * @param type $adminID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function obtainCampaignsForAdmin($adminID)
    {
        $campaigns = Campaign::forAdmin($adminID)->activeForNow()->get()->toArray();

        foreach ($campaigns as &$c) {
            unset($c['filters']);
        }

        return $campaigns;
    }

    /**
     * Basic things to create campaign
     *
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Campaign
     */
    public function createCampaign(array $data = array())
    {
        $parsed = array(
            'name'          => array_get($data, 'name', ''),
            'color'         => array_get($data, 'color', '#000000'),
            'description'   => array_get($data, 'description', ''),
            'date_start'    => Carbon::parse(array_get($data, 'date_start')),
            'date_end'      => Carbon::parse(array_get($data, 'date_end')),
            'filters'       => array_get($data, 'filters', array()),
        );

        $new = new Campaign($parsed);
        $new->save();

        $adminsIDs  = array_get($data, 'admins', array());
        if(!empty($adminsIDs)) {
            $new->admins()->attach($adminsIDs);
        }

        $resourcesIDs = $this->filterResourcesForCampaign($new->filters);
        if(!empty($resourcesIDs)) {
            $new->resources()->attach($resourcesIDs);
        }

        return $new;
    }

    /**
     * Update campaign parameters.
     *
     * @notice each update will re-assign mached records based by filters
     * @param int   $id
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Campaign
     */
    public function updateCampaign($id, array $data = array())
    {

        $campaign = $this->getModel()->find($id);

        if(is_null($campaign)) {
            throw new Exception('Campaign not found');
        }


        $parsed = array(
            'name'          => array_get($data, 'name', ''),
            'color'         => array_get($data, 'color', '#000000'),
            'description'   => array_get($data, 'description', ''),
            'date_start'    => Carbon::parse(array_get($data, 'date_start')),
            'date_end'      => Carbon::parse(array_get($data, 'date_end')),
            'filters'       => array_get($data, 'filters', array()),
        );

        $campaign->fill($parsed);
        $campaign->save();

        $adminsIDs  = array_get($data, 'admins', array());
        if(!empty($adminsIDs)) {
            $campaign->admins()->sync($adminsIDs);
        }

        $resourcesIDs = $this->filterResourcesForCampaign($campaign->filters);
        if(!empty($resourcesIDs)) {
            $campaign->resources()->sync($resourcesIDs);
        }

        return $campaign;
    }


    /**
     * Delete campaign
     *
     * @param int   $id
     * @return bolean
     */
    public function deleteCampaign($id)
    {
        $found = $this->getModel()->where('id', '=', $id)->first();

        if(is_null($found)) {
            throw new Exception(sprintf("Couldn't find Campaign #%d", $id));
        }

        return $found->delete();
    }
    
    

    /**
     * Simple Enough, obrain requested Campaign
     *
     * @param int   $id
     * @return \Modulesgarden\Crm\Models\Campaign
     */
    public function getCampaign($id)
    {
        $campaign = $this->getModel()->find($id);
        $return = $campaign->toArray();

        if(is_null($campaign)) {
            throw new Exception('Campaign not found');
        }

        $return['admins'] = $campaign->admins->lists('id');

        return $return;
    }



    /**
     * Force reload assignments by filters
     *
     * @param type $id
     * @return boolean
     * @throws Exception
     */
    public function syncCampaignResourcesByFilters($id)
    {
        $campaign = $this->getModel()->find($id);

        if(is_null($campaign)) {
            throw new Exception('Campaign not found');
        }

        $resourcesIDs = $this->filterResourcesForCampaign($campaign->filters);
        if(!empty($resourcesIDs)) {
            $campaign->resources()->sync($resourcesIDs);
        }
        
        return true;
    }


    /**
     * Just wrapper
     * This function is used in subpage with create campagn, to generate table
     */
    public function ResourcesTableQueryByFilters(array $data = array(), $showHidden = false)
    {
        return $this->filterResources($data, false);
    }


    /**
     * Just wrapper
     * This function is used to obtain only ID's for resources based by filters for campaign
     */
    public function filterResourcesForCampaign($data)
    {
        $parsedData = array(
            'search' => $data
        );

        return $this->filterResources($parsedData, true);
    }


    /**
     * Parse filters from input to cleaner format that we can use in query builder
     * Also get rid of not enabled filters
     *
     * @param array
     */
    public function parseSearchByFilters($data)
    {
        // some quick closure
        $filter = function($array) {
            $return = array();
            foreach ($array as $sName => $sVal) {
                if(array_get($sVal, 'enabled', false) == true) {
                    $sName = (is_numeric($sName)) ? "field_{$sName}" : $sName;
                    $return[$sName] = isset($sVal['value']) ? $sVal['value'] : '';
                }
            }
            return $return;
        };

        $return = $filter(array_get($data, 'static', array())) + $filter(array_get($data, 'dynamic', array()));
        $return = array_filter($return);
        
        return $return;
    }

    
    /**
     * Based by Campaign filters, get me result of mached resources
     * Kind of similar to query for smart table, in resources repository, but there are diferences
     * compatible with campaign filters stored in DB
     *
     * @param array  $data       params sended by smart table
     * @param bolean $onlyIDs    skip all additional parameters, return me plain array with mached ID's (skip pagination & fields, but mach filters)
     * @return array
     */
    public function filterResources(array $data = array(), $onlyIDs = false)
    {
        /**
         *
         * Parse input
         */

        // limit - disabled in this type
        $limit      = array_get($data, 'params.pagination.number', 10);
        $ofset      = array_get($data, 'params.pagination.start', 0);
        // order - disabled
        $orderBy    = array_get($data, 'params.sort.predicate', false);
        $orderDesc  = array_get($data, 'params.sort.reverse', true);
        $orderDesc  = ($orderDesc === true) ? 'DESC' : 'ASC';
        // search
        $searchRaw         = array_get($data, 'search', false);


        // parse search array filters by enabled, etc, in order to easy access later
        $search = array();
        if(is_array($searchRaw) && !empty($searchRaw)) {
            $search = $this->parseSearchByFilters($searchRaw);
        }


        /**
         *
         * start build query
         */
        $whereAND = array();

        //base query
        $query = DB::table('crm_resources')
                    ->groupBy('crm_resources.id')
                    ->whereNull('crm_resources.deleted_at')
                    ->leftJoin('crm_resources_statuses', function($join) {
                        $join->on('crm_resources.status_id', '=', 'crm_resources_statuses.id');
                    })
        ;



        // standar selects
        $select = array();
        $select[]   = DB::raw('fields.*');
        $select[]   = 'crm_resources.id as id';
        $select[]   = 'crm_resources.deleted_at';
        $select[]   = 'crm_resources.type_id as type_id';
        $availableSelects = array(
            'id'            => 'crm_resources.id',
            'name'          => 'crm_resources.name',
            'status'        => 'crm_resources.status_id',
            'email'         => 'crm_resources.email',
            'phone'         => 'crm_resources.phone',
            'priority'      => 'crm_resources.priority',
            'type_id'       => 'crm_resources.type_id',
            'created_at'    => 'crm_resources.created_at',
            'updated_at'    => 'crm_resources.updated_at',
            'admin_id'      => 'crm_resources.admin_id',
            'client_id'     => 'crm_resources.client_id',
            'status_id'     => 'crm_resources.status_id',
        );

        // play with static fields
        foreach ($availableSelects as $staticFieldName => $inject)
        {

            $column     = sprintf('%s AS %s', $inject, $staticFieldName);
            $select[]   = DB::raw($column);


            // push where conditions
            $fieldFilter = array_get($search, $staticFieldName, false);
            if(!empty($fieldFilter))
            {
                $operand    = $valuePattern = null;

                // static - text - use %like
                if( in_array($staticFieldName, array('name', 'email', 'phone')) )
                {
                    $whereAND[]     = array($inject, 'LIKE', sprintf('%%%s%%', $fieldFilter));
                }
                elseif(in_array($staticFieldName, array('status', 'priority', 'type_id', 'status_id')) )
                {
                    if(is_array($fieldFilter))
                    {
                        $tmp        = array();
                        foreach ($fieldFilter as $idek) {
                            // special case since priority low is also when is null
                            if($idek == 1 && $staticFieldName == 'priority') {
                                $tmp[] = "({$inject} is NULL OR {$inject} = '{$idek}' )";
                                continue;
                            }
                            $tmp[] = "({$inject} = '{$idek}')";
                        }

                        if(!empty($tmp)) {
                            $whereAND[]     = DB::raw('(' . implode(' OR ', $tmp) . ')');
                        }
                    } else {
                        $whereAND[]     = array($inject, '=', sprintf('%s', $fieldFilter));
                    }
                }

                if( !empty($fieldFilter) && $operand !== null && $valuePattern !== null ) {
                    $whereAND[] = array($inject, $operand, sprintf($valuePattern, $fieldFilter));
                }
            }
        }
        $select[]   = DB::raw('fields.*');


        $fields         = Field::joinOptions()->get();
        $miltipleFields = array();


        /**
         *
         * add another selects for each field
         * and add filters here
         */
        $selectFields = array();
        foreach ($fields as $fieldId => $field)
        {
            // this will be usefulla at the end
            if( $field->isMultiple() )
            {
                $options    = $field->options->toArray();
                $parsed     = array();
                // cleanup
                if( ! empty($options) ) {
                    array_map(function($a) use(&$parsed) {
                        $parsed[$a['id']] = $a['value'];
                        return true;
                    }, $options);
                }

                $miltipleFields["field_{$field->id}"] = $parsed;

            }

            // push to select
            if($field->isMultiple()) {
                $selectFields[] = sprintf("GROUP_CONCAT( CASE WHEN crm_fields_data.field_id = %s THEN crm_fields_data_options.option_id ELSE NULL END ORDER BY crm_fields_data_options.id ASC SEPARATOR ';') AS field_%s", $field->id, $field->id);
            } else {
                $selectFields[] = sprintf("MAX(CASE WHEN crm_fields_data.field_id = %s THEN crm_fields_data.data ELSE NULL END) field_%s", $field->id, $field->id);
            }


            // push where conditions
            $fieldFilter = array_get($search, "field_{$field->id}", false);

            if(!empty($fieldFilter))
            {
                $inject = $operand = $valuePattern = null;

                // static - text - use %like
                if( $field->isMultiple() )
                {
                    $operand        = '=';
                    $valuePattern   = '%s';
                    $inject         = DB::raw("fields.field_{$field->id}");

                    if(is_array($fieldFilter))
                    {
                        $tmp = array();
                        foreach ($fieldFilter as $idek) {
                            $tmp[] = "({$inject} = '{$idek}')";
                        }

                        if(!empty($tmp)) {
                            $whereAND[]     = DB::raw('(' . implode(' OR ', $tmp) . ')');
                        }

                    } else {
                        $whereAND[]     = array($inject, '=', sprintf('%s', $fieldFilter));
                    }

                }
                else
                {
                    $operand        = 'LIKE';
                    $valuePattern   = "%%%s%%";
                    $inject         = DB::raw("fields.field_{$field->id}");

                    if( !empty($fieldFilter) && $operand !== null && $valuePattern !== null && $inject !== null ) {
                        $whereAND[] = array($inject, $operand, sprintf($valuePattern, $fieldFilter));
                    }
                }


            }
        }

        // inject custom fields that are requested
        // if we need to include custom fields
        if(!empty($selectFields))
        {
            $selectFieldsQuery = implode(", \n", $selectFields);
            $query = $query->leftJoin(DB::raw("
                        (
                            SELECT
                            `crm_fields_data`.`resource_id`,

                            {$selectFieldsQuery}

                            FROM `crm_fields_data`
                            left join `crm_fields_data_options` on `crm_fields_data`.`id` = `crm_fields_data_options`.`field_data_id`

                            group by `crm_fields_data`.`resource_id`
                        ) AS fields
                        "), function($join)
                    {
                        $join->on('fields.resource_id', '=', 'crm_resources.id');
                    });
        }

        // apply filters
        if( !empty($whereAND) )
        {
            $query = $query->where(function($query) use ($whereAND)
            {
                foreach ($whereAND as $push) 
                {
                    if(is_array($push)) {
                        $query->where($push[0], $push[1], $push[2]);
                    } elseif($push instanceof Expression) {
                        $query->whereRaw($push);
                    }

                }
            });
        }

        
        // we want to get only ID's of resources that mach
        if ( $onlyIDs === true ) 
        {
            // run prepared query
            return $query->select(array('crm_resources.id'))->lists('id');
            
        } else {
            
            // basically the same query but no orderby/limit/select
            $count = clone $query;

            // add limitations & order to obtain total count
            $total = count($count->select(DB::raw('count(crm_resources.id) as total'))->lists('total'));


            // run prepared query
            $query = $query->select($select)->take($limit)->offset($ofset);

            $data = $query->get();

            // right now there is a fix that will exchange option ID's with selected values ;) since either way we have fields in memory
            // at this point it is not relevant, since this will be for campaigns
            foreach($data as $row => $rowData)
            {
                foreach($rowData as $k => $v)
                {
                    if(array_get($miltipleFields, $k, false) !== false) {
                        $ccc = array();
                        foreach (explode(';', $v) as $y) {
                            $ccc[] = array_get($miltipleFields, "{$k}.{$y}", null);
                        }
                        array_set($data, "{$row}.{$k}", implode(', ', $ccc));
                    }

                }
            }

            // gather to data format for smart table
            $return = array(
                'data'  => $data,
                'total' => $total,
            );

            return $return;
        }
    }


    /**
     * List Campaigns for SmartTable
     *
     * @param array $data
     */
    public function getCampaignListTableQuery($data)
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
        $query = $this->getModel();

        if(!empty($name)) {
            $query = $query->whereName($name);
        }
        if(!empty($description)) {
            $query = $query->whereDescription($description);
        }
        if(!empty($searchGlobal)) {
            $query = $query->whereNameOrDescription($searchGlobal);
        }
        // basically the same query but no orderby/limit/select
        $total   = $query->count();

        // run this damm queries
        $results = $query->withResourcesCount()->withAdmins()->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->get();

        // gather to data format for smart table
        $return = array(
            'data'  => $results->toArray(),
            'total' => $total,
        );

        return $return;
    }


    /**
     * Simple enough, obtain list of all campaigns for API request
     *
     * @return array
     */
    public function getCampaignsForAPI()
    {
        return $this->getModel()->withResourcesCount()->withAdmins()->get()->toArray();
    }
}
