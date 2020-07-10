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
        'newPerMonthStatisticsController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state',  '$translate', '$http',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,    $translate,   $http)
{
    
    // for datapicker
    $scope.tableDateOpen = false;
    $scope.tableDate     = new Date();
    $scope.yeardatapicker = {
        'show-weeks': false,
        'min-mode': 'year',
        'max-mode': 'year',
    };
    
    $scope.chartOptions  = {
        animation: false,
        responsive: true,
        maintainAspectRatio: false,
    };
    
    // year
    $scope.selectedYear = new Date().getFullYear();
    
    // set up watcher that will keep it updated within backend
    var dynamicDateWatcher = $scope.$watch('tableDate', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        
        $scope.selectedYear = $scope.tableDate.getFullYear();
        $scope.getRefreshData();
    });
    

    $scope.newInYearTable  = blockUI.instances.get('newInYearTable');
    $scope.newInYearGraph  = blockUI.instances.get('newInYearGraph');
    

    $scope.tableData      = [];
    $scope.totals      = [];
    $scope.series = [];
    $scope.colours        = [];
//    $scope.series = ['Leads', 'Potentials'];
//    $scope.colours        = ['#8EEA6A', '#3FE6E4'];
            
    $scope.months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];
    $scope.data = [];
    
    
    
    // get from backend
    $scope.getRefreshData = function()
    {
        // Start blocking the table
        $scope.newInYearTable.start();
        $scope.newInYearGraph.start();
        
        var params = {
            admin: $scope.requestForAdmin,
            year:  $scope.selectedYear,
        };

        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/statistics/new/yearly/json', params, {
            isArray: true,
            responseType: 'json',
        }).then(function(result) 
        {
            $scope.tableData = result.data;
            
//            $scope.potentials   = result.data.potentials;

            parseDataForGraph();
            
            $scope.newInYearTable.stop();
            $scope.newInYearGraph.stop();
        }, function(error) {
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
            
            $scope.newInYearTable.stop();
            $scope.newInYearGraph.stop();
        });
    }
    $scope.getRefreshData();
    
    parseDataForGraph = function()
    {
        $scope.data     = [];
        $scope.totals   = [];
        $scope.series   = [];
        $scope.colours  = [];

        
        // set up initial contact type, as an first from all available (but needs to be active)
        for(i=0; i < $scope.tableData.length; i++) {
            $scope.data.push($scope.tableData[i].month);
            $scope.series.push($scope.tableData[i].type.name);
            $scope.colours.push($scope.tableData[i].type.color);
        }
        
        for(j=0; j < 12; j++) {
            total = 0;

            for(i=0; i < $scope.data.length; i++) {
                total = total + $scope.data[i][j];
            }
            
            $scope.totals[j] = total;
        }
    }
    

    
    
    
    
    $scope.$on('statistics_requested_admin_change', function(scope) {
        $scope.getRefreshData();
    });
    
    
    
    
    $scope.setProperHeight = function(isFullscreen) 
    {
        if(isFullscreen === true) {
            $('#month-chart-bar').css('height', (window.innerHeight - 90)+ 'px');
        } else {
            $('#month-chart-bar').css('height', '445px');
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
        dynamicDateWatcher();
    });
}]);