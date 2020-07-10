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
        'dashboardResourcesController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', 'tableColumns', '$translate', '$http',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,   tableColumns,   $translate,   $http)
{
    
    
    $scope.selectedStatus     = '';
    $scope.tableColumnsParsed = [];
    $scope.clientUrl = $rootScope.settings.config.viewCustomerUrl;
    $scope.dashboardTable = blockUI.instances.get('dashboardTable');
    
    
    // cached data for filters
    $scope.cached = {
        admins:   [],
        statuses: [],
    };
    
    /////////////////////////////
    // Perform actions on initialize these controller
    /////////////////////////////
    $http.get($rootScope.settings.config.apiURL + '/helpers/select/adminToReassign/json', {cache: true}).then(function(response) {
        $scope.cached.admins = response.data;
    });
    
        
    $scope.rawData   = [];
    $scope.displayed = [];
    $scope.filterByStatus = '';
    
    // manipulate to handle global change outside form (isolated scope from smart table module)
    
    // neat but works :D
        localStorage.removeItem('dashboardTable');
    var savedState = JSON.parse(localStorage.getItem('dashboardTable'));
    $scope.displayFilters = false;
    if( savedState != null && typeof(savedState) == 'object' ) {
        if(typeof(savedState.search) == 'object') {
            if(typeof(savedState.search.predicateObject) == 'object') 
            {
                if(Object.keys(savedState.search.predicateObject).length > 0 ) {
                    if(typeof(savedState.search.predicateObject.$) != 'undefined' && Object.keys(savedState.search.predicateObject).length == 1) {
                        $scope.displayFilters = false;
                    } else {
                        $scope.displayFilters = true;
                    }
                }
            }
        }
    }
    
    
    // set up watcher that will keep it updated within backend
    var dynamicStatusFilter = $scope.$watch('filterByStatus', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        
        angular.element('#statuschanger').trigger('change');
        
    });
    
    $scope.changeStatusFilter = function(statusId) {
        $scope.filterByStatus = statusId;
    }
    
    
    $scope.$on('dashboard_requested_admin_change', function(scope) {
        angular.element('#statuschanger').trigger('change');
    });
    
    $scope.$on('dashboard_requested_campaign_change', function(scope) {
        angular.element('#statuschanger').trigger('change');
    });
    
    
    
    
    
    /////////////////////////////
    // CLEANERS
    // when scope will be destroned, to avoid memory leaks
    // clear watchers/timeouts/intervals etc
    /////////////////////////////
    $scope.$on('$destroy', function () {
        // since we use watcher to check if priority has been changed
        dynamicStatusFilter();
    });
    

    
    function parseColumnsData()
    {
        for (var i = 0; i < tableColumns.data.length; i++) 
        {
            tmp = {};
            $scope.tableColumnsParsed[i] = tmp;
            if(typeof tableColumns.data[i].id != 'undefined') {
                tmp.fieldType = 'dynamic';
                tmp.id = 'field_' + tableColumns.data[i].id;
                tmp.name = tableColumns.data[i].name;
            } else {
                tmp.fieldType = 'static';
                tmp.id = tableColumns.data[i];
                tmp.name = tableColumns.data[i];
            }
                        
            $scope.tableColumnsParsed[i] = tmp;
        }   
    }
    parseColumnsData();

    
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

    $scope.callLeadsTableServer = function(tableState) 
    {
        // start blockui indicator    
        $scope.dashboardTable.start();


        var pagination       = tableState.pagination;
        var start            = pagination.start || 0;     // This is NOT the page number, but the index of item in the list that you want to use to display the table.
        var number           = pagination.number || 10;   // Number of entries showed per page.
        var requestedColumns = $scope.tableColumnsParsed.map(function(obj) {
            return obj.id
        });

        var params = {
            start:      start,
            number:     number,
            params:     tableState,
            columns:    requestedColumns,
            admin:      $scope.requestForAdmin,
            campaign:   $scope.requestCampaign,
            type:       $scope.initialContactType,
        };
            
        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/contacts/table/query/json', params).then(function(result) 
        {
            // update controller container for data from response
            $scope.displayed = result.data.data;
            // stop blockui indicator    
            $scope.dashboardTable.stop();
              
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

        // helper for foreach
        $scope.getRowData = function(row, column) {
            return row[column.id];
        }
        
    };
    
    $scope.getStatusLabel = function($statusID)
    {
        for(i=0; i < $scope.statuses.length; i++) {
            if($statusID == $scope.statuses[i].id) {
                return '<span class="label" style="background-color: ' + $scope.statuses[i].color + ';">' + $scope.statuses[i].name + '</span>';
            }
        }
    }
    
    $scope.dummysearchforclient = '';
    // for searched client by ajax
    $scope.searchedClients  = [];
    
    /**
     * ajax select client For Select
     */
    $scope.refreshClietns =  function(query) 
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
  
    // just focus client selector
    $scope.setFocusClient = function() {
        $scope.$broadcast('setFocusClient');
    };
    
    $scope.ForceUpdateSearchForClient = function()
    {
        angular.element('#awesome-hiddenc-lient-search').trigger('input');
    };
    
}]);