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
        'newPerDayStatisticsController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state',  '$translate', '$http',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,    $translate,   $http)
{
    // for datapicker
    $scope.requestedDateOpen = false;
    $scope.requestedDate     = new Date();
    $scope.yeardatapicker = {
        'show-weeks': false,
        'min-mode': 'month',
        'max-mode': 'year',
    };
    $scope.rawData = [];
    $scope.graphInMonth       = blockUI.instances.get('graphInMonth');
    
    // year
    $scope.selectedYear  = new Date().getFullYear();
    // month
    $scope.selectedMonth = new Date().getMonth() + 1;
    
    // set up watcher that will keep it updated within backend
    var dynamicRequestedDateWatcher = $scope.$watch('requestedDate', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        
        $scope.selectedYear  = $scope.requestedDate.getFullYear();
        $scope.selectedMonth = $scope.requestedDate.getMonth() + 1;
        
        $scope.getRefreshDataPerMonth();
    });
    
    
    $scope.series   = [];
    $scope.colours  = [];
    $scope.labels   = [];
    $scope.data     = [];
    
    $scope.chartOptions  = {
        animation: false,
        responsive: true,
        maintainAspectRatio: false,
    };
    
    
    // get from backend
    $scope.getRefreshDataPerMonth = function()
    {
        // Start blocking the table
        $scope.graphInMonth.start();
        
        var params = {
            admin: $scope.requestForAdmin,
            year:  $scope.selectedYear,
            month: $scope.selectedMonth,
        };

        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/statistics/total/per/month/json', params, {
            isArray: true,
            responseType: 'json',
        }).then(function(result) 
        {
            $scope.rawData        = result.data;
            
            parseRecievedTotalPerDayData();

            $scope.graphInMonth.stop();
        }, function(error) {
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
            $scope.graphInMonth.stop();
            
        });
    }
    $scope.getRefreshDataPerMonth();
    
    // parse backend data to chart
    
    parseRecievedTotalPerDayData = function()
    {
        $scope.labels   = [];

        $scope.data     = [];
        $scope.series   = [];
        $scope.colours  = [];

        
        // set up initial contact type, as an first from all available (but needs to be active)
        for(i=0; i < $scope.rawData.length; i++) {
            $scope.data.push($scope.rawData[i].month);
            $scope.series.push($scope.rawData[i].type.name);
            $scope.colours.push($scope.rawData[i].type.color);
        }
        
        
        for(j=0; j < moment($scope.requestedDate).daysInMonth(); j++) {
            $scope.labels.push(String($scope.selectedYear)+'-'+String($scope.selectedMonth) + '-' + String(j+1));
        }
    }
    
    
    $scope.$on('statistics_requested_admin_change', function(scope) {
        $scope.getRefreshDataPerMonth();
    });
    
    
    $scope.setProperHeight = function(isFullscreen) 
    {
        if(isFullscreen === true) {
            $('#day-chart-bar').css('height', (window.innerHeight - 90)+ 'px');
        } else {
            $('#day-chart-bar').css('height', '450px');
        }
        
        $scope.forceResizeGraphs();
    };
    
    
    /////////////////////////////
    // CLEANERS
    // when scope will be destroned, to avoid memory leaks
    // clear watchers/timeouts/intervals etc
    /////////////////////////////
    $scope.$on('$destroy', function () {
        // since we use watcher to check if priority has been changed
        dynamicRequestedDateWatcher();
    });
    
}]);