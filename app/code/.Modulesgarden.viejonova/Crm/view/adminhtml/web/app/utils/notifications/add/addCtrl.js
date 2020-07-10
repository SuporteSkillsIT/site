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
        'notificationsAddController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', '$translate', '$http', 'ngDialog',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,   $translate,   $http,   ngDialog)
{
    $scope.scopeMessages   = [];
    
    $scope.notificationFormBlock    = blockUI.instances.get('notificationForm');
    
    
    $scope.model = {
        date_start: moment(moment().year()+"-01-01").toDate(),
        date_end:   moment(moment().year() + 1 +"-01-01").toDate(),
        class: '',
        type: 'temporary',
    };
    
    
    // cached data for filters
    $scope.cached = {
        admins: [],
    };
    
    /////////////////////////////
    // Perform actions on initialize these controller
    /////////////////////////////
    $http.get($rootScope.settings.config.apiURL + '/helpers/select/adminToReassign/json', {cache: true}).then(function(response) {
        $scope.cached.admins = response.data;
    });
    
        

    $scope.addNotificaionFormSubmit = function()
    {
        $scope.notificationFormBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        var params = angular.copy($scope.model);
        
        // fix dates in order to sent correct format for backend
        params.date_start = moment(params.date_start).format();
        params.date_end   = moment(params.date_end).format();

        // send query
        res = $http.put($rootScope.settings.config.apiURL + '/notifications/create/json', params);

        res.then(function(response) {

            // show message just in case
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Success!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
            
            
            $state.go('utils.notifications.list');
            return;

        },function(response) {

            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.notificationFormBlock.stop();
        });
    };

}]);