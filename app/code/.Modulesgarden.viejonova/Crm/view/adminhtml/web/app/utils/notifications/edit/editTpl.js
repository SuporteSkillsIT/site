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
        'notificationsEditController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', '$translate', '$http', 'ngDialog', 'notificationID',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,   $translate,   $http,   ngDialog,   notificationID)
{
    $scope.scopeMessages   = [];

    $scope.showOnlyPotentials = false;
    
    $scope.notificationFormBlock    = blockUI.instances.get('notificationForm');
    
    $scope.types = [
        {code: '', name: 'Normal'},
        {code: 'info', name: 'Information'},
        {code: 'warning', name: 'Warning'},
        {code: 'danger', name: 'Danger'},
    ];
    
    // cached data for filters
    $scope.cached = {
        admins: [],
    };
    
    
    
    /////////////////////////////
    // Perform actions on initialize these controller
    $http.get($rootScope.settings.config.apiURL + '/notifications/get/'+notificationID+'/json', {cache: false, isArray: false}).then(function(response) {
        $scope.model = response.data;

        $scope.model.date_start = moment($scope.model.date_start).toDate();
        $scope.model.date_end   = moment($scope.model.date_end).toDate();
        
    });
    /////////////////////////////
    $http.get($rootScope.settings.config.apiURL + '/helpers/select/adminToReassign/json', {cache: true}).then(function(response) {
        $scope.cached.admins = response.data;
    });
    
        
    $scope.editNotificaionFormSubmit = function()
    {
        $scope.notificationFormBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        var params = angular.copy($scope.model);
        
        // fix dates in order to sent correct format for backend
        params.date_start = moment(params.date_start).format();
        params.date_end   = moment(params.date_end).format();

        // send query
        res = $http.post($rootScope.settings.config.apiURL + '/notifications/update/' + notificationID + '/json', params);

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
            $scope.notificationFormBlock.stop();
        });
    };

}]);