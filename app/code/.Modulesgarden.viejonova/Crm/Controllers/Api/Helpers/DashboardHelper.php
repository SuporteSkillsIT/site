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
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Models\Magento\EmailTemplates;
use Modulesgarden\Crm\Models\FollowupType;
use Modulesgarden\Crm\Repositories\Campaigns;
use \Exception;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class DashboardHelper extends AbstractController
{

    /**
     * Same but for followups subpage
     *
     * @return type
     */
    public function backgroundData()
    {

        $emailPaths = array(
            1 => 'trans_email/ident_general/email',
            2 => 'trans_email/ident_sales/email',
            3 => 'trans_email/ident_support/email',
            4 => 'trans_email/ident_custom1/email',
            5 => 'trans_email/ident_custom2/email',
        );
        $systemEmails = DB::table('core_config_data')->whereIn('path', $emailPaths)->get(array('value', 'config_id'));
        $templates = EmailTemplates::forSelect()->onlyCrmType()->get()->toArray();
        //   $smstemplates       = EmailTemplates::forSelect()->onlyAdminType()->get()->toArray();
        //   $clienttemplates    = EmailTemplates::forSelect()->onlyClientType()->get()->toArray();
        $admins = Admin::filterIrrelevantParams()->get()->toArray();
        $followupTypes = FollowupType::orderred()->get()->toArray();
        $campaigns = Campaigns::obtainCampaignsForAdmin($this->app->currentAdmin->user_id);

        return $this->returnData(array(
                    'departments' => $systemEmails,
                    'admins' => $admins,
                    'followup' => array(
                        'types' => $followupTypes,
                    ),
                    'templates' => array(
                        'admin' => $templates,
                    //   'sms'    => $smstemplates,
                    //    'client' => $clienttemplates,
                    ),
                    'campaigns' => $campaigns,
        ));

        return $this->returnData($results);
    }

}
