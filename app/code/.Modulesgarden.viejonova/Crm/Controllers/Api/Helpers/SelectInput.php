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


namespace Modulesgarden\Crm\Controllers\Api\Helpers;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Models\Magento\EmailTemplates;
use Modulesgarden\Crm\Models\FollowupType;
use Modulesgarden\Crm\Models\Mailbox;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class SelectInput extends AbstractController
{
    protected $limit = 10;


    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function findClients()
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            $searched = array_get($requestData, 'query', '');

            $results = DB::table('customer_entity')
                        ->select('entity_id', DB::raw('CONCAT(firstname, " ", lastname) AS name'))
                        ->where(DB::raw('CONCAT(firstname, " ", lastname) '), 'like', "%{$searched}%")
                        ->orWhere('entity_id', '=', $searched)
                        ->limit($this->limit)
                        ->orderBy('entity_id')
                        ->get();

            return $this->returnData(array(
                'status' => 'success',
                'results'=> $results,
            ));

        } catch (Exception $ex) {

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($results);
    }


//    /**
//     * Handle search for ticket tor dynamic input
//     *
//     * @return array
//     */
//    public function findTicket()
//    {
//        try {
//
//            $requestData = json_decode($this->app->request->getBody(), true);
//            $searched = array_get($requestData, 'query', '');
//
//            $results = DB::table('tbltickets')
//                        ->select('id', 'title', 'tid')
//                        ->where('tid', 'like', "%{$searched}%")
//                        ->orWhere('id', '=', $searched)
//                        ->orWhere('title', 'like', "%{$searched}%")
//                        ->limit($this->limit)
//                        ->orderBy('id')
//                        ->get();
//
//            return $this->returnData(array(
//                'status' => 'success',
//                'results'=> $results,
//            ));
//
//        } catch (Exception $ex) {
//
//            $this->app->response->setStatus(409);
//            return $this->returnData(array(
//                'status' => 'error',
//                'msg'    => $ex->getMessage(),
//            ));
//        }
//
//
//        return $this->returnData($results);
//    }

    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function findAdminsToReassign()
    {
        try {

            $admins = Admin::select(array(
                    'user_id',
                    'username',
                    'firstname',
                    'lastname',
                    'email',
                    DB::raw("CONCAT(firstname, ' ', lastname) as name")
                ))
              //  ->whereIn('roleid', $this->app->acl->getAssignedAccessRoles())
                ->where('is_active', '=', 1)
                ->get();

            return $this->returnData($admins->ToArray());

        } catch (Exception $ex) {

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($results);
    }

    /**
     * Gather and return some basic data for lead summary
     * usefull for forms etc
     *
     * @return type
     */
    public function backgroundFormData()
    {
        try {

            $emailPaths = array (
                1 => 'trans_email/ident_general/email',
                2 => 'trans_email/ident_sales/email',
                3 => 'trans_email/ident_support/email',
                4 => 'trans_email/ident_custom1/email',
                5 => 'trans_email/ident_custom2/email',
            );
            $systemEmails = DB::table('core_config_data')->whereIn('path', $emailPaths)->get(array('value', 'config_id'));
            $templates   = EmailTemplates::forSelect()->onlyCrmType()->get();
            $departments = Mailbox::filterIrrelevantParams()->get();

            return $this->returnData(array(
                'departments'  => $departments,
                'templates'    => $templates->toArray(),
                'system_email' => $systemEmails,
            ));

        } catch (Exception $ex) {

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($results);

    }

    /**
     * Same but for followups subpage
     *
     * @return type
     */
    public function backgroundForFollowups()
    {
        try {

           // $departments        = TicketDepartment::orderred()->filterIrrelevantParams()->get()->toArray();
            $templates          = EmailTemplates::forSelect()->onlyCrmType()->get()->toArray();
           // $smstemplates       = EmailTemplates::forSelect()->onlyAdminType()->get()->toArray();
           // $clienttemplates    = EmailTemplates::forSelect()->onlyClientType()->get()->toArray();
            $admins             = Admin::filterIrrelevantParams()->get()->toArray();
            $followupTypes      = FollowupType::activeGroups()->orderred()->get()->toArray();

            return $this->returnData(array(
             //   'departments' => $departments,
                'admins'      => $admins,
                'followup'    => array(
                    'types' => $followupTypes,
                ),
                'templates'   => array(
                    'admin'  => $templates,
//                    'sms'    => $smstemplates,
//                    'client' => $clienttemplates,
                ),
            ));

        } catch (Exception $ex) {

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($results);

    }

}
