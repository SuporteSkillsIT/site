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
        'lastTenStatisticsController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state',  '$translate', '$http',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,    $translate,   $http)
{
    $scope.lastTenLeads       = blockUI.instances.get('lastTenLeads');
    

    $scope.data                 = [];
    $scope.showData             = [];
    $scope.initialContactType   = $scope.contactTypes[0].id;
    
    // set up initial contact type, as an first from all available (but needs to be active)
    for(i=0; i < $scope.contactTypes.length; i++) {
        if($scope.contactTypes[i].active === true) {
            $scope.initialContactType       = $scope.contactTypes[i].id;
            break;
        }
    }
    
    
    // breadcast event to inform controler to renew requests for other campaign
    var contactTypeWacher = $scope.$watch('initialContactType', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        $scope.remarkDisplayed();
    });
    
    
    $scope.remarkDisplayed = function()
    {
        // set up initial contact type, as an first from all available (but needs to be active)
        for(i=0; i < $scope.data.length; i++) {
            if($scope.data[i].type.id == $scope.initialContactType) {
                $scope.showData = $scope.data[i].data;
                break;
            }
        }
        
    };
    
    
    // get from backend
    $scope.getRefreshData = function()
    {
        $scope.data      = [];
    
        // Start blocking the table
        $scope.lastTenLeads.start();
        
        var params = {
            admin: $scope.requestForAdmin,
        };

        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/statistics/last/ten/json', params, {
            isArray: true,
            responseType: 'json',
        }).then(function(result) 
        {
            $scope.data        = result.data;
            $scope.remarkDisplayed();

            $scope.lastTenLeads.stop();
        }, function(error) {
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
            $scope.lastTenLeads.stop();
            
        });
    }
    $scope.getRefreshData();
    
    
    $scope.$on('statistics_requested_admin_change', function(scope) {
        $scope.getRefreshData();
    });
    
    
    /////////////////////////////
    // CLEANERS
    // when scope will be destroned, to avoid memory leaks
    // clear watchers/timeouts/intervals etc
    /////////////////////////////
    $scope.$on('$destroy', function () {
        // since we use watcher to check if priority has been changed
        contactTypeWacher();
    });
    
    
}]);