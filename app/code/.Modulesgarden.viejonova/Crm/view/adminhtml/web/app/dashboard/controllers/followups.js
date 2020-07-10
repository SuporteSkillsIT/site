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
        'dashboardFollowupsController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state', '$translate', '$http', '$locale',
                function($rootScope, $scope, $stateParams, ngDialog, $q, blockUI, $state, $translate, $http, $locale)
                        {

                        // for now dont change this
//    $scope.requestForAdmin = $rootScope.currentAdminID;
//    $scope.requestForAdmin = false;

                        // parse some initial info to use in form after
                        $scope.settings = {
                        usedatatime: !$rootScope.settings.config.app.followups_per_day
                        }

                        // TABLE
                        $scope.followupsTable = blockUI.instances.get('followupsTable');
                                $scope.monthlyCalendar = blockUI.instances.get('monthlyCalendar');
                                $scope.displayFilters = false;
                                $scope.rawData = [];
                                $scope.displayed = [];
                                // containers for some overall stats
                                $scope.itemsByPage = 10;
                                $scope.itemsOffset = 0;
                                $scope.itemsFirstNr = 0;
                                $scope.itemsLastNr = 0;
                                $scope.itemsTotal = 0;
                                $scope.updateTotalStats = function(paginationData)
                                {
                                $scope.itemsTotal = paginationData.totalItemCount;
                                        $scope.itemsFirstNr = paginationData.start + 1;
                                        $scope.itemsLastNr = $scope.itemsFirstNr + paginationData.number - 1;
                                        if ($scope.itemsLastNr > $scope.itemsTotal) {
                                $scope.itemsLastNr = $scope.itemsTotal;
                                }
                                }

                        $scope.callServerForFollowups = function(tableState)
                        {
                        // start blockui indicator    
                        $scope.followupsTable.start();
//        $('#followups-table').fixedHeaderTable('destroy');


                                var pagination = tableState.pagination;
                                var start = pagination.start || 0; // This is NOT the page number, but the index of item in the list that you want to use to display the table.
                                var number = pagination.number || 5; // Number of entries showed per page.

                                var params = {
                                start: start,
                                        number: number,
                                        params: tableState,
                                        admin: $scope.requestForAdmin,
                                        campaign: $scope.requestCampaign,
                    };
                                // come on give me data from backend
                                $http.post($rootScope.settings.config.apiURL + '/dashboard/followups/get/json', params).then(function(result)
                        {
                        // update controller container for data from response
                        $scope.displayed = result.data.data;
                                //set the number of pages so the pagination can update
                                tableState.pagination.totalItemCount = result.data.total;
                                tableState.pagination.numberOfPages = Math.ceil(tableState.pagination.totalItemCount / tableState.pagination.number);
                                $scope.updateTotalStats(tableState.pagination);
                                if (result.data.total) {
//                $('#followups-table').fixedHeaderTable({ footer: false, cloneHeadToFoot: false, fixedColumn: false , height: '287px'});
                        }

                        // stop blockui indicator    
                        $scope.followupsTable.stop();
                        }, function(error) {

                        // show message just in case
                        $scope.scopeMessages.push({
                        type:   'danger',
                                title:   "Error!",
                                content: error.data.msg ? error.data.msg : error.statusText,
                        });
                        });
                                // helper for foreach
                                $scope.getRowData = function(row, column) {
                                return row[column.id];
                                }

                };
                                // set up watcher that will keep it updated within backend
                                var dynamicDateFilter = $scope.$watch('filterByDay', function(newVal, oldVal) {
                                if (newVal == null && oldVal == null || newVal == oldVal) { return; }

                                // triger refresh smart table with followups
                                angular.element('#filterbyday').trigger('change');
                                });
                                $scope.changeFilterByDay = function(year, month, day) {
                                $scope.filterByDay = year + '-' + (month + 1) + '-' + day;
                                }


                        $scope.$on('dashboard_requested_admin_change', function(scope) {
                        angular.element('#filterbyday').trigger('change');
                                $scope.refreshFollowupsCount();
                        });
                                $scope.$on('dashboard_requested_campaign_change', function(scope) {
                                angular.element('#filterbyday').trigger('change');
                                        $scope.refreshFollowupsCount();
                                });
                                /////////////////////////////
                                // CLEANERS
                                // when scope will be destroned, to avoid memory leaks
                                // clear watchers/timeouts/intervals etc
                                /////////////////////////////
                                $scope.$on('$destroy', function () {
                                // since we use watcher to check if priority has been changed
                                dynamicDateFilter();
                            });
                                // show remidners
                                $scope.showRemindersForFollowup = function($followup)
                                {
                                ngDialog.open({
                                template: $rootScope.settings.config.rootDir + '/app/contacts/details/followups/templates/modals/reminders.html',
                                        className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
                                        overlay: false,
                                        data: {followup: $followup}
                                });
                        };
                                /**
                                 * for delete followup
                                 */
                                $scope.triggerDeleteFollowup = function(followup)
                                {
                                // triger confirm dialog
                                $scope.confirmDeleteDialog = ngDialog.openConfirm({
                                template:'\
                <h2>Are you sure you want to delete this follow-up ?</h2>\
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
                                        // come on give me data from backend
                                        $http.delete($rootScope.settings.config.apiURL + '/lead/' + followup.resource_id + '/followups/' + followup.id + '/json', {
                                        cache: false,
                                                isArray: true
                                        }).then(function(result)
                                {
                                for (i = 0; i < $scope.displayed.length; i++)
                                {
                                if ($scope.displayed[i].id == followup.id) {
                                $scope.displayed.splice(i, 1);
                                }
                                }

                                $rootScope.$broadcast('loadingNotification', {type: 'finished'});
                                        // triger refresh smart table with followups
                                        $rootScope.$broadcast('refreshFollowupsCount');
                                        // show message just in case
                                        $scope.scopeMessages.push({
                                        type:    'success',
                                                title:   "Success!",
                                                content:  'Follow-up has been deleted',
                                        });
                                }, function(error) {
                                $scope.$emit('loadingNotification', {type: 'finished'});
                                        // show message just in case
                                        $scope.scopeMessages.push({
                                        type:   'danger',
                                                title:   "Error!",
                                                content: error.data.msg ? error.data.msg : error.statusText,
                                        });
                                });
//                

                                });
                                }



                        /**
                         * Followup details modal
                         */
                        $scope.triggerFollowupDetails = function(followup)
                        {
                        $scope.modal = ngDialog.open({
                        template: $rootScope.settings.config.rootDir + '/app/calendar/templates/modals/followup.html',
                                className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
                                overlay: false,
                                data: {
                                followup: followup,
                                        admins:   $scope.$parent.admins,
                                },
                                controller: ['$scope', 'blockUI', '$http', function($scope, blockUI, $http) {
                                // container for reminders array
                                $scope.reminders = [];
                                        // Get the reference to the block service.
                                        $scope.remindersTable = blockUI.instances.get('remindersTable');
                                        $scope.remindersTable.start();
                                        // come on give me data from backend
                                        $http.get($rootScope.settings.config.apiURL + '/calendar/followups/' + $scope.ngDialogData.followup.id + '/reminders/json', {
                                        cache: false,
                                                isArray: true
                                        }).then(function(result)
                                {
                                $scope.reminders = result.data;
                                        $scope.remindersTable.stop();
                                });
                                }]
                        });
                        }


                        /**
                         * for reschedue
                         */
                        $scope.triggerFollowupReschedue = function(followup)
                        {
                        $scope.modal = ngDialog.open({
                        template: $rootScope.settings.config.rootDir + '/app/calendar/templates/modals/rescheduleFollowup.html',
                                className: 'ngdialog-theme-default mg-wrapper ngdialog-overlay',
                                overlay: false,
                                data: {
                                followup: followup,
                                        admins:   $scope.$parent.admins,
                                        usedatatime: $scope.$parent.settings.usedatatime,
                                },
                                controller: ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {

                                // global for datapicker
                                $scope.datapicker = {
                                options: {
                                showWeeks: false,
                                        startingDay: 1
                                }
                                };
                                        if ($scope.ngDialogData.usedatatime === true) {
                                $scope.datapicker.format = 'yyyy-MM-dd HH:mm';
                                        $scope.datapicker.enabletime = true;
                                } else {
                                $scope.datapicker.format = 'yyyy-MM-dd';
                                        $scope.datapicker.enabletime = false;
                                }

                                $scope.reschedue = {
                                followup_id: $scope.ngDialogData.followup.id,
                                        date: new Date(),
                                        updateReminders: true,
                                };
                                        $scope.followupReschedueFormSubmit = function()
                                        {
                                        var params = $scope.reschedue;
                                                params.date = moment(params.date).format();
                                                $http.post($rootScope.settings.config.apiURL + '/lead/' + $scope.ngDialogData.followup.resource_id + '/followups/' + $scope.ngDialogData.followup.id + '/reschedue/json',
                                                        params
                                                        ).then(function(result)
                                        {
                                        $rootScope.$broadcast('loadingNotification', {type: 'finished'});
                                                $rootScope.$broadcast('refreshFollowupsCount');
                                                $scope.closeThisDialog(0);
                                                // triger refresh smart table with followups
                                                angular.element('#filterbyday').trigger('change');
                                        });
                                        };
                                }]
                        });
                    };
                                $scope.followups = [];
//    console.log($scope.followups);

                                $scope.selectedDay = new Date();
                                setUpDate = function(year, month) {

                                $scope.calendarDate = new Date(year, month);
                                        $scope.displayMonth = $locale.DATETIME_FORMATS.MONTH[$scope.calendarDate.getMonth()];
                                        $scope.displayYear = $scope.calendarDate.getFullYear();
                                }
                        setUpDate($scope.selectedDay.getFullYear(), $scope.selectedDay.getMonth());
                                $scope.nextMonth = function () {
                                setUpDate($scope.calendarDate.getFullYear(), $scope.calendarDate.getMonth() + 1);
                                        $scope.refreshFollowupsCount();
                                };
                                $scope.prevMonth = function () {
                                setUpDate($scope.calendarDate.getFullYear(), $scope.calendarDate.getMonth() - 1);
                                        $scope.refreshFollowupsCount();
                                };
                                $scope.alertDay = function (day) {
                                $scope.changeFilterByDay($scope.calendarDate.getFullYear(), $scope.calendarDate.getMonth(), day);
                                };
                                $scope.activeDayFnc = function (day) {
//        console.log('activeDayFnc ', day); 
                        };
                                getCalendarFollowups = function(year, month)
                                {
                                if (year === undefined || typeof year != 'number') {
                                return;
                                }
                                if (month === undefined || typeof month != 'number') {
                                return;
                                }

                                $scope.monthlyCalendar.start();
                                        var params = {
                                        admin: $scope.requestForAdmin,
                                                year:  year,
                                                month: month,
                                                campaign: $scope.requestCampaign,
                                        };
                                        $http.post($rootScope.settings.config.apiURL + '/dashboard/calendar/json',
                                                params, {
                                                cache: false,
                                                        isArray: true
                                                }
                                        ).then(function(result)
                                {
                                $scope.followups = result.data;
                                        $scope.monthlyCalendar.stop();
                                });
                                };
                                $scope.refreshFollowupsCount = function() {
                                getCalendarFollowups($scope.calendarDate.getFullYear(), $scope.calendarDate.getMonth());
                                };
                                $scope.refreshFollowupsCount();
                                $scope.$on('refreshFollowupsCount', function(scope) {
                                $scope.refreshFollowupsCount();
                                });
                        }]);