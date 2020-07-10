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

namespace Modulesgarden\Crm\Repositories;

use \Modulesgarden\Crm\Integration\Slim\SlimApp;
use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;
use Modulesgarden\Crm\Repositories\Fields;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\ResourceType;
use Modulesgarden\Crm\Models\Field;
use Modulesgarden\Crm\Models\FieldStatus;
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Model\ResourceModel\Customer as Client;
use Modulesgarden\Crm\Models\Validators\Common;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Repository pattern for Resource
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class Resources extends AbstractRepository implements RepositoryInterface
{

    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\Resource
     */
    function determinateModel()
    {
        return 'Modulesgarden\Crm\Models\Resource';
    }

    /**
     * Wrap create resource for Lead type
     *
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Resource
     */
    public function createNewLead($data)
    {
        return $this->createNew($data, false);
    }

    /**
     * Wrap create resource for Potential type
     *
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Resource
     */
    public function createNewPotential($data)
    {
        return $this->createNew($data, true);
    }

    /**
     * Create Resource
     *
     * @param array $data
     * @param type $isPotential
     * @return \Modulesgarden\Crm\Models\Resource
     */
    public function createNew(array $data = array(), $isPotential = false)
    {
        // parse input
        $static = array_get($data, 'static', array());
        $dynamic = array_get($data, 'dynamic', array());


        // first of all lets create new Resource
        $resource = $this->getModel();
        // blank fill with static one
        $resource->fillMainDetails($static);

        /* Contact STATUS REQUIRED */
        if (array_get($data, 'assignedStatus.id', null) != null) {
            $resource->assignStatus(FieldStatus::find(array_get($data, 'assignedStatus.id')));
        } elseif ((array_get($data, 'status_id', null) != null)) {
            $resource->assignStatus(FieldStatus::find(array_get($data, 'status_id')));
        } elseif ((array_get($data, 'static.status_id', null) != null)) {
            $resource->assignStatus(FieldStatus::find(array_get($data, 'static.status_id')));
        } else {
            throw new Exception('Invalid Contact Status');
        }

        /* Contact TYPE REQUIRED */
        if (array_get($data, 'assignedType.id', null) != null) {
            $resource->assignType(ResourceType::find(array_get($data, 'assignedType.id')));
        } elseif ((array_get($data, 'type_id', null) != null)) {
            $resource->assignType(ResourceType::find(array_get($data, 'type_id')));
        } elseif ((array_get($data, 'static.type_id', null) != null)) {
            $resource->assignType(ResourceType::find(array_get($data, 'static.type_id')));
        } else {
            throw new Exception('Invalid Contact Type');
        }


        if (array_get($data, 'assignedAdmin.user_id', null) != null) {
            $resource->fill(array('admin_id' => array_get($data, 'assignedAdmin.user_id')));
        } elseif ((array_get($data, 'admin_id', null) != null)) {
            $resource->fill(array('admin_id' => array_get($data, 'admin_id')));
        } elseif ((array_get($data, 'static.admin_id', null) != null)) {
            $resource->fill(array('admin_id' => array_get($data, 'static.admin_id')));
        } else {
            // use native admin who is alogged to perform this request
            $resource->fill(array('admin_id' => SlimApp::getInstance()->currentAdmin->user_id));
        }


        // assign client
        if (array_get($data, 'assignedClient.entity_id', null) != null) {
            $resource->fill(array('client_id' => array_get($data, 'assignedClient.entity_id')));
        } elseif ((array_get($data, 'client_id', null) != null)) {
            $resource->fill(array('client_id' => array_get($data, 'client_id')));
        } elseif ((array_get($data, 'static.client_id', null) != null)) {
            $resource->fill(array('client_id' => array_get($data, 'static.client_id')));
        }
        // assign ticket
//        if (array_get($data, 'ticket_id', null) != null) {
//            $resource->assignTicket(Ticket::find(array_get($data, 'ticket_id', null)));
//        } elseif ((array_get($data, 'static.ticket_id', null) != null)) {
//            $resource->assignTicket(Ticket::find(array_get($data, 'static.ticket_id', null)));
//        }
        // save in DB (so from now we have ID)
        $resource->fill(array('priority' => 1));
        $saved = $resource->save();

        // handle custom fields
        $fieldsRepository = new Fields();
        $fieldsErrors = $fieldsRepository->createAndAssignToResource($resource, $dynamic);

        // in a matter a fact this is compleatly skipped
        // we dont want to throw exception, lets add log message
        if (is_array($fieldsErrors) && !empty($fieldsErrors)) {
            SlimApp::getInstance()->log->error('Resource Create, custom fields errors: {errors}', array('errors' => implode(" \n", $fieldsErrors)));
        }

        // register hook :D
        // first and last, since my TODO does not include hooks for now
        // idea was to trigger in that 'pushEvent' function hook from Slim Framework Core
        // since Slim already providing this 'hooks' feature
        $resource->pushEvent('CreateFollowupOnCreate', array_get($static, 'followup', false));

        return $resource;
    }

    /**
     * Wrapper for parser table function
     * resource types - archive (with deleted_at column)
     *
     * @param type $data
     * @return array
     */
    public function parseTrashedForTable($data = array())
    {
        return $this->parseForTable($data, false, true);
    }

    /**
     * Wrapper for parser table function
     * resource type - leads
     *
     * @param type $data
     * @return array
     */
    public function parseLeadsForTable($data = array())
    {
        return $this->parseForTable($data, false);
    }

    /**
     * Wrapper for parser table function
     * resource type - potentials
     *
     * @param type $data
     * @return array
     */
    public function parsePotentialsForTable($data = array())
    {
        return $this->parseForTable($data, true);
    }

    /**
     * Main logic for this class to obtain for table
     *
     * @param array $data               params sended by smart table
     * @param bolean $queryPotentials   show potentials
     * @param bolean $showHidden        show trashed records
     * @return array                    array for smart table format later parsed to json
     */
    public function parseForTable(array $data = array(),
            $queryPotentials = false, $showHidden = false)
    {
        /**
         * Parse input
         */
        // limit
        $limit = array_get($data, 'params.pagination.number', 10);
        $ofset = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy = array_get($data, 'params.sort.predicate', false);
        $orderDesc = array_get($data, 'params.sort.reverse', true);
        $orderDesc = ($orderDesc === true) ? 'DESC' : 'ASC';
        // search
        $search = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal = array_pull($search, '$', false);

        // damm most important
        // based by this request, we will filter everything below
        $columns = array_get($data, 'columns', array());

        /**
         * start build query
         */
        $whereAND = array();
        $whereOR = array();

        // base query
        $query = DB::table('crm_resources')
                ->groupBy('crm_resources.id')
                ->leftJoin('admin_user', function($join) {
                    $join->on('crm_resources.admin_id', '=', 'admin_user.user_id');
                })
                ->leftJoin('customer_entity', function($join) {
                    $join->on('crm_resources.client_id', '=', 'customer_entity.entity_id');
                })
//                ->leftJoin('tbltickets', function($join) {
//                    $join->on('crm_resources.ticket_id', '=', 'tbltickets.id');
//                })
                ->leftJoin('crm_resources_statuses', function($join) {
            $join->on('crm_resources.status_id', '=', 'crm_resources_statuses.id');
        })
        ;

        // if show hidden, dont care if its lead or potential
        if ($showHidden === true) {
            $query = $query->whereNotNull('crm_resources.deleted_at');
        } else {
            $query = $query->whereNull('crm_resources.deleted_at');
            if ($queryPotentials === true) {
                $query = $query->where('crm_resources.is_potential', '=', 1);
            } elseif ($queryPotentials === false) {
                $query = $query->where('crm_resources.is_potential', '=', 0);
            } // this is on purpopse, since where it will be something diferent than bolean, we will display both
        }

        // standard selects
        // dont you date to change this
        $select = array();
        $select[] = 'crm_resources.id as id';
        $select[] = 'crm_resources.deleted_at';
        $select[] = 'crm_resources.is_potential as is_potential';
        $availableSelects = array(
            'id' => 'crm_resources.id',
            'name' => 'crm_resources.name',
            'status' => 'crm_resources.status_id',
            'email' => 'crm_resources.email',
            'phone' => 'crm_resources.phone',
            'priority' => 'crm_resources.priority',
            'is_potential' => 'crm_resources.is_potential',
            'created_at' => 'crm_resources.created_at',
            'updated_at' => 'crm_resources.updated_at',
            // 'ticket' => 'tbltickets.tid',
            'admin_id' => 'crm_resources.admin_id',
            'client_id' => 'crm_resources.client_id',
            'status_id' => 'crm_resources.status_id',
//          'ticket_id' => 'crm_resources.ticket_id',
            'admin' => "CONCAT_WS(' ', admin_user.firstname, admin_user.lastname)",
            'client' => "CONCAT_WS(' ', customer_entity.firstname, customer_entity.lastname)",
        );


        /**
         * Build conditions for static fields
         * inject filters by that fields, or selects for query, if we should return certain column
         */
        foreach ($availableSelects as $staticFieldName => $inject) {
            // selects
            if (!in_array($staticFieldName, $columns) && !empty($columns) && !in_array($staticFieldName, array('admin_id', 'client_id', 'status_id'))) {
                continue;
            }

            $column = sprintf('%s AS %s', $inject, $staticFieldName);
            $select[] = DB::raw($column);

            // push where conditions
            $fieldFilter = array_get($search, $staticFieldName, false);

            // if filter for this field was provided
            // build where's
            if (!empty($fieldFilter) || !empty($searchGlobal)) {
                $operand = $valuePattern = null;

                // static - text - use %like
                if (in_array($staticFieldName, array('name', 'email', 'phone'))) {
                    $operand = 'LIKE';
                    $valuePattern = '%%%s%%';
                } elseif (in_array($staticFieldName, array('status', 'priority', 'is_potential', 'id', 'is_potential', 'ticket', 'admin_id', 'client_id', 'status_id'))) {
                    $operand = '=';
                    $valuePattern = '%s';

                    // special case since priority low is also when is null
                    if ($staticFieldName == 'priority' && $fieldFilter == 1) {
                        $whereAND[] = array($inject, $operand, DB::raw("1 OR {$inject} is NULL"));
                        continue;
                    }
                } elseif (in_array($staticFieldName, array('admin', 'client'))) {
                    $operand = 'LIKE';
                    $valuePattern = '%%%s%%';
                    $inject = DB::raw($inject);
                }

                if (!empty($fieldFilter) && $operand !== null && $valuePattern !== null) {
                    $whereAND[] = array($inject, $operand, sprintf($valuePattern, $fieldFilter));
                }
                if (!empty($searchGlobal) && $operand !== null && $valuePattern !== null) {
                    $whereOR[] = array($inject, $operand, sprintf($valuePattern, $searchGlobal));
                }
            }
        }

        // obtain All possible custom fields
        // with possible options to filter
        $fields = Field::joinOptions()->get();
        // helper to parse result later
        $miltipleFields = array();

        /**
         * Build conditions for custom defined fields
         * add another selects for each field requested
         * and add filters here
         */
        $selectFields = array();
        foreach ($fields as $fieldId => $field) {
            // if this particular field is not in requested columns, dont include this
            if (!in_array("field_{$field->id}", $columns) && !empty($columns)) {
                continue;
            }

            // this will be usefulla at the end
            if ($field->isMultiple()) {
                $options = $field->options->toArray();
                $parsed = array();

                // cleanup
                if (!empty($options)) {
                    array_map(function($a) use(&$parsed) {
                        $parsed[$a['id']] = $a['value'];
                        return true;
                    }, $options);
                }

                $miltipleFields["field_{$field->id}"] = $parsed;
            }

            // push to select
            if ($field->isMultiple()) {
                $selectFields[] = sprintf("GROUP_CONCAT( CASE WHEN crm_fields_data.field_id = %s THEN crm_fields_data_options.option_id ELSE NULL END ORDER BY crm_fields_data_options.id ASC SEPARATOR ';') AS field_%s", $field->id, $field->id);
            } else {
                $selectFields[] = sprintf("MAX(CASE WHEN crm_fields_data.field_id = %s THEN crm_fields_data.data ELSE NULL END) field_%s", $field->id, $field->id);
            }

            // get filter conditions
            $fieldFilter = array_get($search, "field_{$field->id}", false);

            // based by filter build query with where's
            if (!empty($fieldFilter) || !empty($searchGlobal)) {
                $inject = $operand = $valuePattern = null;

                // static - text - use %like
                if ($field->isMultiple()) {
                    $operand = '=';
                    $valuePattern = '%s';
                    $inject = DB::raw("fields.field_{$field->id}");
                } else {
                    $operand = 'LIKE';
                    $valuePattern = "%%%s%%";
                    $inject = DB::raw("fields.field_{$field->id}");
                }

                // and add generated
                if (!empty($fieldFilter) && $operand !== null && $valuePattern !== null && $inject !== null) {
                    $whereAND[] = array($inject, $operand, sprintf($valuePattern, $fieldFilter));
                }
                if (!empty($searchGlobal) && $operand !== null && $valuePattern !== null && $inject !== null) {
                    $whereOR[] = array($inject, $operand, sprintf($valuePattern, $searchGlobal));
                }
            }
        }


        /**
         * Custom fields subquery
         *
         * since we requested custom fields, so lets join them to main query (by subquery)
         */
        if (!empty($selectFields)) {
            // to make sure it it will be selected in main query
            $select[] = DB::raw('fields.*');
            // builld fields to select
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
                        "), function($join) {
                $join->on('fields.resource_id', '=', 'crm_resources.id');
            });
        }

        /**
         * Apply Filters
         */
        // apply filters
        if (!empty($whereAND)) {
            $query = $query->where(function($query) use ($whereAND) {
                foreach ($whereAND as $push) {
                    $query->where($push[0], $push[1], $push[2]);
                }
            });
        }
        // apply global search
        if (!empty($whereOR)) {
            $query = $query->where(function($query) use ($whereOR) {
                foreach ($whereOR as $push) {
                    $query->orWhere($push[0], $push[1], $push[2]);
                }
            });
        }

        /**
         * Requested campaign!
         */
        $campaignID = array_get($data, 'campaign');
        if (Common::isPositiveUnsignedNumber($campaignID)) {
            $query = $query->leftJoin('crm_campaigns_resources', function($join) use($campaignID) {
                $join->on('crm_resources.id', '=', 'crm_campaigns_resources.resource_id')
                        ->on('crm_campaigns_resources.campaign_id', '=', DB::raw($campaignID));
            });
            $query = $query->where('crm_campaigns_resources.campaign_id', '=', $campaignID);
        } elseif ($campaignID === 0) {
            $query = $query->leftJoin('crm_campaigns_resources', function($join) {
                return $join->on('crm_resources.id', '=', 'crm_campaigns_resources.resource_id');
            });
            $query = $query->whereNull('crm_campaigns_resources.campaign_id');
        }

        /**
         * Requested admin
         */
        $adminID = array_get($data, 'admin');
        if (Common::isPositiveUnsignedNumber($adminID) && $adminID !== null) {
            $query = $query->where('crm_resources.admin_id', '=', $adminID);
        } elseif ($adminID === 0) {
            $query = $query->whereNull('crm_resources.admin_id');
        }


        // basically the same query but no orderby/limit/select
        $count = clone $query;

        // add limitations & order to obtain total count
        $total = count($count->select(DB::raw('count(crm_resources.id) as total'))->lists('total'));

        /**
         * Order by logic
         */
        // #1 - order by thing selected manually in table
        if ($orderBy !== false) {
            $query = $query->orderBy($orderBy, $orderDesc);
        }
        // #2 - priority
        if ($orderBy != 'priority') {
            $query = $query->orderBy('crm_resources.priority', 'DESC');
        }
        // #3 - status order
        if ($orderBy != 'status') {
            $query = $query->orderBy('crm_resources_statuses.order', 'ASC');
        }

        /**
         * Run prepared query
         */
        $query = $query->select($select)
                ->take($limit)
                ->offset($ofset);

//        rt($query->toSql());

        $data = $query->get();

//        rt($data);
//        die();


        /**
         * Place to parse query results in some ways
         */
        // right now there is a fix that will exchange option ID's with selected values ;) since either way we have fields in memory
        foreach ($data as $row => $rowData) {
            foreach ($rowData as $k => $v) {
                if (array_get($miltipleFields, $k, false) !== false) {
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
            'data' => $data,
            'total' => $total,
        );

        return $return;
    }

    public function parseRepositoriesForTable($data, $showHidden = false,
            $forAPI = false)
    {
        /**
         * Parse input
         */
        // limit
        $limit = array_get($data, 'params.pagination.number', 10);
        $ofset = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy = array_get($data, 'params.sort.predicate', false);
        $orderDesc = array_get($data, 'params.sort.reverse', true);
        $orderDesc = ($orderDesc === true) ? 'DESC' : 'ASC';
        // search
        $search = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal = array_pull($search, '$', false);

        // damm most important
        // based by this request, we will filter everything below
        $columns = array_get($data, 'columns', array());
        // secont damm important
        $contactTypeID = array_get($data, 'type', false);

        /**
         * start build query
         */
        $whereAND = array();
        $whereOR = array();

        // base query
        $query = DB::table('crm_resources')
                ->groupBy('crm_resources.id')
                ->leftJoin('admin_user', function($join) {
                    $join->on('crm_resources.admin_id', '=', 'admin_user.user_id');
                })
                ->leftJoin('customer_entity', function($join) {
                    $join->on('crm_resources.client_id', '=', 'customer_entity.entity_id');
                })
//                ->leftJoin('tbltickets', function($join) {
//                    $join->on('crm_resources.ticket_id', '=', 'tbltickets.id');
//                })
                ->leftJoin('crm_resources_statuses', function($join) {
            $join->on('crm_resources.status_id', '=', 'crm_resources_statuses.id');
        })
        ;

        // if show hidden, dont care if its lead or potential
        if ($showHidden === true) {
            $query = $query->whereNotNull('crm_resources.deleted_at');
        } elseif (Common::isPositiveUnsignedNumber($contactTypeID)) {
            $query = $query->whereNull('crm_resources.deleted_at')
                    ->where('crm_resources.type_id', '=', $contactTypeID);
        }

        // standard selects
        // dont you date to change this
        $select = array();
        $select[] = 'crm_resources.id as id';
        $select[] = 'crm_resources.deleted_at';
        $select[] = 'crm_resources.type_id as type_id';
        $availableSelects = array(
            'id' => 'crm_resources.id',
            'name' => 'crm_resources.name',
            'status' => 'crm_resources.status_id',
            'email' => 'crm_resources.email',
            'phone' => 'crm_resources.phone',
            'priority' => 'crm_resources.priority',
            'type_id' => 'crm_resources.type_id',
            'created_at' => 'crm_resources.created_at',
            'updated_at' => 'crm_resources.updated_at',
            //  'ticket' => 'tbltickets.tid',
            'admin_id' => 'crm_resources.admin_id',
            'client_id' => 'crm_resources.client_id',
            'status_id' => 'crm_resources.status_id',
            //  'ticket_id' => 'crm_resources.ticket_id',
            'admin' => "CONCAT_WS(' ', admin_user.firstname, admin_user.lastname)",
            'client' => "CONCAT_WS(' ', customer_entity.firstname, customer_entity.lastname)",
        );


        /**
         * Build conditions for static fields
         * inject filters by that fields, or selects for query, if we should return certain column
         */
        foreach ($availableSelects as $staticFieldName => $inject) {
            // selects
            if (!in_array($staticFieldName, $columns) && !empty($columns) && !in_array($staticFieldName, array('admin_id', 'client_id', 'status_id'))) {
                continue;
            }

            $column = sprintf('%s AS %s', $inject, $staticFieldName);
            $select[] = DB::raw($column);

            // push where conditions
            $fieldFilter = array_get($search, $staticFieldName, false);

            // if filter for this field was provided
            // build where's
            if (!empty($fieldFilter) || !empty($searchGlobal)) {
                $operand = $valuePattern = null;

                // static - text - use %like
                if (in_array($staticFieldName, array('name', 'email', 'phone'))) {
                    $operand = 'LIKE';
                    $valuePattern = '%%%s%%';
                } elseif (in_array($staticFieldName, array('status', 'priority', 'id', 'ticket', 'admin_id', 'client_id', 'status_id'))) {
                    $operand = '=';
                    $valuePattern = '%s';

                    // special case since priority low is also when is null
                    if ($staticFieldName == 'priority' && $fieldFilter == 1) {
                        $whereAND[] = array($inject, $operand, DB::raw("1 OR {$inject} is NULL"));
                        continue;
                    }
                } elseif (in_array($staticFieldName, array('admin', 'client'))) {
                    $operand = 'LIKE';
                    $valuePattern = '%%%s%%';
                    $inject = DB::raw($inject);
                }

                if (!empty($fieldFilter) && $operand !== null && $valuePattern !== null) {
                    $whereAND[] = array($inject, $operand, sprintf($valuePattern, $fieldFilter));
                }
                if (!empty($searchGlobal) && $operand !== null && $valuePattern !== null) {
                    $whereOR[] = array($inject, $operand, sprintf($valuePattern, $searchGlobal));
                }
            }
        }

        // obtain All possible custom fields
        // with possible options to filter
        $fields = Field::joinOptions()->get();
        // helper to parse result later
        $miltipleFields = array();

        /**
         * Build conditions for custom defined fields
         * add another selects for each field requested
         * and add filters here
         */
        $selectFields = array();
        foreach ($fields as $fieldId => $field) {
            // if this particular field is not in requested columns, dont include this
            if (!in_array("field_{$field->id}", $columns) && !empty($columns)) {
                continue;
            }

            // this will be usefulla at the end
            if ($field->isMultiple()) {
                $options = $field->options->toArray();
                $parsed = array();

                // cleanup
                if (!empty($options)) {
                    array_map(function($a) use(&$parsed) {
                        $parsed[$a['id']] = $a['value'];
                        return true;
                    }, $options);
                }

                $miltipleFields["field_{$field->id}"] = $parsed;
            }

            // push to select
            if ($field->isMultiple()) {
                $selectFields[] = sprintf("GROUP_CONCAT( CASE WHEN crm_fields_data.field_id = %s THEN crm_fields_data_options.option_id ELSE NULL END ORDER BY crm_fields_data_options.id ASC SEPARATOR ';') AS field_%s", $field->id, $field->id);
            } else {
                $selectFields[] = sprintf("MAX(CASE WHEN crm_fields_data.field_id = %s THEN crm_fields_data.data ELSE NULL END) field_%s", $field->id, $field->id);
            }

            // get filter conditions
            $fieldFilter = array_get($search, "field_{$field->id}", false);

            // based by filter build query with where's
            if (!empty($fieldFilter) || !empty($searchGlobal)) {
                $inject = $operand = $valuePattern = null;

                // static - text - use %like
                if ($field->isMultiple()) {
                    $operand = '=';
                    $valuePattern = '%s';
                    $inject = DB::raw("fields.field_{$field->id}");
                } else {
                    $operand = 'LIKE';
                    $valuePattern = "%%%s%%";
                    $inject = DB::raw("fields.field_{$field->id}");
                }

                // and add generated
                if (!empty($fieldFilter) && $operand !== null && $valuePattern !== null && $inject !== null) {
                    $whereAND[] = array($inject, $operand, sprintf($valuePattern, $fieldFilter));
                }
                if (!empty($searchGlobal) && $operand !== null && $valuePattern !== null && $inject !== null) {
                    $whereOR[] = array($inject, $operand, sprintf($valuePattern, $searchGlobal));
                }
            }
        }


        /**
         * Custom fields subquery
         *
         * since we requested custom fields, so lets join them to main query (by subquery)
         */
        if (!empty($selectFields)) {
            // to make sure it it will be selected in main query
            $select[] = DB::raw('fields.*');
            // builld fields to select
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
                        "), function($join) {
                $join->on('fields.resource_id', '=', 'crm_resources.id');
            });
        }

        /**
         * Apply Filters
         */
        // apply filters
        if (!empty($whereAND)) {
            $query = $query->where(function($query) use ($whereAND) {
                foreach ($whereAND as $push) {
                    $query->where($push[0], $push[1], $push[2]);
                }
            });
        }
        // apply global search
        if (!empty($whereOR)) {
            $query = $query->where(function($query) use ($whereOR) {
                foreach ($whereOR as $push) {
                    $query->orWhere($push[0], $push[1], $push[2]);
                }
            });
        }

        /**
         * Requested campaign!
         */
        $campaignID = array_get($data, 'campaign');
        if (Common::isPositiveUnsignedNumber($campaignID)) {
            $query = $query->leftJoin('crm_campaigns_resources', function($join) use($campaignID) {
                $join->on('crm_resources.id', '=', 'crm_campaigns_resources.resource_id')
                        ->on('crm_campaigns_resources.campaign_id', '=', DB::raw($campaignID));
            });
            $query = $query->where('crm_campaigns_resources.campaign_id', '=', $campaignID);
        } elseif ($campaignID === 0) {
            $query = $query->leftJoin('crm_campaigns_resources', function($join) {
                return $join->on('crm_resources.id', '=', 'crm_campaigns_resources.resource_id');
            });
            $query = $query->whereNull('crm_campaigns_resources.campaign_id');
        }

        /**
         * Requested admin
         */
        $adminID = array_get($data, 'admin');
        if (Common::isPositiveUnsignedNumber($adminID) && $adminID !== null) {
            $query = $query->where('crm_resources.admin_id', '=', $adminID);
        } elseif ($adminID === 0) {
            $query = $query->whereNull('crm_resources.admin_id');
        }


        // basically the same query but no orderby/limit/select
        $count = clone $query;

        // add limitations & order to obtain total count
        if ($forAPI !== true) {
            $total = count($count->select(DB::raw('count(crm_resources.id) as total'))->lists('total'));
        }

        /**
         * Order by logic
         */
        // #1 - order by thing selected manually in table
        if ($orderBy !== false) {
            $query = $query->orderBy($orderBy, $orderDesc);
        }
        // #2 - priority
        if ($orderBy != 'priority') {
            $query = $query->orderBy('crm_resources.priority', 'DESC');
        }
        // #3 - status order
        if ($orderBy != 'status') {
            $query = $query->orderBy('crm_resources_statuses.order', 'ASC');
        }


        // apply limits
        if ($forAPI !== true) {
            $query = $query->take($limit)->offset($ofset);
        }

        // ~rt($query->select($select)->toSql());

        /**
         * Run prepared query
         */
        $data = $query->select($select)->get();

        // rt($data);
        // die();

        /**
         * Place to parse query results in some ways
         */
        // right now there is a fix that will exchange option ID's with selected values ;) since either way we have fields in memory
        foreach ($data as $row => $rowData) {
            foreach ($rowData as $k => $v) {
                if (array_get($miltipleFields, $k, false) !== false) {
                    $ccc = array();

                    foreach (explode(';', $v) as $y) {
                        $ccc[] = array_get($miltipleFields, "{$k}.{$y}", null);
                    }
                    array_set($data, "{$row}.{$k}", implode(', ', $ccc));
                }
            }
        }

        // diferent format for API
        if ($forAPI === true) {
            return $data;
        }

        // gather to data format for smart table
        $return = array(
            'data' => $data,
            'total' => $total,
        );

        return $return;
    }

}
