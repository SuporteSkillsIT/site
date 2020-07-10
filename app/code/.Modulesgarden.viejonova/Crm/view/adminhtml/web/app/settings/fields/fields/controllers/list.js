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
 * CUSTOM FIELDS List
 *
 ***************************************/


angular.module("mgCRMapp").controller(
        'settingsFieldsList',
        ['$scope', '$stateParams', 'ResourceStatusesGroups', '$rootScope', 'ngDialog', '$q', 'blockUI', 'ResourceField', '$timeout',
function( $scope,   $stateParams,   ResourceStatusesGroups,   $rootScope,   ngDialog,   $q,   blockUI,   ResourceField,   $timeout)
{
        
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    
    // just contain single model to push it as create new roles
    $scope.newField         = new ResourceField();   
    // our module roles container
    $scope.fieldGroups      = []; 
    // plain container for fields
    $scope.fields           = [];
    // references to parent scope
    // just for messages
    
    // just function to obtain permisions roles
    $scope.getFieldGroups = function()
    {
        // obtain roles from backend
        $scope.fieldGroups = ResourceStatusesGroups.query();
        
        // when we recieve it
        $scope.fieldGroups.$promise.then(function(data) {
            // groups counter
            synchroFieldsMapToGroups();
    
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    
    $scope.fieldTypes = [
        {
            name: 'text',
            multiple: false,
            validators: [
                'required', 'min', 'max', 'email', 'url', 'ip', 'regex',
            ]
        },
        {
            name: 'textarea',
            multiple: false,
            validators: [
                'required', 'min', 'max', 'regex',
            ]
        },
        {
            name: 'checkbox',
            multiple: true,
            validators: [
                'required', 'min', 'max',
            ]
        },
        {
            name: 'radio',
            multiple: true,
            validators: [
                'required', 'min', 'max',
            ]
        },
        {
            name: 'select',
            multiple: true,
            validators: [
                'required', 'min', 'max',
            ]
        },
    ];
    
    // just function to obtain permisions roles
    $scope.getFields = function()
    {
        // obtain roles from backend
        $scope.fields = ResourceField.query();
        
        // when we recieve it
        $scope.fields.$promise.then(function(data) {
            // do sth
            synchroFieldsMapToGroups();
            
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    
    // Get the reference to the block service.
    $scope.fieldsTable = blockUI.instances.get('fieldsTable');
    initRequiredData = function()
    {
        // Start blocking the table
        $scope.fieldsTable.start();
        
        // on init get that roles
        $scope.getFields();
        
        // on init get that roles
        $scope.getFieldGroups();
        
          
    };
    // run this
    initRequiredData();
    
        
    /**
     * sync this controller on initialize
     * basically this will put fields for correct fields group
     * needs to be done in this way in order to use angular resource model to edit each field under group
     */
    function synchroFieldsMapToGroups()
    {
        // just block whatever any of this is empty
        if( $scope.fieldGroups.length == 0 || $scope.fields.length == 0 ) { 
            return; 
        }
        
        for (var i = 0; i < $scope.fieldGroups.length; i++) 
        {
            $scope.fieldGroups[i].fields = [];
            
            for (var j = 0; j < $scope.fields.length; j++) 
            {
//                console.log('field #', $scope.fields[j].id, ' group #', $scope.fields[j].group_id, 'GRP #', $scope.fieldGroups[i].id);
                if( $scope.fields[j].group_id == $scope.fieldGroups[i].id ) {
                    $scope.fieldGroups[i].fields.push($scope.fields[j]);
                }
                    
            }
        }        
        
        // Start blocking the table
        $scope.fieldsTable.stop();
    }
    
    
    /**
     * helper might be usefull
     */
    function getFieldByID(id)
    {
        
        for (var i = 0; i < $scope.fieldGroups.length; i++) 
        {
            if( typeof($scope.fieldGroups[i].fields) != 'undefined' && $scope.fieldGroups[i].fields.length > 0 ) 
            {
                for (var j = 0; j < $scope.fieldGroups[i].fields.length; j++) 
                {
                    if( id == $scope.fieldGroups[i].fields[j].id ) {
                        return $scope.fieldGroups[i].fields[j];
                    }
                }
            }
        }   
        
        return false;
    }
    
    
    
    
    $scope.formSubmitNewBlock   = blockUI.instances.get('formSubmitNewBlock');
    
    $scope.submitted = false;
    // submit new status form
    $scope.newFieldFormSubmit = function() 
    {
        $scope.formSubmitDone = false;
        $scope.formSubmitNewBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        $scope.newField.$save(function(result) 
        {
            
            if(result.status == 'success') {
                
                $scope.formSubmitDone = true;
                $scope.submitted = false;
                
                $scope.fieldform.$dirty = false;
                $scope.fieldform.$pristine = true;
                $scope.fieldform.$submitted = false;
                
                $timeout(function() {
                    $scope.formSubmitDone = false;
                }, 8000);
                
                $scope.formNewReset();
                $scope.getFields();
                
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
    $scope.formNewReset = function() {
        $scope.newField.name = '';
        $scope.newField.description = '';
        $scope.newField.active = true;
    };
    // trigger on initialize
    $scope.formNewReset();
    
    
    
    
    
    
    
    
    
    
    
    
    /////////////////////////////
    //    
    // UPDATE SINGLE PARAMETER FOR FIELD
    /////////////////////////////
    
    // update role
    $scope.sentFieldToUpdate = function(fieldID, name, data) 
    {
        field = getFieldByID(fieldID);
        
        // send query
        res = ResourceField.updateSingleParam(fieldID, name, data);
        
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
            
            // plain upfate this model by new value
            field[name] = data;
            
            return true;
        }, function(response) {
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
        
    };
    
    
    /////////////////////////////
    //    
    // DELETE FIELD
    /////////////////////////////
    // delete existing status
    $scope.confirmDelete = function(fieldID)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this field ?</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            plain: true,
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false
            
        }).then(function(){
            
            // push loading indicator
            $scope.$emit('loadingNotification', {type: 'progress'});

            // send query
            field = getFieldByID(fieldID);
            res = field.$delete({id: fieldID});
        
            res.then(function(response) {

                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: response.msg,
                });

//                $timeout(function() {
//                    $scope.formSubmitDone = false;
//                }, 8000);
                
                // refresh list
                $scope.getFields();

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
    // ORDER UPDATE
    /////////////////////////////
    
    // options for sortable
    $scope.sortableOptions = 
    {
        handle: '.mySortableHandler',
        placeholder: "sortableItem",
        connectWith: ".sortableContainer",
        stop: function(e, ui) {
            // push loading indicator
            $scope.$emit('loadingNotification', {type: 'progress'});
            
            
            // after we put element in correct place
            // trigger update new order with backend
            updateFieldsOrder();

    
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});



        }
    };

    
    // save new order in backend
    updateFieldsOrder = function()
    {
        // get new order
        var newOrder = {};
        
        for (var i = 0; i < $scope.fieldGroups.length; i++) 
        {
            for (var j = 0; j < $scope.fieldGroups[i].fields.length; j++) 
            {
                newOrder[$scope.fieldGroups[i].fields[j].id] = {
                    order: j,
                    group: $scope.fieldGroups[i].id,
                };
            }    
        }        
        
        
        // send query
        res = ResourceField.reorder(newOrder);
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
    
}]);

