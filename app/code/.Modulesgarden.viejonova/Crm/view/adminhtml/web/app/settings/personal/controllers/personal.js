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
        'settingsPersonalFieldsviewController',
        ['$rootScope', '$scope',   '$stateParams',
function( $rootScope,   $scope,     $stateParams)
{
    
}]);

angular.module("mgCRMapp").controller(
        'settingsPersonalController',
        ['$rootScope', '$scope',   '$stateParams',
function( $rootScope,   $scope,     $stateParams)
{
    
}]);

angular.module("mgCRMapp").controller(
        'settingsPersonalAbstractController',
        ['$rootScope', '$scope',   '$stateParams', '$timeout', 'ngDialog', '$q', 'blockUI', '$http', 'settingsFieldViews',
function( $rootScope,   $scope,     $stateParams,   $timeout,   ngDialog,   $q,   blockUI,   $http,   settingsFieldViews)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////

    $scope.currentlyEdit = null;
    // what is active now
    $scope.currentlyActive  = null;
    // plain container for fields
    $scope.fields           = {};
    // plain container for fields
    $scope.AllFields        = [];
    // for raw configs
    $scope.configs          = {};
    $scope.activeTab        = 'personal';
    $scope.scopeMessages    = [];
            
    // just function to obtain permisions roles
    $scope.getConfig = function()
    {
        // obtain roles from backend
        $scope.configs = settingsFieldViews.query();
        
        // when we recieve it
        $scope.configs.$promise.then(function(data) {
            // do sth
            
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    $scope.getConfig();
    
    
    
    // just function to obtain permisions roles
    $scope.getAllFields = function()
    {
        // obtain roles from backend
        $scope.AllFields = settingsFieldViews.all();
        
        // when we recieve it
        $scope.AllFields.$promise.then(function(data) {
            // do sth
            
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    $scope.getAllFields();

    // plain filter for angular
    $scope.filterAlreadyAdded = function(item) {
        if($scope.currentlyEdit == null) return false;
        exists = ($scope.currentlyEdit.indexOf(item) > -1);
        return !exists;
    };

    // options for sortable
    // kind of tricky with prevent mechanism to not allow drag between two incompatible groups
    // have fun
    $scope.sortableOptions = 
    {
        handle: '.mySortableHandler',
        placeholder: "sortableItem",
        connectWith: ".sortableContainer",
        cancel: ".unsortable",
        beforeStop: function (event, ui) {
//            console.log($(ui.item[0]));
//            console.log($(ui.item[0]).find('span'));
            validclass = ($(ui.item[0]).find('span').hasClass('dynamic') == true) ? 'dynamic' : 'static';

//            console.log(validclass);
//            console.log($(ui.item[0]).closest('.sortableContainer').hasClass(validclass));

            if( ! $(ui.item[0]).closest('.sortableContainer').hasClass(validclass) ) {
                ui.item.sortable.cancel();
            }
        },
        stop: function(e, ui) {
            
            // push loading indicator
            $scope.$emit('loadingNotification', {type: 'progress'});
            
            
            // after we put element in correct place
            // trigger update new order with backend
            $scope.updateFieldsOrder();

    
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});



        }
    };

    
    $scope.updateFieldsOrder = function()
    {
        // send query
        res = settingsFieldViews.updateVisible($scope.currentlyActive, $scope.currentlyEdit);
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        // maintain response
        res .then(function(response) 
        {
                
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
        
            // push scope message
//            $scope.scopeMessages.push({
//                type: 'success',
//                title: "Success!",
//                content: response.data.msg,
//            });
            
                
            return true;
            
        }, function(response) {
            
            // push scope message
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });

        });
        
        
    }
    
    
    $scope.showConfigFor = function(route)
    {
        
        $scope.currentlyActive = route;
        $scope.currentlyEdit   = $scope.configs[$scope.currentlyActive];
        
        $scope.fields = $scope.AllFields;
        $scope.fields = angular.copy($scope.AllFields);
        
        
        for (var i = 0; i < $scope.currentlyEdit.length; i++) 
        {
            indes = $scope.fields.static.indexOf($scope.currentlyEdit[i]);
            if(indes > -1) {
                $scope.fields.static.splice(indes, 1);
            }
        }   
        
        
        for (var i = 0; i < $scope.currentlyEdit.length; i++) 
        {
            if (typeof($scope.currentlyEdit[i]) == 'object') 
            {
                for (var j = 0; j < $scope.fields.fields.length; j++) 
                {
                    if($scope.fields.fields[j].id == $scope.currentlyEdit[i].id) {
                        $scope.fields.fields.splice(j, 1);
                    }
                }   
                
            }
        }   
    };
    
    $scope.isActive = function(what)
    {
        return $scope.currentlyActive == what;
    }
    
    
    
    //////////////////////////////
    // personal settings
    //////////////////////////////
    
    
    $scope.personalSettings = {};
    
    getPersonalSettings = function()
    {
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/settings/personal/json').then(function(response) 
        {
            
            $scope.personalSettings.avatar = response.data.avatar;
            
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
    getPersonalSettings();
    
    $scope.personalSettingsFormSubmit = function()
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/settings/personal/json', $scope.personalSettings).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            $scope.scopeMessages.push({
                type: 'success',
                title: "Success!",
                content: response.data.msg,
            });
            
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
    
    
    
    
}]);

