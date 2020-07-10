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



/**
 * Definition of navigation bar
 *
 * This file should be encoded
 */
return array(
    'createlead' => array
        (
        'translate' => 'navigation.createlead',
        'icon' => '',
        'icon' => 'fa fa-plus-circle',
        'sref' => 'contacts.create',
        'color' => '#97DB37',
        'acl' => 'resources.create_lead',
    ),
    'dashboard' => array
        (
        'translate' => 'navigation.dashboard',
        'icon' => 'icon-bar-chart',
        'sref' => 'dashboard',
    ),
    'dynamicTypes' => true,
    'calendar' => array
        (
        'translate' => 'navigation.calendar',
        'icon' => 'icon-calendar',
        'sref' => 'calendar',
        'acl' => 'calendar.view',
    ),
    'campaigns' => array
        (
        'translate' => 'navigation.campaigns',
        'icon' => 'icon-flag',
        'sref' => 'campaigns.list',
        'acl' => 'settings.campaigns',
    ),
    'dynamicTypesSubmenu' => array
        (
        'display' => true,
        'icon' => 'icon-list',
        'translate' => 'navigation.dynamicTypesSubmenu',
    ),
    'utils' => array
        (
        'translate' => 'navigation.utils.utils',
        'icon' => 'icon-direction',
        'submenu' => array
            (
            'statistics' => array
                (
                'translate' => 'navigation.utils.statistics',
                'icon' => 'icon-graph',
                'sref' => 'utils.statistics',
            ),
            'notifications' => array
                (
                'translate' => 'navigation.utils.notifications',
                'icon' => 'icon-bell',
                'sref' => 'utils.notifications.list',
                'acl' => 'settings.notifications',
            ),
            'massmessage' => array
                (
                'translate' => 'navigation.utils.massmessage',
                'icon' => 'icon-envelope-letter',
                'sref' => 'utils.massmessage.list',
                'acl' => 'settings.massmessage',
            ),
            'archive' => array
                (
                'translate' => 'navigation.utils.archive',
                'icon' => 'icon-trash',
                'sref' => 'contacts.archive',
            ),
        ),
    ),
    'settings' => array
        (
        'translate' => 'navigation.settings.settings',
        'icon' => 'icon-settings',
        'submenu' => array
            (
            'general' => array
                (
                'translate' => 'navigation.settings.general',
                'icon' => 'icon-settings',
                'sref' => 'settings.general.overview',
                'acl' => 'settings.view_general',
            ),
            'personal' => array
                (
                'translate' => 'navigation.settings.personal',
                'icon' => 'icon-user',
                'sref' => 'settings.personal.personal',
            ),
            'mailbox' => array
                (
                'translate' => 'navigation.settings.mailbox',
                'icon' => 'icon-envelope-letter',
                'sref' => 'settings.mailbox.list',
                'acl' => 'settings.mailbox',
            ),
            'emailtemplates' => array
                (
                'translate' => 'navigation.settings.emailtemplates',
                'icon' => 'icon-list',
                'sref' => 'settings.emailtemplates.list',
                'acl' => 'settings.emailtemplates',
            ),
            'fields' => array
                (
                'translate' => 'navigation.settings.fields',
                'icon' => 'fa fa-tags',
                'sref' => 'settings.fields.list',
                'acl' => 'settings.manage_fields',
            ),
            'types' => array
                (
                'translate' => 'navigation.settings.types',
                'icon' => 'icon-layers',
                'sref' => 'settings.types',
                'acl' => 'settings.manage_types',
            ),
            'permissions' => array
                (
                'translate' => 'navigation.settings.permissions',
                'icon' => 'icon-lock',
                'sref' => 'settings.permissions',
                'acl' => 'fullAccess' // this is just non existing acl rule since we want block this page for anyone who isnt full admin
            ),
            'importexport' => array
                (
                'translate' => 'navigation.settings.importexport',
                'icon' => 'fa fa-exchange',
                'sref' => 'settings.importexport.export',
                'acl' => 'settings.importexport',
            ),
            'migrator' => array
                (
                'translate' => 'navigation.settings.migrator',
                'icon' => 'fa fa-flag-checkered',
                'sref' => 'settings.migrator',
                'acl' => 'other.access_migrator' // user have to have that permision to enter
            ),
        ),
    ),
//    'frontend' => array
//    (
//        'translate' => 'navigation.frontend.frontend',
//        'icon'      => 'icon-notebook',
//        'submenu'   => array
//        (
//            'typography' => array
//            (
//                'translate' => 'navigation.frontend.typography',
//                'icon'      => 'fa fa-font',
//                'sref'   => 'typography',
//            ),
//            'panels' => array
//            (
//                'translate' => 'navigation.frontend.panels',
//                'icon'      => 'fa fa-columns',
//                'sref'   => 'panels',
//            ),
//            'buttons' => array
//            (
//                'translate' => 'navigation.frontend.buttons',
//                'icon'      => 'fa fa-delicious',
//                'sref'   => 'buttons',
//            ),
//            'icons' => array
//            (
//                'translate' => 'navigation.frontend.icons',
//                'icon'      => 'fa fa-smile-o',
//                'sref'   => 'icons',
//            ),
//            'boxes' => array
//            (
//                'translate' => 'navigation.frontend.boxes',
//                'icon'      => 'icon-speech',
//                'sref'   => 'boxes',
//            ),
//            'simple' => array
//            (
//                'translate' => 'navigation.tables.simple',
//                'icon'      => 'icon-check',
//                'sref'      => 'tables_simple',
//            ),
//            'extended' => array
//            (
//                'translate' => 'navigation.tables.extended',
//                'icon'      => 'icon-check',
//                'sref'      => 'tables_extended',
//            ),
//            'datatables' => array
//            (
//                'translate' => 'navigation.tables.datatables',
//                'icon'      => 'icon-check',
//                'sref'      => 'tables_datatables',
//            ),
//            'general' => array
//            (
//                'translate' => 'navigation.forms.general',
//                'icon'      => 'icon-check',
//                'sref'      => 'form_general',
//            ),
//            'advanced' => array
//            (
//                'translate' => 'navigation.forms.advanced',
//                'icon'      => 'icon-check',
//                'sref'      => 'form_advanced',
//            ),
//        ),
//    ),
);
