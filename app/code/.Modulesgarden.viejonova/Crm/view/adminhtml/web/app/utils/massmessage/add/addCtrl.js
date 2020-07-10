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
        'massmessageAddController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', '$translate', '$http', 'ngDialog',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,   $translate,   $http,   ngDialog)
{
    $scope.scopeMessages    = [];
    $scope.clientgroups     = [];
    $scope.campaigns        = [];
    
    $scope.massmailBlock           = blockUI.instances.get('massmailBlock');
    
    $scope.model = {
        date:               moment().toDate(),
        description:        '',
        message_content:    '',
        message_title:      '',
        message_type:       'email',
        target_type:        'users',
        target_ids:         [],
    };
    
    $http.get($rootScope.settings.config.apiURL + '/helpers/massmessages/get/json', {cache: true}).then(function(response) {
        $scope.clientgroups = response.data.clientgroups;
        $scope.campaigns = response.data.campaigns;
    });
    

    $scope.insertTinyMceVariable = function(variable)
    {
        $scope.$broadcast('$tinymce:inject', variable);
    };
    
    $scope.toogleTinyMce = function(enabled)
    {
        $scope.$broadcast('$tinymce:toogle', enabled);
    };

    var messageMethodWatcher = $scope.$watch('model.message_type', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        
        if(newVal == 'email') {
            $scope.toogleTinyMce(true);
        } else if(newVal == 'sms') {
            $scope.toogleTinyMce(false);
        }
    });


    $scope.addMassMailFormSubmit = function()
    {
        $scope.massmailBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        var params = angular.copy($scope.model);
        
        // fix dates in order to sent correct format for backend
        params.date = moment(params.date).format();

        // send query
        res = $http.put($rootScope.settings.config.apiURL + '/massmessages/create/json', params);

        res.then(function(response) {

            // show message just in case
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Success!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
            
            
            $state.go('utils.massmessage.list');
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
            $scope.massmailBlock.stop();
        });
    };

    /////////////////////////////
    // CLEANERS
    // when scope will be destroned, to avoid memory leaks
    // clear watchers/timeouts/intervals etc
    /////////////////////////////
    $scope.$on('$destroy', function () {
        // since we use watcher to check if priority has been changed
        messageMethodWatcher();
    });
}]);