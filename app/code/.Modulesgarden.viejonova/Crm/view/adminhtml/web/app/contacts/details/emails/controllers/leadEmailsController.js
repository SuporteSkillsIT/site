/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

angular.module("mgCRMapp").controller(
        'leadEmailsController',
        ['$scope', '$rootScope', '$state', '$stateParams', 'blockUI', '$http', 'leadMainDetailsData', '$timeout',
            function ($scope, $rootScope, $state, $stateParams, blockUI, $http, leadMainDetailsData, $timeout)
            {
                $scope.mainData = leadMainDetailsData;
                // messages 
                $scope.scopeMessages = [];


                $scope.formData = {};
                $scope.formData.departments = [];
                $scope.formData.templates = [];
                $scope.formData.targetEmails = [];

                $scope.modelSentEmail = {
                    to: null,
                    template: 'false',
                };

                $scope.sendEmailDone = false;
                $scope.sendEmailProgress = false;

                // 
                // BOX
                // 
                $scope.newEmailContainer = blockUI.instances.get('newEmailContainer');
                $scope.leadEmailsTable = blockUI.instances.get('leadEmailsTable');

                $scope.displayFilters = false;
                $scope.rawData = [];
                $scope.displayed = [];




                // containers for some overall stats
                $scope.itemsByPage = 10;
                $scope.itemsOffset = 0;
                $scope.itemsFirstNr = 0;
                $scope.itemsLastNr = 0;
                $scope.itemsTotal = 0;


                $scope.updateTotalStats = function (paginationData)
                {
                    $scope.itemsTotal = paginationData.totalItemCount;
                    $scope.itemsFirstNr = paginationData.start + 1;
                    $scope.itemsLastNr = $scope.itemsFirstNr + paginationData.number - 1;
                    if ($scope.itemsLastNr > $scope.itemsTotal) {
                        $scope.itemsLastNr = $scope.itemsTotal;
                    }
                }

                // helper for foreach
                $scope.getRowData = function (row, column) {
                    return row[column.id];
                }

                $scope.callServer = function (tableState)
                {
                    // start blockui indicator    
                    $scope.leadEmailsTable.start();


                    var pagination = tableState.pagination;
                    var start = pagination.start || 0;     // This is NOT the page number, but the index of item in the list that you want to use to display the table.
                    var number = pagination.number || 10;   // Number of entries showed per page.

                    var params = {
                        start: start,
                        number: number,
                        params: tableState,
                    };



                    // come on give me data from backend
                    $http.post($rootScope.settings.config.apiURL + '/lead/' + $stateParams.id + '/logs/emails/json', params).then(function (result)
                    {
                        // update controller container for data from response
                        $scope.displayed = result.data.data;
                        // stop blockui indicator    
                        $scope.leadEmailsTable.stop();

                        //set the number of pages so the pagination can update
                        tableState.pagination.totalItemCount = result.data.total;
                        tableState.pagination.numberOfPages = Math.ceil(tableState.pagination.totalItemCount / tableState.pagination.number);

                        $scope.updateTotalStats(tableState.pagination);

                    }, function (error) {

                        // show message just in case
                        $scope.scopeMessages.push({
                            type: 'danger',
                            title: "Error!",
                            content: error.data.msg ? error.data.msg : error.statusText,
                        });

                    });


                };


                // set up defaults for form
                if (leadMainDetailsData.email != 'undefined' && leadMainDetailsData.email != '') {
                    $scope.formData.targetEmails.push(leadMainDetailsData.email);
                }
                if (typeof leadMainDetailsData.client !== undefined && leadMainDetailsData.client != null) {
                    if (typeof leadMainDetailsData.client.email !== undefined && leadMainDetailsData.client.email != null && $scope.formData.targetEmails.indexOf(leadMainDetailsData.client.email) == -1) {
                        $scope.formData.targetEmails.push(leadMainDetailsData.client.email);
                    }
                }

                if ($scope.formData.targetEmails.length) {
                    $scope.modelSentEmail.to = $scope.formData.targetEmails[0];
                }

                /**
                 * Kind of tricky feature
                 * there are plenty of data we want to use for fill forms/etc
                 * there is no need to wait for whole page to render as we do not obtain it
                 * so lets load it in background
                 * 
                 * // support departments
                 * // templates to use
                 * 
                 */
                getDataForFormsInBackground = function ()
                {
                    // come on give me data from backend
                    $http.get($rootScope.settings.config.apiURL + '/helpers/lead/backgroundFormData/json', {
                        cache: true,
                    }).then(function (result)
                    {
                        $scope.formData.departments = $scope.temp.system_email;
                        $scope.formData.templates = $scope.temp.templates.admin;
                        if ($scope.formData.departments.length > 0)
                        {
                            $scope.formData.departments.splice(0,$scope.formData.departments.length);
                        }
                        $scope.temp.departments.forEach(function (item, index)
                        {
                            $scope.formData.departments.insert(0, {id: '#' + item.id, fullemail: item.fullemail});
                        });
//            if($scope.formData.templates.length) {
//                $scope.modelSentEmail.template  = $scope.formData.templates[0].id;
//            }
                    });
                };
                getDataForFormsInBackground();


                /**
                 * Popup new window with email details
                 * @param {type} id
                 * @returns {undefined}
                 */
                $scope.showEmailPreviewWindow = function (id)
                {
                    window.open('crm.php/email/show/' + id, '', 'width=650,height=400,scrollbars=yes');
                }


                /**
                 * Send email!
                 * 
                 * @returns {undefined}
                 */
                $scope.sendEmailFormSubmit = function ()
                {
                    $scope.sendEmailProgress = true;
                    $scope.newEmailContainer.start();
                    // push loading indicator
                    $scope.$emit('loadingNotification', {type: 'progress'});

                    var emailForm = new FormData();

                    emailForm.append("to", $scope.modelSentEmail.to);
                    emailForm.append("from", $scope.modelSentEmail.from);
                    emailForm.append("template", $scope.modelSentEmail.template);
                    emailForm.append("content", $scope.modelSentEmail.content);
                    emailForm.append("subject", $scope.modelSentEmail.subject);

                    if (jQuery('#files').length)
                    {
                        var files = jQuery('#files').prop('files');   // forgive me for using jquery :D

                        for (i = 0; i < files.length; i++) {
                            emailForm.append("files[]", files[i]);
                        }
                    }


                    // come on give me data from backend
                    $http.post(
                            $rootScope.settings.config.apiURL + '/lead/' + $stateParams.id + '/emails/send/json',
                            emailForm,
                            {
                                withCredentials: true,
                                headers: {'Content-Type': undefined},
                                transformRequest: angular.identity
                            })
                            .then(function (response) {

                                $scope.$emit('loadingNotification', {type: 'finished'});
                                $scope.newEmailContainer.stop();

                                $scope.sendEmailDone = true;
                                $scope.sendEmailProgress = false;

                                // triger refresh smart table
                                jQuery('#emails-table-search').trigger('input');

                                $timeout(function () {
                                    $scope.sendEmailDone = false
                                }, 8000);

                            }, function (response) {

                                console.log(response);
                                $scope.$emit('loadingNotification', {type: 'finished'});
                                $scope.newEmailContainer.stop();
                                $scope.sendEmailDone = false;

                                // show message just in case
                                $scope.scopeMessages.push({
                                    type: 'danger',
                                    title: "Error!",
                                    content: response.data.msg ? response.data.msg : response.statusText,
                                });

                            });



                };









            }]);