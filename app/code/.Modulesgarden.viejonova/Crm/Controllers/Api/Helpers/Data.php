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

namespace Modulesgarden\Crm\Controllers\Api\Helpers;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Repositories\FieldStatuses;
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Models\Magento\EmailTemplates;
use Modulesgarden\Crm\Models\Campaign;
use Modulesgarden\Crm\Models\FollowupType;
use Modulesgarden\Crm\Repositories\Campaigns;
use Modulesgarden\Crm\Models\Mailbox;
use \Exception;
use \Illuminate\Database\Capsule\Manager as DB;
use \Illuminate\Support\Collection;

/**
 * Class to maintain actions for single lead instance
 */
class Data extends AbstractController
{

    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function getLeadUsefull()
    {
        try {

            // ticket departments
       //     $departments = TicketDepartment::orderred()->filterIrrelevantParams()->get()->toArray();
            // statuses
            $statusesRepo = new FieldStatuses();
            $statuses = $statusesRepo->orderBy('order', 'ASC')->get()->toArray();
            // get global email address from configuration
            $emailPaths = array (
                1 => 'trans_email/ident_general/email',
                2 => 'trans_email/ident_sales/email',
                3 => 'trans_email/ident_support/email',
                4 => 'trans_email/ident_custom1/email',
                5 => 'trans_email/ident_custom2/email',
            );
            $systemEmails = DB::table('core_config_data')->whereIn('path', $emailPaths)->get(array('value AS fullemail', 'config_id AS id'));
            // ticket departments
            $departments = Mailbox::filterIrrelevantParams()->get()->toArray();
            // get crm email templates
            $templates = EmailTemplates::forSelect()->onlyCrmType()->get()->toArray();
            // sms templates for admin
           // $smstemplates = EmailTemplates::forSelect()->onlyAdminType()->get()->toArray();
            // general templates for client
          //  $clienttemplates = EmailTemplates::forSelect()->onlyClientType()->get()->toArray();
            // admins
            $admins = Admin::filterIrrelevantParams()->get()->toArray();
            // followup types
            $followupTypes = FollowupType::activeGroups()->orderred()->get()->toArray();
            // campaigns
            $campaigns = Campaigns::obtainAllCampaignsListForAdmin($this->app->currentAdmin->user_id);

            $result = array(
                'statuses' => $statuses,
                'departments' => $departments,
                'admins' => $admins,
                'system_email' => $systemEmails,
                'followupTypes' => $followupTypes,
                'campaigns' => $campaigns->toArray(),
                'templates' => array(
                    'admin' => $templates,
                  //  'sms' => $smstemplates,
                  //  'client' => $clienttemplates,
                ),
            );

            return $this->returnData($result);
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }
    }

    public function getResourcesTableHelpers()
    {
        try {
            // statuses
            $statusesRepo = new FieldStatuses();
            $statuses = $statusesRepo->orderBy('order', 'ASC')->get()->toArray();
            $admins = Admin::filterIrrelevantParams()->get()->toArray();
            // campaigns
            $campaigns = Campaigns::obtainCampaignsForAdmin($this->app->currentAdmin->user_id);

            $result = array(
                'statuses' => $statuses,
                'admins' => $admins,
                'campaigns' => $campaigns,
            );

            return $this->returnData($result);
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }
    }

    public function getMassMessagesHelpers()
    {
        try {
            $clientGrpsCollection = new Collection(DB::table('customer_group')->
                            select(array('customer_group.customer_group_id', 'customer_group.customer_group_code as name'))->get());
            $clientgroups = $clientGrpsCollection->map(function ($item, $key) {
                return array(
                    'id' => intval($item['customer_group_id']),
                    'name' => $item['name'],
                );
            });

            $campaigns = Campaign::all()->map(function ($item, $key) {
                return array(
                    'id' => $item->id,
                    'name' => $item->name,
                );
            });

            return $this->returnData(array(
                        'clientgroups' => $clientgroups->toArray(),
                        'campaigns' => $campaigns->toArray(),
            ));
        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                        'status' => 'error',
                        'msg' => $ex->getMessage(),
            ));
        }
    }

}
