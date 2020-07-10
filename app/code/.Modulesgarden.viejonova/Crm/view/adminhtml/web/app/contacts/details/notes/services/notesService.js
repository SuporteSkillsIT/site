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



mgCRMapp.factory('notesService', 
            ['$rootScope', '$resource', '$http',
    function ($rootScope ,  $resource,   $http) 
{
    var container = $resource($rootScope.settings.config.apiURL + '/lead/:resourceID/notes/get/json', {id:'@id', resource_id:'@resource_id'}, 
    {
        get: {
            method: 'GET', 
            url: $rootScope.settings.config.apiURL + '/lead/:resourceID/notes/get/:limit/json',
            isArray: true,
            responseType: 'json',
            cache: false,
//            transformResponse: function(data) {
//
//                angular.forEach(data, function (item, index) {
//                    item.id             = parseInt(item.id);
//                    item.resource_id    = parseInt(item.resource_id);
//                });
//                
//                return data;
//            },
        },
        getWithDeleted: {
            method: 'GET', 
            url: $rootScope.settings.config.apiURL + '/lead/:resourceID/notes/getWithDeleted/:limit/json',
            isArray: true,
            cache: false,
            responseType: 'json',
//            transformResponse: function(data) {
//
//                angular.forEach(data, function (item, index) {
//                    item.id             = parseInt(item.id);
//                    item.resource_id    = parseInt(item.resource_id);
//                });
//                
//                return data;
//            },
        },
        update: {
            method: 'POST', 
            url: $rootScope.settings.config.apiURL + '/lead/:resource_id/notes/:id/edit/json',
            isArray: false,
            cache: false,
            responseType: 'json',
        },
        softDelete: {
            url: $rootScope.settings.config.apiURL + '/lead/:resource_id/notes/:id/json',
            method: 'DELETE', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
        forceDelete: {
            url: $rootScope.settings.config.apiURL + '/lead/:resource_id/notes/:id/force/json',
            method: 'DELETE', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
        restore: {
            url: $rootScope.settings.config.apiURL + '/lead/:resource_id/notes/:id/resotre/json',
            method: 'PUT', 
            isArray: false,
            cache: false,
            responseType: 'json',
        }
    });

    container.addNew = function(resourceID, content) 
    {
        var params = {};
        params['content'] = content;

        return $http.post($rootScope.settings.config.apiURL + '/lead/' + resourceID + '/notes/add/json', params);
    }
    

    return container;
}]);
