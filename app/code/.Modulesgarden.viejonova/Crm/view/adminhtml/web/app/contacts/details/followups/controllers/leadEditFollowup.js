/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



angular.module("mgCRMapp").controller(
        'leadEditFollowupController',
        ['$scope', '$rootScope', '$state', '$stateParams', 'blockUI', 'ngDialog', '$http', 'singleFollowuprderService', 'singleReminderService',
function( $scope,   $rootScope,   $state,   $stateParams,   blockUI,   ngDialog,   $http,   singleFollowuprderService,   singleReminderService)
{
    
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    // just contain single model to push it as create new roles
    $scope.followup                 = {};
    // just for messages
    $scope.scopeMessages            = [];
    $scope.followupDetailsVisible   = true;
    $scope.remindersVisible         = true;

    // Get the reference to the block service.
    $scope.followupEditForm         = blockUI.instances.get('followupEditForm');
    $scope.followupReminders        = blockUI.instances.get('followupReminders');

    // container for reminders
    $scope.reminders                = [];
    
    // settings from resolve
    $scope.settings    = {};
    $scope.settings.datapicker    = {};
    $scope.followupDataOpen = false;
    
    $scope.formData = {
        departments: [],
        admins: [],
        templates: {
            admin: [],
            client: [],
            sms: [],
        },
        followup: {
            types: [],
        },
    };
    

    /////////////////////////////
    //    
    // GET Followup TO EDIT
    /////////////////////////////
    
    $scope.followup = singleFollowuprderService.get({id:$stateParams.followupID, resource_id: $stateParams.id});
    // when we recieve it
    $scope.followup.$promise.then(function(data) {
        // maybe do sth on init
        // 
        // trigger on initialize
        $scope.reminderFormReset();
        
        $scope.followup.updateReminders = true;
    }, function(error) {
        // show message
        $scope.scopeMessages.push({
            type:   'danger',
            title:   "Error!",
            content: error.data.msg ? error.data.msg : error.statusText,
        });
    });
    

    /////////////////////////////
    //    
    // GET Followup Reminders
    /////////////////////////////
    
    $scope.refreshReminders = function()
    {
        $scope.followupReminders.start();
        
        $scope.reminders = singleReminderService.query({followup_id:$stateParams.followupID, resource_id: $stateParams.id});
        // when we recieve it
        $scope.reminders.$promise.then(function(data) {
            // trigger on initialize
            $scope.followupReminders.stop();
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            // trigger on initialize
            $scope.followupReminders.stop();
        });
    };
    $scope.refreshReminders();
    
    
    
    // parse some initial info to use in form after
    parseSettings = function(){
        $scope.settings.usedatatime         = !$rootScope.settings.config.app.followups_per_day;
        $scope.settings.showAdminReminers   = true;
        $scope.settings.showClientReminers  = false;
        $scope.settings.sms                 = true;
        
        $scope.settings.beforeOptions   = [{key: 'minutes', name:'Minutes'}, {key: 'hours', name:'Hours'}, {key: 'days', name:'Days'}];
    
    
        // global for datapicker
        $scope.settings.datapicker.options = {
            showWeeks: false,
            startingDay: 1
        };
        
        if($scope.settings.usedatatime === true) {
            $scope.settings.datapicker.format = 'yyyy-MM-dd HH:mm';
            $scope.settings.datapicker.enabletime = true;
            
            
        } else {
            $scope.settings.datapicker.format = 'yyyy-MM-dd';
            $scope.settings.datapicker.enabletime = false;
        }
    
    };
    parseSettings(); // trigger on init
    
    
    $scope.formData = {
        departments: [],
        admins: [],
        followup: {
            types: [],
        },
        templates: {
            admin: [],
            client: [],
            sms: [],
        },
    };
    getDataForFormsInBackground = function()
    {
        
        // come on give me data from backend
        $http.get($rootScope.settings.config.apiURL + '/helpers/lead/background/followups/json', {
            cache: true,
        }).then(function(result) 
        {
            $scope.formData.departments         = result.data.departments;
            $scope.formData.admins              = result.data.admins;
            $scope.formData.followup.types      = result.data.followup.types;
            $scope.formData.templates.admin     = result.data.templates.admin;
            $scope.formData.templates.sms       = result.data.templates.sms;
            $scope.formData.templates.client    = result.data.templates.client;
            
        });
    };
    getDataForFormsInBackground();
    
    // trigger on submit
    $scope.editFollowupFormSubmit = function ()
    {
        // Start blocking
        $scope.followupEditForm.start();
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        $scope.newFormWorking = true;
        
        // send query
        res = $scope.followup.$save().then(function(response) 
        {
            $scope.followup = response;
            
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.followupEditForm.stop();
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Success!",
                content: 'Follow-up has been updated',
            });
            
            
        }, function(response) {
            console.log(response);
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
    };
    
    
    /////////////////////////////
    //    
    // ADD NEW GROUP
    /////////////////////////////
    
    $scope.newReminder = {};
        
    // show modal with new reminder form
    $scope.triggerAddReminderModal = function () {
    
        $scope.modal = ngDialog.open({ 
            template: $rootScope.settings.config.rootDir + '/app/contacts/details/followups/templates/modals/addReminder.html',
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false,
            scope: $scope
        });
    };
    
    
    $scope.formSubmitNewBlock   = blockUI.instances.get('formSubmitNewBlock');
    // submit new reminder form
    $scope.remindersubmitted = false;
    $scope.subnutNewReminder = function() 
    {
        $scope.formSubmitDone = false;
        
        // Start blocking
        $scope.formSubmitNewBlock.start();
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
//        $scope.newFormWorking = true;
        
        // send query
        res = $scope.newReminder.$save().then(function(response) 
        {
            
            $scope.formSubmitNewBlock.stop();
            $scope.refreshReminders();
            $scope.formSubmitDone = true;
            $scope.remindersubmitted = false;

            $scope.reminderform.$dirty = false;
            $scope.reminderform.$pristine = true;
            $scope.reminderform.$submitted = false;
            
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            // show message just in case
//            $scope.scopeMessages.push({
//                type:   'success',
//                title:   "Success!",
//                content: 'New Reminder-up has been created',
//            });
            
            // trigger on initialize
            $scope.reminderFormReset();
            
        }, function(response) {
            
            $scope.formSubmitNewBlock.stop();
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });
        });
    };
    
    // reset new reminder data
    $scope.reminderFormReset = function() {
        $scope.newReminder = new singleReminderService;
        $scope.newReminder.resource_id = $scope.followup.resource_id;
        $scope.newReminder.followup_id = $scope.followup.id;
        $scope.newReminder.date = new Date();
    };
    $scope.convertToInt = function(id){
        return parseInt(id, 10);
    };
    $scope.editableReminder = {};
    $scope.triggerReminderEdit = function($index)
    {
        $scope.reminders[$index].date = $scope.reminders[$index].date.slice(0, -3);
        $scope.editableReminder = angular.copy($scope.reminders[$index]);
        
        $scope.modal = ngDialog.open({ 
            template: $rootScope.settings.config.rootDir + '/app/contacts/details/followups/templates/modals/editReminder.html',
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false,
            scope: $scope
        });
    };
    
    $scope.submitEditReminder = function()
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        res = $scope.editableReminder.$save({resource_id: $scope.followup.resource_id}).then(function(response) 
        {
            
            $scope.editableReminder      = {};
            
            $scope.modal.close(0);
//            console.log(response);

            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});

            // show message just in case
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Success!",
                content: 'Reminder has been updated',
            });
            
            // trigger on initialize
            $scope.reminderFormReset();
            $scope.refreshReminders();
            
        }, function(response) {
            console.log(response);
            $scope.modal.close(0);
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });
        });
    };
    
    
    /**
     * for delete followup
     */
    $scope.triggerReminderDelete = function(reminder)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this reminder ?</h2>\
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
            $http.delete($rootScope.settings.config.apiURL + '/lead/'+ reminder.followup.resource_id +'/followups/'+ reminder.followup.id +'/reminders/'+ reminder.id +'/json', {
                cache: false,
                isArray: true
            }).then(function(result) 
            {
                $scope.refreshReminders();
                
                $scope.$emit('loadingNotification', {type: 'finished'});
                
                // show message just in case
                $scope.scopeMessages.push({
                    type:    'success',
                    title:   "Success!",
                    content:  'Reminder has been deleted',
                });
                
            }, function(error) {
                $scope.$emit('loadingNotification', {type: 'finished'});
                // show message just in case
                $scope.scopeMessages.push({
                    type:   'danger',
                    title:   "Error!",
                    content: error.data.msg ? error.data.msg : error.statusText,
                });
            });
//                
    
        });
    }
    
}]);