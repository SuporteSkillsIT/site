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



/***************************************
 * 
 * CUSTOM FIELDS GROUPS
 *
 ***************************************/


angular.module("mgCRMapp").controller(
        'settingsFieldsGroups',
        ['$scope', '$stateParams', 'ResourceStatusesGroups', '$rootScope', 'ngDialog', '$q', 'blockUI',
function( $scope, $stateParams, ResourceStatusesGroups, $rootScope, ngDialog, $q, blockUI)
{
    
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    
    // just contain single model to push it as create new roles
    $scope.newGroup         = new ResourceStatusesGroups();   
    // our module roles container
    $scope.groups           = [];                               
    
    
    /////////////////////////////
    //    
    // GETTERS ON INIT
    /////////////////////////////
    

    // Get the reference to the block service.
    $scope.fieldGroupsTable = blockUI.instances.get('fieldGroupsTable');
    // just function to obtain permisions roles
    $scope.getGroups = function()
    {
        // Start blocking the table witr roles
        $scope.fieldGroupsTable.start();
        // obtain roles from backend
        $scope.groups = ResourceStatusesGroups.query();
        
        // when we recieve it
        $scope.groups.$promise.then(function(data) {

        }, function(error) {
            
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        // regardless of type response
        }).finally(function(response) {
            // stop blocking table
            $scope.fieldGroupsTable.stop();
        });
    };
    // on init get that roles
    $scope.getGroups();
    
    
    
    /////////////////////////////
    //    
    // ORDER UPDATE
    /////////////////////////////
    
    // save new order in backend
    updateGroupsOrder = function()
    {
        // get new order
        var newOrder = $scope.groups.map(function(i){
          return i.id;
        });
        
        // send query
        res = ResourceStatusesGroups.reorder(newOrder);
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
            
            $scope.$emit('loadingNotification', {type: 'finished'});
            // push scope message
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });

        });
        
    };
    
    // options for sortable
    $scope.sortableOptions = 
    {
        handle: '> .mySortableHandler',
//        update: function(e, ui) {
//        },
        stop: function(e, ui) {
            // after we put element in correct place
            // trigger update new order with backend
            updateGroupsOrder();
        }
    };
    
    
    /////////////////////////////
    //    
    // ADD NEW GROUP
    /////////////////////////////
    
    
    $scope.formSubmitNewBlock   = blockUI.instances.get('formSubmitNewBlock');
    
    $scope.submitted = false;
    // submit new status form
    $scope.groupFormSubmit = function() 
    {

        $scope.formSubmitDone = false;
        $scope.formSubmitNewBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        
        $scope.newGroup.$save(function(result) 
        {
            
            if(result.status == 'success') {
                
                $scope.formSubmitDone = true;
                $scope.submitted = false;
                
                $scope.groupform.$dirty = false;
                $scope.groupform.$pristine = true;
                $scope.groupform.$submitted = false;
   
                $scope.groupFormReset();
                $scope.getGroups();
                
            } else {
                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: result.msg,
                });
            }
            
            $scope.formSubmitNewBlock.stop();
            $scope.$emit('loadingNotification', {type: 'finished'});
            
        });
    };
    
    // reset new status data
    $scope.groupFormReset = function() {
        $scope.newGroup.name = '';
        $scope.newGroup.color = '#000000';
        $scope.newGroup.active = true;
    };
    // trigger on initialize
    $scope.groupFormReset();
    
    
    /////////////////////////////
    //    
    // DELETE GROUP
    /////////////////////////////
    // delete existing status
    $scope.confirmDelete = function(roleIndex)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this group ?</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            plain: true,
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false
            
        }).then(function(){
            
            // perform resource delete
            var res = $scope.groups[roleIndex].$delete({id: $scope.groups[roleIndex].id});

            res.then(function(response) {

                // show message
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: 'Group has been deleted',
                });

                // remove from scope
                $scope.groups.splice(roleIndex, 1);

            },function(response) {

                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: response.data.msg ? response.data.msg : response.statusText,
                });

            });
    
        });
    }
    
    
    
    /////////////////////////////
    //    
    // UPDATE SINGLE PARAMETER FOR MODEL
    /////////////////////////////
    
    // update role
    $scope.sentModelToUpdate = function(index, name, data) 
    {
        // send query
        res = ResourceStatusesGroups.updateSingleParam($scope.groups[index].id, name, data);
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        // maintain response
        res .then(function(response) 
        {
            // push scope message
//            $scope.scopeMessages.push({
//                type: 'success',
//                title: "Success!",
//                content: response.data.msg,
//            });

            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            return true;
        }, function(response) {
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
        
    };
    
    
    
}]);

