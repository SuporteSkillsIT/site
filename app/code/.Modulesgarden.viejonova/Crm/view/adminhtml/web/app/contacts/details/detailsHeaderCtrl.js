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
        'detailsHeaderCtrl',
        ['$scope', '$rootScope', '$state', '$stateParams', 'leadMainDetailsData', '$http', 'ngDialog', 'AclService',
function( $scope,   $rootScope,   $state,   $stateParams,   leadMainDetailsData,   $http,   ngDialog,   AclService)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    $scope.mainData = leadMainDetailsData;
    // dynamic variables only for this scope manipulation on the fly
    $scope.temp = {
        departments: [],
        admins: [],
        statuses: [],
        system_email: [],
        followupTypes: [],
        templates: {
            admin:  [],
//            client: [],
//            sms:    [],
        },
    };
    
    // check!
    if(!AclService.can('resources.not_mine') && $scope.mainData.admin_id != $rootScope.currentAdminID) {
        $state.go('dashboard');
        $rootScope.$broadcast('AclNoAccess', {rule: 'resources.not_mine'});
    }
    
    /////////////////////////////
    // Perform actions on initialize these controller
    /////////////////////////////
    // come on give me data from backend
    $http.get($rootScope.settings.config.apiURL + '/helpers/lead/background/all/json', {
        cache: true,
    }).then(function(result) 
    {
        $scope.temp.statuses            = result.data.statuses;
        $scope.temp.departments         = result.data.departments;
        $scope.temp.admins              = result.data.admins;
        $scope.temp.system_email        = result.data.system_email;
        $scope.temp.followupTypes       = result.data.followupTypes;
        $scope.temp.templates.admin     = result.data.templates.admin;
    //    $scope.temp.templates.sms       = result.data.templates.sms;
     //   $scope.temp.templates.client    = result.data.templates.client;
        $scope.temp.campaigns           = result.data.campaigns;

        $scope.temp.adminsUnassign      = result.data.admins;
      //  $scope.temp.adminsUnassign.push({id:0, full: '-- Unassign'});

        $scope.$broadcast('header_gotTmpData');
    });
    
    
    /**
     * Simple function trigger update 
     * ONE parameter for resource
     * 
     * mainly these are used for manage static fields
     */
    $scope.updateStatic = function(what, data)
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // collect params
        var params = {};
        params[what] = data;
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/updateSingleParam/json', params).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.mainData.updated_at = response.data.updated_at;
            
            $scope.mainData[what] = data;
            
            if(what == 'type_id') {
                
                name = 'unknown';
                for(i=0; i< $scope.contactTypes.length; i++) {
                    if(parseInt(data) == $scope.contactTypes[i].id) {
                        name = $scope.contactTypes[i].name;
                    }
                }
                
                // show message
                $scope.addConvertMessages('success', "Success!", 'Contact has been converted to ' + name);
            }
            
            return true;
        }, function(response) {
            
            if(what == 'type_id') {
                
                
                // show message
                $scope.addConvertMessages('danger', "Error!", error.data.msg ? error.data.msg : error.statusText);
            }
            
            
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'Undefined error';
        });

    };
    
    
    /////////////////////////////
    // Rating Management
    /////////////////////////////
    // show dynamic priority percentage indicator as it changes
    $scope.priorityChangeOnHover = function(value) {
        if(AclService.can('resources.change_priority') && !!!$scope.mainData.deleted_at) {
            $scope.temp.priorityRating = value;
        }
    };
    // recalculate priority percentage value
    $scope.priorityChangeOnLave = function() {
        $scope.temp.priorityRating = $scope.mainData.priority;
    };
    // trigger on init, based on model value
    $scope.priorityChangeOnLave();
    // set up watcher that will keep it updated within backend
    var priorityChangeWacher = $scope.$watch('mainData.priority', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        
        $scope.updateStatic('priority', newVal);
        
    });
    // set up watcher that will keep it updated within backend
    var contacTypeChangerWatch = $scope.$watch('mainData.type_id', function(newVal, oldVal) {
        if(newVal == null && oldVal == null || newVal ==  oldVal) { return;}
        $state.reload();
    });
    
    

    /////////////////////////////
    // Status Change
    /////////////////////////////
    var statusChangeWacher = $scope.$watch('mainData.status_id', function(newVal, oldVal) {
        if (newVal !== oldVal) {
            
            for(i=0; i < $scope.temp.statuses.length; i++)
            {
                if($scope.temp.statuses[i].id == newVal) {
                    $scope.mainData.status = $scope.temp.statuses[i];
                    
                    return $scope.updateStatic('status_id', newVal);
                }
            }
        }
    });
    
    
    /////////////////////////////
    // Change Admin
    /////////////////////////////
    var adminChangeWacher = $scope.$watch('mainData.admin_id', function(newVal, oldVal) {
        if (newVal !== oldVal) {
            
            for(i=0; i < $scope.temp.admins.length; i++)
            {
                if($scope.temp.admins[i].user_id == newVal) {
                    $scope.mainData.admin = $scope.temp.admins[i];
                    
                    return $scope.updateStatic('admin_id', newVal);
                }
            }
        }
    });


    /////////////////////////////
    // Convert this resource to other type
    /////////////////////////////
    $scope.convertToType = function(what) {
        $scope.updateStatic('type_id', what);
    };
    
    
    /////////////////////////////
    // Delete lead, or at least hide
    /////////////////////////////
    $scope.deleteLead = function() {
        
        // triger confirm dialog
        ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to send this Contact to Archive?</h2>\
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
            res = $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/softDelete/json');
        
            res.then(function(response) {

                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});

                if(response.data.deleted_at) {
                    $scope.mainData.deleted_at = response.data.deleted_at;
                }

                // and now we should go to another state lets say lead list
                
                // show message
                $scope.addConvertMessages('success', "Success!", response.data.msg);
                
            },function(response) {

                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
                
                // show message
                $scope.addConvertMessages('danger', "Error!", response.data.msg);
                
            });
    
        });
    };
    
    /////////////////////////////
    // restore lead basically remove deleted flag
    /////////////////////////////
    $scope.restoreLead = function() {
        
        // triger confirm dialog
        ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to restore this Contact from Archive ?</h2>\
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
            res = $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/restore/json');
        
            res.then(function(response) {

                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});

                $scope.mainData.deleted_at = null;
                
                // and now we should go to another state lets say lead list
                // show message
                $scope.addConvertMessages('success', "Success!", response.data.msg);
                
            },function(response) {

                // loading indicator as compleate
                $scope.$emit('loadingNotification', {type: 'finished'});
                

                // show message
                $scope.addConvertMessages('danger', "Error!", response.data.msg);
            });
    
        });
    };
    
    
    $scope.isArchive = function() {
        return ($scope.mainData.deleted_at === null) ? false : true;
    };
    
    
    
    $scope.getMyAdmin = function(onlyid)
    {
        if(onlyid===true) {
            return $rootScope.currentAdminID; 
        }
        
        for(i=0; i < $scope.temp.admins.length; i++)
        {
            if($rootScope.currentAdminID == $scope.temp.admins[i].id) 
            {
                return $scope.temp.admins[i];
            }
        }
        
        return {};
    }
    


    /////////////////////////////
    // CLEANERS
    // when scope will be destroned, to avoid memory leaks
    // clear watchers/timeouts/intervals etc
    /////////////////////////////
    $scope.$on('$destroy', function () {
        // since we use watcher to check if priority has been changed
        priorityChangeWacher();
        statusChangeWacher();
        adminChangeWacher();
    });
}]);