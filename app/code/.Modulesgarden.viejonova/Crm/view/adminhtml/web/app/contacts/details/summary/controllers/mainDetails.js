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
        'detailsSummaryMainDetailsCtrl',
        ['$scope', '$rootScope', '$state', 'leadMainDetailsData', '$http', 'dynamicType', '$window',
function( $scope,   $rootScope,   $state,   leadMainDetailsData,   $http,   dynamicType,   $window)
{
    $scope.contactType = dynamicType;
    $scope.createCustomerUrl = $rootScope.settings.config.customerCreateUrl;
    $scope.customerViewUrl = $rootScope.settings.config.viewCustomerUrl;
    /////////////////////////////
    //
    // (re)ASSIGN CLIETN
    /////////////////////////////
    $scope.searchedClients = [];
    
    // Open modal with reassign ticket
    $scope.openReassignClientOppened = false;
    $scope.openReassignClient = function () {
        $scope.openReassignClientOppened = true;
        $scope.unassign = false;
    };
    
    $scope.setSessionResourceId = function()
    {
        res = $http.post($rootScope.settings.config.apiURL + '/lead/setResourceSessionId/json', {
            query: $scope.mainData.id
        });
        $window.open($scope.createCustomerUrl, '_blank');
    }
    
    // ajax select client For Select
    $scope.refreshClients =  function(query) 
    {
        // just skip on init ot when there is nothing in form
        if(query == '') return true;
        
        // obtain clientsfrom backend
        res = $http.post($rootScope.settings.config.apiURL + '/helpers/select/clients/json', {
            query: query
        });
        // when we recieve it update results container
        res.then(function(data) {
            $scope.searchedClients = data.data.results;
        });
    };
  
    // just focus ticket selector
    $scope.setFocusClient = function() {
        $scope.$broadcast('setFocusClient');
    };
    
    // update backend
    $scope.reasignClientFormSubmit = function(unassign)
    {
        $scope.modalFormProgress = true;
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        // collect params
        var params = {};
        if(unassign === true) {
            params.unassign  = true;
        } else {
            params.client_id = $scope.selectedClient.entity_id;
        }

        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/reassign/client/json', params).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            $scope.mainData.updated_at = response.data.updated_at;
            $scope.mainData.client_id  = response.data.client_id;
            $scope.mainData.client     = response.data.client;
            $scope.openReassignClientOppened  = false;

            $scope.modalFormProgress = false;
            $scope.setUpTargetEmailsToForm();
            
        }, function(response) {
            
            $scope.modalFormProgress = false;
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
        });
    }
    
    
    
    
    
    /////////////////////////////
    // (re)ASSIGN TICKET
    /////////////////////////////
    $scope.searchedTickets = [];
    /**
     * Open modal with reassign ticket
     */
    $scope.openReassignTicketDisplay = false;
    $scope.openReassignTicket = function () {
        $scope.openReassignTicketDisplay = true;
        $scope.unassignTicket = false;
    };
    
    // ajax select client For Select
    $scope.refreshTickets =  function(query) 
    {
        // just skip on init ot when there is nothing in form
        if(query == '') return true;
        
        // obtain clientsfrom backend
        res = $http.post($rootScope.settings.config.apiURL + '/helpers/select/tickets/json', {
            query: query
        });
        // when we recieve it update results container
        res.then(function(data) {
            $scope.searchedTickets = data.data.results;
        });
    };
  
    // just focus ticket selector
    $scope.setFocusTicket = function() {
        $scope.$broadcast('setFocusTicket');
    };
    
    // update backend
    $scope.reasignTicketFormSubmit = function(unassign)
    {
        $scope.modalFormTicketProgress = true;
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        params = {};
        if(unassign === true) {
            params.unassign  = true;
        } else {
            params.ticket_id = $scope.selectedTicket.id;
        }

        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/reassign/ticket/json', params).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            $scope.mainData.updated_at = response.data.updated_at;
            $scope.mainData.ticket_id  = response.data.ticket_id;
            $scope.mainData.ticket     = response.data.ticket;

            $scope.modalFormTicketProgress = false;
            $scope.openReassignTicketDisplay = false;
        }, function(response) {
            
            $scope.modalFormTicketProgress = false;
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
        });
    }
    
    
    // CAMPAIGN update
    $scope.updateAssignedCampaigns = function(data)
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/sync/campaigns/json', { campaigns: data }).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.mainData.updated_at = response.data.updated_at;
            
            return true;
        }, function(response) {
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'Undefined error';
        });
    };
    
    
    
    // filter for campaigns
    $scope.campaignIsAssigned = function(item) {
        return $scope.mainData.campaigns.indexOf(item.id) > -1;
    };
    
    
}]);