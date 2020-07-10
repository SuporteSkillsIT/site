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
//use Modulesgarden\Crm\Models\Mailbox;
use Modulesgarden\Crm\Models\Magento\EmailTemplates as TemplateModel;
use Modulesgarden\Crm\Models\Field;
use \Exception;
use \Carbon\Carbon;

/**
 * Repository pattern for Mailbox
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class EmailTemplates extends AbstractRepository implements RepositoryInterface
{

    /**
     * Determinate model used by this Repository
     *
     * @return mgCRM2\Models\Mailbox
     */
    function determinateModel()
    {
        return 'Modulesgarden\Crm\Models\Magento\EmailTemplates';
    }

    /**
     * Basic things to create email template
     *
     * @param array $data
     * @return \mgCRM2\Models\EmailTemplate
     */
    public function createEmailTemplate(array $data = array())
    {
        $parsed = array(
            'template_code' => 'crm_' . array_get($data, 'name', ''),
            'template_subject' => array_get($data, 'subject', ''),
            'template_text' => array_get($data, 'message_content', ''),
            'template_type' => 2
        );

        $nameCheck = TemplateModel::where('template_code', '=', $parsed['template_code'])->count();
       // exit(var_dump($nameCheck));
        if($nameCheck > 0)
        {
            return 'Template exist';
        }
        $new = new TemplateModel($parsed);
        $new->save();

        return $new;
    }

    /**
     * Update Email Template parameters.
     *
     * @param int   $id
     * @param array $data
     * @return \mgCRM2\Models\EmailTemplate
     */
    public function updateEmailTemplate($id, array $data = array())
    {

        $emailTemplate = $this->getModel()->find($id);

        if (is_null($emailTemplate)) {
            throw new Exception('Email Template not found');
        }

        $parsed = array(
            'template_code' => array_get($data, 'type', 'crm') . '_' . array_get($data, 'name', ''),
            'template_subject' => array_get($data, 'subject', ''),
            'template_text' => array_get($data, 'message_content', ''),
        );

        $emailTemplate->fill($parsed);
        $emailTemplate->save();

        return $emailTemplate;
    }

    /**
     * Delete email template
     *
     * @param int   $id
     * @return bolean
     */
    public function deleteEmailTemplate($id)
    {
        $found = $this->getModel()->where('template_id', '=', $id)->first();

        if (is_null($found)) {
            throw new Exception(sprintf("Couldn't find Email Template #%d", $id));
        }

        return $found->delete();
    }

    /**
     * Simple Enough, obrain requested Email Template
     *
     * @param int   $id
     * @return \mgCRM2\Models\EmailTemplate
     */
    public function getEmailTemplate($id)
    {
        $emailTemplate = $this->getModel()->find($id);
        if (is_null($emailTemplate)) {
            throw new Exception('Email Template not found');
        }

        $return = $emailTemplate->toArray();
        $return['message_content'] = $return['template_text'];
        $return['name'] = substr($return['template_code'], 4);
        $return['subject'] = $return['template_subject'];

        return $return;
    }

    /**
     * List Campaigns for SmartTable
     *
     * @param array $data
     */
    public function getEmailTemplatesListTableQuery($data)
    {
        // limit
        $limit = array_get($data, 'params.pagination.number', 10);
        $ofset = array_get($data, 'params.pagination.start', 0);

        // order
        $orderBy = array_get($data, 'params.sort.predicate', 'template_id');
        $orderDesc = array_get($data, 'params.sort.reverse', true);
        $orderDesc = ($orderDesc === true) ? 'DESC' : 'ASC';

        // search
        $name = array_get($data, 'params.search.predicateObject.template_code', false);
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
        $total = $query->onlyCrmType()->count();

        // run this damm queries
        $results = $query->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->onlyCrmType()->get();

        // gather to data format for smart table
        $return = array(
            'data' => $results->toArray(),
            'total' => $total,
        );

        return $return;
    }

}
