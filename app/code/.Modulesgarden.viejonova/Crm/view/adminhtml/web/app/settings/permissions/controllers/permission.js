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
        'settingsPermissionController',
        ['$rootScope', '$scope', '$stateParams', '$timeout', 'adminRole', 'settingsPermissionGroups', 'ngDialog', '$q', 'blockUI', '$translate',
function( $rootScope,   $scope,   $stateParams,   $timeout,   adminRole,   settingsPermissionGroups,   ngDialog,   $q,   blockUI,   $translate)
{
                
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    
    // just contain single model to push it as create new roles
    $scope.newGroup         = new settingsPermissionGroups();   
    // whmcs admin role groups container
    $scope.adminRoles       = [];                               
    // our module roles container
    $scope.roles            = [];                               
    // just for messages
    $scope.scopeMessages    = [];
    $scope.updateMessages    = [];
    
    //
    /////////////////////////////
    //    
    // GETTERS ON INIT
    /////////////////////////////
    
    
    // lets get admin roles those whmcs default one
    adminRole.query().$promise.then(function(data) 
    {
        $scope.adminRoles = data;
        
        // on init mark every group as not used
        // for editable forms
        angular.forEach($scope.adminRoles, function(val, name) {
            val.used = false;
        });
            
        // mark first as selected to damm modal - since is binded to model ;)
        if( $scope.adminRoles.length > 0 ) {
            if( typeof $scope.adminRoles[0].role_id != 'undefined' ) {
                $scope.newGroup.role = $scope.adminRoles[0].role_id;
            }
        }
    });
    
    

    // Get the reference to the block service.
    $scope.blockRolesTable      = blockUI.instances.get('permisionRolesTable');
    $scope.formSubmitNewBlock   = blockUI.instances.get('formSubmitNewBlock');
    // just function to obtain permisions roles
    $scope.getPermisionRoles = function()
    {
        // Start blocking the table witr roles
        $scope.blockRolesTable.start();
        // obtain roles from backend
        $scope.roles = settingsPermissionGroups.query();
        
        
        
        
        // when we recieve it
        $scope.roles.$promise.then(function(data) {
            
            
            angular.forEach($scope.roles, function (item, index) {
                if (item.admin_groups == null) {
                    item.admin_groups = [];
                }
            });
            
            // initialize rules for each role to parse it significantly
            reInitializeUsedRules();
            
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
            $scope.blockRolesTable.stop();
        });
    };
    // on init get that roles
    $scope.getPermisionRoles();
    
    
    // function to initialize rules for each role to parse it significantly
    reInitializeUsedRules = function()
    {
        // recalculate which group is used, shal we not duplicate!
        // for editable forms
        used = [];

        // parse each role
        angular.forEach($scope.roles, function(val, name) 
        {
            // each groups inside role
            angular.forEach(val.admin_groups, function(rval, rname) {
                // push it to total uses
                used.push(rval);
            });
        });

        // disable these that are used
        angular.forEach($scope.adminRoles, function(val, name) {
            val.used = (used.indexOf(val.role_id) > -1);
        });
    }
    
    /////////////////////////////
    //    
    // ADD NEW ROLE GROUP
    /////////////////////////////
    
    // submit new group
    $scope.submitted = false;
    $scope.formSubmitNew = function() 
    {
        $scope.formSubmitDone = false;
        $scope.formSubmitNewBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // trigger backend save 
        $scope.newGroup.$save(function(result) 
        {
            // check result
            if(result.status == 'success') 
            {
                $scope.formSubmitDone = true;
                $scope.submitted = false;
                
                $scope.groupform.$dirty = false;
                $scope.groupform.$pristine = true;
                $scope.groupform.$submitted = false;
                
                $scope.getPermisionRoles();
            }
            else
            {
                // push error msg
                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: result.msg,
                });
            }
            
            // push loading indicator
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.formSubmitNewBlock.stop();
            
        });
    };
    
    
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
                <h2>Are you sure you want to delete this role ?</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            plain: true,
            disableAnimation: true,
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false

        }).then(function(){
            
            // perform resource delete
            var res = $scope.roles[roleIndex].$delete({id: $scope.roles[roleIndex].id});

            res.then(function(response) {

                // show message
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: response.msg,
                });

                // remove from scope
                $scope.roles.splice(roleIndex, 1);
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
    // UPDATE SINGLE PARAMETER FOR GROUP
    /////////////////////////////
    
    // update role
    $scope.sentModelToUpdate = function(index, name, data) 
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        res = settingsPermissionGroups.updateSingleParam($scope.roles[index].id, name, data);

        // maintain response
        res .then(function(response) 
        {
            // push loading indicator
            $scope.$emit('loadingNotification', {type: 'finished'});
            
            // update disabled groups
            if (name == 'admin_groups') {
                reInitializeUsedRules();
            }
                
            return true;
        }, function(response) {
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
        
    };
    
    
    
    
    /////////////////////////////
    //    
    // SHOW PERMISSION ASSOCIATED WITH GROUP
    /////////////////////////////
    
    // keep this as a container to render html for each rule group
    $scope.availableRules = $rootScope.acl.rulesConfig;
    // parsed rules to handle operations on it in more easier way
    $scope.parsedRules    = $rootScope.acl.parsedRules;
    // currently edited group
    $scope.currentlyEdit  = null;
    
    // reinitialize roules based all possible one and the one that single group have access to
    function parseActiveRoles(checked)
    {
        var returnRoles = {};

        // group
        angular.forEach($rootScope.acl.parsedRules, function(grpRoles, grpName) 
        {
            // have to initialize
            this[grpName] = {};

            // single role
            angular.forEach(grpRoles, function(rChecked, rName) 
            {
                if ( checked == null ) {
                    this[rName] = false;
                }
                else 
                {
                    if ( typeof checked[grpName] == 'undefined' ) {
                        this[rName] = false;
                    }
                    else 
                    {
                        if ( typeof checked[grpName][rName] == 'undefined' ) {
                            this[rName] = false;
                        } else {
                            this[rName] = checked[grpName][rName];
                        }
                    }
                }
            }, this[grpName] );
            
        }, returnRoles);
        
        return returnRoles;
    }
    
    // discard changes
    $scope.roleAccessFormCancel = function() {
        $scope.currentlyEdit  = null;
    };
    
    
    // clicked on selected role
    // parse role actual permisions, and put it to currently edited to show form
    $scope.managePermissions = function(index) 
    {
        $scope.currentlyEdit = $scope.roles[index];
        $scope.currentlyEdit.parsedRules = parseActiveRoles($scope.currentlyEdit.allowed);
    };
    
    // mass actions for permission group
    $scope.roleAccessFormUpdateGroup = function(group, checked) 
    {
        angular.forEach($scope.currentlyEdit.parsedRules[group], function(val, name) 
        {
            $scope.currentlyEdit.parsedRules[group][name] = checked;
        });
    };
    
    
    
    // mass actions for permission group
    $scope.roleAccessFormUpdateAll = function(checked) 
    {
        angular.forEach($scope.currentlyEdit.parsedRules, function(val, name) 
        {
            $scope.roleAccessFormUpdateGroup(name, checked);
        });
    };
    
    // custom filter to amch roles
    $scope.filterRolesMatch = function( criteria ) 
    { 
        return function( item ) 
        {
            for (var i = 0; i < criteria.length; i++) {
                if (criteria[i] == item.role_id) {
                    return true;
                }
            }

            return false;
        };
    };
    
    // custom filter to amch roles
    $scope.filterRolesMatchAndUsed = function( criteria ) 
    {
        return function( item ) 
        {
            for (var i = 0; i < criteria.length; i++) {
                if (criteria[i] == item.role_id) {
                    return true;
                }
            }

            return (item.used == false );
        };
    };
    
    
    
    /////////////////////////////
    //    
    // HANDLE UPDATE OF PERMISSIONS FOR GROUP
    /////////////////////////////
    $scope.roleAccessUpdate = function(currentlyEdit)
    {
        
        // send query
        res = settingsPermissionGroups.updateSingleParam(currentlyEdit.id, 'allowed', currentlyEdit.parsedRules);

        // maintain response
        res .then(function(response) {
            
            // refresh
            $scope.getPermisionRoles();
            
            $scope.updateMessages = [{
                type: 'success',
                title: "Success!",
                content: response.data.msg,
            }];
        
            $timeout(function() {
                $scope.updateMessages = [];
            }, 8000);
                
            return true;
        }, function(response) {
            return response.data.msg ? response.data.msg : 'error occured';

        });
        
    };

}]);
