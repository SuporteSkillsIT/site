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
        'campaignsEditController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', '$translate', '$http', 'ngDialog', 'campaignID',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,   $translate,   $http,   ngDialog,   campaignID)
{
    $scope.scopeMessages   = [];
    $scope.campaignID   = campaignID;

    $scope.showOnlyPotentials = false;
    
    $scope.leadsTable           = blockUI.instances.get('leadsTable');
    $scope.campaignFormBlock    = blockUI.instances.get('campaignForm');
    
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
    $http.get($rootScope.settings.config.apiURL + '/campaigns/get/'+campaignID+'/json', {cache: false, isArray: false}).then(function(response) {
        $scope.model = response.data;

        if(jQuery.isEmptyObject(response.data.filters)) {
            $scope.model.filters = {};
        }

        $scope.model.date_start = moment($scope.model.date_start).toDate();
        $scope.model.date_end   = moment($scope.model.date_end).toDate();
    });
    /////////////////////////////
    $http.get($rootScope.settings.config.apiURL + '/settings/statuses/json', {cache: true}).then(function(response) {
        $scope.cached.statuses = response.data;
    });
    $http.get($rootScope.settings.config.apiURL + '/helpers/select/adminToReassign/json', {cache: true}).then(function(response) {
        $scope.cached.admins = response.data;
    });
    $http.get($rootScope.settings.config.apiURL + '/campaigns/filters/json', {cache: true}).then(function(response) {
        $scope.filters = response.data;
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
        $http.post($rootScope.settings.config.apiURL + '/campaigns/resources/query/json', params, {cache:false}).then(function(result) 
        {
            // update controller container for data from response
            $scope.displayed = result.data.data;
            // stop blockui indicator    
            $scope.leadsTable.stop();
              
//            console.log(result.data.data);
//            console.log(tableState.pagination);
              
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
    
    // come on give me data from backend
//    $http.get($rootScope.settings.config.apiURL + '/campaigns/resources/query/json').then(function(result) 
//    {
//        // start blockui indicator    
//        $scope.leadsTable.start();
//        // update controller container for data from response
//        $scope.rawData = result.data.data;
//        // stop blockui indicator    
//        $scope.leadsTable.stop();
//
//    }, function(error) {
//
//        // show message just in case
//        $scope.scopeMessages.push({
//            type:   'danger',
//            title:   "Error!",
//            content: error.data.msg ? error.data.msg : error.statusText,
//        });
//
//    });
    
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
    
    
    
    $scope.convertToLead = function(id) {
        $scope.updateSingleResource(id, 'convertLead');
    };
    $scope.convertToPotential = function(id) {
        $scope.updateSingleResource(id, 'convertPotential');
    };
    
    /////////////////////////////
    // Delete lead, or at least hide
    /////////////////////////////
    $scope.deleteResource = function(id) {
        
        // triger confirm dialog
        ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this record ?</h2>\
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

            // send query
            res = $http.post($rootScope.settings.config.apiURL + '/lead/' + id + '/softDelete/json');
        
            res.then(function(response) {
                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
                angular.element('#main-table-global-search').trigger('input');

            },function(response) {
                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
            });
    
        });
    };
    
    /**
     * Simple function trigger update 
     * ONE parameter for resource
     * 
     * mainly these are used for manage static fields
     */
    $scope.updateSingleResource = function(id, action)
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        var params = {};
        if(action == 'convertLead') {
            params.is_potential = 0;
        } else if(action == 'convertPotential') {
            params.is_potential = 1;
        }
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + id + '/updateSingleParam/json', params).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            angular.element('#main-table-global-search').trigger('input');
            
            return true;
        }, function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
        });

    };
    
    
    /////////////////////////////
    // restore lead basically remove deleted flag
    /////////////////////////////
    $scope.restoreResource = function(id)
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        // send query
        res = $http.post($rootScope.settings.config.apiURL + '/lead/' + id + '/restore/json');

        res.then(function(response) {

            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});

            angular.element('#main-table-global-search').trigger('input');

        },function(response) {

            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});


        });

    };
    
    
    $scope.UpdateInputChange = function(model)
    {
        if(typeof(model.value) == 'string') {
            if(model.value == '') {
                model.enabled = false;
            } else {
                model.enabled = true;
            }
        } else if(typeof(model.value) == 'object') {
            
            if(Object.keys(model.value).length > 0) {
                model.enabled = true;
            } else {
                model.enabled = false;
            }
        }
    };

    $scope.updateCampaignFormSubmit = function()
    {
        $scope.campaignFormBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        var params = angular.copy($scope.model);
        
        // fix dates in order to sent correct format for backend
        params.date_start = moment(params.date_start).format();
        params.date_end   = moment(params.date_end).format();

        // send query
        res = $http.post($rootScope.settings.config.apiURL + '/campaigns/update/' + campaignID + '/json', params);

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
            $scope.campaignFormBlock.stop();
        });
    };

}]);