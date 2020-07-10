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

angular.module("mgCRMapp").controller(
        'detailsSummaryQuickActionTabsCtrl',
        ['$scope', '$rootScope', '$state', '$stateParams', 'leadMainDetailsData', '$http', 'ngDialog', 'AclService', 'blockUI', 'notesService', '$timeout',
function( $scope,   $rootScope,   $state,   $stateParams,   leadMainDetailsData,   $http,   ngDialog,   AclService,   blockUI,   notesService,   $timeout)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    // contain messages
    $scope.scopeMessages        = [];
    $scope.newNoteContent       = '';
    $scope.newFollowup          = {
        date:   new Date(),
        admin:  $rootScope.currentAdminID,
    };
    
    
    
    // set up active tab
    if(AclService.can('resources.allow_notes')) {
        $scope.activeTab = 'note';
    } else if(AclService.can('resources.allow_email')) {
        $scope.activeTab = 'email';
    } else if(AclService.can('resources.create_followup')) {
        $scope.activeTab = 'followup';
    } else if(AclService.can('resources.allow_ticket_respose')) {
        $scope.activeTab = 'ticketResponse';
    }
            
    // email
    $scope.newEmailContainer = blockUI.instances.get('newEmailContainer');
    // set up block ui references
    $scope.blockContainers= {};
    $scope.blockContainers.newNote              = blockUI.instances.get('newNoteContainer');
    $scope.blockContainers.newTicketReply       = blockUI.instances.get('newTicketReplyContainer');
    $scope.blockContainers.newFollowupContainer = blockUI.instances.get('newFollowupContainer');
    
    
    $scope.formData = {};
    $scope.formData.departments  = [];
    $scope.formData.templates    = [];
    $scope.formData.targetEmails = [];
    

    $scope.sendEmailDone     = false;
    $scope.modelSentEmail = {
        to: null,
        template: 'false',
    };
    
    
    /**
     * initialize some shit from parent states
     * just to set up parameters to choose from form
     */
    $scope.setupDataForms = function()
    {
        
        $scope.formData.departments = $scope.temp.system_email;
        $scope.formData.templates   = $scope.temp.templates.admin;

        $scope.temp.departments.forEach(function(item,index)
        {
            $scope.formData.departments.insert(0, {id:'#' + item.id, fullemail: item.fullemail});
        });
       // $scope.formData.departments.insert(0, {id:0, fullemail: $scope.temp.departments});

//        if($scope.formData.departments.length) {
//            $scope.modelSentEmail.from      = $scope.formData.departments[0].id;
//        }
            
    };
    
    
    $scope.$on('header_gotTmpData', function(event) {
        $scope.newFollowup.type  = $scope.temp.followupTypes[0].id;
        
        $scope.setupDataForms();
    });
    $scope.setupDataForms();
    
    
    // set up defaults for form
    $scope.setUpTargetEmailsToForm = function()
    {
        $scope.formData.targetEmails = [];
        
        if(leadMainDetailsData.email != 'undefined' && leadMainDetailsData.email != '') {
            $scope.formData.targetEmails.push(leadMainDetailsData.email);
        }
        if(typeof leadMainDetailsData.client !== undefined && leadMainDetailsData.client != null) {
            if(typeof leadMainDetailsData.client.email !== undefined && leadMainDetailsData.client.email != null && $scope.formData.targetEmails.indexOf(leadMainDetailsData.client.email) == -1 ) {
                $scope.formData.targetEmails.push(leadMainDetailsData.client.email);
            }
        }

        if($scope.formData.targetEmails.length) {
            $scope.modelSentEmail.to = $scope.formData.targetEmails[0];
        }
    };
    $scope.setUpTargetEmailsToForm();
    
    
    
    
    
    /////////////////////////////
    // TAB ADD NEW NOTE
    /////////////////////////////
    $scope.addNewNote = function() {
        $scope.blockContainers.newNote.start();
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        res = notesService.addNew($stateParams.id, $scope.newNoteContent).then(function(response) 
        {
            $scope.newNoteContent = '';
            
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $rootScope.$broadcast('summaryNotesTriggerRefresh');
            $scope.blockContainers.newNote.stop();
            
            $scope.formSubmitNoteDone     = true;
            
            $timeout(function() {
                $scope.formSubmitNoteDone = false
            }, 8000);

            
        }, function(response) {
            console.log(response);
            $scope.formSubmitNoteDone     = true;
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
            $scope.blockContainers.newNote.stop();
        });
        
    };
    
    
    
    
    
    /////////////////////////////
    // TAB SEND EMAIL
    /////////////////////////////
    
    /**
     * Send email!
     * 
     * @returns {undefined}
     */
    $scope.sendEmailFormSubmit = function()
    {
        $scope.newEmailContainer.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        var emailForm = new FormData();
        
        emailForm.append("to", $scope.modelSentEmail.to);
        emailForm.append("from", $scope.modelSentEmail.from);
        emailForm.append("template", $scope.modelSentEmail.template);
        emailForm.append("content", $scope.modelSentEmail.content);
        emailForm.append("subject", $scope.modelSentEmail.subject);

        if(jQuery('#files').length)
        {
            var files = jQuery('#files').prop('files');   // forgive me for using jquery :D

            for(i=0; i<files.length; i++) {
                emailForm.append("files[]", files[i]);
            }
        }
        
        
        // come on give me data from backend
        $http.post(
                $rootScope.settings.config.apiURL + '/lead/' + $stateParams.id + '/emails/send/json', 
                emailForm,
                {
                    withCredentials: true,
                    headers: {'Content-Type': undefined },
                    transformRequest: angular.identity
                })
        .then(function(response) {
            
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.newEmailContainer.stop();
            
            $scope.sendEmailDone     = true;
            
            $timeout(function() {
                $scope.sendEmailDone = false
            }, 8000);

        }, function(response) {
            
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.newEmailContainer.start();
            $scope.sendEmailDone     = false;
            
            // show message just in case
            $scope.scopeSummaryMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
            
        });
        
        
        
    };
    
    
    
    
    /////////////////////////////
    // FOLLOWUP ADD
    /////////////////////////////
    // update backend
    $scope.newFollowupResult = {};
    $scope.newFollowupFormSubmit = function()
    {
        $scope.blockContainers.newFollowupContainer.start();
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        var params = {
            description: $scope.newFollowup.description,
            type: $scope.newFollowup.type,
            date: moment($scope.newFollowup.date).format(),
            admin: $scope.newFollowup.admin
        };

        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/lead/' + leadMainDetailsData.id + '/followups/addWithoutReminders/json', params)
        .then(function(response) {
            $scope.newFollowupResult.error  = false;
            
            // triger refresh smart table
            $('#followups-table-search').trigger('input');
            
        }, function(response) {
            $scope.newFollowupResult.error  = response.data.msg;
            
        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.blockContainers.newFollowupContainer.stop();
            $scope.newFollowup.description = null;
            $scope.newFollowupResult.done   = true;
            
            $timeout(function() {
                $scope.newFollowupResult.done   = false;
            }, 8000);
            
        });
        
    }
    $scope.followupMessages = [];
    
    
    /////////////////////////////
    // TICKET REPLY
    /////////////////////////////
    $scope.newTicketReply = {
        resource_id: $stateParams.id,
    };
    // update backend
    $scope.newTicketReplyFormSubmit = function()
    {
        $scope.blockContainers.newTicketReply.start();
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        
        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/lead/ticket/' + leadMainDetailsData.ticket.id + '/respond/forcewhmcs/json', $scope.newTicketReply)
        .then(function(response) {
            $scope.formSubmitTicketError    = false;
            
        }, function(response) {
            $scope.formSubmitTicketDone     = true;
            $scope.formSubmitTicketError    = response.data.msg;
            
        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.blockContainers.newTicketReply.stop();
            
            $scope.formSubmitTicketDone     = true;
            
            $timeout(function() {
                $scope.formSubmitTicketDone = false
            }, 8000);
            
        });
    }
    
    
}]);