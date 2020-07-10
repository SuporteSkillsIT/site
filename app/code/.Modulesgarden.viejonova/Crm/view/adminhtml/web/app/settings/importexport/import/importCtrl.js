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
        'importCtrl',
        ['$scope', '$rootScope', '$state', '$stateParams', '$http', 'ngDialog', 'AclService',
function( $scope,   $rootScope,   $state,   $stateParams,   $http,   ngDialog,   AclService)
{
    $scope.fileSummary = {};
    
    $scope.getfileSummary = function()
    {
        $scope.$emit('loadingNotification', {type: 'progress'});
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/importexport/import/summary/json').then(function(response) 
        {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.fileSummary = response.data;
            
        }, function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
        });
        
    };
    $scope.getfileSummary();
    
    /**
     * Send file for import, ooł ye
     * 
     * @returns {undefined}
     */
    $scope.uploadFileFormSubmit = function()
    {
        // push loading indicator
        $scope.importexportBlock.start();
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        var fileForm = new FormData();
        var files = $('#files').prop('files');   // forgive me for using jquery :D
        for(i=0; i<files.length; i++) {
            fileForm.append("files[]", files[i]);
        }
        
        // come on give me data from backend
        $http.post(
                $rootScope.settings.config.apiURL + '/importexport/import/upload/json', 
                fileForm,
                {
                    withCredentials: true,
                    headers: {'Content-Type': undefined },
                    transformRequest: angular.identity
                })
        .then(function(response) {
            
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.importexportBlock.stop();
            
            $scope.uploadFileFormDone     = true;
            
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Succes!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
            
            $scope.getfileSummary();

        }, function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.importexportBlock.stop();
            
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
            
        });
    };
    
    
    $scope.startImporting = function()
    {
        // push loading indicator
        $scope.importexportBlock.start();
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        var params = {
            start: true
        };
        
        // come on give me data from backend
        $http.post(
                $rootScope.settings.config.apiURL + '/importexport/import/start/json', 
                params)
        .then(function(response) {
            
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.importexportBlock.stop();
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Succes!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
            

        }, function(response) {

            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.importexportBlock.stop();
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });
            
        });
    };
    
}]);