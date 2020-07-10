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
 * set default parameter validation options
 */
\Slim\Route::setDefaultConditions(array(
    'id' => '\d+',
    'responseFormat' => '(html|json)',
));
$app = $this->app;
// main root route
$app->get('/', 'Modulesgarden\\Crm\\Controllers\\Home:index');


// API groupped request!
$app->group('/api', function () use ($app) {

    // just test response
    $app->get('/', 'Modulesgarden\\Crm\\Controllers\\Api\\Home:index');
    $app->get('/test(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Home:test');
    $app->get('/debug(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Home:debug');
    $app->get('/test/logs', 'Modulesgarden\\Crm\\Controllers\\Api\\Home:generateTestLogs');


    // Library group
    $app->group('/samplePage', function () use ($app) {
        $app->get('/test', 'Modulesgarden\\Crm\\Controllers\\Api\\SamplePages:test');
        $app->get('/page/:template', 'Modulesgarden\\Crm\\Controllers\\Api\\SamplePages:page');
    });


    // Routes for operation within single lead instance
    $app->group('/lead', function () use ($app) {
        // Get for Every Page
        $app->get('/:id/getLeadHeaderData(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Lead:getLeadHeaderData');
        // Get data to render summary page
        $app->get('/:id/getLeadSummaryData(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Lead:getLeadSummaryData');
    });

    // follopups
    $app->group('/dashboard', function () use ($app) {
        $app->post('/followups/get(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Dashboard\\DashboardFollowups:getForTable')
                ->name('followups/getFor');
        $app->get('/followups/get(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Dashboard\\DashboardFollowups:getForTable');
        // dashboard
        $app->post('/logs/get(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Dashboard\\DashboardLogs:getForTable');
        $app->get('/logs/get(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Dashboard\\DashboardLogs:getForTable');
        // calendar monthly summary followups
        $app->post('/calendar(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Dashboard\\DashboardCalendar:getCounters');
        $app->get('/background(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\DashboardHelper:backgroundData');
    });

    // operation to many contacts
    $app->group('/contacts', function () use ($app) {
        // Get for Every Page
        $app->get('/table/query(/:query)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ContactrsList:queryTable')
                ->name('contacts/get');
        $app->post('/table/query(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ContactrsList:queryTable');

        // Get for Every Page
        $app->get('/list(/:query)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:query');

        // give me params nesesary for create form
        $app->get('/create/getParams(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:getCreateParams');
        // create new lead ;)
        $app->post('/create(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:createLead')
                ->name('contacts/add');
        $app->get('/create(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:createLead');

        // handle smart table queries for archive
        $app->post('/archive/query(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ContactrsList:queryArchiveTable');
        $app->get('/archive/query(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ContactrsList:queryArchiveTable');
    });

    // operation to many leads /list/create/etc
    $app->group('/leads', function () use ($app) {
//        // Get for Every Page
//        $app->get(      '/list(/:query)',                           'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:query');
//        $app->post(     '/table/query(/:responseFormat)',           'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:LeadsTableQuery');
//        $app->get(      '/table/query(/:responseFormat)',           'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:LeadsTableQuery');
//
//        // give me params nesesary for create form
//        $app->get(      '/create/getParams(/:responseFormat)',      'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:getCreateParams');
//        // create new lead ;)
//        $app->post(     '/create(/:responseFormat)',                'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:createLead');
//        $app->get(      '/create(/:responseFormat)',                'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:createLead');
    });


    // potential type group
    $app->group('/potentials', function () use ($app) {
        // Get for Every Page
        $app->post('/table/query(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:PotentialsTableQuery');
        $app->get('/table/query(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesList:PotentialsTableQuery');
    });

    // most relevant lead type group
    $app->group('/lead', function () use ($app) {
        $app->post('/setResourceSessionId(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:setResourceSessionId');
        // get static fields mainly for lead header
        $app->get('/:id/getMainDetails(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:getMainDetails')
                ->name('contacts/getSingle');
        // update static lead param
        $app->post('/:id/updateSingleParam(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:updateSingleParam');
        $app->post('/:id/sync/campaigns(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:syncAssignedCampaigns');
        $app->post('/:id/updatefield(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFields:updatefield');
        // soft delete (plain put to archive :D)
        $app->post('/:id/softDelete(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:softDelete');
        // restore from archive
        $app->post('/:id/restore(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:restoreSoftDeleted');
        // reassignments
//        $app->post('/:id/reassign/ticket(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:reassignTicket');
        $app->post('/:id/reassign/client(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceSingle:reassignClient');
        // maintain many notes
        $app->get('/:id/notes/get(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:get')
                ->name('contacts/getNotes');
        $app->get('/:id/notes/get/:limit(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:getLimited');
        $app->get('/:id/notes/getWithDeleted(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:getWithDeleted');
        $app->get('/:id/notes/getWithDeleted/:limit(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:getWithDeletedLimited');
        $app->post('/:id/notes/add(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:addNote')
                ->name('contacts/addNote');
        // maintain single notes
        $app->post('/:id/notes/:noteID/edit(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:editNote');
        $app->delete('/:id/notes/:noteID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:softDelete');
        $app->delete('/:id/notes/:noteID/force(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:forceDelete');
        $app->put('/:id/notes/:noteID/resotre(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourceNotes:restoreDeleted');
        // obtain logs
        $app->get('/:id/logs(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesLogs:get');
        $app->post('/:id/logs(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesLogs:parseForTable');
        // obtain orders
        $app->get('/:id/orders(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesOrders:parseForTable');
        $app->post('/:id/orders(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesOrders:parseForTable');
        // obtain emails
        $app->get('/:id/logs/emails(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesLogEmails:get');
        $app->post('/:id/logs/emails(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesLogEmails:parseForTable');
        // send EMAIL
        $app->post('/:id/emails/send(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesLogEmails:sendEmail');
        // FILES
        $app->post('/:id/files/upload(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Files\\Manage:uploadFile');
        $app->post('/:id/files(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Files\\Manage:parseForTable');
        $app->get('/:id/files(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Files\\Manage:parseForTable');
        $app->get('/:id/files/:fileId', 'Modulesgarden\\Crm\\Controllers\\Api\\Files\\Manage:getFile');
        $app->delete('/:id/files/:fileId(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Files\\Manage:deleteFile');
        // field values
        $app->get('/:id/field/getAll(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFields:getAll');
        // maintain followups
        $app->post('/:id/followups/getForTable(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:getForTable')
                ->name('contacts/getFollowups');
        $app->post('/:id/followups/addWithReminders(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:addWithReminders');
        $app->post('/:id/followups/addWithoutReminders(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:addWithoutReminders')
                ->name('contacts/addFollowup');
        $app->get('/:id/followups/:followupID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:getSingleFollowup');
        $app->get('/:id/followups/getSingle/:followupID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:getSingleFollowupWithReminders');
        $app->put('/:id/followups/getSingle/:followupID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:updateSingleFollowup');
        $app->delete('/:id/followups/:followupID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:deleteSingleFollowup');
        $app->post('/:id/followups/:followupID/reschedue(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowups:reschedueFollowup');
        // maintain followups reminders
        $app->get('/:id/followups/:followupID/reminders(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowupReminders:get');
        $app->get('/:id/followups/:followupID/reminders/:reminderID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowupReminders:getSingle');
        $app->post('/:id/followups/:followupID/reminders(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowupReminders:createSingle')
                ->name('contacts/followups/addReminder');
        $app->post('/:id/followups/:followupID/reminders/:reminderID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowupReminders:updateSingle');
        $app->delete('/:id/followups/:followupID/reminders/:reminderID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesFollowupReminders:deleteSingle');


//        $app->post('     /ticket/:id/respond/forcewhmcs(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesTicket:sendTicketResponse');
    });

    // Routes for operation on settings pages
    // basically helers
    $app->group('/helpers', function () use ($app) {
        // select helper to obtain fast clients to fast choose
        $app->post('/select/clients(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\SelectInput:findClients');
        // select helper to obtain last tickets
//        $app->post('/select/tickets(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\SelectInput:findTicket');
        // select helper to obtain admins only
        $app->get('/select/adminToReassign(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\SelectInput:findAdminsToReassign');

        // helper to get many information used by frontend, to not separate this for many small request
        $app->get('/lead/backgroundFormData(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\SelectInput:backgroundFormData');
        // bring me a list of whmcs client custom fields
        $app->get('/get/whmcs/customfields(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\Whmcs:getCustomFields');

        // nesesary data for followups forms
        $app->get('/lead/background/followups(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\SelectInput:backgroundForFollowups');
        // manny day
        $app->get('/lead/background/all(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\Data:getLeadUsefull');

        // ticket/quote view lead summary modal dialog box
        $app->get('/popup/summary/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\Popups:resourceSummary');

        // used data for render each table resources page
        $app->get('/resources/table(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\Data:getResourcesTableHelpers');
        // mass mail helpers
        $app->get('/massmessages/get(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Helpers\\Data:getMassMessagesHelpers');
    });

    // Routes for calendar
    $app->group('/calendar', function () use ($app) {
        $app->get('/followups/my(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Followups\\ListCalendar:getMine');
        $app->get('/followups/admin/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Followups\\ListCalendar:getForAdmin')
                ->name('followups/getForAdmin');
        $app->get('/followups/admins(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Followups\\ListCalendar:getForAllAdmins');
        // get reminder for followup
        $app->get('/followups/:id/reminders(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Followups\\ListCalendar:getFollowupReminders')
                ->name('followups/getReminders');
    });

    // Routes for statistics
    $app->group('/statistics', function () use ($app) {
        $app->post('/last/per/status(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Statistics\\LastPerStatus:getForPieChart');
        $app->post('/last/ten(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Statistics\\LastTenRecords:get');
        $app->post('/total/per/admin(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Statistics\\TotalPerAdmin:get');
        $app->post('/total/per/month(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Statistics\\TotalPerMonth:get');
        $app->post('/new/yearly(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Statistics\\TotalInYear:get');
    });

    // Routes for Migration
    $app->group('/migration', function () use ($app) {
        $app->get('/overview(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Migrator\\MigrationOverview:getOverview');
        $app->post('/perform(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Migrator\\MigrationOverview:startMigration');

        $app->get('/actual/getFields(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Migrator\\MigrationOverview:getNewVersionFields');
        $app->get('/actual/getStatuses(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Migrator\\MigrationOverview:getNewVersionStatuses');

        $app->get('/old/getStatuses(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Migrator\\MigrationOverview:getOldVersionStatuses');
        $app->get('/old/getFields(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Migrator\\MigrationOverview:getOldVersionFields');
    });

    // Routes for operation on settings pages
    $app->group('/settings', function () use ($app) {

        // Settings > fields > statuses
        $app->get('/statuses(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldStatuses:query')
                ->name('settings/getContactStatuses');
        $app->get('/statuses/dashboard/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldStatuses:queryDashboard');
        $app->post('/statuses/dashboard(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldStatuses:generateSummary');
        $app->put('/statuses(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldStatuses:add');
        $app->delete('/statuses/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldStatuses:delete');
        $app->post('/statuses(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldStatuses:massUpdate');
        $app->post('/statuses/reorder(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldStatuses:reorder');


        // Settings > fields > groups
        $app->get('/fieldgroups(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldGroups:query')
                ->name('settings/getFieldGroups');
        $app->put('/fieldgroups(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldGroups:addGroup');
        $app->post('/fieldgroups/reorder(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldGroups:reorder');
        $app->post('/fieldgroups/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldGroups:updateGroup');
        $app->delete('/fieldgroups/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldGroups:deleteGroup');

        // Settings > permissions
        $app->get('/permissions/adminroles(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:getMagentoAdminRoles');
        $app->get('/permissions/groups(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:getRoles');
        $app->put('/permissions/groups(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:addRole');
        $app->post('/permissions/groups/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:updateRole');
        $app->delete('/permissions/groups/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:deleteRole');

        // Settings > permissions // others helpfull
        $app->get('/permissions/mine(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:getMinePermissions');
        $app->get('/permissions/rules/config(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:getRulesConfig');
        $app->get('/permissions/rules/parsed(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PermissionGroups:getParsedRules');

        // Settings > fields > fields
        $app->get('/fields(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:query')
                ->name('settings/getFields');
        $app->put('/fields(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:addField');
        $app->post('/fields/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:updateField');
        $app->get('/fields/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:getField');
        $app->delete('/fields/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:deleteField');
        $app->post('/fields/reorder(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:reorder');

        // Settings > fields > validators
        $app->get('/fields/:id/validators(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:getValidatorFor');
        $app->put('/fields/:id/validators(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:addValidator');
        $app->delete('/fields/:id/validators/:validatorID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:deleteValidator');

        // Settings > fields > options
        $app->get('/fields/:id/options(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:getFieldOption');
        $app->put('/fields/:id/options(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:addFieldOption');
        $app->delete('/fields/:id/options/:optionsID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:deleteFieldOption');
        $app->post('/fields/:id/options/:optionsID(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldsManage:updateOption');

        $app->get('/fields/withgroups(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldGroups:queryFields')
                ->name('settings/getFieldsWithGroups');

        // configure views
        $app->get('/fields/views(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldView:allColumns');
        $app->get('/fields/views/forme(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldView:getForAdmin');
        $app->get('/fields/views/for/:type(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldView:getTopeForAdmin');
        $app->post('/fields/views/store(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\FieldView:updateForAdmin');


        // Settings > fields > map
        $app->get('/fields/map/get(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\ManageSettings:getFieldsMap');
        $app->post('/fields/map/update(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\ManageSettings:updateFieldsMap');

        // Settings > general > followup types
        $app->get('/general/followupType(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\TypeFollowups:get')
                ->name('followups/getTypes');
        $app->put('/general/followupType(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\TypeFollowups:addType');
        $app->post('/general/followupType/reorder(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\TypeFollowups:reorder');
        $app->post('/general/followupType/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\TypeFollowups:updateType');
        $app->delete('/general/followupType/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\TypeFollowups:deleteType');

        // Personal Settings > manage
        $app->get('/personal(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PersonalSettings:get');
        $app->post('/personal(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\PersonalSettings:update');

        // General Settings Manage
        $app->get('/general(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\GeneralSettings:get');
        $app->get('/generalWithStatus(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\GeneralSettings:getWithStatus');
        $app->post('/general(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Settings\\GeneralSettings:update');

        // Settings Contact Types
        $app->get('/types/table(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesTypes:get')
                ->name('settings/getContactTypes');
        ;
        $app->post('/types/update/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesTypes:updateSingleParameter');
        $app->post('/types/reorder(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesTypes:reorder');
        $app->post('/types/new(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesTypes:addType');
        $app->post('/types/delete(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Resources\\ResourcesTypes:delete');
    });

    // Routes for Campaigns
    $app->group('/campaigns', function () use ($app) {
        $app->get('/filters(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:getAllColumnsForFilters');
        $app->post('/resources/query(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:ResourcesTableQueryByFilters');
        $app->get('/resources/query(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:ResourcesTableQueryByFilters');

        $app->get('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:getCampaignList')
                ->name('campaigns/getList');
        $app->post('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:getCampaignList');
        $app->put('/create(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:createCampaign');

        $app->get('/refresh/:id/filters(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:refreshCampaignAssignments');
        $app->get('/get/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:getCampaign');
        $app->post('/update/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:updateCampaign');
        $app->delete('/delete/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Campaigns\\CampaignsHelpers:deleteCampaign');
    });
    
        // Routes for Mailbox
    $app->group('/mailbox', function () use ($app)
    {
        $app->post('/resources/query(/:responseFormat)',     'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:ResourcesTableQueryByFilters');
        $app->get( '/resources/query(/:responseFormat)',     'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:ResourcesTableQueryByFilters');

        $app->get( '/list(/:responseFormat)',                'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:getMailboxList')
            ->name('mailbox/getList');
        $app->post('/list(/:responseFormat)',                'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:getMailboxList');
        $app->put( '/create(/:responseFormat)',              'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:createMailbox');

        $app->get( '/refresh/:id/filters(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:refreshMailboxAssignments');
        $app->get( '/get/:id(/:responseFormat)',             'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:getMailbox');
        $app->post('/update/:id(/:responseFormat)',          'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:updateMailbox');
        $app->delete( '/delete/:id(/:responseFormat)',       'Modulesgarden\\Crm\\Controllers\\Api\\Mailbox\\MailboxHelpers:deleteMailbox');

    });

    // Routes for Email Templates
    $app->group('/emailtemplates', function () use ($app) {
        $app->get('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\EmailTemplates\\EmailTemplatesHelpers:getEmailTemplatesList')
                ->name('mailbox/getList');
        $app->post('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\EmailTemplates\\EmailTemplatesHelpers:getEmailTemplatesList');
        $app->put('/create(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\EmailTemplates\\EmailTemplatesHelpers:createEmailTemplate');

        $app->get('/get/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\EmailTemplates\\EmailTemplatesHelpers:getEmailTemplate');
        $app->post('/update/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\EmailTemplates\\EmailTemplatesHelpers:updateEmailTemplate');
        $app->delete('/delete/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\EmailTemplates\\EmailTemplatesHelpers:deleteEmailTemplate');
    });

    // Routes for Cotifications
    $app->group('/notifications', function () use ($app) {
        $app->get('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:getNotificationList');
        $app->post('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:getNotificationList');
        $app->put('/create(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:createNotification');

        $app->get('/mine(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:getMineNotification')
                ->name('notifications/get');
        $app->get('/get/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:getNotification');
        $app->delete('/delete/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:deleteNotification');
        $app->post('/update/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:updateNotification');
        $app->post('/accept(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\Notifications\\NotificationsHelpers:acceptNotification');
    });

    // Routes for Mass Messages
    $app->group('/massmessages', function () use ($app) {
        $app->get('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\MassMessages\\MassMessagesActions:getForTable');
        $app->post('/list(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\MassMessages\\MassMessagesActions:getForTable');
        $app->get('/get/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\MassMessages\\MassMessagesActions:getSingle');

        $app->put('/create(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\MassMessages\\MassMessagesActions:addConfig');
        $app->post('/update/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\MassMessages\\MassMessagesActions:updateConfig');
        $app->delete('/delete/:id(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\MassMessages\\MassMessagesActions:deleteConfig');
    });

    // Routes for Import Esport
    $app->group('/importexport', function () use ($app) {
        $app->post('/import/upload(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\ImportExport\\Import:uploadFile');
        $app->get('/import/summary(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\ImportExport\\Import:getFileSummaryFile');
        $app->post('/import/start(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\ImportExport\\Import:importContacts');
        $app->get('/import/start(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Api\\ImportExport\\Import:importContacts');
        $app->get('/export/:type', 'Modulesgarden\\Crm\\Controllers\\Api\\ImportExport\\Export:exportContacts');
    });
});

// email preview
$app->get('/email/show/:id', 'Modulesgarden\\Crm\\Controllers\\Home:emailPreview');
// test rute
$app->get('/test(/:responseFormat)', 'Modulesgarden\\Crm\\Controllers\\Home:test');
