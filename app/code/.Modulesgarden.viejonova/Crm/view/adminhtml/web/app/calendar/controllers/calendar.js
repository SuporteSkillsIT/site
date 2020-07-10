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
 * 
 *  Setup Rounting For All Pages 
 */
mgCRMapp.config([
            'calendarConfigProvider',
    function(calendarConfigProvider) {
        
//    set up some calendar config

    calendarConfigProvider.setDateFormatter('moment'); // use either moment or angular to format dates on the calendar. Default angular. Setting this will override any date formats you have already set.

    calendarConfigProvider.setDateFormats({
      hour: 'HH:mm' // this will configure times on the day view to display in 24 hour format rather than the default of 12 hour
    });

    calendarConfigProvider.setTitleFormats({
      day: 'ddd D MMM YYYY' //this will configure the day view title to be shorter
    });
}]);


angular.module("mgCRMapp").controller(
        'leadsCalendarController',
        ['$rootScope', '$scope',   '$stateParams', '$timeout', 'ngDialog', '$q', 'blockUI', '$ocLazyLoad', '$http', '$state', '$translate', 'AclService',
function( $rootScope,   $scope,     $stateParams,   $timeout,   ngDialog,   $q,   blockUI,   $ocLazyLoad,   $http,   $state,   $translate,   AclService)
{
    // dont aske me why ...
    // just leave it here, this help autoloader to parse loaded libs to not crash -.-
    $ocLazyLoad.isLoaded(['mwl.calendar', 'moment.min.js']);

    $scope.requestForAdmin  = $rootScope.currentAdminID;
    
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    // events for calendar
    $scope.events                   = [];
    // just for messages
    $scope.scopeMessages            = [];
    // container for admins array
    $scope.admins                   = [];


    $scope.translations = {};

    $translate([
        'lead', 
        'potential', 
        'reminders', 
        'msg.success',
        'msg.error', 
        'msg.confirm.delete.followup', 
        'msg.confirm.yes', 
        'msg.confirm.no', 
        'msg.followup.deleted']).then(function (transl) {
        $scope.translations        = transl;
    });


    // Get the reference to the block service.
    $scope.calendarBlock            = blockUI.instances.get('calendarBlock');

    // settings from resolve
    $scope.settings    = {};
    
    $scope.followupTypes = [];


    /**
     * ACL - allow user to preview other admin's dashboard
     */
    if(AclService.can('calendar.other_followups')) {
        $scope.allowChangeAdmin = true;
    } else {
        $scope.allowChangeAdmin = false;
    }
    if(AclService.can('calendar.other_delete')) {
        $scope.allowAdminDeleteFollowups = true;
    } else {
        $scope.allowAdminDeleteFollowups = false;
    }
    if(AclService.can('calendar.other_reschedue')) {
        $scope.allowAdminReschedueFollowups = true;
    } else {
        $scope.allowAdminReschedueFollowups = false;
    }

    // breadcast event to inform controler to renew requests for other admin data
    var followupActiveAdmin = $scope.$watch('requestForAdmin', function(newVal, oldVal) {
        if( newVal ==  oldVal ) { return; }
        
        $scope.getFollowups();
    });
    
    // parse some initial info to use in form after
    parseSettings = function(){
        $scope.settings.usedatatime         = !$rootScope.settings.config.app.followups_per_day;
    };
    parseSettings(); // trigger on init
    
    
    $translate('form.reschedule').then(function (txt) {
        $scope.htmlEdit = "'<button class=\'btn btn-xs btn-inverse btn-primary\'>" + txt + "</button>'";
    });
    $translate('form.delete').then(function (txt) {
        $scope.htmlDlt  = "'<button class=\'btn btn-xs btn-inverse  btn-danger\'>" + txt + "</button>'";
    });

    
    /**
     * just get followup types to render them correctly
     * there will be more stuff available here but lets dont care about that here
     */
    getDataForFormsInBackground = function()
    {
        // come on give me data from backend
        $http.get($rootScope.settings.config.apiURL + '/helpers/lead/background/followups/json', {
            cache: true,
        }).then(function(result) 
        {
            $scope.followupTypes      = result.data.followup.types;
            $scope.admins             = result.data.admins;
        });
    };
    getDataForFormsInBackground();
    
    
    /**
     * just get followup types to render them correctly
     */
    
    $scope.getFollowups = function()
    {
        $scope.calendarBlock.start();
        if($scope.requestForAdmin) {
            url = $rootScope.settings.config.apiURL + '/calendar/followups/admin/' + $scope.requestForAdmin + '/json';
        } else {
            url = $rootScope.settings.config.apiURL + '/calendar/followups/admins/json';
        }
        // come on give me data from backend
        $http.get(url, {
            cache: false,
            isArray: true
        }).then(function(result) 
        {
            $scope.parseFollowupToEvents(result.data);
            
            $scope.calendarBlock.stop();
        });
    };
    $scope.getFollowups();
    
    
    /**
     * function used to parse backend folowups
     * to calendar events
     * basically generate title for each event
     */
    $scope.parseFollowupToEvents = function(followups)
    {
        $scope.events = [];
        var events = [];
        
        for(i=0; i < followups.length; i++)
        {
            // plain object
            var e = {};
            // generate title html
            e.title = '<span class="event-lead-link primary-font">';
//            e.title = '<a class="event-lead-link" ui-sref="contacts.details.summary({id: '+ followups[i].resource_id +' })">';
            if(followups[i].resource.type_icon) {
                e.title = e.title + ' <i class="' + followups[i].resource.type_icon + '"></i> ';
            }
            e.title = e.title + ' #' + followups[i].resource_id + ' ' + followups[i].resource.name + '</span>';
            e.title = e.title + '<b> ' + followups[i].description + '</b>';
            
            if(followups[i].reminders_count != null && typeof(followups[i].reminders_count) == 'object') {
                e.title = e.title + ' <span class="font-grey-cascade">('+ followups[i].reminders_count.count + ' ' + $scope.translations.reminders + ')</span>';
            }
            
            // build date ( start and end )
//            e.startsAt = new Date(followups[i].date);
            e.startsAt = moment(followups[i].date).toDate();
            
            e.endsAt = moment(followups[i].date).add(1,'hours').toDate();
            // set up type for colors
            e.type = 'calendar-type' + followups[i].type_id;
            
            // If edit-event-html is set and this field is explicitly set to false then dont make it editable.
            e.editable = false;
            // If delete-event-html is set and this field is explicitly set to false then dont make it deleteable
            e.deletable = false;
            
            // get from acl to override that settings
            if($scope.allowAdminReschedueFollowups === true) {
                e.editable = true;
            }
            if($scope.allowAdminDeleteFollowups === true) {
                e.deletable = true;
            }

            //Allow an event to be dragged and dropped
            e.draggable             = false;
            //Allow an event to be resizable
            e.resizable             = false; 
            //If set to false then will not count towards the badge total amount on the month and year view
            e.incrementsBadgeTotal  = true; 
            
            // e.endsAt: new Date(2014,8,26,15), // Optional - a javascript date object for when the event ends
            
            e.data                  = followups[i];
            
            events.push(e);
            delete(e);
        }
        
        $scope.events = [];
        $scope.events = events;
            
        return true;
    }
    
    // set up startup point of view
    $scope.calendarView = 'month';
    // set up calendar title
    $scope.viewTitle = 'Follow-ups schedule';
    // set up TODAY date
    $scope.currentDay = new Date();
    
    
    /**
     * This function is wrapper for calendar Event Click
     * Basically show modal with Followup details
     */
    $scope.eventCliecked = function(event)
    {
        
        $scope.modal = ngDialog.open({ 
            template: $rootScope.settings.config.rootDir + '/app/calendar/templates/modals/followup.html',
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false,
            data: {
                followup: event.data,
                admins:   $scope.admins,
            },
            controller: ['$scope', 'blockUI', '$http', function($scope, blockUI, $http) {
                // container for reminders array
                $scope.reminders                   = [];


                // Get the reference to the block service.
                $scope.remindersTable            = blockUI.instances.get('remindersTable');
                $scope.remindersTable.start();

                // come on give me data from backend
                $http.get($rootScope.settings.config.apiURL + '/calendar/followups/'+ $scope.ngDialogData.followup.id +'/reminders/json', {
                    cache: false,
                    isArray: true
                }).then(function(result) 
                {
                    $scope.reminders = result.data;

                    $scope.remindersTable.stop();
                });
            }]
        });
    }
    
    /**
     * for reschedue
     */
    $scope.eventEdited = function(event)
    {
        $scope.modal = ngDialog.open({ 
            template: $rootScope.settings.config.rootDir + '/app/calendar/templates/modals/rescheduleFollowup.html',
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false,
            data: {
                followup: event.data,
                admins:   $scope.admins,
                usedatatime: $scope.settings.usedatatime,
            },
            controller: ['$scope', '$http', function($scope, $http) {
                    
                // global for datapicker
                $scope.datapicker = {
                    options: {
                        showWeeks: false,
                        startingDay: 1
                    }
                };

                if($scope.ngDialogData.usedatatime === true) {
                    $scope.datapicker.format = 'yyyy-MM-dd HH:mm';
                    $scope.datapicker.enabletime = true;
                } else {
                    $scope.datapicker.format = 'yyyy-MM-dd';
                    $scope.datapicker.enabletime = false;
                }
                
                $scope.reschedue = {
                    followup_id: $scope.ngDialogData.followup.id,
                    date: new Date(),
                    updateReminders: true,
                };
            
                $scope.followupReschedueFormSubmit = function()
                {
                    var params = $scope.reschedue;
//                    params.date = moment(params.date).unix();
                    params.date = moment(params.date).format();
                    
                    $http.post($rootScope.settings.config.apiURL + '/lead/'+ $scope.ngDialogData.followup.resource_id +'/followups/'+ $scope.ngDialogData.followup.id +'/reschedue/json', 
                        params
                    ).then(function(result) 
                    {
                        $scope.$emit('loadingNotification', {type: 'finished'});
                        $scope.closeThisDialog(0);
                        $state.go($state.current, {}, {reload: true});
                    });
                };

            }]
        });
    }
    
    /**
     * for delete followup
     */
    $scope.eventDeleted = function(event)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this follow-up ?</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            plain: true,
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false
        }).then(function(){
            // push loading indicator
            $scope.$emit('loadingNotification', {type: 'progress'});

            // come on give me data from backend
            $http.delete($rootScope.settings.config.apiURL + '/lead/'+ event.data.resource_id +'/followups/'+ event.data.id +'/json', {
                cache: false,
                isArray: true
            }).then(function(result) 
            {
                
                $scope.$emit('loadingNotification', {type: 'finished'});
                
                // show message just in case
                $scope.scopeMessages.push({
                    type:    'success',
                    title:   "Success!",
                    content:  'Follow-up has been deleted',
                });
                
                $scope.events.splice($scope.events.indexOf(event), 1);
                
            }, function(error) {
                $scope.$emit('loadingNotification', {type: 'finished'});
                // show message just in case
                $scope.scopeMessages.push({
                    type:   'danger',
                    title:   "Error!",
                    content: error.data.msg ? error.data.msg : error.statusText,
                });
            });
    
        });
    }
    
    
    
    

}]);