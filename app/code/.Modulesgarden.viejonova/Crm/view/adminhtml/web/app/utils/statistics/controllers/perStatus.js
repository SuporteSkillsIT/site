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
        'perStatusStatisticsController',
        ['$rootScope', '$scope', '$stateParams', 'ngDialog', '$q', 'blockUI', '$state',  '$translate', '$http',
function( $rootScope,   $scope,   $stateParams,   ngDialog,   $q,   blockUI,   $state,    $translate,   $http)
{
    
    $scope.potentialsPerStatus  = blockUI.instances.get('potentialsPerStatus');
    $scope.leadsPerStatus       = blockUI.instances.get('leadsPerStatus');
    
    $scope.chartOptions  = {
        animation: false,
        responsive: true,
        maintainAspectRatio: true
    };

    $scope.labels = [];
    
    $scope.$on('statuses-updated', function(event) 
    {
        $scope.gotStatuses = true;
        parseRecievedData();
    });
    
    $scope.labels           = [];
    $scope.colors           = [];
    $scope.dataLeads        = [];
    $scope.dataPotentials   = [];
    $scope.data             = [];
    
    $scope.gotStatuses = false;
    $scope.gotData     = false;
    
    parseLabels = function()
    {
        for(i=0; i < $scope.statuses.length; i++)
        {
            $scope.labels.push($scope.statuses[i].name);
            $scope.colors.push($scope.statuses[i].color);
            $scope.data.push(5);
        }
    }
    
    
    // get from backend
    $scope.getRefreshData = function()
    {
        $scope.data             = [];
        $scope.dataParsed       = [];
        
        // Start blocking the table
//        $scope.potentialsPerStatus.start();
        $scope.leadsPerStatus.start();
        
        var params = {
            admin: $scope.requestForAdmin,
        };

        // come on give me data from backend
        $http.post($rootScope.settings.config.apiURL + '/statistics/last/per/status/json', params, {
            isArray: true,
            responseType: 'json'
        }).then(function(result) 
        {
            $scope.data        = result.data;
            $scope.gotData     = true;
            parseRecievedData();

            $scope.leadsPerStatus.stop();
        }, function(error) {
            
            // show message just in case
            $scope.scopeMessages.push({
                type:   'danger',
                title:   "Error!",
                content: error.data.msg ? error.data.msg : error.statusText,
            });
            
            $scope.blockContainers.fields.stop();
            
        });
    }
    $scope.getRefreshData();
    
    
    // parse backend data to chart
    
    parseRecievedData = function()
    {
        if( $scope.gotStatuses == true && $scope.gotData == true )
        {
            for(i=0; i < $scope.data.length; i++)
            {
                var single = {
                    id:     i,
                    name:   $scope.data[i].type.name,
                    data:   [],
                };
                                
                for(j=0; j < $scope.data[i].data.length; j++)
                {
                    // now give me status stats
                    for(k=0; k < $scope.statuses.length; k++)
                    {
                        if ( $scope.statuses[k].id == $scope.data[i].data[j].status_id ) {
                            single.data.push({"label": $scope.statuses[k].name, "color": $scope.statuses[k].color, "value": $scope.data[i].data[j].total})
                        }
                    }
                }
                
                $scope.dataParsed.push(single);
            }

        }
    };
    
    $scope.drawGraphs = function(size)
    {
        for(var i = 0; i < $scope.dataParsed.length; i++)
        {
            new d3pie("pieChartContainer"+i, {
                "header": {
                    "title": {
                        "text": $scope.dataParsed[i].name,
                        "fontSize": 27,
                        "font": "open sans"
                    },
                    "titleSubtitlePadding": 9
                },
                "size": {
                    "canvasWidth": size*10,
                    "pieOuterRadius": "90%"
                },
                "data": {
                    "sortOrder": "value-desc",
                    "content": $scope.dataParsed[i].data
                },
                "labels": {
                    "outer": {
                        "pieDistance": 19,
                    },
                    "mainLabel": {
                        "fontSize": 18,
                    },
                    "inner": {
                        "hideWhenLessThanPercentage": 10
                    },
                    "lines": {
                        "enabled": true,
                        "style": "straight"
                    },
                    "percentage": {
                        "color": "#ffffff",
                        "fontSize": 18,
                        "decimalPlaces": 0
                    },
                    "truncation": {
                        "enabled": true
                    }
                },
                "effects": {
                    "pullOutSegmentOnClick": {
                        "effect": "linear",
                        "speed": 400,
                        "size": 8
                    }
                },
                "misc": {
                    "gradient": {
                        "enabled": true,
                        "percentage": 100
                    }
                }
            });
        }
    }
    
    
    $(window).resize(function(){
        $scope.$apply(function(){
            for(var i=0; i < $scope.dataParsed.length; i++)
            {
                for(var i = 0; i < $scope.dataParsed.length; i++)
                {
                    $("#pieChartContainer"+i).html("");
                }
                var width = $(".PieBox").first().width();
                
                $scope.drawGraphs((width-250)/width * 100);
            }
        });
    });

    $scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) 
    {
        $scope.drawGraphs(40);
    });
    
        
    $scope.$on('statistics_requested_admin_change', function(scope) {
        console.log('statistics_requested_admin_change');
        $scope.getRefreshData();
    });
    
    
}]);

angular.module("mgCRMapp").directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit('ngRepeatFinished');
                });
            }
        }
    }
});