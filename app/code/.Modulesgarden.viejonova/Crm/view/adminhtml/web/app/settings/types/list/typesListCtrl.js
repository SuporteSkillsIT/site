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
        'settingsTypesListController',
        ['$rootScope', '$scope', '$stateParams', '$timeout', 'blockUI', '$http', 'ngDialog',
function( $rootScope,   $scope,   $stateParams,   $timeout,   blockUI,   $http,   ngDialog)
{
    // container for action messages
    $scope.scopeMessages = [];
    
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    // just contain single model to push it as create new roles
    $scope.newType          = {};
    // our module roles container
    $scope.types            = [];                               
    
    
    /////////////////////////////
    //    
    // GETTERS ON INIT
    /////////////////////////////
    

    // Get the reference to the block service.
    $scope.typesGroupBlock = blockUI.instances.get('typesGroup');
    // just function to obtain permisions roles
    $scope.getTypes = function()
    {
        $scope.typesGroupBlock.start();
        $scope.$emit('loadingNotification', {type: 'progress'});

        // send query
        res = $http.get($rootScope.settings.config.apiURL + '/settings/types/table/json');

        res.then(function(response) {

            $scope.types = response.data;
            
        },function(response) {

            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.typesGroupBlock.stop();
        });
    };
    // on init get that roles
    $scope.getTypes();



    
    /////////////////////////////
    //    
    // UPDATE SINGLE PARAMETER FOR MODEL
    /////////////////////////////
    
    // update role
    $scope.sentModelToUpdate = function(index, name, data) 
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        var params = {};
        params[name] = data;

        // send query
        res = $http.post($rootScope.settings.config.apiURL + '/settings/types/update/'+ $scope.types[index].id +'/json', params);

        res.then(function(response) {

            //
            
        },function(response) {

            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
        });
        
    };
    

    /////////////////////////////
    //    
    // ORDER UPDATE
    /////////////////////////////
    
    // save new order in backend
    updateTypesOrder = function()
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        $scope.typesGroupBlock.start();
        
        // get new order
        var newOrder = $scope.types.map(function(i){
          return i.id;
        });
        
        var params = {};
        params['order'] = newOrder;

        // send query
        res = $http.post($rootScope.settings.config.apiURL + '/settings/types/reorder/json', params);

        // maintain response
        res .then(function(response) 
        {
            // łiii - done
        }, function(response) {
            // push scope message
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });

        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.typesGroupBlock.stop();
        });
        
    };
    
    // options for sortable
    $scope.sortableOptions = 
    {
        handle: '> .mySortableHandler',
        stop: function(e, ui) {
            // after we put element in correct place
            // trigger update new order with backend
            updateTypesOrder();
        }
    };
    
    
    
    /////////////////////////////
    //    
    // ADD NEW TYPE
    /////////////////////////////
    
    $scope.formSubmitNewBlock   = blockUI.instances.get('formSubmitNewBlock');
    $scope.submitted = false;
    
    // submit new status form
    $scope.groupFormSubmit = function() 
    {
        $scope.formSubmitDone = false;
        $scope.formSubmitNewBlock.start();
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        res = $http.post($rootScope.settings.config.apiURL + '/settings/types/new/json', $scope.newType);

        // maintain response
        res .then(function(response) 
        {
            $scope.formSubmitDone = true;
            $scope.submitted = false;

            $scope.groupform.$dirty = false;
            $scope.groupform.$pristine = true;
            $scope.groupform.$submitted = false;

            $scope.groupFormReset();
            $scope.getTypes();
            
        }, function(response) {
            // push scope message
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });

        }).finally(function(response) {
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.formSubmitNewBlock.stop();
        });
    };
    
    // reset new status data
    $scope.groupFormReset = function() {
        $scope.newType = {
            name: '',
            color: '#000000',
            active: true,
            show_in_nav: true,
            show_in_submenu: true,
            show_in_dashboard: true,
        };
    };
    // trigger on initialize
    $scope.groupFormReset();
    
    
    
    /////////////////////////////
    //    
    // DELETE GROUP
    /////////////////////////////
    // delete existing status
    $scope.confirmDelete = function(index)
    {
        $scope.modelDelete = {
            convert: '',
            archive: false,
            id: $scope.types[index].id,
        };
                
        if($scope.types.length == 1) {
            
            ngDialog.open({
                template:'\
                    <h2>You can not delete last contact type</h2>\
                    <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">Close</button>\
                    </div>',
                plain: true,
                className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
                overlay: false,
            });
            
            return;
        }
        
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this contact type ?</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            template: $rootScope.settings.config.rootDir + '/app/settings/types/modals/delete.html',
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false,
            scope: $scope,
        }).then(function(){
            
            $scope.$emit('loadingNotification', {type: 'progress'});
            
            // send query
            res = $http.post($rootScope.settings.config.apiURL + '/settings/types/delete/json', $scope.modelDelete);

            // maintain response
            res .then(function(response) 
            {
                // show message
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: 'Contact Type has been deleted',
                });

                // remove from scope
                $scope.types.splice(index, 1);

            }, function(response) {
                // push scope message
                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: response.data.msg ? response.data.msg : 'error occured',
                });

            }).finally(function(response) {
                $scope.$emit('loadingNotification', {type: 'finished'});
            });
    
        });
    }
}]);
