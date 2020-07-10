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
        'leadsCreateController',
        ['$scope', '$rootScope', 'ngDialog', 'blockUI', 'createLeadService', 'createLeadParams', '$state', 'additionalParams',
function( $scope,   $rootScope,   ngDialog,   blockUI,   createLeadService,   createLeadParams,   $state,   additionalParams)
{
    
    /////////////////////////////
    //    
    // INITIALIZE CONTAINERS ETC    
    /////////////////////////////
    
    // new Lead object
    $scope.newLead          = new createLeadService;
    $scope.assignedAdmin    = {};
    $scope.assignedStatus   = {};
    $scope.assignedType     = {};
    // just for messages
    $scope.scopeMessages    = [];
    // for searched client by ajax
    $scope.searchedClients  = [];
    
    // data resolved from backend
    $scope.params           = {};
    
    // Get the reference to the block service.
    $scope.LeadForm = blockUI.instances.get('createLeadForm');
    
    
    /**
     * ajax select client For Select
     */
    $scope.refreshClietns =  function(query) 
    {
        // just skip on init ot when there is nothing in form
        //if(query == '') return true;
        // obtain clientsfrom backend
        res = createLeadParams.searchClient(query);
        // when we recieve it update results container
        res.then(function(data) {
            $scope.searchedClients = data.data.results;
        });
    };
    $scope.refreshClietns('');
  
    // just focus admin selector
    $scope.setFocusAdmin = function() {
        $scope.$broadcast('setFocusAdmin');
    };
    // just focus client selector
    $scope.setFocusClient = function() {
        $scope.$broadcast('setFocusClient');
    };
    // just focus client selector
    $scope.setFocusType = function() {
        $scope.$broadcast('setFocusType');
    };
    
    
    /**
     * Obtain all params needed to generate this form
     */
    getCreateFormParams = function()
    {
        // obtain from backend
        $scope.params = createLeadParams.get();
        
        // when we recieve it
        $scope.params.then(function(response) {
            // assign
            $scope.params = response.data;
            // play with initial params etc
            parseFormOnInitialize();
            
        }, function(error) {
            
            // show message
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
        });
    };
    
    /**
     * Just initialize function
     */
    initRequiredData = function()
    {
        // Start blocking the table
        $scope.LeadForm.start();
        
        // trigger to obtain backend data
        getCreateFormParams();
        
        // Stop blocking the table
        $scope.LeadForm.stop();
          
    };
    // trigger this
    initRequiredData();
    
    /**
     * As we recieve some data lets parse initial state of form
     */
    parseFormOnInitialize = function()
    {
        // mark admin
        for (var i = 0; i < $scope.params.admins.length; i++) 
        {
            if($scope.params.admins[i].id == $scope.params.currentAdmin.id) {
                $scope.newLead.assignedAdmin = $scope.params.admins[i];
            }
        }
        
        
        // parse fields validators to add nesesary atributres
        

        // mark admin
        for (var i = 0; i < $scope.params.fieldGroups.length; i++) 
        {
            group = $scope.params.fieldGroups[i];
            for (var j = 0; j < group.fields.length; j++) 
            {
                field = group.fields[j];
                field.customType = field.type;
                for (var k = 0; k < field.validators.length; k++) 
                {
                    validator = field.validators[k];
                    
                    
                    if(field.type == 'text') 
                    {
                        
                        if(validator.type == 'min') {
                            $scope.params.fieldGroups[i]['fields'][j].min = parseInt(validator.value);
                        } else if(validator.type == 'max') {
                            $scope.params.fieldGroups[i]['fields'][j].max = parseInt(validator.value);
                        } else if(validator.type == 'regex') {
                            $scope.params.fieldGroups[i]['fields'][j].regex = validator.value;
                        } else if(validator.type == 'email') {
                            $scope.params.fieldGroups[i]['fields'][j].customType = 'email';
                        } else if(validator.type == 'url') {
                            $scope.params.fieldGroups[i]['fields'][j].customType = 'url';
                        }
                    }
                    else if(field.type == 'textarea') 
                    {
                        
                        if(validator.type == 'min') {
                            $scope.params.fieldGroups[i]['fields'][j].min = parseInt(validator.value);
                        } else if(validator.type == 'max') {
                            $scope.params.fieldGroups[i]['fields'][j].max = parseInt(validator.value);
                        } else if(validator.type == 'regex') {
                            $scope.params.fieldGroups[i]['fields'][j].regex = validator.value;
                        }
                    }
                    else if(field.type == 'checkbox') 
                    {
                        if(validator.type == 'min') {
                            $scope.params.fieldGroups[i]['fields'][j].min = parseInt(validator.value);
                        } else if(validator.type == 'max') {
                            $scope.params.fieldGroups[i]['fields'][j].max = parseInt(validator.value);
                        }
                    }
                    else if(field.type == 'select' || field.type == 'multiselect') 
                    {
                        $scope.params.fieldGroups[i]['fields'][j].checked = [];
                        if(validator.type == 'min' && parseInt(validator.value) > 1) {
                            $scope.params.fieldGroups[i]['fields'][j].min     = parseInt(validator.value);
                            field.type = 'multiselect';
                        } else if(validator.type == 'max' && parseInt(validator.value) > 1) {
                            $scope.params.fieldGroups[i]['fields'][j].max = validator.value;
                            field.type = 'multiselect';
                        }
                    }
                    
                    if(validator.type == 'required') {
                        $scope.params.fieldGroups[i]['fields'][j].isRequired = true;
                    }

                }
            }
        }
        
        // set up initial mark status, as an first from all available (but needs to be active)
        for(i=0; i < $scope.params.statuses.length; i++) {
            if($scope.params.statuses[i].active === true) {
                $scope.newLead.assignedStatus = $scope.params.statuses[i];
                break;
            }
        }
        
        // set up initial contact type, as an first from all available (but needs to be active)
        for(i=0; i < $scope.contactTypes.length; i++) {
            if($scope.contactTypes[i].active === true) {
                $scope.newLead.assignedType = $scope.contactTypes[i];
                break;
            }
        }
        
        // set up relation id's injected from link
        if ( additionalParams.ticket_id !== false ) {
            $scope.newLead.ticket_id = additionalParams.ticket_id;
        }
    
    };
    
    
    
    $scope.toggleOpenDatePicker = function($event,datePicker) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope[datePicker] = !$scope[datePicker];
    };
    
    
    
    /////////////////////////////
    //    
    // FORM SUBMIT TRIGGER
    /////////////////////////////
    
    /**
     * Trigerred on form submit action
     * After form validation ;)
     */
    $scope.createLeadFormSubmit = function() 
    {
        // perform save
        var res = $scope.newLead.$save();

        res.then(function(response) {

            // show message
            $scope.scopeMessages.push({
                type: 'success',
                title: "Success!",
                content: response.msg,
            });

            $state.go('contacts.details.summary', {id:response.new.id});

        },function(response) {
            console.log('save failed');
            console.log(response);
            $scope.scopeMessages.push({
                type: 'danger',
                title: "Error!",
                content: response.data.msg ? response.data.msg : response.statusText,
            });

        });
    }
        
    
}]);