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

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;
use Modulesgarden\Crm\Repositories\FieldDatas;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\EmailLog;
use Modulesgarden\Crm\Models\Mailbox;
use Modulesgarden\Crm\Models\Validators\Common;
use Modulesgarden\Crm\Services\Mailer;
use \Exception;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Just container for Email Logs
 * as repository pattern
 */
class EmailLogs extends AbstractRepository implements RepositoryInterface
{

    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\EmailLog
     */
    function determinateModel()
    {
        return 'Modulesgarden\Crm\Models\EmailLog';
    }

    /**
     * Handle Smart Table requests with filters
     *
     * @param type  $resourceID related resource
     * @param array $data       params sended by smart table
     * @return array            array for smart table format later parsed to json
     */
    public function parseForTable($resourceID, array $data = array())
    {
        // limit
        $limit = array_get($data, 'params.pagination.number', 10);
        $ofset = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy = array_get($data, 'params.sort.predicate', 'date');
        $orderDesc = array_get($data, 'params.sort.reverse', true);
        $orderDesc = ($orderDesc === true) ? 'DESC' : 'ASC';
        // search
        $search = array_get($data, 'params.search.predicateObject.message', false);

        // global search
        $search = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal = array_pull($search, '$', false);
        // search by column
        $searchColumn = array(
            'date' => array_get($data, 'params.search.predicateObject.date', false),
            'to' => array_get($data, 'params.search.predicateObject.to', false),
            'followup_id' => array_get($data, 'params.search.predicateObject.followup_id', false),
            'reminder_id' => array_get($data, 'params.search.predicateObject.reminder_id', false),
            'subject' => array_get($data, 'params.search.predicateObject.subject', false),
            'cc' => array_get($data, 'params.search.predicateObject.cc', false),
            'attachments' => array_get($data, 'params.search.predicateObject.attachments', false),
        );


        // base query with limits etc to obrain what we need
        $query = $this->getModel()
                ->withResource($resourceID)
                ->withFollowup();

        // search global
        if (!empty($searchGlobal) && $searchGlobal !== false) {
            $query = $query->withMessage($searchGlobal);
        }

        // search
        foreach ($searchColumn as $column => $val) {
            if (!empty($val) && $val !== false) {
                $query = $query->where($column, 'LIKE', "%{$val}%");
            }
        }

        // basically the same query but no orderby/limit/select
        $total = $query->count();

        // run this damm queries
        $results = $query->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->get();

        // gather to data format for smart table
        $return = array(
            'data' => $results->toArray(),
            'total' => $total,
        );

        return $return;
    }

    /**
     * Handle send raw email from form
     *
     * @param type  $resourceID
     * @param array $data
     * @param array $files
     * @return type
     * @throws Exception
     */
    public function sendRawEmail($resourceID, array $data = array(),
            array $files = array())
    {
        // have to be filled
        $from = array_get($data, 'from', null);
        $to = array_get($data, 'to', null);

        // conditionaly
        $template = array_get($data, 'template', null);
        $subject = array_get($data, 'subject', null);
        $content = array_get($data, 'content', null);

        // validate target email
        if (!Common::isValidEmail($to)) {
            throw new Exception(sprintf('Email (%s) is not valid', $to));
        }
        if (is_numeric($from)) {
            $emailPaths = array(
                1 => 'trans_email/ident_general/email',
                2 => 'trans_email/ident_sales/email',
                3 => 'trans_email/ident_support/email',
                4 => 'trans_email/ident_custom1/email',
                5 => 'trans_email/ident_custom2/email',
            );
            DB::enableQueryLog();
            $email = DB::table('core_config_data')->where('config_id', '=', $from)->whereIn('path', $emailPaths)->take(1)->first(array('value'));
            if (is_null($email)) {
                throw new Exception('invalid system email');
            }
            $smtp = false;
            $from = $email['value'];
        } else {
            $id = str_replace('#', '', $from);
            $email = Mailbox::find($id)->getAttribute('email');
            if (is_null($email)) {
                throw new Exception('invalid system email');
            }
            $smtp = true;
            $from = $email;
        }
        // compose smarty variables
        $smartyVariables = array();
        $customFieldRepo = new FieldDatas();
        $resource = Resource::findOrFail($resourceID);
        $fieldsData = $customFieldRepo->getOnlyFieldsForResourceSummary($resource->id);

        // set this up
        array_set($smartyVariables, 'fields', $fieldsData);
        array_set($smartyVariables, 'resource', $resource->load('status', 'client')->toArray());


        // send email based by template
        if (Common::isPositiveNumber($template) && $template != 'false') {
            // its time to send
            $result = Mailer::getInstance($from)->sentRawEmailFromTemplate($from, $to, $template, $smartyVariables, array(
                'resource_id' => $resource->id,
                'smtp' => $smtp
            ));
        }
        // send email based by plain form data
        else {
            // its time to send
            $result = Mailer::getInstance($from)->sentEmailFromRawData($from, $to, $subject, $content, $files, $smartyVariables, array(
                'resource_id' => $resource->id,
                'smtp' => $smtp
            ));
        }

        return $result;
    }

}
