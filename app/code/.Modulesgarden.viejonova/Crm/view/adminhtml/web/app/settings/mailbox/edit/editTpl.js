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

angular.module("mgCRMapp").controller(
        'mailboxEditController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', '$translate', '$http', 'ngDialog', 'mailboxID',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,   $translate,   $http,   ngDialog,   mailboxID)
{
    $scope.scopeMessages   = [];
    $scope.mailboxID   = mailboxID;

    $scope.showOnlyPotentials = false;
    
    $scope.leadsTable       = blockUI.instances.get('leadsTable');
    $scope.mailboxFormBlock = blockUI.instances.get('mailboxForm');
    
    // cached data for filters
    $scope.cached = {
        admins: [],
        statuses: [],
    };
    
    $scope.model = {
        filters: {}
    };
    
    
    /////////////////////////////
    // Perform actions on initialize these controller
    $http.get($rootScope.settings.config.apiURL + '/mailbox/get/'+mailboxID+'/json', {cache: false, isArray: false}).then(function(response) {
        $scope.model = response.data;

        if(jQuery.isEmptyObject(response.data.filters)) {
            $scope.model.filters = {};
        }
    });
    
    // manipulate to handle global change outside form (isolated scope from smart table module)
    $scope.rawData   = [];
    //copy the references (you could clone ie angular.copy but then have to go through a dirty checking for the matches)
    $scope.displayed = [].concat($scope.rawData);
    
    // by default show filters
    $scope.showMachTable = false;
    
    $scope.showMachedRecords = function()
    {
        $scope.showMachTable = true;
        angular.element('#awesome-hiddenc-lient-search').trigger('input');
    };
    
    // containers for some overall stats
    $scope.itemsByPage = 10;
    $scope.itemsOffset = 0;
    $scope.itemsFirstNr = 0;
    $scope.itemsLastNr = 0;
    $scope.itemsTotal = 0;

    
    $scope.updateTotalStats = function(paginationData)
    {
        $scope.itemsTotal   = paginationData.totalItemCount;
        $scope.itemsFirstNr = paginationData.start + 1;
        $scope.itemsLastNr  = $scope.itemsFirstNr + paginationData.number - 1;
        if($scope.itemsLastNr > $scope.itemsTotal) {
            $scope.itemsLastNr = $scope.itemsTotal;
        }
    }
    
    $scope.callServerEdit = function(tableState) 
    {
        // start blockui indicator    
        $scope.leadsTable.start();


        var pagination       = tableState.pagination;
        var start            = pagination.start || 0;     // This is NOT the page number, but the index of item in the list that you want to use to display the table.
        var number           = pagination.number || 10;   // Number of entries showed per page.

        var params = {
            start: start,
            number: number,
            params: tableState,
            search: angular.copy($scope.model.filters),
        };
        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/mailbox/resources/query/json', params, {cache:false}).then(function(result) 
        {
            // update controller container for data from response
            $scope.displayed = result.data.data;
            // stop blockui indicator    
            $scope.leadsTable.stop();
            
            //set the number of pages so the pagination can update
            tableState.pagination.totalItemCount = result.data.total;
            tableState.pagination.numberOfPages  = Math.ceil(tableState.pagination.totalItemCount / tableState.pagination.number);
              
            $scope.updateTotalStats(tableState.pagination);

        }, function(error) {
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
        
    };
    
    // helper for foreach
    $scope.getRowData = function(row, column) {
        return row[column.id];
    }
    
    $scope.getStatusLabel = function($statusID)
    {
        for(i=0; i < $scope.cached.statuses.length; i++) {
            if($statusID == $scope.cached.statuses[i].id) {
                return '<span class="label" style="background-color: ' + $scope.cached.statuses[i].color + ';">' + $scope.cached.statuses[i].name + '</span>';
            }
        }
    }
    
    $scope.updateMailboxFormSubmit = function()
    {
        $scope.mailboxFormBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        var params = angular.copy($scope.model);

        // send query
        res = $http.post($rootScope.settings.config.apiURL + '/mailbox/update/' + mailboxID + '/json', params);

        res.then(function(response) {

            // show message just in case
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Success!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        },function(response) {

            // show message just in case
            $scope.scopeMessages.push({
                type:   'error',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.mailboxFormBlock.stop();
        });
    };

}]);