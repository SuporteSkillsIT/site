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
        'massmessageAbstractController',
        ['$rootScope', '$scope', '$translate', '$http',
                function($rootScope, $scope, $translate, $http)
                        {
                        // used for child states, no need to make it double
                        $scope.tinymceOptions = {
                        inline: false,
                                plugins : [
                                        "advlist autolink lists link image charmap preview hr",
                                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                                        "insertdatetime nonbreaking save table contextmenu directionality",
                                        "paste textcolor colorpicker textpattern imagetools"
                                ],
                                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview | forecolor backcolor",
                                skin: 'lightgray',
                                theme : 'modern'
                    };
                                $scope.availableVariables = [
//                                {
//                                header: 'Store Contact Information',
//                                        show:   'clients',
//                                        alert:  'These variables might be empty when Mass Email will be sent to Contacts',
//                                        variables: [
//                                        {id: '{{config path="web/unsecure/base_url"}}', description: 'Base Unsecure URL'},
//                                        {id: '{{config path="web/secure/base_url"}}', description: 'Base Secure URL'},
//                                        {id: '{{config path="trans_email/ident_general/name"}}', description: 'General Contact Name'},
//                                        {id: '{{config path="trans_email/ident_general/email"}}', description: 'General Contact Email'},
//                                        {id: '{{config path="trans_email/ident_sales/name"}}', description: 'Sales Representative Contact Name'},
//                                        {id: '{{config path="trans_email/ident_sales/email"}}', description: 'Sales Representative Contact Email'},
//                                        {id: '{{config path="trans_email/ident_custom1/name"}}', description: 'Custom1 Contact Name'},
//                                        {id: '{{config path="trans_email/ident_custom1/email"}}', description: 'Custom1 Contact Email'},
//                                        {id: '{{config path="trans_email/ident_custom2/name"}}', description: 'Custom2 Contact Name'},
//                                        {id: '{{config path="trans_email/ident_custom2/email"}}', description: 'Custom2 Contact Email'},
//                                        {id: '{{config path="general/store_information/name"}}', description: 'Store Name'},
//                                        {id: '{{config path="general/store_information/phone"}}', description: 'Store Phone Number'},
//                                        {id: '{{config path="general/store_information/address"}}', description: 'Store Contact Address'},
//                                        ],
//                                },
//        {
//            header: 'Assigned Contact',
//            show:   'campaigns',
//            alert:  'These variables might be empty when Mass Email will be sent to client. Or some variables might be empty (for example when contact does not have ticket assigned to)',
//            variables: [
//                {id: '{$contact.id}',           description: 'Contact ID'},
//                {id: '{$contact.name}',         description: 'Contact Name'},
//                {id: '{$contact.email}',        description: 'Contact Main Data Email'},
//                {id: '{$contact.phone}',        description: 'Contact Main Data Phone'},
//                {id: '{$contact.priority}',     description: 'Contact Priority as number'},
//                {id: '{$contact.created_at}',   description: 'Date when Contact was created'},
//                {id: '{$contact.updated_at}',   description: 'Date when Contact was updated'},
//                {id: '{$contact.deleted_at}',   description: 'Date when Contact was deleted'},
//                
//                {id: '{$contact.status.id}',    description: 'Contact Status Id'},
//                {id: '{$contact.status.name}',  description: 'Contact Status Name'},
//                {id: '{$contact.status.color}', description: 'Contact Status Color'},
//                
//                {id: '{$contact.type.id}',      description: 'Contact Type ID'},
//                {id: '{$contact.type.name}',    description: 'Contact Type Name'},
//                {id: '{$contact.type.color}',   description: 'Contact Type Color'},
//
//                {id: '{$contact.ticket.id}',    description: 'Assigned Ticket Id'},
//                {id: '{$contact.ticket.tid}',   description: 'Assigned Ticket Number'},
//                {id: '{$contact.ticket.title}', description: 'Assigned Ticket Title'},
//                
//                {id: '{$contact.admin.id}',         description: 'Assigned Admin ID'},
//                {id: '{$contact.admin.firstname}',  description: 'Assigned Admin First Name'},
//                {id: '{$contact.admin.lastname}',   description: 'Assigned Admin Last Name'},
//                {id: '{$contact.admin.email}',      description: 'Assigned Admin Email'},
//                
//                {id: '{$contact.client.id}',            description: 'Client assigned ID'},
//                {id: '{$contact.client.firstname}',     description: 'Client assigned First Name'},
//                {id: '{$contact.client.lastname}',      description: 'Client assigned Last Name'},
//                {id: '{$contact.client.email}',         description: 'Client assigned Email'},
//                {id: '{$contact.client.companyname}',   description: 'Client assigned Company Name'},
//                {id: '{$contact.client.address1}',      description: 'Client assigned Adress 1'},
//                {id: '{$contact.client.address2}',      description: 'Client assigned Adress 1'},
//                {id: '{$contact.client.city}',          description: 'Client assigned City'},
//                {id: '{$contact.client.state}',         description: 'Client assigned State/Region'},
//                {id: '{$contact.client.postcode}',      description: 'Client assigned Postcode'},
//                {id: '{$contact.client.country}',       description: 'Client assigned Country'},
//                {id: '{$contact.client.phonenumber}',   description: 'Client assigned Phone Number'},
//                {id: '{$contact.client.lastlogin}',     description: 'Client assigned Last Login'},
//                {id: '{$contact.client.ip}',            description: 'Client assigned Last Login IP'},
//                
//                {id: '{$contact.fields.<id>.name}',            description: 'Custim Field <id> name'},
//                {id: '{$contact.fields.<id>.description}',     description: 'Custim Field <id> description'},
//                {id: '{$contact.fields.<id>.type}',            description: 'Custim Field <id> type'},
//                {id: '{$contact.fields.<id>.data}',            description: 'Custim Field <id> value (for text/textarea) types'},
//                {id: '{$contact.fields.<id>.options}',         description: 'Custim Field <id> selected options (for radio/select/checkbos) types'},
//            ],
//        },
//        {
//            header: 'System Variables',
//            show:   true,
//            variables: [
//                {id: '{$company_name}', description: 'Company Name'},
//                {id: '{$company_domain}', description: 'Company Domain'},
//                {id: '{$company_logo_url}', description: 'Company Logo Url'},
//                {id: '{$whmcs_url}', description: 'Link To WHMCS'},
//                {id: '{$whmcs_link}', description: 'HTML generated Link to WHMCS'},
//                {id: '{$whmcs_admin_url}', description: 'Link To WHMCS Adminarea'},
//                {id: '{$whmcs_admin_link}', description: 'HTML generated Link to WHMCS Adminarea'},
//                {id: '{$signature}', description: 'Global Email Signature'},
//                {id: '{$date}', description: 'Date when send'},
//                {id: '{$time}', description: 'Date and Time when send'},
//            ],
//        }
                                ];
                        }]);