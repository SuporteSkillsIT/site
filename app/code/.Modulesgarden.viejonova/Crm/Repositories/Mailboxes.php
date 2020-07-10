<?php

/* * *************************************************************************************
 *
 *
 *                  ██████╗██████╗ ███╗   ███╗         Customer
 *                 ██╔════╝██╔══██╗████╗ ████║         Relations
 *                 ██║     ██████╔╝██╔████╔██║         Manager
 *                 ██║     ██╔══██╗██║╚██╔╝██║
 *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For WHMCS
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

use \Illuminate\Database\Capsule\Manager as DB;
use \Illuminate\Database\Query\Expression as Expression;
use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;
use Modulesgarden\Crm\Models\Mailbox;
use \Exception;
use \Carbon\Carbon;

/**
 * Repository pattern for Mailbox
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class Mailboxes extends AbstractRepository implements RepositoryInterface
{

    /**
     * Determinate model used by this Repository
     *
     * @return Modulesgarden\Crm\Models\Mailbox
     */
    function determinateModel()
    {
        return 'Modulesgarden\Crm\Models\Mailbox';
    }

    /**
     * Basic things to create mailbox
     *
     * @param array $data
     * @return \Modulesgarden\Crm\Models\Mailbox
     */
    public function createMailbox(array $data = array())
    {
        $parsed = array(
            'name' => array_get($data, 'name', ''),
            'description' => array_get($data, 'description', ''),
            'email' => array_get($data, 'email', ''),
            'SMTPHost' => array_get($data, 'server', ''),
            'SMTPUsername' => array_get($data, 'username', ''),
            'SMTPPassword' => base64_encode(array_get($data, 'password', '')),
            'SMTPSSL' => array_get($data, 'encryption', '0'),
            'SMTPPort' => array_get($data, 'port', ''),
            'MailEncoding' => array_get($data, 'mailencoding', '0')
        );

        $new = new Mailbox($parsed);
        $new->save();

        return $new;
    }

    /**
     * Update mailbox parameters.
     *
     * @param int   $id
     * @param array $data
     * @return \mgCRM2\Models\Mailbox
     */
    public function updateMailbox($id, array $data = array())
    {

        $mailbox = $this->getModel()->find($id);

        if (is_null($mailbox)) {
            throw new Exception('Mailbox not found');
        }

        $parsed = array(
            'name' => array_get($data, 'name', ''),
            'description' => array_get($data, 'description', ''),
            'email' => array_get($data, 'email', ''),
            'SMTPHost' => array_get($data, 'SMTPHost', ''),
            'SMTPUsername' => array_get($data, 'SMTPUsername', ''),
            'SMTPPassword' => base64_encode(array_get($data, 'password', '')),
            'SMTPSSL' => array_get($data, 'SMTPSSL', '0'),
            'SMTPPort' => array_get($data, 'SMTPPort', ''),
            'MailEncoding' => array_get($data, 'MailEncoding', '0')
        );

        $mailbox->fill($parsed);
        $mailbox->save();

        return $mailbox;
    }

    /**
     * Delete campaign
     *
     * @param int   $id
     * @return bolean
     */
    public function deleteMailbox($id)
    {
        $found = $this->getModel()->where('id', '=', $id)->first();

        if (is_null($found)) {
            throw new Exception(sprintf("Couldn't find Mailbox #%d", $id));
        }

        return $found->delete();
    }

    /**
     * Simple Enough, obrain requested Mailbox
     *
     * @param int   $id
     * @return \mgCRM2\Models\Mailbox
     */
    public function getMailbox($id)
    {
        $mailbox = $this->getModel()->find($id);
        if (is_null($mailbox)) {
            throw new Exception('Mailbox not found');
        }

        $return = $mailbox->toArray();
//        if(!empty($return['port'])) {
//            $return['port'] = intval($return['port']);
//        }

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

        if (is_null($campaign)) {
            throw new Exception('Campaign not found');
        }

        $resourcesIDs = $this->filterResourcesForCampaign($campaign->filters);
        if (!empty($resourcesIDs)) {
            $campaign->resources()->sync($resourcesIDs);
        }

        return true;
    }

    /**
     * Just wrapper
     * This function is used in subpage with create campagn, to generate table
     */
    public function ResourcesTableQueryByFilters(array $data = array(),
            $showHidden = false)
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
                if (array_get($sVal, 'enabled', false) == true) {
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
     * List Campaigns for SmartTable
     *
     * @param array $data
     */
    public function getMailboxListTableQuery($data)
    {
        // limit
        $limit = array_get($data, 'params.pagination.number', 10);
        $ofset = array_get($data, 'params.pagination.start', 0);

        // order
        $orderBy = array_get($data, 'params.sort.predicate', 'id');
        $orderDesc = array_get($data, 'params.sort.reverse', true);
        $orderDesc = ($orderDesc === true) ? 'DESC' : 'ASC';

        // search
        $name = array_get($data, 'params.search.predicateObject.name', false);
        $description = array_get($data, 'params.search.predicateObject.description', false);

        // global search
        $search = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal = array_pull($search, '$', false);

        // base query with limits etc to obrain what we need
        $query = $this->getModel();

        if (!empty($name)) {
            $query = $query->whereName($name);
        }
        if (!empty($description)) {
            $query = $query->whereDescription($description);
        }
        if (!empty($searchGlobal)) {
            $query = $query->searchGlobal($searchGlobal);
        }
        // basically the same query but no orderby/limit/select
        $total = $query->count();

        // run this damm queries
//        $results = $query->withResourcesCount()->withAdmins()->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->get();
        $results = $query->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->get();

        // gather to data format for smart table
        $return = array(
            'data' => $results->toArray(),
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
