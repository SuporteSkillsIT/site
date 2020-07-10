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
        'dashboardController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state',  '$translate', '$http', 'AclService', 'ContactTypes',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,    $translate,   $http,   AclService,   ContactTypes)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    // just for messages
    $scope.scopeMessages            = [];
    // container for admins array
    $scope.admins                   = [];
    // followup types
    $scope.followupTypes            = [];
    // followup types
    $scope.statuses                 = [];
    // keep total count number
    $scope.totalCount               = 0;
    $scope.contactTypes             = ContactTypes.get();
    

    // set up initial contact type, as an first from all available (but needs to be active)
    for(i=0; i < $scope.contactTypes.length; i++) {
        if($scope.contactTypes[i].dashboard === true && $scope.contactTypes[i].active === true) {
            $scope.initialContactType       = $scope.contactTypes[i].id;
            break;
        }
    }
    
    
    // for now dont change this
    $scope.requestForAdmin  = $rootScope.currentAdminID;
    $scope.requestCampaign  = '';

    /**
     * ACL - allow user to preview other admin's dashboard
     */
    if(AclService.can('other.preview_dashboard')) {
        $scope.allowChangeAdmin = true;
    } else {
        $scope.allowChangeAdmin = false;
    }
    
    $scope.showOnlyPotentials = false;
    $scope.$on('dashboard_requested_resource_type_changed', function(scope, showOnlyPotentials) {
        $scope.showOnlyPotentials = showOnlyPotentials;
        $scope.refreshStatuses();
    });
    
    
    // breadcast event to inform controler to renew requests for other admin data
    var followupActiveAdmin = $scope.$watch('requestForAdmin', function(newVal, oldVal) {
        if( newVal ==  oldVal && $scope.allowChangeAdmin === true ) { return; }
        $scope.refreshStatuses();
        $scope.$broadcast('dashboard_requested_admin_change');
    });
    
    // breadcast event to inform controler to renew requests for other campaign
    var followupActiveCampaign = $scope.$watch('requestCampaign', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        $scope.$broadcast('dashboard_requested_campaign_change');
        $scope.refreshStatuses();
    });
    
    // breadcast event to inform controler to renew requests for other campaign
    var contactTypeWacher = $scope.$watch('initialContactType', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        $scope.refreshStatuses();
    });
    
    $scope.changeInitialContactType = function(id) 
    {
        $scope.initialContactType = id;
    };
    
    $scope.refreshStatuses = function() 
    {
        $http.post($rootScope.settings.config.apiURL + '/settings/statuses/dashboard/json', {
            admin_id:     $scope.requestForAdmin,
            type_id:      $scope.initialContactType,
            campaign:     $scope.requestCampaign,
        }).then(function(response) {
            $scope.statuses = response.data;

            $scope.totalCount = 0;
            for(i = 0; i < response.data.length; i++) {
                $scope.totalCount += response.data[i].resourcesCount;
            }

        });
    };
    $scope.refreshStatuses();
    
    
    /**
     * just get followup types to render them correctly
     * there will be more stuff available here but lets dont care about that here
     */
    getDataForFormsInBackground = function()
    {
        // come on give me data from backend
        $http.get($rootScope.settings.config.apiURL + '/dashboard/background/json', {
            cache: true,
        }).then(function(result) 
        {
            $scope.followupTypes      = result.data.followup.types;
            $scope.admins             = result.data.admins;
            $scope.campaigns          = result.data.campaigns;
        });
    };
    getDataForFormsInBackground();
    
    
    
    
    /////////////////////////////
    // CLEANERS
    // when scope will be destroned, to avoid memory leaks
    // clear watchers/timeouts/intervals etc
    /////////////////////////////
    $scope.$on('$destroy', function () {
        // since we use watcher to check if priority has been changed
        followupActiveAdmin();
        followupActiveCampaign();
        contactTypeWacher();
    });
    
}]);