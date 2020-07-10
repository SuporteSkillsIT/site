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
        'campaignsAbstractController',
        ['$rootScope', '$scope', '$translate', '$http',
function( $rootScope,   $scope,   $translate,   $http)
{
    $scope.getContactTypeColor = function(id)
    {
        id = parseInt(id);
        
        for(i=0; i<$scope.contactTypes.length; i++){
            if($scope.contactTypes[i].id == id) {
                return $scope.contactTypes[i].color;
            }
        }
    }
    
    $scope.getContactTypeName = function(id)
    {
        id = parseInt(id);
        
        for(i=0; i<$scope.contactTypes.length; i++){
            if($scope.contactTypes[i].id == id) {
                return $scope.contactTypes[i].name;
            }
        }
    }
}]);