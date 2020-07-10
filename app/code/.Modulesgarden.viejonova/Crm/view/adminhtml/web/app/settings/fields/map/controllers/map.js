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


mgCRMapp.factory(
        "settingsFieldMap", 
       ['$resource', '$rootScope',
function($resource, $rootScope) 
{
    return $resource($rootScope.settings.config.apiURL + '/settings/fields/map/get/json', {}, 
    {
        all: {
            url: $rootScope.settings.config.apiURL + '/settings/fields/map/get/json',
            method: 'GET', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
    });
    
}]);


angular.module("mgCRMapp").controller(
        'settingsFieldsMap',
        ['$rootScope', '$scope', 'blockUI', '$translate', '$http', 'settingsFieldViews', 'settingsFieldMap',
function( $rootScope,   $scope,   blockUI,   $translate,   $http,   settingsFieldViews,   settingsFieldMap)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    $scope.map = {};
    // plain container for fields
    $scope.possibleFields    = [];
    $scope.AllFields         = {
        static: [],
        fields: [],
    };
    $scope.EmptyField = [''];
    $scope.WHMCScustomFields = [];
    
    $scope.backend = {
        fields: false,
        custom: false,
        actual: false,
    }
    $scope.uiBlock = blockUI.instances.get('FieldsMapperBlock');
    $scope.uiBlock.start();
    
    // what is active now
    $scope.staticWHMCSfields  = [
        { key: 'firstname', name: 'First Name' },
        { key: 'address1', name: 'Address 1' },
        { key: 'lastname', name: 'Last Name' },
        { key: 'address2', name: 'Address 2' },
        { key: 'companyname', name: 'Company Name' },
        { key: 'city', name: 'City' },
        { key: 'email', name: 'Email Address' },
        { key: 'state', name: 'State/Region' },
        { key: 'postcode', name: 'Postcode' },
        { key: 'phonenumber', name: 'Phone Number' },
    ];
    
    
    // just function to obtain permisions roles
    getAllFields = function()
    {
        // obtain roles from backend
        res = settingsFieldViews.all();
        
        // when we recieve it
        res.$promise.then(function(data) {
            $scope.AllFields = data;
            $scope.AllFields.static = [''].concat( data.static );
            $scope.AllFields.fields = data.fields;
            
            $scope.backend.fields = true;
            $scope.tryToUnblock();
            
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };

    
    // just obtain whmcs custom fields for client
    getWHMCScustomFields = function()
    {
        // come on give me data from backend
        $http.get($rootScope.settings.config.apiURL + '/helpers/get/whmcs/customfields/json', {
            cache: true,
        }).then(function(result) 
        {
            $scope.WHMCScustomFields = result.data.fields;
            $scope.backend.custom = true;
            $scope.tryToUnblock();
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    
    
    // just obtain whmcs custom fields for client
    getFieldsMap = function()
    {
        // obtain roles from backend
        ress = settingsFieldMap.all();
        
        // when we recieve it
        ress.$promise.then(function(data) {
            $scope.map = data.value;
            $scope.backend.actual = true;
            $scope.tryToUnblock();
            
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    
    
    init = function()
    {
        // block table, to not change values
        getAllFields();
        getWHMCScustomFields();
        getFieldsMap();
    };
    init();
    
    $scope.tryToUnblock = function()
    {
        if  (
                $scope.backend.fields &&
                $scope.backend.custom &&
                $scope.backend.actual
            )
        {
            $scope.uiBlock.stop();
//            $scope.$apply();
        }
    };
    
    
    // submit form
    $scope.fieldsMapperFormSubmit = function()
    {
        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/settings/fields/map/update/json', $scope.map).then(function(result) 
        {
//            console.log(result.data);
            $scope.uiBlock.stop();
            
            $scope.scopeMessages.push({
                type:   'success',
                title:   "Success!",
                content: result.data.msg ? result.data.msg : result.statusText,
            });
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    
    
    
}]);

