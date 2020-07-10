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
        'contactsListCtrl',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', 'tableColumns', '$translate', '$http', 'statesConfig', 'ngDialog', 'cachedListData', 'dynamicType',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,   tableColumns,   $translate,   $http,   statesConfig,   ngDialog,   cachedListData,   dynamicType)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    $scope.IsItArchive        = statesConfig.IsItArchive;
    $scope.contactTypeID      = dynamicType.id;
    
    $scope.tableColumnsParsed       = [];
    $scope.tableColumnsParsedMapped = [];
    $scope.requestCampaign          = '';
    $scope.viewCustomer = $rootScope.settings.config.viewCustomerUrl;
    
    // smart table data containers
    $scope.rawData      = [];
    $scope.displayed    = [];
    $scope.persistName  = 'ContactsTable-'+statesConfig.contactTypeID;
    
    
    
    // containers for some overall stats
    $scope.itemsByPage  = 10;
    $scope.itemsOffset  = 0;
    $scope.itemsFirstNr = 0;
    $scope.itemsLastNr  = 0;
    $scope.itemsTotal   = 0;
    
    // variables for use by dynamic client filter
    $scope.dummysearchforclient = '';
    $scope.searchedClients      = [];
    
    // blockui
    $scope.leadsTableBlock = blockUI.instances.get('leadsTableBlock');
    
    // cached data for filters
    $scope.cached = {
        admins:     cachedListData.data.admins,
        statuses:   cachedListData.data.statuses,
        campaigns:  cachedListData.data.campaigns,
    };
    
    /////////////////////////////
    //    
    // INITIALIZE THIS
    /////////////////////////////

    localStorage.removeItem($scope.persistName);
    
    // manipulate to handle global change outside form (isolated scope from smart table module)
    // neat but works :D
    var savedState = JSON.parse(localStorage.getItem($scope.persistName));
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


    /**
     * As we got defined contact type and each onte there are diferent columns possible
     * needs to parse it to friendly format
     */
    function parseColumnsData()
    {
        for (var i = 0; i < tableColumns.data.length; i++) 
        {
            tmp = {};
            $scope.tableColumnsParsed[i] = tmp;
            
            if(typeof tableColumns.data[i].id != 'undefined') {
                tmp             = tableColumns.data[i];
                tmp.fieldType   = 'dynamic';
                tmp.id          = 'field_' + tableColumns.data[i].id;
                tmp.name        = tableColumns.data[i].name;
            } else {
                tmp.fieldType   = 'static';
                tmp.id          = tableColumns.data[i];
                tmp.name        = tableColumns.data[i];
            }
                        
            $scope.tableColumnsParsed[i] = tmp;
        }
        
        $scope.tableColumnsParsedMapped = $scope.tableColumnsParsed.map(function(obj) {
            return obj.id
        });
    }
    parseColumnsData(); // do it only once
    
    
    
    /////////////////////////////
    //    
    // SMART TABLE
    /////////////////////////////
    
    // stats for pagination and overall
    $scope.updateTotalStats = function(paginationData)
    {
        $scope.itemsTotal   = paginationData.totalItemCount;
        $scope.itemsFirstNr = paginationData.start + 1;
        $scope.itemsLastNr  = $scope.itemsFirstNr + paginationData.number - 1;
        
        if($scope.itemsLastNr > $scope.itemsTotal) {
            $scope.itemsLastNr = $scope.itemsTotal;
        }
    }
    
    // obtain data from backend
    $scope.callServer = function callServer(tableState) 
    {
        // start blockui indicator    
        $scope.leadsTableBlock.start();

        var pagination       = tableState.pagination;
        var start            = pagination.start     || 0;    // This is NOT the page number, but the index of item in the list that you want to use to display the table.
        var number           = pagination.number    || 10;   // Number of entries showed per page.

        // addintiona parameters (not related strictly to smart table)
        var params = {
            start:      start,
            number:     number,
            params:     tableState,
            // our app custom params :)
            columns:    $scope.tableColumnsParsedMapped,
            campaign:   $scope.requestCampaign,
            type:       $scope.contactTypeID,
        };
            
        if( $scope.IsItArchive === true) {
            url = $rootScope.settings.config.apiURL + '/contacts/archive/query/json';
        } else {
            url = $rootScope.settings.config.apiURL + '/contacts/table/query/json';
        }
        
        // come on give me data from backend
        $http.post(url, params).then(function(result) 
        {
            // update controller container for data from response
            $scope.displayed = result.data.data;
            // stop blockui indicator    
            $scope.leadsTableBlock.stop();
              
            //set the number of pages so the pagination can update
            tableState.pagination.totalItemCount = result.data.total;
            tableState.pagination.numberOfPages  = Math.ceil(tableState.pagination.totalItemCount / tableState.pagination.number);

            // update stats
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
    
    
    /////////////////////////////
    //    
    // SMART TABLE - RENDER CONTENT
    /////////////////////////////
    
    
    // helper for foreach
    $scope.getRowData = function(row, column) {
        return row[column.id];
    }
    
    // specific for status
    $scope.getStatusLabel = function($statusID)
    {
        for(i=0; i < $scope.cached.statuses.length; i++) {
            if($statusID == $scope.cached.statuses[i].id) {
                return '<span class="label" style="background-color: ' + $scope.cached.statuses[i].color + ';">' + $scope.cached.statuses[i].name + '</span>';
            }
        }
    }
    
    
    
    /////////////////////////////
    //    
    // SMART TABLE - DYNAMIC FILTER FOR ASSIGNED CLIENT
    /////////////////////////////

    // ajax select client For Select
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
    
    // kinda hack to force reload table once client has been changed
    $scope.ForceUpdateSearchForClient = function() {
        angular.element('#awesome-hiddenc-lient-search').trigger('input');
    };
    
    
    
    
    /////////////////////////////
    //    
    // Delete  > move to trashed .-.-
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
    
    
    /////////////////////////////
    //    
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

    
    
    
    /////////////////////////////
    //    
    // CONVERTER RECORDS TO OTHER TYPE
    /////////////////////////////


    /////////////////////////////
    // Convert this resource to other type
    /////////////////////////////
    $scope.convertToType = function(id, what) {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        var params = {
            type_id: what
        };
        
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
    //    
    // set up watcher that will keep it updated within backend
    /////////////////////////////
    var campaignChangerWatcher = $scope.$watch('requestCampaign', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        
        $scope.ForceUpdateSearchForClient();
        
    });
    
    
    /////////////////////////////
    // CLEANERS
    // when scope will be destroned, to avoid memory leaks
    // clear watchers/timeouts/intervals etc
    /////////////////////////////
    $scope.$on('$destroy', function () {
        // since we use watcher to check if priority has been changed
        campaignChangerWatcher();
    });
    
    
    $scope.clicktest = function(yyy) {
        $state.transitionTo('contacts.list', {contactTypeID: yyy}, {location:false, notify: true});
    };
    
}]);