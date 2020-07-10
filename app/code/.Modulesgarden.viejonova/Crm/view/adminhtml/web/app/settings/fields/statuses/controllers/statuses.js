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
        'settingsFieldsStatuses',
        ['$scope', '$stateParams', '$rootScope', 'ngDialog', '$q', 'blockUI', 'ResourceStatus',
function( $scope,   $stateParams,   $rootScope,   ngDialog,   $q,   blockUI,   ResourceStatus)
{
    // container for statuses
    $scope.statuses = [];
    
    // Get the reference to the block service.
    $scope.statusesBlock = blockUI.instances.get('statusesTable');
    $scope.getStatuses = function()
    {
        // Start blocking the table witr roles
        $scope.statusesBlock.start();
        // obtain roles from backend
        $scope.statuses = ResourceStatus.query();
        
        // when we recieve it
        $scope.statuses.$promise.then(function(data) {

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
            $scope.statusesBlock.stop();
        });
    };
    

    // on controller init get all statuses
    $scope.getStatuses();
    
        
        
    // determinate if form needs to be saved
    $scope.savedForm = 1;
    $scope.updateFormStatus = function(state) {
        $scope.savedForm = state;
    }
        
    // new status container
    $scope.newStatus = {};
    
    
    $scope.formSubmitNewBlock   = blockUI.instances.get('formSubmitNewBlock');
    $scope.submitted = false;
    // submit new status form
    $scope.statusFormSubmit = function() 
    {
        $scope.formSubmitDone = false;
        $scope.formSubmitNewBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        $scope.newStatus.$save(function(result) 
        {
            
            if(result.status == 'success') {
                
                $scope.formSubmitDone = true;
                $scope.submitted = false;
                
                $scope.statusform.$dirty = false;
                $scope.statusform.$pristine = true;
                $scope.statusform.$submitted = false;
                
                $scope.statusFormReset();
                $scope.getStatuses();
                
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
    $scope.statusFormReset = function() {
        $scope.newStatus = new ResourceStatus();
        $scope.newStatus.name = '';
        $scope.newStatus.color = '#000000';
        $scope.newStatus.active = true;
    };
    // trigger on initialize
    $scope.statusFormReset();
        
        
    // delete existing status
    $scope.confirmDelete = function(status_id)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this status?</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            plain: true,     
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false

        }).then(function(){
            // confirmed
            
            // find status that we need
            $scope.statuses.forEach(function(status, index) 
            {
                // gotcha
                if (status_id === status.id) 
                {
                    // perform resource delete
                    var res = status.$delete({id:status.id});
                    
                    // show message
                    res.then(function(response) {
                        
                        $scope.scopeMessages.push({
                            type: 'success',
                            title: "Success!",
                            content: response.msg,
                        });
                        
                        $scope.statuses.splice(index, 1);
                    },function(response) {
                        
                        $scope.scopeMessages.push({
                            type: 'danger',
                            title: "Error!",
                            content: response.data.msg ? response.data.msg : response.statusText,
                        });
                        
                    });
                    
                }
            });
    
        });
        
        
    }
        
    // options for sortable
    $scope.sortableOptionsStatuese = 
    {
        handle: '> .mySortableHandler',
        stop: function(e, ui) {
            // after we put element in correct place
            // trigger update new order with backend
            updateStatusesOrder();
        }
    };
    
    
    /////////////////////////////
    //    
    // ORDER UPDATE
    /////////////////////////////
    
    // save new order in backend
    updateStatusesOrder = function()
    {
        // get new order
        var newOrder = $scope.statuses.map(function(i){
            return i.id;
        });
        
        // send query
        res = ResourceStatus.reorder(newOrder);
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        // maintain response
        res .then(function(response) 
        {
                
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
        
                
            return true;
            
        }, function(response) {
            
            // push scope message
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : 'error occured',
            });

        });
        
    };
    
    // Update all statuses
    $scope.massSaveStatuses = function()
    {
        // block table, to not change values
        $scope.statusesBlock.start();
        
        // perform update by resource model
        res = ResourceStatus.massUpdate($scope.statuses);
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        // maintain response
        res .then(function(response) {
                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
                
//                $scope.scopeMessages.push({
//                    type: response.data.status,
//                    title: response.data.status == 'success' ? "Success!" : "Error!",
//                    content: response.data.msg,
//                });

                $scope.updateFormStatus(1);

        }, function(response) {
                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: response.data.msg ? response.data.msg : response.statusText,
                });

            })
            .finally(function(response) {
                $scope.statusesBlock.stop();
        });
        
        
        
    };
        
        
    
}]);