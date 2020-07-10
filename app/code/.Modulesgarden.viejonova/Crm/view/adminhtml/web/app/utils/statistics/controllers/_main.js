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
        'statisticsController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state',  '$translate', '$http', 'AclService',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,    $translate,   $http,   AclService)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    // container for admins array
    $scope.admins              = [];
    // statuses
    $scope.statuses            = [];
    // contain messages
    $scope.scopeMessages       = [];
    
    // for now dont change this
    $scope.requestForAdmin  = $rootScope.currentAdminID;

    /**
     * ACL - allow user to preview other admin's 
     */
    if(AclService.can('statistics.other_admins')) {
        $scope.allowChangeAdmin = true;
    } else {
        $scope.allowChangeAdmin = false;
    }
    /**
     * ACL - allow user to preview other admin's global statistics
     */
    if(AclService.can('statistics.allow_global')) {
        $scope.allowAllAdmin = true;
    } else {
        $scope.allowAllAdmin = false;
    }
    
    
    // breadcast event to inform controler to renew requests for other admin data
    var followupActiveAdmin = $scope.$watch('requestForAdmin', function(newVal, oldVal) {
        if( newVal ==  oldVal ) { return; }
        $scope.$broadcast('statistics_requested_admin_change');
    });
    
    /**
     * just get followup types to render them correctly
     * there will be more stuff available here but lets dont care about that here
     */
    getDataForFormsInBackground = function()
    {
        
        // come on give me data from backend
        $http.get($rootScope.settings.config.apiURL + '/contacts/create/getParams/json', {
            cache: true,
        }).then(function(result) 
        {
            $scope.statuses      = result.data.statuses;
            $scope.admins        = result.data.admins;
            
            $scope.$broadcast('statuses-updated');
        });
    };
    getDataForFormsInBackground();
    
    
    $scope.forceResizeGraphs = function() {
        window.dispatchEvent(new Event('resize'));
    };
}]);