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
        'settingsMigratorController',
        ['$rootScope', '$scope',   '$stateParams', '$timeout', 'ngDialog', '$q', 'blockUI', '$http', 'isAllowed',
function( $rootScope,   $scope,     $stateParams,   $timeout,   ngDialog,   $q,   blockUI,   $http,   isAllowed)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    $scope.activeTab            = 'overview';
    // just for messages
    $scope.scopeMessages        = [];
    // container for global settings object
    $scope.status               = {};
    $scope.statusescheck        = false;
    $scope.fieldscheck          = false;
    // disable tabsif version wont mach
    $scope.versionMach          = false;
    
    
    // datas containers
    $scope.container = {
        actual: {
            statuses: [],
            fields: [],
        },
        old: {
            statuses: [],
            fields: [],
        },
    };
    
    $scope.migrationSubmitBlock   = blockUI.instances.get('migrationSubmitBlock');
    
    // mapper model
    $scope.map = {};
    $scope.migrateForm = {};
    
    $scope.changeActiveTab = function(tab) {
        if(tab == 'fields' && $scope.statusescheck != true ) {
            return;
        } else if(tab == 'resources' && $scope.fieldscheck != true && $scope.statusescheck != true ) {
            return;
        } else {
            $scope.activeTab = tab;
        }
    };
    
    
    // just function to obtain permisions roles
    $scope.getConfig = function()
    {
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/migration/overview/json').then(function(response) 
        {
//            if( Object.prototype.toString.call( response.data.global ) === '[object Array]' ) {
//                $scope.settings = {};
//            } else {
//                $scope.settings = response.data.global;
//            }
            
            $scope.status = response.data;
            
            // version macher set up
            if($scope.status.lastVersion == '1.2.4') {
                $scope.versionMach = true;
            }
            
            
        }, function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            // push scope message
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });
        });
    };
    $scope.getConfig();
    
    
    
    
    
    // grab list of statuses in 1.x version
    //
    getOldListOfStatuses = function()
    {
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/migration/old/getStatuses/json').then(function(response) 
        {
            $scope.container.old.statuses = response.data;
        });
    };
    getOldListOfStatuses();
    
    // grab list of statuses in 2.x version
    //
    getActualListOfStatuses = function()
    {
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/migration/actual/getStatuses/json').then(function(response) 
        {
            $scope.container.actual.statuses = response.data;
        }, function(response) {
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });
        });
    };
    getActualListOfStatuses();
    
    
    
    // grab list of fields in 1.x version
    //
    getOldListOfFields = function()
    {
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/migration/old/getFields/json').then(function(response) 
        {
            $scope.container.old.fields = response.data;
        });
    };
    getOldListOfFields();
    
    
    
    // grab list of fields in 2.x version
    //
    getActualListOfFields = function()
    {
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/migration/actual/getFields/json').then(function(response) 
        {
            $scope.container.actual.fields = response.data;
        }, function(response) {
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });
        });
    };
    getActualListOfFields();
    
    
    
    
    $scope.showResultStatus = false;
    $scope.logfile = '';
    $scope.migrationMessages = [];
    $scope.migrationFormSubmit = function()
    {
        $scope.showResultStatus = true;
        $scope.migrationSubmitBlock.start();
            
        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/migration/perform/json', $scope.map).then(function(result) 
        {
            $scope.migrationSubmitBlock.stop();
            
            $scope.migrationMessages.push({
                type:   'success',
                title:   "Success!",
                content: result.data.msg ? result.data.msg : result.statusText,
            });
            
            $scope.logfile = result.data.logfile;
            
        }, function(error) {
            
            $scope.migrationSubmitBlock.stop();
            
            // show message
            $scope.migrationMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    
    
}]);

