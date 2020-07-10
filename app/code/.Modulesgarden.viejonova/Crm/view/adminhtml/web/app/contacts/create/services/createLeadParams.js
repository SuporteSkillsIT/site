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
 * Basically this is service that will obtain
 * all nesesary params from API that we will use to create form in controller
 * 
 * Im gonna put many information in single service (not spread it for many) in order to get all that i want by single request
 */
mgCRMapp.factory('createLeadParams', 
            ['$q', '$filter', '$timeout', '$rootScope', '$http', '$resource',
    function ($q,  $filter,    $timeout, $rootScope, $http, $resource) 
{

    var container = {};
    container.get = function() 
    {
        return $http.get($rootScope.settings.config.apiURL + '/contacts/create/getParams/json', {
            cache: true,
            responseType: 'json',
        });
    };


    container.searchClient = function(searchQuery) 
    {
        return $http.post($rootScope.settings.config.apiURL + '/helpers/select/clients/json', {
            query: searchQuery
        });
    };

    return container;
}]);
