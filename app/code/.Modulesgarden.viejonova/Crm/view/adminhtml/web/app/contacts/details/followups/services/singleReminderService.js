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



mgCRMapp.factory('singleReminderService', 
            ['$rootScope', '$resource', '$http',
    function ($rootScope , $resource,    $http) 
{
    
    return $resource($rootScope.settings.config.apiURL + '/lead/:resource_id/followups/:followup_id/reminders/:id/json', {id:'@id', resource_id:'@resource_id', followup_id:'@followup_id'}, 
    {
        query: {
            method: 'GET', 
            cache: false,
            isArray: true,
            responseType: 'json',
            transformResponse: function(data) {

//                angular.forEach(data, function (item, index) {
//                    item.id         = parseInt(item.id);
//                    item.order      = parseInt(item.order);
//                    item.group_id   = parseInt(item.group_id);
//                    item.active     = Boolean(parseInt(item.active));
//                });
                
                return data;
            },
        },
        save: {
            method: 'POST', 
            transformRequest: function(data) {
//                data.date = moment(data.date).unix();
                data.date = moment(data.date).format();
                return angular.toJson(data);
            },
        },
    });

    
}]);
