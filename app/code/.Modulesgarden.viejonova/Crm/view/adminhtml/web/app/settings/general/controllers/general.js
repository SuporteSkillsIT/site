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
        'settingsAbstractGeneralController',
        ['$scope',
function( $scope)
{
    // just for messages
    $scope.scopeMessages        = [];
}]);



angular.module("mgCRMapp").controller(
        'settingsGeneralController',
        ['$rootScope', '$scope',   '$stateParams', '$timeout', 'ngDialog', '$q', 'blockUI', '$http', 'ResourcefollowupTypes',
function( $rootScope,   $scope,     $stateParams,   $timeout,   ngDialog,   $q,   blockUI,   $http,   ResourcefollowupTypes)
{
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    $scope.activeTab            = 'overview';
    // crm email templates
    $scope.templates            = [];
    // container for global settings object
    $scope.status               = {};
    $scope.settings = {
        general: {},
        followups: {},
    };
    
    // just function to obtain permisions roles
    $scope.getConfig = function()
    {
        // send query
        return $http.get($rootScope.settings.config.apiURL + '/settings/generalWithStatus/json').then(function(response) 
        {
            if( Object.prototype.toString.call( response.data.global ) === '[object Array]' ) {
                $scope.settings = {};
            } else {
                $scope.settings = response.data.global;
                
            }
            
            $scope.status       = response.data.status;
            $scope.templates    = response.data.templates;
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
    
    $scope.submitGeneralSettings = function()
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/settings/general/json', $scope.settings).then(function(response) 
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /////////////////////////////
    //    
    //      FOLLOW UP's
    //   TYPES MANAGEMENT
    // 
    /////////////////////////////
    // init
    $scope.newFollowupType      = {};
    $scope.followupTypes        = [];
    $scope.followupTypesBlock   = blockUI.instances.get('followupTypesBlock');
    $scope.formSubmitNewBlock   = blockUI.instances.get('formSubmitNewBlock');
    /**
     * getters
     */
    getFollowupTypes = function()
    {
        // Start blocking the table witr roles
        $scope.followupTypesBlock.start();
        // obtain roles from backend
        $scope.followupTypes = ResourcefollowupTypes.query();
        
        // when we recieve it
        $scope.followupTypes.$promise.then(function(data) {

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
            $scope.followupTypesBlock.stop();
        });
    };
    // get on init list
    getFollowupTypes();
    
    
    
    /**
     * Submit create new type form
     */
    $scope.submitted = false;
    $scope.addFollowupTypeFormSubmit = function()
    {
        $scope.formSubmitDone = false;
        $scope.formSubmitNewBlock.start();
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        $scope.newFollowupType.$save(function(result) 
        {
            
            if(result.status == 'success') {
                
                $scope.formSubmitDone = true;
                
                $scope.submitted = false;
                
                $scope.addFollowupTypeForm.$dirty = false;
                $scope.addFollowupTypeForm.$pristine = true;
                $scope.addFollowupTypeForm.$submitted = false;
                
                newFollowupFormReset();
                getFollowupTypes();
                
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
    
    /**
     * reset new status data
     */
    newFollowupFormReset = function() 
    {
        $scope.newFollowupType          = new ResourcefollowupTypes();   
        $scope.newFollowupType.name     = '';
        $scope.newFollowupType.color    = '#000000';
        $scope.newFollowupType.active   = true;
        
    };
    // trigger on initialize
    newFollowupFormReset();
    
    
    /**
     * Update single paremeter
     * on direct changes in table (xeditable)
     */
    $scope.sentFollowupTypeToUpdate = function(index, name, data) 
    {
        // send query
        res = ResourcefollowupTypes.updateSingleParam($scope.followupTypes[index].id, name, data);
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});

        // maintain response
        res .then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            return true;
        }, function(response) {
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'error occured';
        });
        
    };
    
    /**
     * delete existing type
     * with conrfirmation box
     */
    $scope.confirmFollowupTypeDelete = function(roleIndex)
    {
        // triger confirm dialog
        $scope.confirmDeleteDialog = ngDialog.openConfirm({
            template:'\
                <h2>Are you sure you want to delete this Follow-up Type ?</h2>\
                <div class="ngdialog-buttons text-center">\
                    <button type="button" class="ngdialog-button ngdialog-button-secondary" ng-click="closeThisDialog(0)">No</button>\
                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click="confirm(1)">Yes</button>\
                </div>',
            plain: true,
            className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
            overlay: false
            
        }).then(function(){
            
            // perform resource delete
            var res = $scope.followupTypes[roleIndex].$delete({id: $scope.followupTypes[roleIndex].id});

            res.then(function(response) {

                // show message
                $scope.scopeMessages.push({
                    type: 'success',
                    title: "Success!",
                    content: response.msg,
                });

                // remove from scope
                $scope.followupTypes.splice(roleIndex, 1);

            },function(response) {

                $scope.scopeMessages.push({
                    type: 'danger',
                    title: "Error!",
                    content: response.data.msg ? response.data.msg : response.statusText,
                });

                mgCRM.scrollTo($('#settingsBlockHeader .box'));
            });
    
        });
    }
    
    
    /**
     * Trigger dragable order upodate
     * push new order to backend
     */
    updateFollowupTypesOrder = function()
    {
        // get new order
        var newOrder = $scope.followupTypes.map(function(i){
          return i.id;
        });
        
        // send query
        res = ResourcefollowupTypes.reorder(newOrder);
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
    
    /**
     * Just settings for dragable actions
     */
    $scope.sortableOptions = 
    {
        handle: '> .mySortableHandler',
        stop: function(e, ui) {
            // after we put element in correct place
            // trigger update new order with backend
            updateFollowupTypesOrder();
        }
    };
    
}]);

