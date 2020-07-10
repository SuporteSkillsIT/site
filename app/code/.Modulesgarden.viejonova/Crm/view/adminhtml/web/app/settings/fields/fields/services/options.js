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



/**
 * factory service to manage statuses groups
 */
mgCRMapp.factory(
        "ResourceFieldOptions", 
       ['$resource', '$rootScope', '$http',
function($resource, $rootScope, $http) 
{
    return $resource($rootScope.settings.config.apiURL + '/settings/fields/:id/options/json', {field_id: '@id', id: '@field_id'}, 
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
            method: 'PUT', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
        update: {
            url: $rootScope.settings.config.apiURL + '/settings/fields/:id/options/:field_id/json',
            method: 'POST', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
        delete: {
            url: $rootScope.settings.config.apiURL + '/settings/fields/:id/options/:validatorid/json',
            method: 'DELETE', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
    });

}]);