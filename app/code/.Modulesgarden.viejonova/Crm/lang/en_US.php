<?php
/***************************************************************************************
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
 **************************************************************************************/



/**
 * Default language file for module
 *
 * Language translation relay on multidimensional arrays! (to make it easier for angular)
 * and for performance reasons
 *
 *
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 */



/****************************************************************************************
 *                          English Language
 ****************************************************************************************/


/**
 * Some Main Translations
 */

// Module Name
$_LANG['CRM'] = 'CRM';
// Module Description
$_LANG['Customer Relationship Manager'] = 'Customer Relationship Manager';
// prefix used in every window title
$_LANG['window_prefix'] = 'CRM | ';



/**
 * Main navigation tabs in module header
 * you can easly change translation of some element, just keep on mind that its array...
 */
$_LANG['navigation'] = array(
    'createlead' => 'Create Contact',
    'dashboard'  => 'Dashboard',
    'about'      => 'About',
    'leads'      => 'Leads',
    'potentials' => 'Potentials',
    'calendar'   => 'Calendar',
    'campaigns'  => 'Campaigns',
    'dynamicTypesSubmenu'  => 'Contacts',
    'frontend'   => array(
        'frontend'   => 'Frontend',
        'typography' => 'Typography',
        'panels'     => 'panels',
        'buttons'    => 'buttons',
        'icons'      => 'icons',
        'boxes'      => 'boxes',
    ),
    'tables' => array(
        'tables'     => 'tables',
        'simple'     => 'simple',
        'extended'   => 'extended',
        'datatables' => 'datatables',
    ),
    'forms' => array(
        'forms'    => 'Forms',
        'general'  => 'General',
        'advanced' => 'Advanced',
    ),
    'settings' => array(
        'settings'       => 'Settings',
        'mailbox'        => 'Outgoing Mailbox Configuration',
        'emailtemplates' => 'Email Templates',
        'fields'         => 'Fields',
        'permissions'    => 'Permissions',
        'personal'       => 'Personal',
        'types'          => 'Contact Types',
        'general'        => 'General',
        'importexport'   => 'Import / Export',
        'migrator'       => 'Migrator',
    ),
    'utils' => array(
        'utils'         => 'Utilities',
        'statistics'    => 'Statistics',
        'archive'       => 'Archive',
        'notifications' => 'Notifications',
        'massmessage'   => 'Mass Messages',
    ),
    'examples' => array(
        'examples'    => 'Examples',
        'leadsummary' => 'Lead Summary',
        'leadslist'   => 'Leads List',
        'leadcreate'  => 'Lead Create',
    ),
);

// static fields for each lead/potential
$_LANG['staticfields'] = array(
    'id'       => 'ID',
    'name'     => 'Main Name',
    'status'   => 'Status',
    'email'    => 'Email',
    'phone'    => 'Phone',
    'priority' => 'Priority',
    'potentials' => 'Potentials',
    'is_potential' => 'Type',
    'client'   => 'Assigned Client',
    'ticket'   => 'Assigned Tickets',
    'admin'    => 'Assigned Admin',
);


// Breadcrumbs translations
$_LANG['breadcrumbs.summary']           = 'Summary';
$_LANG['breadcrumbs.followups']         = 'Follow-ups';
$_LANG['breadcrumbs.followup.edit']     = 'Follow-up';
$_LANG['breadcrumbs.followup.notes']    = 'Notes';
$_LANG['breadcrumbs.followup.emails']   = 'Emails';
$_LANG['breadcrumbs.followup.orders']   = 'Orders';
$_LANG['breadcrumbs.followup.quotes']   = 'Quotes';
$_LANG['breadcrumbs.followup.files']    = 'Files';
$_LANG['breadcrumbs.followup.logs']     = 'Logs';
$_LANG['breadcrumbs.massmessage.list']     = 'Mass Messages';
$_LANG['breadcrumbs.massmessage.add']      = 'Create';
$_LANG['breadcrumbs.massmessage.edit']      = 'Edit';
$_LANG['breadcrumbs.settings.importexport']      = 'Import / Export';



// Logs translations
$_LANG['priority.0'] = $_LANG['priority.1'] = $_LANG['priority.low']        = 'Low';
$_LANG['priority.2'] = $_LANG['priority.medium']                            = 'Medium';
$_LANG['priority.3'] = $_LANG['priority.important']                         = 'Important';
$_LANG['priority.4'] = $_LANG['priority.urgent']                            = 'Urgent';


// Logs translations
$_LANG['log.reassign.priority']     = 'Priority has been changed to <b>:priority</b>';
$_LANG['log.reassign.phone']        = 'Phone has been changed to <b>:phone</b>';
$_LANG['log.reassign.email']        = 'Email has been changed to <b>:email</b>';
$_LANG['log.reassign.name']         = 'Name has been changed to <b>:name</b>';
$_LANG['log.reassign.status']       = 'Status has been changed to <b>:status</b>';
$_LANG['log.reassign.admin']        = 'Admin has been reassigned to <b>:admin</b>';
$_LANG['log.reassign.ticket']       = 'Ticket has been reassigned to <b>:ticket</b>';
$_LANG['log.reassign.client']       = 'Client has been reassigned to <b>:client</b>';
$_LANG['log.note.new']              = 'New note has been added: :conent';
$_LANG['log.unassign.ticket']       = 'Ticket has been unassigned';
$_LANG['log.unassign.client']       = 'Client has been unassigned';
$_LANG['log.unassign.admin']        = 'Admin has been unassigned';
// fields
$_LANG['log.field.text.update']     = 'Field #:id <i>:field</i> updated to <b>:value</b>';
$_LANG['log.field.textarea.update'] = 'Field #:id <i>:field</i> updated to <b>:value</b>';
$_LANG['log.field.numeric.update']  = 'Field #:id <i>:field</i> updated to <b>:value</b>';
$_LANG['log.field.date.update']     = 'Field #:id <i>:field</i> updated to <b>:value</b>';
$_LANG['log.field.datetime.update'] = 'Field #:id <i>:field</i> updated to <b>:value</b>';
$_LANG['log.field.checkbox.update'] = 'Field #:id <i>:field</i> updated to <b>:value</b>';
$_LANG['log.field.radio.update']    = 'Field #:id <i>:field</i> updated to <b>:value</b>';
$_LANG['log.field.select.update']   = 'Field #:id <i>:field</i> updated to <b>:value</b>';

// resources priority
$_LANG['priority.low']         = 'Low';
$_LANG['priority.medium']      = 'Medium';
$_LANG['priority.important']   = 'Important';
$_LANG['priority.urgent']      = 'Urgent';


// some primary translations used in multiple places
$_LANG['form.add']                = 'Add';
$_LANG['form.edit']               = 'Edit';
$_LANG['form.date']               = 'Date';
$_LANG['form.description']        = 'Description';
$_LANG['form.admin']              = 'Admin';
$_LANG['form.close']              = 'Close';
$_LANG['form.cancel']             = 'Cancel';
$_LANG['form.update']             = 'Update';
$_LANG['form.reschedule']         = 'Reschedule';
$_LANG['form.delete']             = 'Delete';
$_LANG['form.name']               = 'Name';
$_LANG['form.color']              = 'Color';
$_LANG['form.enabled']            = 'Enabled';
$_LANG['form.any']                = 'Any';
$_LANG['form.empty']              = 'Not Set';
$_LANG['form.search.placeholder'] = 'Search';
$_LANG['lead']                    = 'Lead';
$_LANG['leads']                   = 'Leads';
$_LANG['contacts']                = 'Contacts';
$_LANG['potentials']              = 'Potentials';
$_LANG['potential']               = 'Potential';
$_LANG['reminders']               = 'Reminders';
$_LANG['followup']                = 'Follow-up';
$_LANG['text.summary']            = 'Summary';
$_LANG['text.system']             = 'System';
$_LANG['text.not.assigned']       = 'Not assigned';
$_LANG['text.not.set']            = 'Not Set';
$_LANG['text.delete']             = 'Delete';
$_LANG['text.success']            = 'Success';


//
// tables headers
//
# followups
$_LANG['table.th.id']              = '#ID';
$_LANG['table.th.date']            = 'Date';
$_LANG['table.th.name']            = 'Name';
$_LANG['table.th.method']          = 'Method';
$_LANG['table.th.status']          = 'Status';
$_LANG['table.th.remind']          = 'Remind';
$_LANG['table.th.active']          = 'Active';
$_LANG['table.th.actions']         = 'Actions';
$_LANG['table.th.preview']         = 'Preview';
$_LANG['table.th.color']           = 'Color';
$_LANG['table.reminders.empty']    = 'There are no reminders of this follow-up';
$_LANG['table.pagination.showing'] = 'Showing';
$_LANG['table.reminders.admin']    = 'For Admin: ';
$_LANG['table.reminders.resource'] = 'For Resource';
$_LANG['table.pagination.to']      = 'to';
$_LANG['table.pagination.of']      = 'of';
$_LANG['table.pagination.entries'] = 'entries';
$_LANG['table.pagination.display'] = 'Display';
$_LANG['table.pagination.perpage'] = 'per page.';
$_LANG['table.empty.msg']          = 'There is nothing to show';


$_LANG['Quotes']   = 'Quotes';


// calendar page
$_LANG['calendar.widget.name']         = 'Calendar';
$_LANG['calendar.navigation.previous'] = 'Previous';
$_LANG['calendar.navigation.today']    = 'Today';
$_LANG['calendar.navigation.name']     = 'Next';
$_LANG['calendar.navigation.month']    = 'Month';
$_LANG['calendar.navigation.week']     = 'Week';
$_LANG['calendar.navigation.day']      = 'Day';
$_LANG['calendar.legend.header']       = 'Types of follow-ups displayed on calendar:';
$_LANG['calendar.legend.helper']       = "Click on a follow-up's name to show details";

// followup edit
$_LANG['followup.reminders']                       = 'Reminders';
$_LANG['followup.form.assignedfor']                = 'Assigned To';
$_LANG['followup.to.admin']                        = 'Admin';
$_LANG['followup.to.client']                       = 'Client';
$_LANG['followup.reschedue']                       = 'Reschedule';
$_LANG['followup.reschedue.reason']                = 'Reason';
$_LANG['followup.reschedue.placeholder']           = 'Type in the reason of rescheduling';
$_LANG['followup.reschedue.updatereminders']       = 'Update Reminders';
$_LANG['followup.reschedue.updatereminders.descr'] = 'When enabled, each reminder date will be adjusted according to the changed date.';


$_LANG['text.current.date'] = 'Current Date';
$_LANG['text.disabled']     = 'Disabled';
$_LANG['text.disable']      = 'Disable';
$_LANG['text.enabled']      = 'Enabled';
$_LANG['text.enable']       = 'Enable';
$_LANG['text.show']         = 'Show';
$_LANG['text.hide']         = 'Hide';
$_LANG['text.active']       = 'Active';
$_LANG['text.inactive']     = 'Inactive';
$_LANG['text.new.date']     = 'New Date';
$_LANG['text.ok']           = 'OK';
$_LANG['text.yes']          = 'Yes';
$_LANG['text.no']           = 'No';
$_LANG['text.none']         = 'none';
$_LANG['text.error']        = 'Error';
$_LANG['text.warning']      = 'Warning';
$_LANG['text.empty']        = 'empty';
$_LANG['text.not.required'] = 'Not required';
$_LANG['text.created_at']   = 'Created';
$_LANG['text.updated_at']   = 'Updated';
$_LANG['text.no.invoice']   = 'No Invoice';
$_LANG['text.quotations.disabled']   = 'Quotations integration is disabled';


// validators
$_LANG['form.validator.text']                 = 'New Date';
$_LANG['form.validator.text.required']        = 'Field cannot be empty';
$_LANG['form.validator.text.required_digits'] = 'Field cannot be empty. Only numbers allowed.';
$_LANG['form.validator.regex.required']       = 'Regex expression must be valid!';
$_LANG['form.validator.checkbox.required']    = 'Confirmation is required';
$_LANG['form.validator.date']                 = 'Date must be valid';
$_LANG['form.validator.reason']               = 'Reason is required';
$_LANG['form.validator.select.required']      = 'Please Select';






///////////////////////////////////
// Dashboard
///////////////////////////////////

$_LANG['dashboard.preview.for']                         = 'Dashboard Preview for';
$_LANG['dashboard.followups.widgetname']                = "Follow-ups for ";
$_LANG['dashboard.followups.dateincalendar']            = 'select in calendar';
$_LANG['dashboard.followups.emptyfordaydateincalendar'] = 'select in calendar';
$_LANG['dashboard.followups.emptyforday']               = 'Sorry, no follow-ups could be found in the chosen day!';
$_LANG['table.th.followup.resource']                    = 'Contact Name';
$_LANG['table.th.followup.type']                        = 'Type';
$_LANG['table.th.followup.descr']                       = 'Description';
$_LANG['table.th.followup.status']                      = 'Status';
$_LANG['table.th.followup.priority']                    = 'Priority';
$_LANG['table.th.followup.reminders']                   = 'Reminders';

$_LANG['dashboard.activity.widgetname']                 = 'Last Activity';
$_LANG['table.th.activity.author']                      = 'Author';
$_LANG['table.th.activity.event']                       = 'Event';
$_LANG['table.th.activity.message']                     = 'Message';



///////////////////////////////////
// Statistics
///////////////////////////////////
$_LANG['statistics.previewfor']              = 'Statistics for';
$_LANG['statistics.last10.leads']            = 'Last 10 ';
$_LANG['statistics.last10.potentials']       = 'Last 10 Potentials';
$_LANG['statistics.last10.th.leadname']      = 'Contact Name';
$_LANG['statistics.last10.th.potentialname'] = 'Potential Name';
$_LANG['statistics.last10.th.status']        = 'Status';
$_LANG['statistics.last10.th.admin']         = 'Assigned Admin';
$_LANG['statistics.last10.th.created']       = 'Created On';

$_LANG['statistics.month']                   = 'Month';
$_LANG['statistics.new.in.month']            = 'New Contacts in';
$_LANG['statistics.new.in.year']             = 'New in';
$_LANG['statistics.choose.month']            = 'Choose Month';
$_LANG['statistics.choose.year']             = 'Choose Year';

$_LANG['statistics.leads.per.status']        = 'Leads Per Status';
$_LANG['statistics.potentials.per.status']   = 'Potentials Per Status';
$_LANG['statistics.widget.per.admin']        = 'Assigned Contacts to Admins in total';

///////////////////////////////////
// Settings > Personal
///////////////////////////////////
$_LANG['settings.personal.tab']                       = 'Personal Settings';
$_LANG['settings.personal.tab.fields']                = "Fields' View";
$_LANG['settings.personal.avatar.url']                = 'Avatar URL';
$_LANG['settings.personal.upgrade']                   = 'Update Personal Settings';

$_LANG['settings.personal.view.top.info']             = "Here you can determine what columns you want to be visible in various data tables. You can add new fields for resources and configure settings according to which tables and columns will be displayed. This section refers to table configuration on Dashboard page and Contact main tables accordingly.<br/> Please select section that you want to configure first:";
$_LANG['settings.personal.view.visiblity.leads']      = "In this section you can define general order of fields and their visibility that will be applied to <b>Contacts' listing page</b> (in Main table). Choose from the fields below to adjust Contact's page as needed. ";
$_LANG['settings.personal.view.visiblity.potentials'] = "In this section you can define order and visibility that will be applied to <b>Potentials' listing page</b> (in Main table). Outline here general visibility of fields and their order as you wish.";
$_LANG['settings.personal.view.visiblity.dashboard']  = "This order is applied to a Dashboard page. Table for Leads and for Potentials can be designed here according to your preferences. These settings allow you to define fields for <b>both Leads and Potentials.</b>";
$_LANG['settings.personal.view.editing.curently']     = 'Currently Edited';
$_LANG['settings.personal.view.tag.dashboard']        = 'Dashboard';

$_LANG['settings.personal.view.edit.header']          = 'Configure visible columns for: ';
$_LANG['settings.personal.view.edit.note']            = 'Drag & drop the fields to a suitable container in order to get the desired fields order and visibility.';
$_LANG['settings.personal.view.visible']              = 'Visible';
$_LANG['settings.personal.view.visible.tooltip']      = 'Displayed columns and their order';
$_LANG['settings.personal.static.available']          = 'Available Static Fields';
$_LANG['settings.personal.static.available.tooltip']  = 'These are fields specified by the system';
$_LANG['settings.personal.view.no.fields']            = 'There are no fields';
$_LANG['settings.personal.available']                 = 'Available Fields';
$_LANG['settings.personal.available.tooltip']         = 'Defined Custom Fields';
$_LANG['settings.personal.available.drag']            = 'Drag Field here';


///////////////////////////////////
// Settings > Permissions
///////////////////////////////////
$_LANG['settings.permissions.new.role.widget']            = 'Add New Role';
$_LANG['settings.permissions.new.role.success']           = 'New Role has been added';
$_LANG['settings.permissions.new.role.name.placeholder']  = 'Enter a new role name here';
$_LANG['settings.permissions.new.role.group']             = 'Admin Role Group';
$_LANG['settings.permissions.new.role.descr.placeholder'] = 'Describe a new role shortly';

$_LANG['settings.permissions.roles.widget']               = "Role Permissions";
$_LANG['settings.permissions.roles.widget']               = "Role Permissions";
$_LANG['settings.permissions.roles.th.descr']             = 'Description';
$_LANG['settings.permissions.roles.th.admin']             = 'Assigned Admin Group';
$_LANG['settings.permissions.roles.empty']                = 'There are no roles now, add one!';
$_LANG['settings.permissions.roles.not.possible']         = 'No available admin group to select';

$_LANG['settings.permissions.for']                        = 'Permissions for';



///////////////////////////////////
// Settings > general
///////////////////////////////////
$_LANG['settings.general.tab.overview']                = "System Overview";
$_LANG['settings.general.tab.settings']                = "Options";
$_LANG['settings.general.tab.followups']               = "Follow-ups";
$_LANG['settings.general.widget.monitor']              = 'System Monitoring';
$_LANG['settings.general.tab.api']                     = 'API';

$_LANG['settings.general.integrated']                  = 'Not Integrated';
$_LANG['settings.general.not.integrated']              = 'System Monitor';

$_LANG['settings.general.integration.asterisk']        = 'Asterisk VoIP Center Integration';
$_LANG['settings.general.integration.asterisk.descr']  = 'Simple indicator that informs if integration with Asterisk Module For WHMCS (developed by MG) is successful. If no errors occur here, you will get the possibility to call clients by VoIP (depending on the proper configuration of VoIP).';
$_LANG['settings.general.integration.smscenter']       = 'SMS Center Integration';
$_LANG['settings.general.integration.smscenter.descr'] = 'Integrate your CRM Module with SMS Center (developed by MG) to send follow-up text messages to admins and to send short messages directly from a contact.';
$_LANG['settings.general.monitor.cron.descr']          = 'The cron job has not been executed yet. Please, check if you have configured it properly.';
$_LANG['settings.general.monitor.cron.descr.ok']       = 'System detected that cron has been executed at least once.';
$_LANG['settings.general.monitor.cron']                = 'Cron';
$_LANG['settings.general.monitor.emails']              = 'Email Templates';
$_LANG['settings.general.monitor.emails.in.use']       = 'Email templates in use:';
$_LANG['settings.general.monitor.uploads']             = 'Uploads';
$_LANG['settings.general.monitor.uploads.descr']       = 'Directory is NOT configured properly. The system is unable to create folder automatically. Create folder and set directory permissions to <code>0777</code>.';

$_LANG['settings.general.monitor.cron.details']        = 'Cron Details';
$_LANG['settings.general.monitor.cron.info']           = 'Cron needs to be set manually by an administrator. It will handle various functionalities such as sending emails in specified time. It is recommended that cron run should be set at least once a day to review configured notifications in the system.';
$_LANG['settings.general.monitor.cron.path']           = 'Path';
$_LANG['settings.general.monitor.cron.url']            = 'URL';
$_LANG['settings.general.monitor.cron.lastrun']        = 'Last Execution';
$_LANG['settings.general.monitor.cron.lastrun.error']  = 'No records of execution';
$_LANG['settings.general.monitor.cron.interval']       = 'Interval';
$_LANG['settings.general.monitor.cron.interval.ago']   = 'minutes ago';
$_LANG['settings.general.monitor.cron.interval.error'] = 'SYSTEM CANNOT DEFINE INTERVAL';
$_LANG['settings.general.monitor.cron.interval.descr'] = 'Adjust your cron job interval time to desired frequency. Email/follow-up sending is strongly related to cron execution. Remember that if you set cron interval once per day, you will receive follow-ups once a day only!';

$_LANG['settings.general.widget']                      = 'Additional Options';
$_LANG['settings.general.enable.quotation']            = 'Enable Quotations';
$_LANG['settings.general.enable.quotation.descr']      = 'If enabled, gives the possibility to create quotes for contacts.';

$_LANG['settings.general.enable.adminassign']          = 'Contacts Assignment';
$_LANG['settings.general.enable.adminassign.descr']    = 'If enabled, each contact can be assigned to a single administrator who will manage them.';
$_LANG['settings.general.update.btn']                  = 'Update Settings';
$_LANG['settings.general.followups.day']               = "Follow-ups Per Day";
$_LANG['settings.general.followups.day.descr']         = 'Once enabled, you will be able to create follow-ups in a single day only. The option to configure follow-ups for a specific hour will be disabled.';
$_LANG['settings.followups.notif']                     = 'Reschedule Follow-up';
$_LANG['settings.followups.create.lead.followup.type'] = 'Follow-up Type On Contact Creation';
$_LANG['settings.followups.create.lead.followup.type.descr'] = 'Configure Type of Follow-up that will be created upon contact creation.';
$_LANG['settings.followups.notif.descr']               = 'Choose email template that will be used to notify admins about a follow-up being rescheduled.';
$_LANG['settings.general.followup.widget.newtype']     = 'Add New Follow-up Type';
$_LANG['settings.general.followup.newtype.success']    = 'New type of follow-up has been added';
$_LANG['settings.general.followup.types']              = 'Follow-up Types';
$_LANG['settings.general.followup.th.color']           = 'Color';
$_LANG['settings.general.followup.th.active']          = 'Status';

///////////////////////////////////
// Settings > fields
///////////////////////////////////

$_LANG['settings.fields.tab.list']                    = 'List';
$_LANG['settings.fields.tab.groups']                  = 'Groups';
$_LANG['settings.fields.tab.statuses']                = 'Statuses';
$_LANG['settings.fields.tab.map']                     = 'Mapping';

$_LANG['settings.fields.status.new.success']          = 'New status has been created';
$_LANG['settings.fields.status.widget.new']           = 'Add New Status';
$_LANG['settings.fields.list.widget.name']            = 'Fields List';
$_LANG['settings.fields.status.status.name']          = 'Status Name';
$_LANG['settings.fields.status.new.name.placehoder']  = 'Type in the name of a new status';
$_LANG['settings.fields.status.widget.statuses']      = 'Statuses';
$_LANG['settings.fields.statuses.th.preview']         = 'Preview';
$_LANG['settings.fields.statuses.th.color']           = 'Color';

$_LANG['settings.fields.map.no.custom.fields']        = 'There are no WHMCS custom client fields';
$_LANG['settings.fields.map.widget.custom']           = 'WHMCS Client Custom Fields Mapper';
$_LANG['settings.fields.map.widget.standard']         = 'WHMCS Standard Fields Mapper';

$_LANG['settings.fields.groups.widget']               = 'Add New Group Of Fields';
$_LANG['settings.fields.groups.widget.new.succed']    = 'New group has been created';
$_LANG['settings.fields.groups.new.name']             = 'Group Name';
$_LANG['settings.fields.groups.new.name.placeholder'] = 'Enter a new group name here';
$_LANG['settings.fields.widget.groups']               = 'Groups Of Fields';
$_LANG['settings.fields.widget.new']                  = 'Add New Field';
$_LANG['settings.fields.new.name.placeholder']        = 'Enter a new field name here';
$_LANG['settings.fields.new.descr.placeholder']       = 'Describe a new field shortly';
$_LANG['settings.fields.new.type']                    = 'Type';
$_LANG['settings.fields.new.type.required']           = 'Please select one of the available types';
$_LANG['settings.fields.new.group']                   = 'Group';
$_LANG['settings.fields.new.group.required']          = 'Please select one of the available groups';
$_LANG['settings.fields.widget.new.succeed']          = 'New Field has been added';
$_LANG['settings.fields.tooltip.disabled']            = 'Group is disabled';
$_LANG['settings.fields.no.in.group']                 = 'There are no fields assigned to this group';

$_LANG['settings.fields.edit.header']            = 'You are editing field #';
$_LANG['settings.fields.edit.active']            = 'Active';
$_LANG['settings.fields.edit.type']              = 'Type';
$_LANG['settings.fields.edit.type.helper']       = 'Delete all validators in order to change field type.';
$_LANG['settings.fields.edit.descr.placeholder'] = 'Description that will be displayed in tooltip';
$_LANG['settings.fields.edit.widget.validators'] = 'Validators';
$_LANG['settings.fields.edit.th.type']           = 'Type';
$_LANG['settings.fields.edit.th.config']         = 'Config';
$_LANG['settings.fields.edit.th.error']          = 'Error';
$_LANG['settings.fields.edit.validators.none']   = 'No validators found';
$_LANG['settings.fields.edit.error.msg']         = 'Error Message';
$_LANG['settings.fields.edit.v.min']             = 'Minimum Value';
$_LANG['settings.fields.edit.v.max']             = 'Maximum Value';
$_LANG['settings.fields.edit.v.regex']           = 'Regex';
$_LANG['settings.fields.edit.v.available']       = 'Available Validators';



$_LANG['settings.fields.edit.v.text.required']    = 'Cannot be empty';
$_LANG['settings.fields.edit.v.text.min']         = 'Minimum characters';
$_LANG['settings.fields.edit.v.text.max']         = 'Maximum characters';
$_LANG['settings.fields.edit.v.text.email']       = 'Must be a valid email address';
$_LANG['settings.fields.edit.v.text.url']         = 'Must be a valid URL';
$_LANG['settings.fields.edit.v.text.ip']          = 'Must be a valid IP address (IPv4 or IPv6)';
$_LANG['settings.fields.edit.v.text.regex']       = 'Must be a valid regex expression';
$_LANG['settings.fields.edit.v.select.required']  = 'At least one option must be selected';
$_LANG['settings.fields.edit.v.numeric.required'] = 'Muse be a valid number';
$_LANG['settings.fields.edit.v.select.min']       = 'Minimum options to choose';
$_LANG['settings.fields.edit.v.select.max']       = 'Maximum options to choose';
$_LANG['settings.fields.edit.o.widget']           = 'Options To Choose For This Field';
$_LANG['settings.fields.edit.o.th.val']           = 'Value';
$_LANG['settings.fields.edit.o.none']             = 'There are no options';
$_LANG['settings.fields.edit.o.newoption']        = 'New Option';

///////////////////////////////////
// Resources > List (table subpage)
///////////////////////////////////
$_LANG['resource.text.convert']                   = 'Convert';
$_LANG['resource.text.convert.potential']         = 'Convert To Potential';
$_LANG['resource.text.convert.lead']              = 'Convert To Lead';
$_LANG['resource.text.convert.to']                = 'Convert To ';
$_LANG['resource.text.convert.delete']            = 'Move To Archive';
$_LANG['resource.text.convert.restore']           = 'Restore from Archive';
$_LANG['resource.create.widget.name']             = 'Create New Lead';
$_LANG['resource.create.main.options']            = 'Options';
$_LANG['resource.create.select.admin']            = 'Assigned Admin';
$_LANG['resource.create.select.admin.placeholder']= 'Select Admin';
$_LANG['resource.create.select.client']           = 'Assigned Client';
$_LANG['resource.create.select.client.placeholder']= 'Search for Client';
$_LANG['resource.create.select.type']             = 'Contact Type';
$_LANG['resource.create.select.type.placeholder'] = 'Search for Contact Type';
$_LANG['resource.create.followup.add']            = 'Create Follow-up';
$_LANG['resource.create.name.placeholder']        = 'Enter a new field name here';
$_LANG['resource.create.main.info']               = 'Information';
$_LANG['resource.create.main.name']               = 'Name';
$_LANG['resource.create.main.name.tooltip']       = 'Name of a lead that is visible in the system';
$_LANG['resource.create.main.name.placeholder']   = 'Enter a lead name here';
$_LANG['resource.create.main.status']             = 'Status';
$_LANG['resource.create.main.status.placeholder'] = 'Select Status';
$_LANG['resource.create.main.email']              = 'Email';
$_LANG['resource.create.main.email.placeholder']  = 'Enter an e-mail address here';
$_LANG['resource.create.main.email.tooltip']      = 'Primary email address used to contact by emails with this lead';
$_LANG['resource.create.main.phone']              = 'Phone';
$_LANG['resource.create.main.phone.placeholder']  = 'Enter a phone number here';
$_LANG['resource.create.main.phone.tooltip']      = 'Primary phone number used to contact by sms/phone calls with this lead';
$_LANG['resource.create.form.invalid']            = 'Invalid Form!';
$_LANG['resource.header.marked.archive']          = 'This is an archive record';
$_LANG['resource.header.last.update']             = 'Last Update:';
$_LANG['resource.header.tab.summary']             = 'Summary';
$_LANG['resource.header.tab.followups']           = 'Follow-ups';
$_LANG['resource.header.tab.notes']               = 'Notes';
$_LANG['resource.header.tab.emails']              = 'Emails';
$_LANG['resource.header.tab.orders']              = 'Orders';
$_LANG['resource.header.tab.quotes']              = 'Quotes';
$_LANG['resource.header.tab.logs']                = 'Logs';
$_LANG['resource.header.tab.fields']              = 'Files';

$_LANG['resource.summary.widget.main']          = 'Main Details';
$_LANG['resource.summary.widget.main.descr']    = ' information assigned by the system';
$_LANG['resource.summary.main.assignedclient']  = 'Assigned Client';
$_LANG['resource.summary.main.client.create']   = 'Create';
$_LANG['resource.summary.main.unassignclient']  = 'Unassign the already assigned client';
$_LANG['resource.summary.main.ticket']          = 'Assigned Ticket';
$_LANG['resource.summary.main.ticket.search']   = 'Search for Ticket';
$_LANG['resource.summary.main.ticket.unassign'] = 'Unassign the already assigned ticket';
$_LANG['resource.summary.main.email']           = 'Email';
$_LANG['resource.summary.main.phone']           = 'Phone';
$_LANG['resource.summary.main.type']            = 'Type';
$_LANG['resource.summary.main.campaign']        = 'Campaign';
$_LANG['resource.summary.main.created']         = 'Created On';
$_LANG['resource.summary.main.deleted']         = 'Deleted On';
$_LANG['resource.summary.main.campaign.notes']  = 'Campaign Notes';
$_LANG['resource.summary.main.campaign.notes.descr']  = 'additional information by assigned campaigns';

$_LANG['resource.summary.tab.addnote']       = 'Add Note';
$_LANG['resource.summary.tab.sentemail']     = 'Send Email';
$_LANG['resource.summary.tab.addfollowup']   = 'Add Follow-up';
$_LANG['resource.summary.tab.addticketresp'] = 'Send Ticket Response';

$_LANG['resource.summary.notes.add.success']     = 'Note has been added';
$_LANG['resource.summary.notes.add.placeholder'] = 'Type in some text here to create a note!';
$_LANG['resource.summary.notes.btn.add']         = 'Add Note';

$_LANG['resource.summary.email.add.success']          = 'Email has been sent';
$_LANG['resource.summary.email.from']                 = 'From';
$_LANG['resource.summary.email.from.tooltip']         = 'Choose ticket department email or use a global system email address';
$_LANG['resource.summary.email.to']                   = 'To';
$_LANG['resource.summary.email.to.tooltip']           = 'You can select email address assigned to a client or an email assigned to this lead';
$_LANG['resource.summary.email.template']             = 'Template';
$_LANG['resource.summary.email.template.tooltip']     = 'Instead of composing the message from scratch, you can use a ready-made template';
$_LANG['resource.summary.email.template.none']        = '--- none';
$_LANG['resource.summary.email.subject']              = 'Subject';
$_LANG['resource.summary.email.subject.placeholder']  = 'Enter subject here';
$_LANG['resource.summary.email.content']              = 'Main Content';
$_LANG['resource.summary.email.content.placeholder']  = 'Type in the text of the message here!';
$_LANG['resource.summary.email.files']                = 'Files';
$_LANG['resource.summary.email.sent']                 = 'Send';
$_LANG['resource.summary.followup.add.success']       = 'New Follow-up has been created.';
$_LANG['resource.summary.followup.admin.tooltip']     = 'Choose administrator that a new follow-up will be assigned to';
$_LANG['resource.summary.followup.admin']             = 'Admin';
$_LANG['resource.summary.followup.date']              = 'Date';
$_LANG['resource.summary.followup.date.tooltip']      = 'Choose date of a follow-up';
$_LANG['resource.summary.followup.type']              = 'Type';
$_LANG['resource.summary.followup.descr']             = 'Description';
$_LANG['resource.summary.followup.descr.placeholder'] = 'Describe your follow-up shortly';
$_LANG['resource.summary.followup.add']               = 'Add Follow-up';
$_LANG['resource.summary.ticket.noclient']            = 'You cannot send a response, since there are no tickets assigned to this lead';
$_LANG['resource.summary.ticket.sent']                = 'Response to the ticket has been sent';
$_LANG['resource.summary.ticket.msg']                 = 'Message';
$_LANG['resource.summary.ticket.msg.placeholder']     = 'Type in the contents of your ticket reply!';
$_LANG['resource.summary.ticket.addreply']            = 'Add Reply';

$_LANG['resource.summary.followupswidget']        = 'Follow-ups';
$_LANG['resource.summary.followups.th.type']      = 'Type';
$_LANG['resource.summary.followups.th.admin']     = 'Admin';
$_LANG['resource.summary.followups.th.descr']     = 'Description';
$_LANG['resource.summary.followups.th.reminders'] = 'Reminders';
$_LANG['resource.summary.followups.none']         = "There are no follow-ups";

$_LANG['resource.summary.notes']             = 'Notes';
$_LANG['resource.summary.notes.last']        = 'Last';
$_LANG['resource.summary.notes.hide.hidden'] = 'Do Not Show Hidden';
$_LANG['resource.summary.notes.show.hidden'] = 'Show Hidden';
$_LANG['resource.summary.notes.hidde']       = 'Hide';
$_LANG['resource.summary.notes.unhide']      = 'Unhide';
$_LANG['resource.summary.notes.delete']      = 'Delete';
$_LANG['resource.summary.notes.edit']        = 'Edit';
$_LANG['resource.summary.notes.none']        = 'There are no notes';

$_LANG['resource.quotes.widget.name']     = 'Quotes';
$_LANG['resource.quotes.form.new']        = 'Add New Quote';
$_LANG['resource.quotes.th.stage']        = 'Stage';
$_LANG['resource.quotes.form.sync']       = 'Synchronize Quotes';
$_LANG['resource.quotes.th.subject']      = 'Subject';
$_LANG['resource.quotes.th.datemodified'] = 'Last Modified';
$_LANG['resource.quotes.th.datesent']     = 'Sent On';
$_LANG['resource.quotes.th.dateacc']      = 'Accepted On';
$_LANG['resource.quotes.th.total']        = 'Total';
$_LANG['resource.quotes.stage.draft']     = 'Draft';
$_LANG['resource.quotes.stage.delivered'] = 'Delivered';
$_LANG['resource.quotes.stage.accepted']  = 'Accepted';
$_LANG['resource.quotes.stage.lost']      = 'Lost';
$_LANG['resource.quotes.stage.dead']      = 'Dead';

$_LANG['resource.orders.widget.name']      = 'Client orders';
$_LANG['resource.orders.no.client']        = 'No clients are assigned to this lead. Consequently, there are no orders to show.';
$_LANG['resource.orders.widget.descr']     = 'from client assigned to this record';
$_LANG['resource.orders.btn.new']          = 'Add New Order';
$_LANG['resource.orders.th.ordernum']      = 'Order #';
$_LANG['resource.orders.th.payment']       = 'Payment Method';
$_LANG['resource.orders.th.total']         = 'Total';
$_LANG['resource.orders.th.orderstatus']   = 'Order Status';
$_LANG['resource.orders.th.invoice']       = 'Invoice';
$_LANG['resource.orders.th.paymentstatus'] = 'Invoice Payment Status';

$_LANG['resource.orders.s.pending']                   = 'Pending';
$_LANG['resource.orders.s.complete']                  = 'Complete';
$_LANG['resource.orders.s.cancelled']                 = 'Canceled';
$_LANG['resource.orders.s.fraud']                     = 'Suspected Fraud';
$_LANG['resource.orders.s.closed']                    = 'Closed';
$_LANG['resource.orders.s.holded']                    = 'On Hold';
$_LANG['resource.orders.s.payment_review']            = 'Payment Review';
$_LANG['resource.orders.s.paypal_canceled_reversal']  = 'PayPal Canceled Reversal';
$_LANG['resource.orders.s.paypal_reversed']           = 'PayPal Reversed';
$_LANG['resource.orders.s.pending_payment']           = 'Pending Payment';
$_LANG['resource.orders.s.pending_paypal']            = 'Pending PayPal';
$_LANG['resource.orders.s.processing']                = 'Processing';
$_LANG['resource.orders.s.paid']        = 'Paid';
$_LANG['resource.orders.s.unpaid']      = 'Unpaid';
$_LANG['resource.orders.s.cancel']      = 'Canceled';
$_LANG['resource.orders.s.refunded']    = 'Refunded';
$_LANG['resource.orders.s.collections'] = 'Collections';

$_LANG['resource.notes.widget.name']         = 'New Note';
$_LANG['resource.notes.content.placeholder'] = 'Here you can enter the text of your note!';
$_LANG['resource.notes.content.btn.add']     = 'Add Note';
$_LANG['resource.notes.content.btn.edit']    = 'Save Edited Note';
$_LANG['resource.notes.content.btn.cancel']  = 'Cancel';
$_LANG['resource.notes.list.widget']         = 'List of Notes';
$_LANG['resource.notes.hide.hidden']         = 'Do Not Display Hidden';
$_LANG['resource.notes.show.hidden']         = 'Display Hidden';
$_LANG['resource.notes.editing']             = 'currently edited';

$_LANG['resource.logs.widget.name'] = 'Logs';
$_LANG['resource.logs.th.date']     = 'Date';
$_LANG['resource.logs.th.author']   = 'Author';
$_LANG['resource.logs.th.event']    = 'Event';
$_LANG['resource.logs.th.msg']      = 'Message';

$_LANG['resource.followups.add.widget']             = 'New Follow-up';
$_LANG['resource.followups.add.success']            = 'New Follow-up has been created.';
$_LANG['resource.followups.form.add.admin']         = 'Admin';
$_LANG['resource.followups.form.add.admin.tooltip'] = 'Select Admin that will be assigned to this follow-up';
$_LANG['resource.followups.form.date']              = 'Date';
$_LANG['resource.followups.form.date.tooltip']      = 'Choose date for follow-up';
$_LANG['resource.followups.form.type']              = 'Type';
$_LANG['resource.followups.form.descr']             = 'Description';
$_LANG['resource.followups.form.descr.placeholder'] = 'Describe your follow-up shortly';

$_LANG['resource.summary.followup.reminders.for']              = 'Reminders for';
$_LANG['resource.summary.followup.reminders.admin']            = 'Admin';
$_LANG['resource.summary.followup.reminders.client']           = 'Client';
$_LANG['resource.summary.followup.configure']                  = 'Configure';
$_LANG['resource.summary.followup.hide']                       = 'Hide';
$_LANG['resource.summary.followup.on.create']                  = 'On Create';
$_LANG['resource.summary.followup.on.create.descr']            = 'Instantly after creation';
$_LANG['resource.summary.followup.on.create.admin.explain']    = 'Notification is sent to an admin, who is assigned to a contact, immediately when a follow-up is created.';
$_LANG['resource.summary.followup.on.create.client.explain']   = 'Notification is sent to a client, who is assigned to a contact, immediately when a follow-up is created.';
$_LANG['resource.summary.followup.reminder.email']             = 'EMAIL';
$_LANG['resource.summary.followup.reminder.sms']               = 'SMS';
$_LANG['resource.summary.followup.for.date']                   = 'Follow-up Due Date';
$_LANG['resource.summary.followup.for.date.admin.explain']     = 'Notification is sent to an admin, who is assigned to a contact, on the set date.';
$_LANG['resource.summary.followup.for.date.client.explain']    = 'Notification is sent to a client, who is assigned to a contact, on the set date.';
$_LANG['resource.summary.followup.before.date']                = 'Before Follow-up Due Date';
$_LANG['resource.summary.followup.before.date.descr']          = 'e.g. half an hour earlier';
$_LANG['resource.summary.followup.before.date.admin.explain']  = 'Specify time (date/hours/minutes etc) before the Follow-up Due Date to send notification to an admin, who is assigned to a contact.';
$_LANG['resource.summary.followup.before.date.client.explain'] = 'Specify time (date/hours/minutes etc) before the Follow-up Due Date to send notification to a client, who is assigned to a contact.';

$_LANG['resource.summary.followup.form.email']                  = 'Email';
$_LANG['resource.summary.followup.form.tpl']                    = 'Template';
$_LANG['resource.summary.followup.form.admin']                  = 'Admin';
$_LANG['resource.summary.followup.form.cc']                     = 'CC';
$_LANG['resource.summary.followup.form.reply']                  = 'Reply To';
$_LANG['resource.summary.followup.form.timebefore']             = 'Define Time';
$_LANG['resource.summary.followup.form.timebefore.esplain']     = 'Configure the time when a reminding notification should be sent.';
$_LANG['resource.summary.followup.form.timebeforesms.esplain']  = 'Configure the time when a reminding notification should be sent.';
$_LANG['resource.summary.followup.admin.reminders.helperdescr'] = 'Reminders will be sent to a selected email address or phone number that is provided in SMS Center for WHMCS.';
$_LANG['resource.summary.followups.widget.name']                = 'Follow-ups';

$_LANG['resource.emails.widget.name']              = 'Emails';
$_LANG['resource.emails.noclientmsg']              = 'Unfortunately, no client is assigned to this Lead and there are no emails configured.';
$_LANG['resource.emails.sendtext']                 = 'Send Email';
$_LANG['resource.emails.new.send.success']         = 'Email has been sent';
$_LANG['resource.emails.form.from']                = 'From';
$_LANG['resource.emails.form.from.tooltip']        = 'Choose CRM mailbox configuration or one of Magento system emails.';
$_LANG['resource.emails.form.to']                  = 'To';
$_LANG['resource.emails.form.to.tooltip']          = 'You can select email address assigned to a client or an email assigned to this lead';
$_LANG['resource.emails.form.tpl']                 = 'Template';
$_LANG['resource.emails.form.tpl.tooltip']         = 'Instead of composing the message from scratch, you can use a ready-made template';
$_LANG['resource.emails.form.subject']             = 'Subject';
$_LANG['resource.emails.form.subject.placeholder'] = 'Enter subject here';
$_LANG['resource.emails.form.content']             = 'Main Content';
$_LANG['resource.emails.form.content.placeholder'] = 'Type in the text of the message here!';
$_LANG['resource.emails.form.files']               = 'Files';
$_LANG['resource.emails.log.widget']               = 'Email Log';
$_LANG['resource.emails.log.th.date']              = 'Date';
$_LANG['resource.emails.log.th.to']                = 'To';
$_LANG['resource.emails.log.th.followup']          = 'Follow-up';
$_LANG['resource.emails.log.th.reminder']          = 'Reminder';
$_LANG['resource.emails.log.th.subj']              = 'Subject';
$_LANG['resource.emails.log.th.cc']                = 'Copy To';
$_LANG['resource.emails.log.th.attachment']        = 'Attachments';

$_LANG['resource.header.tab.files']                   = 'Files';
$_LANG['resource.files.widget.name']                  = 'Add New File';
$_LANG['resource.files.added.success']                = 'File has been added';
$_LANG['resource.files.form.select']                  = 'Select File To Upload';
$_LANG['resource.files.form.btn.upload']              = 'Upload';
$_LANG['resource.files.form.description']             = 'File Description';
$_LANG['resource.files.form.description.placeholder'] = 'Type here description of uploaded file';
$_LANG['resource.files.th.filename']                  = 'File Name';
$_LANG['resource.files.th.uploader']                  = 'Uploaded By';
$_LANG['resource.files.th.uploaded']                  = 'Uploaded On';
$_LANG['resource.files.th.filesize']                  = 'Size';
$_LANG['resource.files.th.description']               = 'Description';
$_LANG['resource.files.list.widget.name']             = 'Files';

$_LANG['resource.followup.edit.widget.name']            = 'Follow-up #';
$_LANG['resource.followup.edit.descr']                  = 'Description';
$_LANG['resource.followup.edit.type']                   = 'Type';
$_LANG['resource.followup.edit.date']                   = 'Date';
$_LANG['resource.followup.edit.admin']                  = 'Admin';
$_LANG['resource.followup.edit.update.reminders']       = 'Update Reminders';
$_LANG['resource.followup.edit.update.reminders.descr'] = 'When enabled, each reminder date will be adjusted according to the changed date.';

$_LANG['resource.followup.reminder.add.widget']       = 'Add Reminder Of Follow-up #';
$_LANG['resource.followup.reminder.add.succes']       = 'New Reminder has been created';
$_LANG['resource.followup.reminder.date']             = 'Reminder Date';
$_LANG['resource.followup.reminder.date.required']    = 'You need to specify date for this reminder.';
$_LANG['resource.followup.reminder.for']              = 'Reminder For';
$_LANG['resource.followup.reminder.for.required']     = 'You need to specify who will receive this reminder';
$_LANG['resource.followup.reminder.method']           = 'Method';
$_LANG['resource.followup.reminder.method.required']  = 'You need to specify how a reminder will be sent';
$_LANG['resource.followup.reminder.admin']            = 'Choose Admin';
$_LANG['resource.followup.reminder.admin.required']   = 'Choose Administrator who will receive this reminder';
$_LANG['resource.followup.reminder.tpl']              = 'Choose Template';
$_LANG['resource.followup.reminder.tpl.required']     = 'Choose Template that will be used to send the reminder';
$_LANG['resource.followup.reminder.cc']               = 'CC';
$_LANG['resource.followup.reminder.cc.required']      = 'Choose admins who will receive a copy of this reminder';
$_LANG['resource.followup.reminder.reply']            = 'Reply To';
$_LANG['resource.followup.reminder.reply.required']   = 'Choose Admin who will be set up as a person to reply to this reminder email';
$_LANG['resource.followup.reminder.reminders.widget'] = 'List Of Reminders';
$_LANG['resource.followup.reminders.th.method']       = 'Method';
$_LANG['resource.followup.reminders.th.status']       = 'Status';
$_LANG['resource.followup.reminders.th.tpl']          = 'Template';
$_LANG['resource.followup.reminders.th.remind']       = 'Remind';
$_LANG['resource.followup.reminders.to.client']       = 'Client';
$_LANG['resource.followup.reminders.none']            = 'There are no reminders of this follow-up';


$_LANG['reminder.modal.header']                    = 'Edit Reminder #';
$_LANG['reminder.modal.dateto']                    = 'Reminder Date';
$_LANG['reminder.modal.edit.date.required']        = 'You need to specify date of this reminder.';
$_LANG['reminder.modal.edit.for']                  = 'Reminder For';
$_LANG['reminder.modal.edit.admin']                = 'Admin';
$_LANG['reminder.modal.edit.method']               = 'Method';
$_LANG['reminder.modal.edit.method.email']         = 'Email';
$_LANG['reminder.modal.edit.method.sms']           = 'SMS';
$_LANG['reminder.modal.edit.choseadmin']           = 'Choose Admin';
$_LANG['reminder.modal.edit.choseadmin.required']  = 'Choose Administrator who will receive this reminder';
$_LANG['reminder.modal.edit.chose.tpl']            = 'Choose Template';
$_LANG['reminder.modal.edit.chose.tpl.required']   = 'Choose Template that will be used to send this reminder';
$_LANG['reminder.modal.edit.chose.cc']             = 'CC';
$_LANG['reminder.modal.edit.chose.cc.required']    = 'Choose admins who will receive a copy of this reminder';
$_LANG['reminder.modal.edit.chose.reply']          = 'Reply To';
$_LANG['reminder.modal.edit.chose.reply.required'] = 'Choose Admin who will be set up as a person to reply to this reminder email';

$_LANG['reminders.modal.details.header'] = 'Reminders Of Follow-up #';
$_LANG['reminders.modal.btn.edit']       = 'Edit Reminders';
$_LANG['reminders.modal.foradminid']     = 'Admin #';
$_LANG['reminders.modal.client']         = 'Client';


$_LANG['migration.navtab.overview']            = 'Migration Overview';
$_LANG['migration.navtab.statuses']            = 'Statuses';
$_LANG['migration.navtab.fields']              = 'Fields';
$_LANG['migration.navtab.finish']              = 'Finish Migration';
$_LANG['migration.text.notice']                = 'Notice';
$_LANG['migration.text.overview.notice']       = "Please note that <strong>CRM 2.x.x</strong> is a completely rewritten module (compared to 1.x.x version). In order to keep compatibility with previous versions we prepared a tool called 'Migrator'. Here you can safely migrate data from your previous version.<br />Keep in mind that follow-ups will not be migrated due to structure incompatibility.";
$_LANG['migration.text.overview.not.detected'] = 'Not detected';
$_LANG['migration.text.overview.last.version'] = 'Last CRM version installed';
$_LANG['migration.text.overview.notfoundcrm']  = 'System did not detect any active previous instances of CRM module. There is nothing to migrate.';
$_LANG['migration.text.overview.incompatible'] = 'You current version is not compatible. In order to use migrator properly, please update your old CRM to <b>1.2.4</b> version.';
$_LANG['migration.text.overview.compatible']   = 'You have compatible and active version of CRM module. You might want to migrate data from the old version to the current one.';
$_LANG['migration.text.overview.flow.header']  = 'Migration Flow';
$_LANG['migration.text.overview.flow.content'] = " <li>You need to manually add and configure used statuses in the latest CRM version</li>
                                                        <li>You need to manually add personal custom fields to the latest CRM version</li>
                                                        <li>You need to configure mapper for statuses, so you can map status from your old CRM, to status in CRM V2. To illustrate, you can map 'Active' (from old CRM) status with 'Prospering' status into CRM V2.x.x (or of course to the same status name).</li>
                                                        <li>You need to configure mapper for custom fields, so you can map fields from old CRM, to CRM V2.x.x. This configuration is crucial, since both system can have different custom fields, and you need to specify what value from old custom field you want to assign to the new version.</li>
                                                        <li>Before running migration, we strongly suggest making a backup of your database (tables with prefix 'crm_' are responsible for CRM V2.x.x).</li>";
$_LANG['migration.text.overview.bottom.info']    = "Migrator will generate logs to file. This will allow you to check migration process and catch records that might encounter some problems during migration (for example mapping text fields to non existing value in 'select' field).
                                                      <br />Detailed logs can be found in <code>modules/addons/mgCRM2/app/Storage/logs</code> directory.";
$_LANG['migration.text.map.empty.status']        = 'This status will not be set';
$_LANG['migration.text.map.statuses.widget']     = 'Statuses in CRM <b>1.2.4</b>';
$_LANG['migration.text.confirmation']            = 'Confirmation';
$_LANG['migration.btn.configure.fields']         = 'Configure Fields Mapping';
$_LANG['migration.btn.begin.conf']               = 'Begin Migration';
$_LANG['migration.btn.last.step']                = 'Go To Last Step';
$_LANG['migration.text.finish.widget']           = 'Finish Migration';
$_LANG['migration.text.statusescheck']           = 'I have properly set mapping for <b>statuses</b>';
$_LANG['migration.text.fieldscheck']             = 'I have properly set mapping for <b>custom fields</b>';
$_LANG['migration.text.map.fields.widget']       = 'Fields in CRM <b>1.2.4</b>';
$_LANG['migration.text.map.empty.field']         = 'This field will not be ported (there will be an empty value)';
$_LANG['migration.how.many.to.import.1']         = 'There are';
$_LANG['migration.how.many.to.import.2']         = 'leads/potentials to import from old CRM module';
$_LANG['migration.text.finish.proceed']          = 'Are you sure you want to proceed ?';
$_LANG['migration.text.finish.rewerseinfo']      = 'In case of reversing this migration you can either restore backup or manually remove rows that start from';
$_LANG['migration.text.finish.btn.start']        = 'Start Migration';
$_LANG['migration.text.finish.result.widget']    = 'Migration Result';
$_LANG['migration.text.finish.result.log']       = 'Detailed logs can be found in file';
$_LANG['migration.text.finish.result.directory'] = 'directory.';

$_LANG['settings.permisions.no.available.roles'] = "There are no admin roles available to assign. Either all roles are assigned as <b>Full Access Admins</b> or there are no other roles that have access to module.";

// Asterisk integration
$_LANG['asterisk.calloutwidget.title']      = 'Asterisk Manager Call Out Widget';
$_LANG['asterisk.current.call']             = 'Current Call';
$_LANG['asterisk.current.call.status']      = 'Current Call Status:';
$_LANG['asterisk.waiting.input']            = 'Waiting for an input...';
$_LANG['asterisk.destination.number']       = 'Destination Number';
$_LANG['asterisk.oryginal.extension']       = 'Original Extension';
$_LANG['asterisk.oryginal.try.call']        = 'Try to Originate a Call';
$_LANG['asterisk.call.originating']         = 'Originating...';

//////////////////////////////////////
// Outgoing Mailbox Configuration List
//////////////////////////////////////
$_LANG['mailbox.list.widgetname']              = 'Outgoing Mailbox';
$_LANG['mailbox.widget.add.main']              = 'New Outgoing Mailbox';

// Outgoing Mailbox Configuration Form Create
$_LANG['mailbox.list.btn.create']              = 'Create Mailbox';
$_LANG['mailbox.widget.add.mailbox']           = 'Mailbox Details';
$_LANG['mailbox.form.name']                    = 'Name';
$_LANG['mailbox.form.name.descr']              = 'The name of the Mailbox';
$_LANG['mailbox.form.name.placeholder']        = 'Type in a short name';
$_LANG['mailbox.form.description']             = 'Description';
$_LANG['mailbox.form.description.descr']       = 'Longer description of Mailbox, visible only on the list.';
$_LANG['mailbox.form.description.placeholder'] = 'Describe this Mailbox shortly';
$_LANG['mailbox.button.add.new']               = 'Add Mailbox';

// Outgoing Mailbox Configuration Form Edit
$_LANG['mailbox.edit.header']                  = 'EDIT OUTGOING MAILBOX #';
$_LANG['mailbox.button.edit.update']           = 'Update';

$_LANG['mailbox.form.email']                   = 'Email';
$_LANG['mailbox.form.email.placeholder']       = 'Outgoing Mailbox Email';
$_LANG['mailbox.form.server']                  = 'Server';
$_LANG['mailbox.form.server.placeholder']      = 'SMTP Server Address (e.g. smtp.example.com)';
$_LANG['mailbox.form.username']                = 'Username';
$_LANG['mailbox.form.username.placeholder']    = 'SMTP Username/Login (e.g. user@example.com)';
$_LANG['mailbox.form.password']                = 'Password';
$_LANG['mailbox.form.password.descr']          = 'SMTP Password.';
$_LANG['mailbox.form.password.placeholder']    = '';
$_LANG['mailbox.form.encryption']              = 'Encryption';
$_LANG['mailbox.form.encryption.descr']        = 'Encryption type.';
$_LANG['mailbox.form.port']                    = 'Port';
$_LANG['mailbox.form.port.placeholder']        = 'SMTP Port (e.g. 465)';
// th
$_LANG['mailbox.list.th.name']                 = 'Name';
$_LANG['mailbox.list.th.description']          = 'Description';
$_LANG['mailbox.list.th.email']                = 'Email';





//////////////////
// Email Templates
//////////////////
// list
$_LANG['emailtemplates.list.btn.create']              = 'Create Template';
$_LANG['emailtemplates.list.widgetname']              = 'Email Templates';
$_LANG['emailtemplates.widget.add.main']              = 'New Email Template';
// add
$_LANG['emailtemplates.widget.add.main']          = 'New Email Template';
$_LANG['emailtemplates.widget.add.emailtemplate'] = 'Email Template Details';
$_LANG['emailtemplates.form.name']                = 'Name';
$_LANG['emailtemplates.form.name.placeholder']    = 'The name of the Email Template';
$_LANG['emailtemplates.form.type']                = 'Type';
$_LANG['emailtemplates.form.type.descr']          = 'Email Template type (admin/crm/general)';
$_LANG['emailtemplates.form.subject']             = 'Subject';
$_LANG['emailtemplates.form.subject.placeholder'] = 'Email subject';
$_LANG['emailtemplates.form.message']             = 'HTML';
$_LANG['emailtemplates.button.add.new']           = 'Add Email Template';
// edit
$_LANG['emailtemplates.edit.header']             = 'EDIT EMAIL TEMPLATE #';
$_LANG['emailtemplates.button.edit.update']           = 'Update';
// th
$_LANG['emailtemplates.list.th.email']            = 'Email';
$_LANG['emailtemplates.list.th.name']             = 'Name';
$_LANG['emailtemplates.list.th.type']             = 'Type';
$_LANG['emailtemplates.list.th.subject']          = 'Subject';





// Campaigns list
$_LANG['campaigns.list.widgetname']             = 'Campaigns';
$_LANG['campaigns.widget.add.main']             = 'New Campaign';
$_LANG['campaigns.widget.add.campaign']         = 'Campaign Details';
$_LANG['campaigns.widget.add.filters']          = 'Campaign Filters';
$_LANG['campaigns.add.campaign.mached.widget']  = 'Found records';

// Campaign Form Create
$_LANG['campaigns.edit.header']             = 'EDIT CAMPAIGN #';
$_LANG['campaigns.list.btn.create']         = 'Create Campaign';
$_LANG['campaigns.form.name']               = 'Name';
$_LANG['campaigns.form.name.descr']         = 'It will be used as an identifier in various dropdown menus';
$_LANG['campaigns.form.name.placeholder']   = 'Type in a short name';
$_LANG['campaigns.form.admins']             = 'Admins';
$_LANG['campaigns.form.color']              = 'Color';
$_LANG['campaigns.form.admins.descr']       = 'Selected admins will be allowed to view records assigned to this campaign and manage them.';
$_LANG['campaigns.form.description']             = 'Description';
$_LANG['campaigns.form.description.descr']       = 'Longer description of campaign, visible only on campaigns list.';
$_LANG['campaigns.form.description.placeholder'] = 'Describe the purpose of this campaign shortly';
$_LANG['campaigns.form.date_start']         = 'Starting Date';
$_LANG['campaigns.form.date_end'] = 'Ending Date';

$_LANG['campaigns.filters.static.name'] = 'Name';
$_LANG['campaigns.filters.static.type'] = 'Contact Type';
$_LANG['campaigns.filters.static.status'] = 'Status';
$_LANG['campaigns.filters.static.email'] = 'Email';
$_LANG['campaigns.filters.static.phone'] = 'Phone';
$_LANG['campaigns.filters.static.priority'] = 'Priority';
$_LANG['campaigns.filters.static.client'] = 'Client';
$_LANG['campaigns.filters.static.ticket'] = 'Ticket';

$_LANG['campaigns.list.th.name'] = 'Name';
$_LANG['campaigns.list.th.description'] = 'Description';
$_LANG['campaigns.list.th.datestart'] = 'Starting Date';
$_LANG['campaigns.list.th.dateend'] = 'Ending Date';
$_LANG['campaigns.list.th.assigned'] = 'Records';
$_LANG['campaigns.list.th.active'] = 'Status';
$_LANG['campaigns.status.active'] = 'Active';
$_LANG['campaigns.status.inactive'] = 'Inactive';
$_LANG['campaigns.list.th.admins'] = 'Admins';

$_LANG['campaigns.tooltip.force.reasign'] = 'Force records reassignment';
$_LANG['campaigns.button.add.new'] = 'Add Campaign';
$_LANG['campaigns.button.edut.update'] = 'Update';
$_LANG['campaigns.button.refresh.table'] = 'Show Matching Records';
$_LANG['campaigns.form.filter.empty'] = 'Will be filtered by empty/not set value';
$_LANG['campaigns.form.date.end.helper'] = 'Campaign will end on the selected date.';
$_LANG['campaigns.form.date.end.helper.descr'] = 'Campaign will be visible for assigned admins during the selected date range.';
$_LANG['campaigns.form.date.start.helper'] = 'Campaign will start on the selected date.';
$_LANG['campaigns.form.system.fields'] = 'System Fields';
$_LANG['campaigns.changable.in.header'] = 'Campaign';
$_LANG['campaigns.changable.noy.in.any'] = 'Not Applied';
$_LANG['campaigns.changable.all'] = 'Any';
$_LANG['admins.changable.any'] = 'Any';
$_LANG['admins.changable.not.assigned'] = 'Not Applied';

// NOTIFICATIONS
$_LANG['notifications.list.widgetname'] = 'Notifications';
$_LANG['notifications.add.widget']         = 'New Notification';
$_LANG['notifications.edit.widget']         = 'Edit Notification';
$_LANG['notifications.list.btn.create']         = 'Create Notification';
$_LANG['notifications.form.importance']         = 'Importance';
$_LANG['notifications.form.importance.normal']         = 'Normal';
$_LANG['notifications.form.importance.info']         = 'Information';
$_LANG['notifications.form.importance.warning']         = 'Warning';
$_LANG['notifications.form.importance.danger']         = 'Danger';
$_LANG['notifications.form.type']         = 'Type';
$_LANG['notifications.form.type.temporary']         = 'Temporary';
$_LANG['notifications.form.type.permanent']         = 'Permanent';
$_LANG['notifications.form.admins']         = 'Admins';
$_LANG['notifications.form.admins.descr']         = 'Choose Administrators to display this notification';
$_LANG['notifications.form.content']         = 'Message';
$_LANG['notifications.form.content.placeholder']         = 'Type message content';
$_LANG['notifications.form.content.descr']         = 'HTML code is allowed';
$_LANG['notifications.form.date_start']         = 'Starting Date';
$_LANG['notifications.form.date_end'] = 'Ending Date';
$_LANG['notifications.form.dates.descr'] = 'Notification will be displayed for selected period of time';
$_LANG['notifications.button.add.new'] = 'Create Notification';
$_LANG['notifications.form.confirmation'] = 'Confirmation';
$_LANG['notifications.form.confirmation.descr'] = 'Require confirmation from admin';
$_LANG['notifications.form.hideafterconfirm'] = 'Hide Once Accepted';
$_LANG['notifications.form.hideafterconfirm.descr'] = 'Once notification is accepted, it will no longer be visible for administrator';
$_LANG['notifications.list.th.importance'] = 'Importance';
$_LANG['notifications.list.th.type'] = 'Type';
$_LANG['notifications.list.th.content'] = 'Content';
$_LANG['notifications.list.th.datestart'] = 'Starting Date';
$_LANG['notifications.list.th.dateend'] = 'Ending Date';
$_LANG['notifications.table.dates.permanent'] = 'Does Not Apply';
$_LANG['notifications.list.th.assigned'] = 'Admins';
$_LANG['notifications.list.th.accepted'] = 'Accepted';
$_LANG['notifications.list.th.active'] = 'Active';
$_LANG['notifications.table.confirmation.disabled'] = 'Confirmation is disabled';
$_LANG['notifications.table.confirmation.noone'] = 'No one confirmed this message yet';
$_LANG['notifications.button.form.edit'] = 'Update';
$_LANG['notifications.main.widget.single'] = 'You have <span class="bold">{{num}}</span> Notification';
$_LANG['notifications.main.widget.many'] = 'You have <span class="bold">{{num}}</span> Notifications';
$_LANG['notifications.main.btn.accept'] = 'Accept';
$_LANG['notifications.main.accepted'] = 'Accepted:';



///////////////////////////////////
// Settings > fields
///////////////////////////////////
$_LANG['settings.types.nav.widget.name']              = 'Contact Types';
$_LANG['settings.types.list.add.widget.name']         = 'New Contact Type';
$_LANG['settings.types.list.tbl.widget.name']         = 'Contact Types';
$_LANG['settings.types.add.form.name']                = 'Name';
$_LANG['settings.types.add.form.name.placeholder']    = 'Type in a name that will be available in your system';
$_LANG['settings.types.add.show.nav']                 = 'Navigation';
$_LANG['settings.types.add.show.nav.descr']           = 'If enabled, a link to the table with this contact type will be available from the main navigation menu';
$_LANG['settings.types.add.show.nav.subm']            = 'Navigation Submenu';
$_LANG['settings.types.add.show.nav.subm.descr']      = "If enabled, a link to the table with this contact type will be available from 'Contacts' submenu (second level navigation)";
$_LANG['settings.types.add.show.dashboard']           = 'Dashboard';
$_LANG['settings.types.add.show.dashboard.descr']     = 'If enabled, a new contact type will be available on dashboard to filter by';
$_LANG['settings.types.add.icon']                     = 'Icon';
$_LANG['settings.types.add.form.icon.placeholder']    = 'Type in icon format here';
$_LANG['settings.types.add.succed']                   = 'New Contact Type has been added';
$_LANG['settings.types.add.icon.descr']               = 'Icon designated to a particular contact type. Always displayed next to the contact type name in navigation menu, dashboard etc. Please use any of <a href="#!/icons" target="_blank">available icons.</a><br /> Remember to use appropriate formats as in examples: <code>fa fa-thumbs-o-up</code>, <code>glyphicon glyphicon-phone-alt</code>, <code>icon-badge</code>';

$_LANG['settings.types.tbl.th.icon']                  = 'Icon';
$_LANG['settings.types.tbl.th.show.in.nav']           = 'Navigation';
$_LANG['settings.types.tbl.th.show.in.subm']          = 'Navigation Submenu';
$_LANG['settings.types.tbl.th.show.in.dashboard']     = 'Dashboard';

$_LANG['settings.types.edit.infobox.notice']          = 'Notice';
$_LANG['settings.types.edit.infobox.txt']             = 'Changes made to a contact type are not instant (especially in relation to navigation). In order to see the changes, you need to reload the page. In some cases it may be required to reload or clear browser cache.';

$_LANG['settings.types.delete.header']               = 'Delete Contact Type';
$_LANG['settings.types.delete.convert.dont']         = "-- Do not convert";
$_LANG['settings.types.delete.convert']              = 'Covert Contacts To Type';
$_LANG['settings.types.delete.convert.error']        = 'As you want to move contacts to Archive, you need to define type to convert';
$_LANG['settings.types.delete.convert.descr']        = 'Every contact that is assigned to the deleted type, will be converted to selected here type.';
$_LANG['settings.types.delete.archive']              = 'Move To Archive';
$_LANG['settings.types.delete.archive.descr']        = 'Do not convert the contact to another type but move it to the archive. <br> If you want to move contacts of this type to archive, choose another type that will be used to describe these contacts once restored form the archive.';


///////////////////////////////////
// Utils > mass mail
///////////////////////////////////
$_LANG['utils.massmessage.widget.add.main']              = 'New Mass Message';
$_LANG['utils.massmessage.widget.edit.main']              = 'Edit Mass Message';
$_LANG['utils.massmessage.add.btn.create']               = 'New Mass Message';
$_LANG['utils.massmessage.add.targetmain']               = 'Send To';
$_LANG['utils.massmessage.add.target.users']             = 'Users';
$_LANG['utils.massmessage.add.target.users.descr']       = 'Message will be sent to all active clients in system';
$_LANG['utils.massmessage.add.target.usergroups']        = 'User Groups';
$_LANG['utils.massmessage.add.target.usergroups.descr']  = 'Message will be sent to clients assigned to the selected Client Groups';
$_LANG['utils.massmessage.add.target.campaigns']         = 'Campaigns';
$_LANG['utils.massmessage.add.target.campaigns.descr']   = 'Message will be sent to contacts from selected campaigns';
$_LANG['utils.massmessage.add.date']                     = 'Select Date';
$_LANG['utils.massmessage.add.date.descr']               = 'Date when messages shall be added to sending queue (handled by cron job)';
$_LANG['utils.massmessage.add.subject']                  = 'Subject';
$_LANG['utils.massmessage.add.subject.placeholder']      = 'Enter subject here';
$_LANG['utils.massmessage.add.subject.content']          = 'Main Content';
$_LANG['utils.massmessage.add.choose.usergrps']          = 'User Groups';
$_LANG['utils.massmessage.add.choose.campaigns']         = 'Campaigns';
$_LANG['resource.emails.form.btn.create']             = 'Create';
$_LANG['utils.massmessage.add.variables']                = 'Available Merge Fields';
$_LANG['utils.massmessage.add.type'] = 'Message Type';
$_LANG['utils.massmessage.add.type.email'] = 'Email';
$_LANG['utils.massmessage.add.type.email.descr'] = 'This message will be sent in form of an email';
$_LANG['utils.massmessage.add.type.sms']= 'SMS';
$_LANG['utils.massmessage.add.type.sms.descr']= 'This message will be sent in form of an SMS';
$_LANG['utils.massmessage.add.description']= 'Description';
$_LANG['utils.massmessage.add.description.placeholder']= 'Describe this Mass Messages configuration shortly to identify it in your system easily';

$_LANG['utils.massmessage.list.widgetname']             = 'Mass Message';
$_LANG['utils.massmessage.list.btn.create']             = 'Create Mass Message';
$_LANG['utils.massmessage.list.th.date']         = 'Date';
$_LANG['utils.massmessage.list.th.description']               = 'Description';
$_LANG['utils.massmessage.list.th.message_type']        = 'Type';
$_LANG['utils.massmessage.list.th.target_type']         = 'Target';
$_LANG['utils.massmessage.list.th.messages.pendind']       = 'In Queue';
$_LANG['utils.massmessage.list.th.messages.total']      = 'Total';
$_LANG['utils.massmessage.list.th.messages.generated']  = 'Status';
$_LANG['utils.massmessage.status.done']  = 'Finished';
$_LANG['utils.massmessage.status.pending']  = 'Pending';
$_LANG['utils.massmessage.status.sending']  = 'In Progress';

// IMPORT / EXPORT
$_LANG['settings.importexport.tab.export']  = 'Export';
$_LANG['settings.importexport.tab.import']  = 'Import';
$_LANG['settings.importexport.widget.name.explain']  = 'Available fields';
$_LANG['settings.importexport.widget.name.explain.small']  = 'and CSV format overview';
$_LANG['settings.importexport.format.choose']  = 'Choose Format';
$_LANG['settings.importexport.import.file.detected']  = 'System detected';
$_LANG['settings.importexport.import.file.rows']  = 'from uploaded file.';
$_LANG['settings.importexport.btn.export']  = 'Export';
$_LANG['settings.importexport.import.start']  = 'Perform Import';
$_LANG['settings.importexport.import.files.descr']  = 'Allowed formats: *.csv,  *.xls,  *.xlsx,  *.ods';

