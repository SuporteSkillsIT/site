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



mgCRMapp.factory(
        "settingsFieldViews", 
       ['$resource', '$rootScope', '$http',
function($resource, $rootScope, $http) 
{
    var container = $resource($rootScope.settings.config.apiURL + '/settings/fields/views/forme/json', {}, 
    {
        query: {
            method: 'GET', 
            cache: false,
            isArray: false,
            responseType: 'json',
        },
        all: {
            url: $rootScope.settings.config.apiURL + '/settings/fields/views/json',
            method: 'GET', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
        save: {
            method: 'PUT', 
            isArray: false,
            cache: false,
            responseType: 'json',
        },
    });

    container.updateVisible = function(rule, newOrder) 
    {
        var params = {};
        params['rule'] = rule;
        params['data'] = newOrder;

        return $http.post($rootScope.settings.config.apiURL + '/settings/fields/views/store/json', params);
    }
    
    return container;
    
}]);
