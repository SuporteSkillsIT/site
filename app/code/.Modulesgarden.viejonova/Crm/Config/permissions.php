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




/**
 * Define navigation items
 * This file should be encoded
 */


return array(
    array
    (
        'rule'       => 'resources',
        'name'      => 'Leads/Potentials',

        'children'  => array(
            array(
                'rule' => 'create_lead',
                'name' => 'Create Lead',
            ),
            array(
                'rule' => 'convert',
                'name' => 'Convert Between Types (Lead/Potential)',
            ),
            array(
                'rule' => 'trash',
                'name' => 'Move To Archive',
            ),
            array(
                'rule' => 'untrash',
                'name' => 'Restore From Archive',
            ),
            array(
                'rule' => 'change_priority',
                'name' => 'Can Change Priority',
            ),
            array(
                'rule' => 'change_status',
                'name' => 'Can Change Status',
            ),
            array(
                'rule' => 'assign_admin',
                'name' => 'Assign/Unassign Administrator',
            ),
            array(
                'rule' => 'assign_client',
                'name' => 'Assign/Unassign Client',
            ),
            array(
                // troublesome
                'rule' => 'not_mine',
                'name' => 'View Details Of Leads Not Assigned To You',
            ),
            array(
                'rule' => 'allow_email',
                'name' => 'Send Email To Lead/Potential',
            ),
            array(
                'rule' => 'allow_notes',
                'name' => 'Add Note For Lead/Potential',
            ),
            array(
                'rule' => 'create_followup',
                'name' => 'Create Follow-up (with reminders) For Lead/Potential',
            ),
            array(
                'rule' => 'view_files',
                'name' => 'View Lead/Potential Files',
            ),
            array(
                'rule' => 'view_logs',
                'name' => 'View Logs Of Lead/Potential',
            ),
        ),
    ),
    array
    (
        'rule'       => 'settings',
        'name'      => 'Settings',

        'children'  => array(
            array(
                'rule'      => 'campaigns',
                'name'      => 'Access To Campaigns Management',
            ),
            array(
                'rule'      => 'notifications',
                'name'      => 'Access To Notifications Management',
            ),
            array(
                'rule'       => 'view_general',
                'name'      => 'View Global Settings',
            ),
            array(
                'rule'       => 'manage_general',
                'name'      => 'Manage Global Settings',
            ),
            array(
                'rule'       => 'view_cron',
                'name'      => 'View Cron',
            ),
            array(
                'rule'       => 'manage_followups',
                'name'      => 'Manage Follow-up Types Settings',
            ),
            array(
                'rule'       => 'mailbox',
                'name'      => 'Manage Outgoing Mailbox Configuration',
            ),
            array(
                'rule'       => 'emailtemplates',
                'name'      => 'Manage Email Templates',
            ),
            array(
                'rule'       => 'manage_fields',
                'name'      => 'Manage Custom Fields',
            ),
            array(
                'rule'       => 'manage_statuses',
                'name'      => 'Manage Statuses',
            ),
            array(
                'rule'       => 'manage_types',
                'name'      => 'Manage Contact Types',
            ),
            array(
                'rule'      => 'manage_massmessage',
                'name'      => 'Access To Mass Messages',
            ),
            array(
                'rule'      => 'importexport',
                'name'      => 'Grant Access To Import/Export Feature For Contacts.',
            ),
        ),
    ),
    array
    (
        'rule'      => 'calendar',
        'name'      => 'Calendar',
        
        'children'  => array(
            array(
                'rule'      => 'view',
                'name'      => 'Access To Calendar Page',
            ),
            array(
                'rule'      => 'other_followups',
                'name'      => 'View Other Administrators Follow-ups',
            ),
            array(
                'rule'       => 'other_reschedue',
                'name'      => 'Reschedule Other Administrators Follow-ups',
            ),
            array(
                'rule'       => 'other_delete',
                'name'      => 'Delete Other Administrators Follow-ups',
            ),
        ),
    ),
    array
    (
        'rule'      => 'statistics',
        'name'      => 'Statistics',

        'children'  => array(
            array(
                'rule'      => 'other_admins',
                'name'      => 'View Statistics Related To Other Administrators',
            ),
            array(
                'rule'      => 'allow_global',
                'name'      => 'View Global Statistics',
            ),
        ),
    ),
    array
    (
        'rule'      => 'other',
        'name'      => 'Other',

        'children'  => array(
            array(
                'rule'      => 'preview_dashboard',
                'name'      => 'View Dashboard of Other Administrators',
            ),
        ),
    ),
);
