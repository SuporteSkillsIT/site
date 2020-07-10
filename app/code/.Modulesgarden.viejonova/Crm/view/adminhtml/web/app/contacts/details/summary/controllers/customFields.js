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
        'detailsSummaryCustomFieldsCtrl',
        ['$scope', '$rootScope', '$state', '$stateParams', 'leadMainDetailsData', '$http', 'ngDialog', 'AclService', 'blockUI',
function( $scope,   $rootScope,   $state,   $stateParams,   leadMainDetailsData,   $http,   ngDialog,   AclService,   blockUI)
{
    
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    $scope.fieldsDataRaw  = [];
    $scope.groupsRaw      = [];
    $scope.fieldsParsed   = [];
    $scope.toogledGroups  = [];
    
    
    
    $scope.fieldsBlockContainer = blockUI.instances.get('fieldsBlockContainer');
    
    
    // just function to obtain permisions roles
    getFieldsWithGroups = function()
    {
        // Start blocking the table
        $scope.fieldsBlockContainer.start();
        
        // come on give me data from backend
        $http.get($rootScope.settings.config.apiURL + '/lead/' + $stateParams.id + '/field/getAll/json').then(function(result) 
        {
            $scope.fieldsDataRaw = result.data.fields;
            $scope.fieldsParsed = $scope.groupsRaw = result.data.groups;


            synchroFieldsToGroups();

            $scope.fieldsBlockContainer.stop();
        }, function(error) {
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
            $scope.fieldsBlockContainer.stop();
            
        });
    };
    getFieldsWithGroups();
    
    
    // trigger on init
    synchroFieldsToGroups = function()
    {
        // each group
        for(i=0; i < $scope.fieldsParsed.length; i++)
        {
            // each field
            for(j=0; j < $scope.fieldsParsed[i].fields.length; j++)
            {
                if($scope.fieldsParsed[i].fields[j].type == 'select') {
                    for(v=0; v < $scope.fieldsParsed[i].fields[j].validators.length; v++) {
                        
                        if($scope.fieldsParsed[i].fields[j].validators[v].type == 'max' && parseInt($scope.fieldsParsed[i].fields[j].validators[v].value) > 1) {
                            $scope.fieldsParsed[i].fields[j].multiple = true;
                        }
                        if($scope.fieldsParsed[i].fields[j].validators[v].type == 'required') {
                            $scope.fieldsParsed[i].fields[j].required = true;
                        }
                    }
                }
                
                // find data for this field
                for(k=0; k < $scope.fieldsDataRaw.length; k++)
                {
                    if($scope.fieldsParsed[i].fields[j].id == $scope.fieldsDataRaw[k].field_id) {
                        
                        $scope.fieldsParsed[i].fields[j].data = $scope.fieldsDataRaw[k];
                        
//                        console.log($scope.fieldsParsed[i].fields[j].type);
//                        console.log($scope.fieldsParsed[i].fields[j].options);
                        $scope.fieldsParsed[i].fields[j].data.optionsRaw = [];
                        if($scope.fieldsParsed[i].fields[j].data.options.length)
                        {
                            $scope.fieldsParsed[i].fields[j].data.optionsParsed = [];
                            for(oi=0; oi < $scope.fieldsParsed[i].fields[j].data.options.length; oi++)
                            {
                                for(oo=0; oo < $scope.fieldsParsed[i].fields[j].options.length; oo++)
                                {
                                    if(parseInt($scope.fieldsParsed[i].fields[j].data.options[oi].option_id) == $scope.fieldsParsed[i].fields[j].options[oo].id) {
                                        $scope.fieldsParsed[i].fields[j].data.optionsParsed.push($scope.fieldsParsed[i].fields[j].options[oo]);
                                        $scope.fieldsParsed[i].fields[j].data.optionsRaw.push($scope.fieldsParsed[i].fields[j].options[oo].id);
                                        break;
                                    }
                                }
                            }
//                            console.log($scope.fieldsParsed[i].fields[j].data.options);
//                            angular.forEach(angular.element("li a"), function(value, key){
//                                var a = angular.element(value);
//                                a.addClass('ss');
//                           });
                        }
                    }
                }
            }
        }
        
        return true;
    };
    
    
    // maintain show/hide groups
    $scope.groupIsHidden = function(id)
    {
        return ($scope.toogledGroups.indexOf(id) > -1);
    };
    
    // maintain show/hide groups
    $scope.groupToogleHideHidden = function(id)
    {
        i = $scope.toogledGroups.indexOf(id);
        if (i > -1) {
            $scope.toogledGroups.splice(i, 1);
        } else {
            $scope.toogledGroups.push(id);
        }
    };
    
    
    
    
    $scope.updateDynamicField = function(fieldID, fieldDataID, $data)
    {
        
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // collect params
        var params = {
            field_id: fieldID,
            field_data_id: fieldDataID,
            data: $data,
        };
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/updatefield/json', params).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.mainData.updated_at = response.data.updated_at;
//            console.log(response);
            
            return true;
        }, function(response) {
            console.log(response);
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'Undefined error';
        });

    }
    
    /////////////////////////////
    // 
    //    CUSTOM FIELDS + XEDITABLE
    //    ASSIGNS/REASSIGNS PLAY WITH MODELS
    //    BASICALLY HANDLE MANAGE FIELS DATA WITH BACKEND
    //    AND XEDITABLE HANDLER   
    // 
    /////////////////////////////
    
    
    // trigger on init
    getParsedFieldByID = function(id)
    {
        // each group
        for(i=0; i < $scope.fieldsParsed.length; i++)
        {
            // each field
            for(j=0; j < $scope.fieldsParsed[i].fields.length; j++)
            {
                if($scope.fieldsParsed[i].fields[j].id == id) {
                    return $scope.fieldsParsed[i].fields[j];
                }
            }
        }
        
        return false;
    };
    
    
    
    // send selected option to backend
    $scope.updateDynamicChoicesField = function(field, fieldDataID, $data)
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // collect params
        var params = {
            field_id: field.id,
            field_data_id: fieldDataID,
            data: $data,
        };
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/updatefield/json', params).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.mainData.updated_at = response.data.updated_at;
            
            field.data = response.data.data;
            
            return true;
        }, function(response) {
            console.log(response);
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'Undefined error';
        });

    }
    
    // aprse select single choice
    $scope.updateDynamicSingleSelectFieldUpdateParsed = function(field)
    {
        field.data.optionsParsed = [];
        for(oi=0; oi < field.options.length; oi++)
        {
            if(parseInt(field.options[oi].id) == parseInt(field.data.optionsRaw[0])) {
                field.data.optionsParsed.push(field.options[oi]);
                break;
            }
        }
    };
    
    // parse response to display it correct via xeditable
    $scope.updateDynamicSelectFieldUpdateParsed = function(field)
    {
        field.data.optionsParsed = [];
        field.data.optionsRaw = [];
        for(oi=0; oi < field.data.options.length; oi++)
        {
            for(oo=0; oo < field.options.length; oo++)
            {
                if(parseInt(field.data.options[oi].option_id) == field.options[oo].id) {
                    field.data.optionsParsed.push(field.options[oo]);
                    field.data.optionsRaw.push(field.options[oo].id);
                    break;
                }
            }
        }
    };
    
    $scope.updateDynamicChoicesFieldUpdateParsed = function(field)
    {
        field.data.optionsParsed = [];
        field.data.optionsRaw = [];
        for(oi=0; oi < field.data.options.length; oi++)
        {
            for(oo=0; oo < field.options.length; oo++)
            {
                if(parseInt(field.data.options[oi].option_id) == field.options[oo].id) {
                    field.data.optionsParsed.push(field.options[oo]);
                    field.data.optionsRaw.push(field.options[oo].id);
                    break;
                }
            }
        }
    };
    
    $scope.updateAssignedCampaigns = function(data)
    {
        // push loading indicator
        $scope.$emit('loadingNotification', {type: 'progress'});
        
        // send query
        return $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.mainData.id + '/sync/campaigns/json', { campaigns: data }).then(function(response) 
        {
            // loading indicator as compleate
            $scope.$emit('loadingNotification', {type: 'finished'});
            $scope.mainData.updated_at = response.data.updated_at;
            
            return true;
        }, function(response) {
            // push message to editable (that module handle show this error in form)
            return response.data.msg ? response.data.msg : 'Undefined error';
        });
    };
    
    
    
}]);