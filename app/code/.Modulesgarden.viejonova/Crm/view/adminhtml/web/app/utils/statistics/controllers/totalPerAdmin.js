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
        'totalPerAdminStatisticsController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state',  '$translate', '$http',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,    $translate,   $http)
{
    $scope.data     = [];
    $scope.labels   = [];
    $scope.series   = [];
    $scope.colours  = [];
    
//    $scope.series   = ['Leads', 'Potentials'];
//    $scope.colours  = ['#8EEA6A', '#3FE6E4'];
    
    
    // set up initial contact type, as an first from all available (but needs to be active)
    for(i=0; i < $scope.contactTypes.length; i++) {
        if($scope.contactTypes[i].active === true) {
            $scope.series.push($scope.contactTypes[i].name);
            $scope.colours.push($scope.contactTypes[i].color);
        }
    }
    
    
    $scope.chartOptions  = {
        animation: false,
        responsive: true,
        maintainAspectRatio: false,
    };
    
    $scope.totalPerAdmin       = blockUI.instances.get('totalPerAdmin');
    $scope.rawData      = [];
    
    
    // get from backend
    getRefreshData = function()
    {
        // Start blocking the table
        $scope.totalPerAdmin.start();
        
        var params = {
            admin: $scope.requestForAdmin,
        };

        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/statistics/total/per/admin/json', params, {
            isArray: false,
            responseType: 'json',
//            transformResponse: function(data) {
//              parsed in backend
//                for(i=0; i < data.length; i++)
//                {
//                    data[i].id              = parseInt(data[i].id);
//                    data[i].potentials      = parseInt(data[i].potentials);
//                    data[i].leads           = parseInt(data[i].leads);
//                }
//                
//                return data;
//            }
        }).then(function(result) 
        {
            $scope.rawAdmins      = result.data.admins;
            $scope.rawData        = result.data.types;
            
            parseRecievedTotalPerAdminData();

            $scope.totalPerAdmin.stop();
        }, function(error) {
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
            $scope.totalPerAdmin.stop();
            
        });
    }
    getRefreshData();
    
    // parse backend data to chart
    
    parseRecievedTotalPerAdminData = function()
    {
        var singleType = [];

            
            
        // set up initial contact type, as an first from all available (but needs to be active)
        for(i=0; i < $scope.rawAdmins.length; i++) {
            $scope.labels.push($scope.rawAdmins[i].firstname + ' ' + $scope.rawAdmins[i].lastname);
        }
            
        // set up initial contact type, as an first from all available (but needs to be active)
        for(i=0; i < $scope.rawData.length; i++) {
            $scope.data.push($scope.rawData[i].data);
            $scope.series.push($scope.rawData[i].type.name);
            $scope.colours.push($scope.rawData[i].type.color);
        }
        
    }
    
    $scope.setProperHeight = function(isFullscreen) 
    {
        if(isFullscreen === true) {
            $('#bar').css('height', (window.innerHeight - 90)+ 'px');
        } else {
            $('#bar').css('height', '200px');
        }
        
        $scope.forceResizeGraphs();
    };
    
    
    
}]);