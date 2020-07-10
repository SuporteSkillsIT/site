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
 * CUSTOM FIELDS Edit
 *
 ***************************************/


angular.module("mgCRMapp").controller(
        'settingsFieldsEdit',
        ['$scope', '$stateParams', 'ResourceStatusesGroups', '$rootScope', 'ngDialog', '$q', 'blockUI', 'ResourceField', 'ResourceFieldValidators', 'ResourceFieldOptions', '$state',
function( $scope,   $stateParams,   ResourceStatusesGroups,   $rootScope,   ngDialog,   $q,   blockUI,   ResourceField,   ResourceFieldValidators,   ResourceFieldOptions,   $state)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    
    // just contain single model to push it as create new roles
    $scope.field            = {};
    $scope.fieldInitialMulti= null;
    // available validators
    $scope.validators       = [];
    $scope.markedFieldType  = null;
    $scope.newValidator     = new ResourceFieldValidators;
    $scope.newValidator.field_id = $stateParams.id;
    // options
    $scope.newOption        = new ResourceFieldOptions;
    $scope.newOption.field_id = $stateParams.id;
    $scope.fieldOptions     = [];

    /////////////////////////////
    //    
    // GET FIELD TO EDIT
    /////////////////////////////

    $scope.field = ResourceField.get({id: $stateParams.id});
    // when we recieve it
    $scope.field.$promise.then(function(data) {
        // maybe do sth on init
        $scope.fieldInitialMulti = checkForMulti($scope.field.type);
        $scope.updateFieldType();
    }, function(error) {
        // show message
        $scope.scopeMessages.push({
            type:   'danger',
            title:   "Error!",
            content: error.data.msg ? error.data.msg : error.statusText,
        });
    });
    
    function checkForMulti(type)
    {
        for (var j = 0; j < $scope.fieldTypes.length; j++) 
        {
            if( $scope.fieldTypes[j].name === type ) {
                return $scope.fieldTypes[j].multiple === true;
            }
        }
        
        return false;
    };
    
    getValidators = function()
    {
        $scope.validators = ResourceFieldValidators.query({id: $stateParams.id});
        $scope.validators.$promise.then(function(data) {
            // maybe do sth on init
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
        });
    };
    getValidators();
    
    
    
    getFieldOptions = function()
    {
        $scope.fieldOptions = ResourceFieldOptions.query({id: $stateParams.id});
        $scope.fieldOptions.$promise.then(function(data) {
            // maybe do sth on init
        }, function(error) {
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
        });
    };
    getFieldOptions();
        
    $scope.updateFieldType = function()
    {
        for (var j = 0; j < $scope.fieldTypes.length; j++) 
        {
            if( $scope.fieldTypes[j].name == $scope.field.type ) {
                $scope.markedFieldType  = $scope.fieldTypes[j];
                return true;
            }

        }
    };
    
    
    $scope.editFieldFormSubmit = function()
    {
        // perform 
        var res = ResourceField.updateManyParam($scope.field);

        res.then(function(response) {
            
            // show message
            $scope.scopeMessages.push({
                type: 'success',
                title: "Success!",
                content: response.data.msg,
            });
            
            $state.go('settings.fields.list');

        },function(response) {

            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        });
        
        
        
    };
    
    
    $scope.tryChangeFieldType = function()
    {
        // if there are validators, disallow to change!
        $scope.updateFieldType();
    }
    
    $scope.fieldTypes = [
        {
            name: 'text',
            multiple: false,
            validators: [
                'required', 'min', 'max', 'email', 'url', 'regex',
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
                'required',
            ]
        },
        {
            name: 'radio',
            multiple: true,
            validators: [
                'required',
            ]
        },
        {
            name: 'select',
            multiple: true,
            validators: [
                'required', 'max',
            ]
        },
    ];
    
    
    $scope.validatorTypes = [
        'required', 'min', 'max', 'email', 'url', 'regex',
    ];
    
    $scope.filterPosibleValidators = function(what)
    {
        if(! $scope.markedFieldType) return false;
        
        one = true;
        for (var j = 0; j < $scope.validators.length; j++)  {
            if( $scope.validators[j].type == what ) {
                return false;
            }
        }
        
        return ($scope.markedFieldType.validators.indexOf(what) > -1 && one);
    };
               
               
    // submit new 
    $scope.newValidatorFormSubmit = function() 
    {
        // perform 
        var res = $scope.newValidator.$save({id: $scope.field.id});

        res.then(function(response) {

            // show message
            $scope.scopeMessages.push({
                type: 'success',
                title: "Success!",
                content: response.msg,
            });

            getValidators();
        },function(response) {

            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        });
    };
    
    
    /////////////////////////////
    //    
    // DELETE 
    /////////////////////////////
    // delete existing status
    $scope.confirmDeleteValidator = function(index)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this Validator ?</h2>\
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

            
            // perform resource delete
            var res = $scope.validators[index].$delete({id: $stateParams.id, validatorid: $scope.validators[index].id});

            res.then(function(response) {

                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
                
                // show message
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: response.msg,
                });

                // remove from scope
                $scope.validators.splice(index, 1);

            },function(response) {

                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: response.data.msg ? response.data.msg : response.statusText,
                });

            });
    
        });
    }
    
    
    
    $scope.newOptionFormSubmit = function()
    {
        // perform 
        var res = $scope.newOption.$save({id: $scope.field.id});

        res.then(function(response) {

            // show message
            $scope.scopeMessages.push({
                type: 'success',
                title: "Success!",
                content: response.msg,
            });
            
            $scope.newOption        = new ResourceFieldOptions;
            $scope.newOption.field_id = $stateParams.id;

            getFieldOptions();
        },function(response) {

            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        });
        
    };
    
    // delete existing status
    $scope.confirmDeleteOption = function(index)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this Option ?</h2>\
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

            
            // perform resource delete
            var res = $scope.fieldOptions[index].$delete({id: $stateParams.id, validatorid: $scope.fieldOptions[index].id});

            res.then(function(response) {

                // show message
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: response.msg,
                });

                // remove from scope
                $scope.fieldOptions.splice(index, 1);

            },function(response) {

                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: response.data.msg ? response.data.msg : response.statusText,
                });

            });
    
        });
    }
    
    /**
     * helper might be usefull
     */
    function getOptionByID(id)
    {
        
        for (var i = 0; i < $scope.fieldOptions.length; i++) 
        {
            if( id == $scope.fieldOptions[i].id ) {
                return $scope.fieldOptions[i];
            }
        }   
        
        return false;
    }
    
    
    $scope.updateOptionValue = function(index, data)
    {
        console.log($scope.fieldOptions[index]);
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        res = $scope.fieldOptions[index].$update();

        // maintain response
        res .then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            return true;
        }, function(response) {
            console.log(response);
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
    }
    
}]);