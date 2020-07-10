<?php

/* app/app.routes.js */
class __TwigTemplate_9ad4a43a46204f30d8c41ce213dc15757b016540c8c7000099ee3f8ed022e1bc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "/***************************************************************************************
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
 * keep on mind that this file is parsed by twig extension !!!!!!!!!!!
 * I didnt renamed it as .twig extension since IDE wont highlight syntax (fu**k)
 * 
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 */

/////////////////////////////
// UU-Routes Provide states and routing
/////////////////////////////
// Use \$urlRouterProvider to configure any redirects (when) and invalid urls (otherwise).
\$urlRouterProvider
  // redirects, since I dont want to render abstract states, just go to other
  .when('/settings/fields',     '/settings/fields/list')
  .when('/settings',            '/settings/personal')
  .when('/campaigns',           '/campaigns/list')
  .when('/contacts',            '/contacts/list')
  .when('/general',             '/general/overview')
  .when('/utils',               '/utils/statistics')
  .when('/utils/notifications', '/utils/notifications/list')
  // If the url is ever invalid, e.g. '/asdf', then redirect to '/' aka the home state
  .otherwise('dashboard');

//////////////////////////
// State Configurations //
//////////////////////////
\$stateProvider

    ////////////////////
    // TEST RUTES
    ////////////////////
    .state(\"buttons\", {
        url: \"/buttons\",
        templateUrl: '";
        // line 63
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/buttons.html',
        data: {pageTitle: 'Buttons'}
    })
    .state(\"typography\", {
        url: \"/typography\",
        templateUrl: '";
        // line 68
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/typography.html',
        data: {pageTitle: 'Typography'},
    })
    .state(\"panels\", {
        url: \"/panels\",
        templateUrl: '";
        // line 73
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/panels.html',
        data: {pageTitle: 'Panels'},
    })
    .state(\"icons\", {
        url: \"/icons\",
        templateUrl: '";
        // line 78
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/icons.html',
        data: {pageTitle: 'Icons'},
    })
    .state(\"boxes\", {
        url: \"/boxes\",
        templateUrl: '";
        // line 83
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/boxes.html',
        data: {pageTitle: 'Boxes'},
    })
    .state(\"tables_simple\", {
        url: \"/tables_simple\",
        templateUrl: '";
        // line 88
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/tables_simple.html',
        data: {pageTitle: 'Tables Simple'},
    })
    .state(\"tables_extended\", {
        url: \"/tables_extended\",
        templateUrl: '";
        // line 93
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/tables_extended.html',
        data: {pageTitle: 'Tables Extended'},
    })
    .state(\"tables_datatables\", {
        url: \"/tables_datatables\",
        templateUrl: '";
        // line 98
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/tables_datatables.html',
        data: {pageTitle: 'Tables Datatables'},
    })
    .state(\"form_general\", {
        url: \"/form_general\",
        templateUrl: '";
        // line 103
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/form_general.html',
        data: {pageTitle: 'Tables General'},
    })
    .state(\"form_advanced\", {
        url: \"/form_advanced\",
        templateUrl: '";
        // line 108
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/form_advanced.html',
        data: {pageTitle: 'Tables Advanced'},
    })

    ////////////////////
    // STATIC PAGES
    ////////////////////

    ";
        // line 117
        echo "    .state(\"static\", {
        url:        \"/static/pages\",
        template:   '<div ui-view></div>',
        name:       'pages',
        abstract:   true, // to activate child states
        data: {
            pageTitle: 'Static Pages',
        },
    })
    .state(\"static.icons\", {
        url: \"/dashboard\",
        templateUrl: '";
        // line 128
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/views/frontend/icons.html',
        name: 'pages.icons',
        data: {
            pageTitle: 'Icons'
        },
    })
    ////////////////////
    // SETTINGS FAMILY
    ////////////////////
    ";
        // line 138
        echo "    .state(\"settings\", {
        url: \"/settings\",
        // Note: abstract still needs a ui-view for its children to populate.
        template: '<ui-view/>',
        name: 'settings',
        redirect: 'settings.personal',
        abstract: true, // to activate child states
        data: {
            pageTitle: 'Settings',
            proxy:     'settings',
            navid:     'settings',
        }
    })
    ";
        // line 152
        echo "    .state(\"settings.fields\", {
        url: \"/fields\",
        // Note: abstract still needs a ui-view for its children to populate.
        templateUrl: '";
        // line 155
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/navigationController.html',
        name: 'settings.fields',
        redirect: 'settings.fields.list',
        controller: 'settingsFieldsNavigationController',
        abstract: true, // to activate child states
        data: {
            pageTitle: 'Fields',
            proxy:     'settings.fields',
        },
        resolve: {
        
            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                      // required controllers
                      '";
        // line 169
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/navigationController.js',
                ]);
            }],
        }
    })
    ";
        // line 175
        echo "    .state(\"settings.fields.list\", {
        url: \"/list\",
        templateUrl: '";
        // line 177
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/templates/fields.html',
        name: 'settings.fields.list',
        data: {
              pageTitle: 'Fields List',
              navid:     'navigation-settings-fields',
        },
        controller: 'settingsFieldsList',
        resolve: {

            // ACL - based on specific rule
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.manage_fields') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_fields'});
                return \$q.reject('Unauthorized');
            }],

            settingsFieldsListServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // required services
                    '";
        // line 201
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/services/field.js',
                    '";
        // line 202
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/groups/services/groups.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'settingsFieldsListServices', function(\$ocLazyLoad, settingsFieldsListServices) {
                return \$ocLazyLoad.load([
                    // required controllers
                    '";
        // line 209
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/controllers/list.js',
                ]);
            }],
        }
    })
    ";
        // line 215
        echo "    .state(\"settings.fields.edit\", {
        url: \"/edit/{id:int}\",
        templateUrl: '";
        // line 217
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/templates/edit.html',
        name: 'settings.fields.edit',
        data: {
            pageTitle: 'Edit #";
        // line 220
        echo "{{ fieldID }}";
        echo "',
            navid:     'navigation-settings-fields',
        },
        controller: 'settingsFieldsEdit',
        resolve: {

            // ACL - based on specific rule
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.manage_fields') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_fields'});
                return \$q.reject('Unauthorized');
            }],

            // update title, tricky, but works :) version for breadcrumbs
            fieldID: function(\$stateParams) {
                return \$stateParams.id;
            },

            settingsFieldsEditServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // required services
                    '";
        // line 246
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/services/field.js',
                    '";
        // line 247
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/services/validators.js',
                    '";
        // line 248
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/services/options.js',
                    '";
        // line 249
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/groups/services/groups.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'settingsFieldsEditServices', function(\$ocLazyLoad, settingsFieldsEditServices) {
                return \$ocLazyLoad.load([
                    // required controllers
                    '";
        // line 256
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/fields/controllers/edit.js',
                ]);
            }],
        }
    })
    ";
        // line 262
        echo "    .state(\"settings.fields.groups\", {
        url: \"/groups\",
        templateUrl: '";
        // line 264
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/groups/templates/groups.html',
        name: 'settings.fields.groups',
        data: {
            pageTitle: 'Fields Groups',
            navid:     'navigation-settings-fields',
        },
        controller: 'settingsFieldsGroups',
        resolve: {

            // ACL - based on specific rule
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.manage_fields') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_fields'});
                return \$q.reject('Unauthorized');
            }],

            settingsFieldsGroupsServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // required services
                    '";
        // line 288
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/groups/services/groups.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'settingsFieldsGroupsServices', function(\$ocLazyLoad, settingsFieldsGroupsServices) {
                return \$ocLazyLoad.load([
                    // required controllers
                    '";
        // line 295
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/groups/controllers/groups.js',
                ]);
            }],
        }
    })
    ";
        // line 301
        echo "    .state(\"settings.fields.statuses\", {
        url: \"/statuses\",
        templateUrl: '";
        // line 303
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/statuses/templates/statuses.html',
        name: 'settings.fields.statuses',
        data: {
            pageTitle: 'Statuses',
            navid:     'navigation-settings-fields',
        },
        controller: 'settingsFieldsStatuses',
        resolve: {
            
            // ACL - based on specific rule
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {
                
                // check access
                if( AclService.can('settings.manage_statuses') ) {
                    return true;
                }
                
                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_statuses'});
                return \$q.reject('Unauthorized');
            }],
              
            settingsFieldsStatusesServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  return \$ocLazyLoad.load([
                        // required services
                        '";
        // line 327
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/statuses/services/statuses.js',
                  ]);
            }],
        
            deps: ['\$ocLazyLoad', 'settingsFieldsStatusesServices', function(\$ocLazyLoad, settingsFieldsStatusesServices) {
                  return \$ocLazyLoad.load([
                        // required controllers
                        '";
        // line 334
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/statuses/controllers/statuses.js',
                  ]);
            }],
        }
    })
    ";
        // line 340
        echo "    .state(\"settings.fields.views\", {
        url: \"/views\",
        templateUrl: '";
        // line 342
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/views/templates/views.html',
        name: 'settings.fields.views',
        data: {
            pageTitle: 'Views',
            navid:     'navigation-settings-fields',
        },
        controller: 'settingsFieldsViews',
        resolve: {
            
            settingsFieldsServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  return \$ocLazyLoad.load([
                        // required services
                        '";
        // line 354
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/views/services/fieldViews.js',
                  ]);
            }],
        
            deps: ['\$ocLazyLoad', 'settingsFieldsServices', function(\$ocLazyLoad, settingsFieldsServices) {
                  return \$ocLazyLoad.load([
                        // required controllers
                        '";
        // line 361
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/views/controllers/views.js',
                  ]);
            }],
        }
    })
    ";
        // line 367
        echo "    .state(\"settings.fields.map\", {
        url: \"/map\",
        templateUrl: '";
        // line 369
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/map/templates/map.html',
        name: 'settings.fields.map',
        data: {
            pageTitle: 'Map',
            navid:     'navigation-settings-fields',
        },
        controller: 'settingsFieldsMap',
        resolve: {
            
            // ACL - based on specific rule
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {
                
                // check access
                if( AclService.can('settings.manage_fields_map') ) {
                    return true;
                }
                
                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_fields_map'});
                return \$q.reject('Unauthorized');
            }],
            
            settingsFieldsMapServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  return \$ocLazyLoad.load([
                        // required services
                        '";
        // line 393
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/personal/services/fieldViews.js',
                  ]);
            }],
        
            deps: ['\$ocLazyLoad', 'settingsFieldsMapServices', function(\$ocLazyLoad, settingsFieldsMapServices) {
                  return \$ocLazyLoad.load([
                        // required controllers
                        '";
        // line 400
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/fields/map/controllers/map.js',
                  ]);
            }],
        }
    })
    ";
        // line 406
        echo "    .state(\"settings.personal\", {
        url: \"/personal\",
        templateUrl: '";
        // line 408
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/personal/abstractTpl.html',
        name: 'settings.personal',
        redirect: 'settings.personal.personal',
        abstract: true,
        controller: 'settingsPersonalAbstractController',
        data: {
            pageTitle: 'Personal',
            proxy:     'settings.personal',
            navid:     'navigation-settings-personal',
        },
        resolve: {
        
            settingsPersonalServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  return \$ocLazyLoad.load([
                        // required services
                    '";
        // line 423
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/personal/services/fieldViews.js',
                  ]);
            }],
        
            deps: ['\$ocLazyLoad', function(\$ocLazyLoad, settingsPersonalServices) {
                return \$ocLazyLoad.load([ 
                    // controllers
                    '";
        // line 430
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/personal/controllers/personal.js',
                ]);
            }],
        
        }
    })
    ";
        // line 437
        echo "    .state(\"settings.personal.personal\", {
        url: \"/general\",
        templateUrl: '";
        // line 439
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/personal/templates/personalTpl.html',
        name: 'settings.personal.personal',
        data: {
            pageTitle: 'Settings',
            navid:     'navigation-settings-personal',
        },
        controller: 'settingsPersonalController',
    })
    ";
        // line 448
        echo "    .state(\"settings.personal.fieldsview\", {
        url: \"/fieldsview\",
        templateUrl: '";
        // line 450
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/personal/templates/fieldsviewTpl.html',
        name: 'settings.personal.fieldsview',
        data: {
            pageTitle: 'Fields\\' View',
            navid:     'navigation-settings-personal',
        },
        controller: 'settingsPersonalFieldsviewController',
    })
    
    ////////////////////
    // MAILBOX
    ////////////////////
    ";
        // line 463
        echo "    .state(\"settings.mailbox\", {
        url: \"/mailbox\",
        templateUrl: '";
        // line 465
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/abstractTpl.html',
        name: 'mailbox',
        redirect: 'mailbox.list',
        abstract: true,
        data: {
            pageTitle: 'Outgoing Mailbox Configuration',
            proxy: 'mailbox',
            navid:     'navigation-settings-mailbox',
        },
        resolve: {
            parentDeps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'mailbox',
                    files: ['";
        // line 479
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/abstractCtrl.js']
                });
            }],
        }
    })
    ";
        // line 485
        echo "    .state(\"settings.mailbox.list\", {
        url: \"/list\",
        templateUrl: '";
        // line 487
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/list/listTpl.html',
        name: 'mailbox.list',
        data: {
            pageTitle: 'List',
            navid:     'navigation-settings-mailbox',
        },
        controller: 'mailboxListController',
        resolve: {

            // TODO - leave it?
            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.mailbox') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.mailbox'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'mailbox.list',
                    files: ['";
        // line 513
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/list/listCtrl.js']
                });
            }],
        }
    })
    ";
        // line 519
        echo "    .state(\"settings.mailbox.add\", {
        url: \"/add\",
        templateUrl: '";
        // line 521
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/add/addTpl.html',
        name: 'mailbox.add',
        data: {
            pageTitle: 'Add Outgoing Mailbox',
            navid:     'navigation-settings-mailbox',
        },
        controller: 'mailboxAddController',
        resolve: {

              // TODO - leave it?
              // ACL - permit only full admins
              isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                  // check access
                  if( AclService.can('settings.mailbox') ) {
                      return true;
                  }

                  \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.mailbox'});
                  return \$q.reject('Unauthorized');
              }],


              deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'mailbox.list',
                      files: ['";
        // line 548
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/add/addCtrl.js']
                  });
              }],
        }
    })
    ";
        // line 554
        echo "    .state(\"settings.mailbox.edit\", {
        url: \"/edit/{id:int}\",
        templateUrl: '";
        // line 556
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/edit/editTpl.html',
        name: 'mailbox.edit',
        data: {
            pageTitle: 'Outgoing Mailbox Configuration #";
        // line 559
        echo "{{ mailboxID }}";
        echo "',
            pageTitleTemplate: 'Mailbox #";
        // line 560
        echo "{{ mailboxID }}";
        echo "',
            proxy: 'mailbox.list',
            navid:     'navigation-settings-mailbox',
        },
        controller: 'mailboxEditController',
        resolve: {

              // TODO: leave it?
              // ACL - permit only full admins
              isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                  // check access
                  if( AclService.can('settings.mailbox') ) {
                      return true;
                  }

                  \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.mailbox'});
                  return \$q.reject('Unauthorized');
              }],

              // update title, tricky, but works :) version for breadcrumbs
              mailboxID: function(\$stateParams) {
                  return \$stateParams.id;
              },

              deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'mailbox.list',
                      files: ['";
        // line 589
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/mailbox/edit/editTpl.js']
                  });
              }],
        }
    })
    
    /**/
    ////////////////////
    // TEMPLATES
    ////////////////////
    ";
        // line 600
        echo "    .state(\"settings.emailtemplates\", {
        url: \"/emailtemplates\",
        templateUrl: '";
        // line 602
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/abstractTpl.html',
        name: 'emailtemplates',
        redirect: 'emailtemplates.list',
        abstract: true,
        data: {
            pageTitle: 'Email Templates',
            proxy: 'emailtemplates',
            navid:     'navigation-settings-emailtemplates',
        },
        resolve: {
            parentDeps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'emailtemplates',
                    files: ['";
        // line 616
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/abstractCtrl.js']
                });
            }],
        }
    })
    ";
        // line 622
        echo "    .state(\"settings.emailtemplates.list\", {
        url: \"/list\",
        templateUrl: '";
        // line 624
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/list/listTpl.html',
        name: 'emailtemplates.list',
        data: {
            pageTitle: 'List',
            navid:     'navigation-settings-emailtemplates',
        },
        controller: 'emailTemplatesListController',
        resolve: {

            // TODO - leave it?
            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.emailtemplates') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.emailtemplates'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'emailtemplates.list',
                    files: ['";
        // line 650
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/list/listCtrl.js']
                });
            }],
        }
    })
    ";
        // line 656
        echo "    .state(\"settings.emailtemplates.add\", {
        url: \"/add\",
        templateUrl: '";
        // line 658
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/add/addTpl.html',
        name: 'emailtemplates.add',
        data: {
            pageTitle: 'Add Email Template',
            navid:     'navigation-settings-emailtemplates',
        },
        controller: 'emailTemplatesAddController',
        resolve: {

              // TODO - leave it?
              // ACL - permit only full admins
              isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                  // check access
                  if( AclService.can('settings.emailtemplates') ) {
                      return true;
                  }

                  \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.emailtemplates'});
                  return \$q.reject('Unauthorized');
              }],
          
              depsTinymce: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'tinymce.lib',
                      files: ['";
        // line 684
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/tinymce/tinymce.min.js']
                  });
              }],

              depsTinymceIntegration: ['\$ocLazyLoad', 'depsTinymce', function(\$ocLazyLoad, depsTinymce) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'ui.tinymce',
                      files: ['";
        // line 692
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']
                  });
              }],


              deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'emailtemplates.list',
                      files: ['";
        // line 701
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/add/addCtrl.js']
                  });
              }],
        }
    })
    ";
        // line 707
        echo "    .state(\"settings.emailtemplates.edit\", {
        url: \"/edit/{id:int}\",
        templateUrl: '";
        // line 709
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/edit/editTpl.html',
        name: 'emailtemplates.edit',
        data: {
            pageTitle: 'Email Template #";
        // line 712
        echo "{{ emailTemplateID }}";
        echo "',
            pageTitleTemplate: 'Template #";
        // line 713
        echo "{{ emailTemplateID }}";
        echo "',
            proxy: 'emailtemplates.list',
            navid:     'navigation-settings-emailtemplates',
        },
        controller: 'emailTemplatesEditController',
        resolve: {

              // TODO: leave it?
              // ACL - permit only full admins
              isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                  // check access
                  if( AclService.can('settings.emailtemplates') ) {
                      return true;
                  }

                  \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.emailtemplates'});
                  return \$q.reject('Unauthorized');
              }],

              // update title, tricky, but works :) version for breadcrumbs
              emailTemplateID: function(\$stateParams) {
                  return \$stateParams.id;
              },
              
              depsTinymce: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'tinymce.lib',
                      files: ['";
        // line 742
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/tinymce/tinymce.min.js']
                  });
              }],

              depsTinymceIntegration: ['\$ocLazyLoad', 'depsTinymce', function(\$ocLazyLoad, depsTinymce) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'ui.tinymce',
                      files: ['";
        // line 750
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']
                  });
              }],

              deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'emailtemplates.list',
                      files: ['";
        // line 758
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/emailtemplates/edit/editTpl.js']
                  });
              }],
        }
    })
    
    ";
        // line 765
        echo "    .state(\"settings.general\", {
        url: \"/general\",
        templateUrl: '";
        // line 767
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/general/abstractTpl.html',
//        template: '<ui-view/>',
        name: 'settings.general',
        redirect: 'settings.general.overview',
        data: {
            pageTitle: 'General',
            navid:     'navigation-settings-general',
            proxy:     'settings.general',
        },
        controller: 'settingsAbstractGeneralController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {
                
                // check access
                if( AclService.can('settings.view_general') ) {
                    return true;
                }
                
                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.view_general'});
                return \$q.reject('Unauthorized');
            }],
        
            settingsGeneralServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  return \$ocLazyLoad.load([
                        // required services
                    '";
        // line 794
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/general/services/followupTypes.js',
                  ]);
            }],

            deps: ['\$ocLazyLoad', 'settingsGeneralServices', function(\$ocLazyLoad, settingsGeneralServices) {
                return \$ocLazyLoad.load([ 
                    // controllers
                    '";
        // line 801
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/general/controllers/general.js',
                ]);
            }],
        }
    })
    ";
        // line 807
        echo "    .state(\"settings.general.overview\", {
        url: \"/overview\",
        templateUrl: '";
        // line 809
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/general/templates/overviewTpl.html',
        name: 'settings.general.overview',
        data: {
            pageTitle: 'System Overview',
            navid:     'navigation-settings-general',
        },
        controller: 'settingsGeneralController',
    })
    ";
        // line 818
        echo "    .state(\"settings.general.options\", {
        url: \"/options\",
        templateUrl: '";
        // line 820
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/general/templates/settingsTpl.html',
        name: 'settings.general.options',
        data: {
            pageTitle: 'Options',
            navid:     'navigation-settings-general',
        },
        controller: 'settingsGeneralController',
    })
    ";
        // line 829
        echo "    .state(\"settings.general.followups\", {
        url: \"/followups\",
        templateUrl: '";
        // line 831
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/general/templates/followupsTpl.html',
        name: 'settings.general.followups',
        data: {
            pageTitle: 'Follow-ups',
            navid:     'navigation-settings-general',
        },
        controller: 'settingsGeneralController',
    })
    ";
        // line 840
        echo "    .state(\"settings.general.api\", {
        url: \"/api\",
        templateUrl: '";
        // line 842
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/general/templates/showApiTpl.html',
        name: 'settings.general.api',
        data: {
            pageTitle: 'API',
            navid:     'navigation-settings-general',
        },
        controller: 'settingsGeneralController',
    })
    ";
        // line 851
        echo "    .state(\"settings.migrator\", {
        url: \"/migrator\",
        templateUrl: '";
        // line 853
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/migrator/templates/migrator.html',
        name: 'settings.general',
        data: {
            pageTitle: 'Migrator',
            navid:     'navigation-settings-migrator',
        },
        controller: 'settingsMigratorController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {
                
                // check access
                if( AclService.can('other.access_migrator') ) {
                    return true;
                }
                
                \$rootScope.\$broadcast('AclNoAccess', {rule: 'other.access_migrator'});
                return \$q.reject('Unauthorized');
            }],
        
            settingsMigratorServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                      '";
        // line 876
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/personal/services/fieldViews.js',
                ]);
            }],
        
            deps: ['\$ocLazyLoad', 'settingsMigratorServices', function(\$ocLazyLoad, settingsMigratorServices) {
                return \$ocLazyLoad.load([ 
                    // controller services
                    '";
        // line 883
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/migrator/controllers/migrator.js',
                ]);
            }],
        }
    })
    ";
        // line 889
        echo "    .state(\"settings.permissions\", {
        url: \"/permissions\",
        templateUrl: '";
        // line 891
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/permissions/templates/permissions.html',
        name: 'settings.permissions',
        data: {
            pageTitle: 'Permissions',
            navid:     'navigation-settings-permissions',
        },
        controller: 'settingsPermissionController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', function(\$q, \$rootScope) {
                
                if( \$rootScope.acl.isFullAdmin == true ) {
                    return true;
                }
                
                // Does not have permission
                \$rootScope.\$broadcast('AclNoAccess', {msg: 'This page is only for Full Access Admins'});
                return \$q.reject('Unauthorized');
            }],

            settingsPermissionServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    '";
        // line 914
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/permissions/services/permissionGroups.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'settingsPermissionServices', function(\$ocLazyLoad, settingsPermissionServices) {
                return \$ocLazyLoad.load([ 
                    '";
        // line 920
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/permissions/controllers/permission.js',
                ]);
            }],
        }
    })
    ";
        // line 926
        echo "    .state(\"settings.types\", {
        url: \"/types\",
        templateUrl: '";
        // line 928
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/types/list/typesListTpl.html',
        name: 'settings.types',
        controller: 'settingsTypesListController',
        data: {
            pageTitle: 'Contact Types',
            navid:     'navigation-settings-types',
        },
        resolve: {

            // ACL - based on specific rule
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {
                
                // check access
                if( AclService.can('settings.manage_types') ) {
                    return true;
                }
                
                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_types'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  return \$ocLazyLoad.load([
                        // required controllers
                        '";
        // line 952
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/types/list/typesListCtrl.js',
                  ]);
            }],
        }
    })
    ////////////////////
    // IMPORT/EXPORT
    ////////////////////
    ";
        // line 961
        echo "    .state(\"settings.importexport\", {
        url: \"/importexport\",
        templateUrl: '";
        // line 963
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/importexport/importexportHeaderTpl.html',
        name: 'settings.importexport',
        controller: 'importexportHeaderCtrl',
        redirect: 'settings.importexport.export',
        abstract: true,
        data: {
            pageTitle: 'Import / Export',
            proxy:     'settings.importexport',
            navid:      'navigation-settings-importexport',
        },
        resolve: {
            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'importexportHeaderCtrl',
                    files: ['";
        // line 978
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/importexport/importexportHeaderCtrl.js']
                });
            }],
        }
    })
    ";
        // line 984
        echo "    .state(\"settings.importexport.export\", {
        url: \"/export\",
        templateUrl: '";
        // line 986
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/importexport/export/exportTpl.html',
        name: 'settings.importexport.export',
        data: {
            pageTitle: 'Export',
            navid:     'navigation-settings-importexport',
        },
        controller: 'exportCtrl',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.importexport') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.importexport'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'exportCtrl',
                    files: ['";
        // line 1011
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/importexport/export/exportCtrl.js']
                });
            }],
        }
    })
    ";
        // line 1017
        echo "    .state(\"settings.importexport.import\", {
        url: \"/import\",
        templateUrl: '";
        // line 1019
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/importexport/import/importTpl.html',
        name: 'settings.importexport.import',
        data: {
            pageTitle: 'Import',
            navid:     'navigation-settings-importexport',
        },
        controller: 'importCtrl',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.importexport') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.importexport'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'importCtrl',
                    files: ['";
        // line 1044
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/settings/importexport/import/importCtrl.js']
                });
            }],
        }
    })
    ////////////////////
    // CALENDAR
    ////////////////////
    ";
        // line 1053
        echo "    .state(\"calendar\", {
        url: \"/calendar\",
        templateUrl: '";
        // line 1055
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/calendar/templates/calendar.html',
        name: 'calendar',
        data: {
            pageTitle: 'Calendar',
            navid:     'navigation-calendar',
        },
        controller: 'leadsCalendarController',
        resolve: {

            leadsCalendarServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load({
                    cache: true,
                    files: [
                    '";
        // line 1068
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.min.js',
                    ]
                });
            }],

            deps: ['\$ocLazyLoad', 'leadsCalendarServices', function(\$ocLazyLoad, leadsCalendarServices) {
                return \$ocLazyLoad.load({
                    cache: true,
                    files: [
                    // required controllers
                    '";
        // line 1078
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/calendar/controllers/calendar.js',
                    ]
                });
            }],
        }
    })
    ////////////////////
    // Dashboard
    ////////////////////
    .state(\"dashboard\", {
        url: \"/dashboard\",
        name: 'dashboard',
        data: {
            pageTitle: 'Dashboard',
            navid:     'navigation-dashboard',
        },
        views: {
            '': {
                templateUrl: '";
        // line 1096
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/templates/_main.html',
//                template: '<h1>main</h1><div ui-view=\"topWidgets\"></div><div ui-view=\"followups\"></div>'
                // we couls use url, but to obtain that simple html ? stsly?
                // when add new state views remember to add it here in correct order
                controller: 'dashboardController'
            },

            'topWidgets@dashboard': {
                templateUrl: '";
        // line 1104
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/templates/top_widgets.html',
                controller: 'topWidgetsDashboardController'
            },
            'followups@dashboard': {
                templateUrl: '";
        // line 1108
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/templates/followups.html',
                controller: 'dashboardFollowupsController'
            },
            'resources@dashboard': {
                templateUrl: '";
        // line 1112
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/templates/resources.html',
                controller: 'dashboardResourcesController'
            },
            'history@dashboard': {
                templateUrl: '";
        // line 1116
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/templates/history.html',
                controller: 'dashboardHistoryController'
            },
        },
        resolve: {
          // guess we will need dependiency for personalization settings
          
            // A function value promises resolved data to controller and child statuses
            tableColumns: ['\$http', '\$rootScope', function(\$http, \$rootScope) {
                // but \$http returns: :D
                // Returns a Promise that will be resolved to a response object when the request succeeds or fails.
                return \$http.get(\$rootScope.settings.config.apiURL + '/settings/fields/views/for/dashboard/json');
            }],
            
            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // load main controllers for each view
                    '";
        // line 1133
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/controllers/_main.js',
                    '";
        // line 1134
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/controllers/topWidgets.js',
                    '";
        // line 1135
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/controllers/followups.js',
                    '";
        // line 1136
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/controllers/resources.js',
                    '";
        // line 1137
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/dashboard/controllers/history.js',
                ]);
            }],
        }
    })
    
    ////////////////////
    // CAMPAIGNS
    ////////////////////
    ";
        // line 1147
        echo "    .state(\"campaigns\", {
        url: \"/campaigns\",
        templateUrl: '";
        // line 1149
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/abstractTpl.html',
        name: 'campaigns',
        redirect: 'campaigns.list',
        abstract: true,
        data: {
            pageTitle: 'Campaigns',
            proxy: 'campaigns',
            navid:     'navigation-campaigns',
        },
        resolve: {
            parentDeps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'campaigns',
                    files: ['";
        // line 1163
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/abstractCtrl.js']
                });
            }],
        }
    })
    ";
        // line 1169
        echo "    .state(\"campaigns.list\", {
        url: \"/list\",
        templateUrl: '";
        // line 1171
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/list/listTpl.html',
        name: 'campaigns.list',
        data: {
            pageTitle: 'List',
            navid:     'navigation-campaigns',
        },
        controller: 'campaignsListController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.campaigns') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.campaigns'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'campaigns.list',
                    files: ['";
        // line 1196
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/list/listCtrl.js']
                });
            }],
        }
    })
    ";
        // line 1202
        echo "    .state(\"campaigns.add\", {
        url: \"/add\",
        templateUrl: '";
        // line 1204
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/add/addTpl.html',
        name: 'campaigns.add',
        data: {
            pageTitle: 'Add Campaign',
            navid:     'navigation-campaigns',
        },
        controller: 'campaignsAddController',
        resolve: {

              // ACL - permit only full admins
              isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                  // check access
                  if( AclService.can('settings.campaigns') ) {
                      return true;
                  }

                  \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.campaigns'});
                  return \$q.reject('Unauthorized');
              }],


              deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'campaigns.list',
                      files: ['";
        // line 1230
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/add/addCtrl.js']
                  });
              }],
        }
    })
    ";
        // line 1236
        echo "    .state(\"campaigns.edit\", {
        url: \"/edit/{id:int}\",
        templateUrl: '";
        // line 1238
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/edit/editTpl.html',
        name: 'campaigns.edit',
        data: {
            pageTitle: 'Campaign #";
        // line 1241
        echo "{{ campaignID }}";
        echo "',
            pageTitleTemplate: 'Campaign #";
        // line 1242
        echo "{{ campaignID }}";
        echo "',
            proxy: 'campaigns.list',
            navid:     'navigation-campaigns',
        },
        controller: 'campaignsEditController',
        resolve: {

              // ACL - permit only full admins
              isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                  // check access
                  if( AclService.can('settings.campaigns') ) {
                      return true;
                  }

                  \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.campaigns'});
                  return \$q.reject('Unauthorized');
              }],

              // update title, tricky, but works :) version for breadcrumbs
              campaignID: function(\$stateParams) {
                  return \$stateParams.id;
              },

              deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'campaigns.list',
                      files: ['";
        // line 1270
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/campaigns/edit/editTpl.js']
                  });
              }],
        }
    })

    ////////////////////
    // UTILS - abstract
    ////////////////////
    ";
        // line 1280
        echo "    .state(\"utils\", {
        url: \"/utils\",
        // Note: abstract still needs a ui-view for its children to populate.
        template: '<ui-view/>',
        name: 'utils',
        redirect: 'utils.statistics',
        abstract: true, // to activate child states
        data: {
            pageTitle: 'Utilities',
            proxy:     'utils',
            navid:     'navigation-utils',
        }
    })
    ";
        // line 1294
        echo "    .state(\"utils.statistics\", {
        url: \"/statistics\",
        name: 'utils.statistics',
        data: {
            pageTitle:          'Statistics',
            navid:              'navigation-utils-statistics',
        },
        views: {
            '': {
                templateUrl: '";
        // line 1303
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/templates/_main.html',
//                template: '<h1>main</h1><div ui-view=\"topWidgets\"></div><div ui-view=\"followups\"></div>'
                // we couls use url, but to obtain that simple html ? stsly?
                // when add new state views remember to add it here in correct order
                controller: 'statisticsController'
            },

            'perStatus@utils.statistics': {
                templateUrl: '";
        // line 1311
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/templates/perStatus.html',
                controller: 'perStatusStatisticsController'
            },
            'lastTen@utils.statistics': {
                templateUrl: '";
        // line 1315
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/templates/lastTen.html',
                controller: 'lastTenStatisticsController'
            },
            'totalPerAdmin@utils.statistics': {
                templateUrl: '";
        // line 1319
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/templates/totalPerAdmin.html',
                controller: 'totalPerAdminStatisticsController'
            },
            'newPerMonth@utils.statistics': {
                templateUrl: '";
        // line 1323
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/templates/newPerMonth.html',
                controller: 'newPerMonthStatisticsController'
            },
            'newPerDay@utils.statistics': {
                templateUrl: '";
        // line 1327
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/templates/newPerDay.html',
                controller: 'newPerDayStatisticsController'
            },
        },
        resolve: {
            
            // chart.js dependiences, since charts are used only here
            loadChartJsLib: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    '";
        // line 1336
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/Chart.js/Chart.min.js',
                    '";
        // line 1337
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/d3pie/d3pie.min.js',
                    '";
        // line 1338
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/d3/d3.min.js'
                ]);
            }],
        
            deps: ['\$ocLazyLoad', 'loadChartJsLib', function(\$ocLazyLoad, loadChartJsLib) {
                return \$ocLazyLoad.load([
                    // load main controller
                    '";
        // line 1345
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/controllers/_main.js',
                    '";
        // line 1346
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/controllers/perStatus.js',
                    '";
        // line 1347
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/controllers/lastTen.js',
                    '";
        // line 1348
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/controllers/totalPerAdmin.js',
                    '";
        // line 1349
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/controllers/newPerMonth.js',
                    '";
        // line 1350
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/statistics/controllers/newPerDay.js',

                    // chart.js dependiences, since charts are used only here
                    '";
        // line 1353
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-chart/dist/angular-chart.min.css',
                    '";
        // line 1354
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-chart/dist/angular-chart.min.js',
                ]);
            }],
        }
    })
    // NOTIFICATIONS
    ";
        // line 1361
        echo "    .state(\"utils.notifications\", {
        url: \"/notifications\",
        template: '<ui-view/>',
        name: 'utils.notifications',
        redirect: 'utils.notifications.list',
        abstract: true,
        data: {
            pageTitle: 'Notifications',
            proxy:     'utils.notifications',
            navid:     'navigation-utils-notifications',
        },
    })
    ";
        // line 1374
        echo "    .state(\"utils.notifications.list\", {
        url: \"/list\",
        templateUrl: '";
        // line 1376
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/notifications/list/listTpl.html',
        name: 'utils.notifications.list',
        data: {
            pageTitle: 'List',
            navid:     'navigation-utils-notifications',
        },
        controller: 'notificationsListController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.notifications') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'utils.notifications'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'campaigns.list',
                    files: ['";
        // line 1401
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/notifications/list/listCtrl.js']
                });
            }],
        }
    })
    ";
        // line 1407
        echo "    .state(\"utils.notifications.add\", {
        url: \"/add\",
        templateUrl: '";
        // line 1409
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/notifications/add/addTpl.html',
        name: 'utils.notifications.add',
        data: {
            pageTitle: 'Add Notification',
            navid:     'navigation-utils-notifications',
        },
        controller: 'notificationsAddController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.notifications') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.notifications'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'campaigns.list',
                    files: ['";
        // line 1434
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/notifications/add/addCtrl.js']
                });
            }],
        }
    })
    ";
        // line 1440
        echo "    .state(\"utils.notifications.edit\", {
        url: \"/edit/{id:int}\",
        templateUrl: '";
        // line 1442
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/notifications/edit/editTpl.html',
        name: 'utils.notifications.edit',
        data: {
            pageTitle: 'Notification #";
        // line 1445
        echo "{{ notificationID }}";
        echo "',
            pageTitleTemplate: 'Notification #";
        // line 1446
        echo "{{ notificationID }}";
        echo "',
            proxy: 'campaigns.list',
            navid:     'navigation-utils-notifications',
        },
        controller: 'notificationsEditController',
        resolve: {

              // ACL - permit only full admins
              isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                  // check access
                  if( AclService.can('settings.notifications') ) {
                      return true;
                  }

                  \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.notifications'});
                  return \$q.reject('Unauthorized');
              }],

              // update title, tricky, but works :) version for breadcrumbs
              notificationID: function(\$stateParams) {
                  return \$stateParams.id;
              },

              deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                  // required controllers
                  return \$ocLazyLoad.load({
                      name: 'campaigns.list',
                      files: ['";
        // line 1474
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/notifications/edit/editTpl.js']
                  });
              }],
        }
    })
    // MASS MAIL
    ";
        // line 1481
        echo "    .state(\"utils.massmessage\", {
        url: \"/massmessage\",
        templateUrl: '";
        // line 1483
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/abstractTpl.html',
        name: 'utils.massmessage',
        redirect: 'utils.massmessage.add',
        abstract: true,
        data: {
            proxy:      'utils.massmessage.list',
            navid:      'navigation-utils-massmessage',
        },
        resolve: {
            parentDeps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'massmessage',
                    files: ['";
        // line 1496
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/abstractCtrl.js']
                });
            }],
        }
    })
    ";
        // line 1502
        echo "    .state(\"utils.massmessage.list\", {
        url: \"/list\",
        templateUrl: '";
        // line 1504
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/list/listTpl.html',
        name: 'utils.massmessage.list',
        data: {
            pageTitle:          '";
        // line 1507
        echo "{{ breadcrumbsTitle }}";
        echo "',
            pageTitleTemplate:  '";
        // line 1508
        echo "{{ breadcrumbsTitle }}";
        echo "',
            navid:              'navigation-utils-massmessage',
        },
        controller: 'massmessageListController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.manage_massmessage') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_massmessage'});
                return \$q.reject('Unauthorized');
            }],

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'massmail.list',
                    files: ['";
        // line 1530
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/list/listCtrl.js']
                });
            }],
        
            breadcrumbsTitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.massmessage.list').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 1544
        echo "    .state(\"utils.massmessage.add\", {
        url: \"/add\",
        templateUrl: '";
        // line 1546
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/add/addTpl.html',
        name: 'utils.massmessage.add',
        data: {
            pageTitle:          '";
        // line 1549
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            pageTitleTemplate:  '";
        // line 1550
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:      'navigation-utils-massmessage',
        },
        controller: 'massmessageAddController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.manage_massmessage') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_massmessage'});
                return \$q.reject('Unauthorized');
            }],

            depsTinymce: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'tinymce.lib',
                    files: ['";
        // line 1572
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/tinymce/tinymce.min.js']
                });
            }],

            depsTinymceIntegration: ['\$ocLazyLoad', 'depsTinymce', function(\$ocLazyLoad, depsTinymce) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'ui.tinymce',
                    files: ['";
        // line 1580
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']
                });
            }],
        
            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'massmessage.add',
                    files: ['";
        // line 1588
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/add/addCtrl.js']
                });
            }],
          
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.massmessage.add').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        
            breadcrumbsTitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.massmessage.list').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 1610
        echo "    .state(\"utils.massmessage.edit\", {
        url: \"/edit/{id:int}\",
        templateUrl: '";
        // line 1612
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/edit/editTpl.html',
        name: 'utils.massmessage.edit',
        data: {
            pageTitle: '";
        // line 1615
        echo "{{ breadcrumbsSubtitle }}";
        echo " #";
        echo "{{ massmessageID }}";
        echo "',
            pageTitleTemplate: '";
        // line 1616
        echo "{{ breadcrumbsSubtitle }}";
        echo " #";
        echo "{{ massmessageID }}";
        echo "',
            navid:     'navigation-massmessage',
        },
        controller: 'massmessageEditController',
        resolve: {

            // ACL - permit only full admins
            isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                // check access
                if( AclService.can('settings.manage_massmessage') ) {
                    return true;
                }

                \$rootScope.\$broadcast('AclNoAccess', {rule: 'settings.manage_massmessage'});
                return \$q.reject('Unauthorized');
            }],
        
            // update title, tricky, but works :) version for breadcrumbs
            massmessageID: function(\$stateParams) {
                return \$stateParams.id;
            },

            depsTinymceIntegration: ['\$ocLazyLoad', 'depsTinymce', function(\$ocLazyLoad, depsTinymce) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'ui.tinymce',
                    files: ['";
        // line 1643
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']
                });
            }],
            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'massmessage.edit',
                    files: ['";
        // line 1650
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/utils/massmessage/edit/editTpl.js']
                });
            }],
        
            depsTinymce: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                // required controllers
                return \$ocLazyLoad.load({
                    name: 'tinymce.lib',
                    files: ['";
        // line 1658
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/tinymce/tinymce.min.js']
                });
            }],

            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.massmessage.edit').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        
            breadcrumbsTitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.massmessage.list').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ////////////////////
    // CONTACTS HAPPEN - Build Contacts Awesome routing
    ////////////////////
    ";
        // line 1683
        echo "    .state(\"contacts\", {
        url: '/contacts',
        template: '<ui-view/>',
        name: 'contacts',
        abstract: true,
        skipBreadcrumbsUrl: true,
        redirect: 'contacts.list',
        params: {
            contactTypeID: null,
        },
        data: {
            pageTitle: 'Contacts',
            proxy:     'contacts',
            navid:     'navigation-campaigns',
        },
        resolve: {

            // I know, but damm it
            dynamicType: ['\$stateParams', 'ContactTypes', function(\$stateParams, ContactTypes) {
                var type = ContactTypes.getById(\$stateParams.contactTypeID);

                if(type !== null && type.name !== 'undefined') {
                    return type;
                }

                all = ContactTypes.get();

                // set up correct initial contact type for table
                for(i=0; i < all.length; i++) {
                    if(all[i].active === true) {
                        return all[i];
                    }
                }
                
                
                return {};
            }],
          
            cachedListData: ['\$http', '\$rootScope', function(\$http, \$rootScope) {
                // Perform actions on initialize these controller
                return \$http.get(\$rootScope.settings.config.apiURL + '/helpers/resources/table/json', {cache: true});
            }],
        }
    })
    ";
        // line 1728
        echo "    .state(\"contacts.list\", {
        url: \"/list\",
        templateUrl: '";
        // line 1730
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/list/contactsListTpl.html',
        name: 'contacts.list',
        data: {
            pageTitle: '";
        // line 1733
        echo "{{  dynamicType.name }}";
        echo "',
            navid: 'contacts-list',
        },
        controller: 'contactsListCtrl',
        resolve: {

            // since there are few states that load exactly the same files/controllers
            // configure here config for that controller
            statesConfig: function() {
                return {
                    IsItArchive: false,
                };
            },

            tableColumns: ['\$http', '\$rootScope', function(\$http, \$rootScope) {
                return \$http.get(\$rootScope.settings.config.apiURL + '/settings/fields/views/for/lists.leads/json');
            }],



            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    '";
        // line 1755
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/list/contactsListCtrl.js',
                ]);
            }],
        }
    })
    ";
        // line 1761
        echo "    .state(\"contacts.archive\", {
        url: \"/archive\",
        templateUrl: '";
        // line 1763
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/list/contactsListTpl.html',
        name: 'contacts.archive',
        data: {
            pageTitle: 'Archive',
            navid: 'navigation-utils-archive',
        },
        controller: 'contactsListCtrl',
        resolve: {

            // since there are few states that load exactly the same files/controllers
            // configure here config for that controller
            statesConfig: function() {
                return {
                    IsItArchive: true,
                };
            },

            tableColumns: ['\$http', '\$rootScope', function(\$http, \$rootScope) {
                return \$http.get(\$rootScope.settings.config.apiURL + '/settings/fields/views/for/lists.leads/json');
            }],


            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    '";
        // line 1787
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/list/contactsListCtrl.js',
                ]);
            }],
        }
    })
    
    ";
        // line 1794
        echo "    .state(\"contacts.create\", {
          url: \"/create\",
          templateUrl: '";
        // line 1796
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/templates/createForm.html',
          name: 'contacts.create',
          data: {
                pageTitle: 'Create',
                navid: 'navigation-createlead',
          },
          controller: 'leadsCreateController',
          resolve: {
                
                // ACL - permit only full admins
                isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                    // check access
                    if( AclService.can('resources.create_lead') ) {
                        return true;
                    }

                    \$rootScope.\$broadcast('AclNoAccess', {rule: 'resources.create_lead'});
                    return \$q.reject('Unauthorized');
                }],
        
                // no additional params bur required to DI
                additionalParams: ['\$stateParams', function(\$stateParams) {
                    return {
                        ticket_id: false,
                        quote_id:  false,
                        client_id: false
                    };
                }],
            
                leadsCreateServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                    return \$ocLazyLoad.load([
                        // required services
                        '";
        // line 1829
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadParams.js',
                        '";
        // line 1830
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadService.js',
                    ]);
                }],
                
                deps: ['\$ocLazyLoad', 'leadsCreateServices', function(\$ocLazyLoad, leadsCreateServices) {
                    return \$ocLazyLoad.load([
                        // required controllers
                        '";
        // line 1837
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/controllers/createLead.js',
                    ]);
                }],
          }
    })
    ";
        // line 1843
        echo "    .state(\"contacts.createFromTicket\", {
          url: \"/create/ticket/{id:int}\",
          templateUrl: '";
        // line 1845
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/templates/createForm.html',
          name: 'contacts.createFromTicket',
          data: {
                pageTitle: 'Create Lead',
                navid: 'navigation-createlead',
          },
          controller: 'leadsCreateController',
          resolve: {
                
                // ACL - permit only full admins
                isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                    // check access
                    if( AclService.can('resources.create_lead') ) {
                        return true;
                    }

                    \$rootScope.\$broadcast('AclNoAccess', {rule: 'resources.create_lead'});
                    return \$q.reject('Unauthorized');
                }],
            
                additionalParams: ['\$stateParams', function(\$stateParams) {
                    return {
                        ticket_id: \$stateParams.id,
                        quote_id:  false,
                        client_id: false
                    };
                }],
            
                leadsCreateServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                    return \$ocLazyLoad.load([
                        // required services
                        '";
        // line 1877
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadParams.js',
                        '";
        // line 1878
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadService.js',
                    ]);
                }],
            
                deps: ['\$ocLazyLoad', 'leadsCreateServices', function(\$ocLazyLoad, leadsCreateServices) {
                    return \$ocLazyLoad.load([
                          // required controllers
                          '";
        // line 1885
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/controllers/createLead.js',
                    ]);
                }],
          }
    })
    ";
        // line 1891
        echo "    .state(\"contacts.createFromQuote\", {
          url: \"/create/quote/{id:int}\",
          templateUrl: '";
        // line 1893
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/templates/createForm.html',
          name: 'contacts.createFromQuote',
          data: {
                pageTitle: 'Create Lead',
                navid: 'navigation-createlead',
          },
          controller: 'leadsCreateController',
          resolve: {
                
                // ACL - permit only full admins
                isAllowed: ['\$q', '\$rootScope', 'AclService', function(\$q, \$rootScope, AclService) {

                    // check access
                    if( AclService.can('resources.create_lead') ) {
                        return true;
                    }

                    \$rootScope.\$broadcast('AclNoAccess', {rule: 'resources.create_lead'});
                    return \$q.reject('Unauthorized');
                }],
            
                additionalParams: ['\$stateParams', function(\$stateParams) {
                    return {
                        ticket_id: false,
                        quote_id:  \$stateParams.id,
                        client_id: false
                    };
                }],
            
                leadsCreateServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                    return \$ocLazyLoad.load([
                        // required services
                        '";
        // line 1925
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadParams.js',
                        '";
        // line 1926
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadService.js',
                    ]);
                }],
            
                deps: ['\$ocLazyLoad', 'leadsCreateServices', function(\$ocLazyLoad, leadsCreateServices) {
                    return \$ocLazyLoad.load([
                        // required controllers
                        '";
        // line 1933
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/controllers/createLead.js',
                    ]);
                }],
          }
    })
    ";
        // line 1939
        echo "    .state(\"contacts.createFromClient\", {
          url: \"/create/client/{id:int}\",
          templateUrl: '";
        // line 1941
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/templates/createForm.html',
          name: 'contacts.createFromClient',
          data: {
                pageTitle: 'Create Lead',
                navid: 'navigation-createlead',
          },
          controller: 'leadsCreateController',
          resolve: {
                
                additionalParams: ['\$stateParams', function(\$stateParams) {
                    return {
                        ticket_id: false,
                        quote_id:  false,
                        client_id: \$stateParams.id
                    };
                }],
            
                leadsCreateServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                    return \$ocLazyLoad.load([
                        // required services
                        '";
        // line 1961
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadParams.js',
                        '";
        // line 1962
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/services/createLeadService.js',
                    ]);
                }],
            
                deps: ['\$ocLazyLoad', 'leadsCreateServices', function(\$ocLazyLoad, leadsCreateServices) {
                    return \$ocLazyLoad.load([
                        // required controllers
                        '";
        // line 1969
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/create/controllers/createLead.js',
                    ]);
                }],
          }
    })
    
    ";
        // line 1976
        echo "    .state(\"contacts.details\", {
        url: \"/{id:int}\",
        templateUrl: '";
        // line 1978
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/detailsHeaderTpl.html',
        name: 'contacts.details',
        controller: 'detailsHeaderCtrl',
        abstract: true,
        data: {
            proxy: 'contacts.list',
            navid: 'contacts-list',
        },
        resolve: {

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    '";
        // line 1990
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/detailsHeaderCtrl.js',
                ]);
            }],

            // A function value promises resolved data to controller and child statuses
            leadMainDetailsData: ['\$stateParams', '\$resource', '\$rootScope', function(\$stateParams, \$resource, \$rootScope) {

                // Return a promise to makle sure the customer is completely
                // resolved before the controller is instantiated
                return \$resource(\$rootScope.settings.config.apiURL + '/lead/:id/getMainDetails/json', {id:'@id'}).get({id: \$stateParams.id}).\$promise;
            }],
        
            // I know, but damm it - this approach works for breadcrumbs so its FINE
            dynamicType: ['leadMainDetailsData', 'ContactTypes', function(leadMainDetailsData, ContactTypes) {
                var type = ContactTypes.getById(leadMainDetailsData.type_id);

                if(type !== null && type.name !== 'undefined') {
                    return type;
                }

                all = ContactTypes.get();
                return all[0];
            }],
        
          }
      })
    ";
        // line 2017
        echo "    .state(\"contacts.details.summary\", {
        url: \"/summary\",
        name: 'contacts.details.summary',
        data: {
            pageTitle:         '#";
        // line 2021
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "', 
            pageTitleTemplate: '#";
        // line 2022
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:             'contacts-list',
        },
        views: {
            '': {
                templateUrl: '";
        // line 2027
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/designTpl.html',
                controller: 'detailsSummaryMainCtrl'
            },

            'customFields@contacts.details.summary': {
                templateProvider: ['\$templateFactory', 'leadMainDetailsData', function (\$templateFactory, leadMainDetailsData) {
                    if(leadMainDetailsData.deleted_at) {
                        return \$templateFactory.fromUrl('";
        // line 2034
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/archive/customFields.html');
                    }
                    return \$templateFactory.fromUrl('";
        // line 2036
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/customFields.html');
                }],
                controller: 'detailsSummaryCustomFieldsCtrl'
            },
            'followups@contacts.details.summary': {
                templateProvider: ['\$templateFactory', 'leadMainDetailsData', function (\$templateFactory, leadMainDetailsData) {
                    if(leadMainDetailsData.deleted_at) {
                        return \$templateFactory.fromUrl('";
        // line 2043
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/archive/followups.html');
                    }
                    return \$templateFactory.fromUrl('";
        // line 2045
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/followups.html');
                }],
                controller: 'detailsSummaryFollowupsCtrl'
            },
            'mainDetails@contacts.details.summary': {
                templateProvider: ['\$templateFactory', 'leadMainDetailsData', function (\$templateFactory, leadMainDetailsData) {
                    if(leadMainDetailsData.deleted_at) {
                        return \$templateFactory.fromUrl('";
        // line 2052
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/archive/mainDetails.html');
                    }
                    return \$templateFactory.fromUrl('";
        // line 2054
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/mainDetails.html');
                }],
                controller: 'detailsSummaryMainDetailsCtrl'
            },
            'notes@contacts.details.summary': {
                templateUrl: '";
        // line 2059
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/notes.html',
                controller: 'detailsSummaryNotesCtrl'
            },
            'quickActionsTab@contacts.details.summary': {
                templateProvider: ['\$templateFactory', 'leadMainDetailsData', function (\$templateFactory, leadMainDetailsData) {
                    if(leadMainDetailsData.deleted_at) {
                        return \$templateFactory.fromUrl('";
        // line 2065
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/archive/quickActionsTab.html');
                    }
                    return \$templateFactory.fromUrl('";
        // line 2067
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/templates/quickActionsTab.html');
                }],
                controller: 'detailsSummaryQuickActionTabsCtrl'
            },
        },
        resolve: {

            leadSummaryServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                    return \$ocLazyLoad.load([
                    // services
                    '";
        // line 2077
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/notes/services/notesService.js',
                ]);
            }],
        
            controllersResolve: ['\$ocLazyLoad', 'leadSummaryServices', function(\$ocLazyLoad, leadSummaryServices) {                
                return \$ocLazyLoad.load([
                    // controllers
                    '";
        // line 2084
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/designTpl.js',
                    '";
        // line 2085
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/controllers/customFields.js',
                    '";
        // line 2086
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/controllers/followups.js',
                    '";
        // line 2087
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/controllers/mainDetails.js',
                    '";
        // line 2088
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/controllers/notes.js',
                    '";
        // line 2089
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/summary/controllers/quickActionsTab.js',
                ]);
            }],
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.summary').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 2103
        echo "    .state(\"contacts.details.followups\", {
        url: \"/followups\",
        templateUrl: '";
        // line 2105
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/followups/templates/followups.html',
        name: 'contacts.details.followups',
        data: {
            pageTitle:         '#";
        // line 2108
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            pageTitleTemplate: '#";
        // line 2109
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:             'contacts-list',
        },
        controller: 'leadFollowupController',
        resolve: {

            leadFollowupServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // services
                    '";
        // line 2118
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/followups/services/followupService.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'leadFollowupServices', function(\$ocLazyLoad, leadFollowupServices) {
                return \$ocLazyLoad.load([
                    // controllers
                    '";
        // line 2125
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/followups/controllers/leadFollowupController.js',
                ]);
            }],
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                    // ";
        // line 2130
        echo "{{ breadcrumbsSubtitle }}";
        echo "
                var deferred = \$q.defer();
                \$translate('breadcrumbs.followups').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 2140
        echo "    .state(\"contacts.details.followup\", {
        url: \"/followup/{followupID:int}\",
        templateUrl: '";
        // line 2142
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/followups/templates/edit.html',
        name: 'contacts.details.followup',
        data: {
            pageTitle:         '#";
        // line 2145
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo " #";
        echo "{{ followupID }}";
        echo "',
            pageTitleTemplate: '#";
        // line 2146
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo " #";
        echo "{{ followupID }}";
        echo "',
            navid:             'contacts-list',
        },
        controller: 'leadEditFollowupController',
        resolve: {

            leadEditFollowupServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // services
                    '";
        // line 2155
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/followups/services/singleFollowuprderService.js',
                    '";
        // line 2156
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/followups/services/singleReminderService.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'leadEditFollowupServices', function(\$ocLazyLoad, leadEditFollowupServices) {
                return \$ocLazyLoad.load([
                    // controllers
                    '";
        // line 2163
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/followups/controllers/leadEditFollowup.js',
                ]);
            }],

            // update title, tricky, but works :) version for breadcrumbs
            followupID: function(\$stateParams) {
                return \$stateParams.followupID;
            },
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.followup.edit').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 2182
        echo "    .state(\"contacts.details.notes\", {
        url: \"/notes\",
        templateUrl: '";
        // line 2184
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/notes/templates/notes.html',
        name: 'contacts.details.notes',
        data: {
            pageTitle:         '#";
        // line 2187
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            pageTitleTemplate: '#";
        // line 2188
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:             'contacts-list',
        },
        controller: 'leadNotesController',
        resolve: {

            leadNotesServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // services
                    '";
        // line 2197
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/notes/services/notesService.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'leadNotesServices', function(\$ocLazyLoad, leadNotesServices) {
                return \$ocLazyLoad.load([
                    // controllers
                    '";
        // line 2204
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/notes/controllers/leadNotesController.js',
                ]);
            }],
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.followup.notes').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 2218
        echo "    .state(\"contacts.details.emails\", {
        url: \"/emails\",
        templateUrl: '";
        // line 2220
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/emails/templates/emails.html',
        name: 'contacts.details.emails',
        data: {
            pageTitle:         '#";
        // line 2223
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            pageTitleTemplate: '#";
        // line 2224
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:             'contacts-list',
        },
        controller: 'leadEmailsController',
        resolve: {

            leadEmailsServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // services
                    '";
        // line 2233
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/emails/services/emailService.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'leadEmailsServices', function(\$ocLazyLoad, leadEmailsServices) {
                return \$ocLazyLoad.load([
                    // controller
                    '";
        // line 2240
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/emails/controllers/leadEmailsController.js',
                ]);
            }],
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.followup.emails').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 2254
        echo "    .state(\"contacts.details.orders\", {
        url: \"/orders\",
        templateUrl: '";
        // line 2256
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/orders/templates/orders.html',
        name: 'contacts.details.orders',
        data: {
            pageTitle:         '#";
        // line 2259
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            pageTitleTemplate: '#";
        // line 2260
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:             'contacts-list',
        },
        controller: 'leadOrdersController',
        resolve: {

            leadOrdersServices: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // services
                    '";
        // line 2269
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/orders/services/orderService.js',
                ]);
            }],

            deps: ['\$ocLazyLoad', 'leadOrdersServices', function(\$ocLazyLoad, leadOrdersServices) {
                return \$ocLazyLoad.load([
                    // controllers
                    '";
        // line 2276
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/orders/controllers/leadOrdersController.js',
                ]);
            }],
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.followup.orders').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 2290
        echo "//    .state(\"contacts.details.quotes\", {
//        url: \"/quotes\",
//        templateUrl: '";
        // line 2292
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/quotes/templates/quotes.html',
//        name: 'contacts.details.quotes',
//        data: {
//            pageTitle:         '#";
        // line 2295
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
//            pageTitleTemplate: '#";
        // line 2296
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
//            navid:             'contacts-list',
//        },
//        controller: 'leadQuotesController',
//        resolve: {
//
//            // You can disable quotes in global settings
//            isEnabled: ['\$q', '\$rootScope', '\$state', function(\$q, \$rootScope, \$state) {
//
//                if( \$rootScope.settings.config.app.quotations_enable == true ) {
//                    return true;
//                }
//
//                // Does not have permission
//                \$rootScope.\$broadcast('AclNoAccess', {msg: 'Quotations integration is disabled'});
//                return \$q.reject('Unauthorized');
//            }],
//
//            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
//                return \$ocLazyLoad.load([
//                    '";
        // line 2316
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/quotes/controllers/leadQuotesController.js',
//                ]);
//            }],
//        
//            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
//                var deferred = \$q.defer();
//                \$translate('breadcrumbs.followup.quotes').then(function (txt) {
//                    deferred.resolve(txt);
//                });
//                return deferred.promise; 
//            }],
//
//        }
//    })
    ";
        // line 2331
        echo "    .state(\"contacts.details.files\", {
        url: \"/files\",
        templateUrl: '";
        // line 2333
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/files/templates/files.html',
        name: 'contacts.details.files',
        data: {
            pageTitle:         '#";
        // line 2336
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            pageTitleTemplate: '#";
        // line 2337
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:             'contacts-list',
        },
        controller: 'leadFilesController',
        resolve: {

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    // controllers
                    '";
        // line 2346
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/files/controllers/leadFilesController.js',
                ]);
            }],
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.followup.files').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })
    ";
        // line 2360
        echo "    .state(\"contacts.details.logs\", {
        url: \"/logs\",
        templateUrl: '";
        // line 2362
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/logs/templates/logs.html',
        name: 'contacts.details.logs',
        data: {
            pageTitle:         '#";
        // line 2365
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            pageTitleTemplate: '#";
        // line 2366
        echo "{{ leadMainDetailsData.id }}";
        echo " ";
        echo "{{ leadMainDetailsData.name }}";
        echo " / ";
        echo "{{ breadcrumbsSubtitle }}";
        echo "',
            navid:             'contacts-list',
        },
        controller: 'leadLogsController',
        resolve: {

            deps: ['\$ocLazyLoad', function(\$ocLazyLoad) {
                return \$ocLazyLoad.load([
                    '";
        // line 2374
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/contacts/details/logs/controllers/leadLogsController.js',
                ]);
            }],
        
            breadcrumbsSubtitle: ['\$translate', '\$q', function(\$translate, \$q) {
                var deferred = \$q.defer();
                \$translate('breadcrumbs.followup.logs').then(function (txt) {
                    deferred.resolve(txt);
                });
                return deferred.promise; 
            }],
        }
    })";
    }

    public function getTemplateName()
    {
        return "app/app.routes.js";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  3233 => 2374,  3218 => 2366,  3210 => 2365,  3204 => 2362,  3200 => 2360,  3184 => 2346,  3168 => 2337,  3160 => 2336,  3154 => 2333,  3150 => 2331,  3133 => 2316,  3106 => 2296,  3098 => 2295,  3092 => 2292,  3088 => 2290,  3072 => 2276,  3062 => 2269,  3046 => 2260,  3038 => 2259,  3032 => 2256,  3028 => 2254,  3012 => 2240,  3002 => 2233,  2986 => 2224,  2978 => 2223,  2972 => 2220,  2968 => 2218,  2952 => 2204,  2942 => 2197,  2926 => 2188,  2918 => 2187,  2912 => 2184,  2908 => 2182,  2887 => 2163,  2877 => 2156,  2873 => 2155,  2855 => 2146,  2845 => 2145,  2839 => 2142,  2835 => 2140,  2823 => 2130,  2815 => 2125,  2805 => 2118,  2789 => 2109,  2781 => 2108,  2775 => 2105,  2771 => 2103,  2755 => 2089,  2751 => 2088,  2747 => 2087,  2743 => 2086,  2739 => 2085,  2735 => 2084,  2725 => 2077,  2712 => 2067,  2707 => 2065,  2698 => 2059,  2690 => 2054,  2685 => 2052,  2675 => 2045,  2670 => 2043,  2660 => 2036,  2655 => 2034,  2645 => 2027,  2633 => 2022,  2625 => 2021,  2619 => 2017,  2590 => 1990,  2575 => 1978,  2571 => 1976,  2562 => 1969,  2552 => 1962,  2548 => 1961,  2525 => 1941,  2521 => 1939,  2513 => 1933,  2503 => 1926,  2499 => 1925,  2464 => 1893,  2460 => 1891,  2452 => 1885,  2442 => 1878,  2438 => 1877,  2403 => 1845,  2399 => 1843,  2391 => 1837,  2381 => 1830,  2377 => 1829,  2341 => 1796,  2337 => 1794,  2328 => 1787,  2301 => 1763,  2297 => 1761,  2289 => 1755,  2264 => 1733,  2258 => 1730,  2254 => 1728,  2208 => 1683,  2181 => 1658,  2170 => 1650,  2160 => 1643,  2128 => 1616,  2122 => 1615,  2116 => 1612,  2112 => 1610,  2088 => 1588,  2077 => 1580,  2066 => 1572,  2041 => 1550,  2037 => 1549,  2031 => 1546,  2027 => 1544,  2011 => 1530,  1986 => 1508,  1982 => 1507,  1976 => 1504,  1972 => 1502,  1964 => 1496,  1948 => 1483,  1944 => 1481,  1935 => 1474,  1904 => 1446,  1900 => 1445,  1894 => 1442,  1890 => 1440,  1882 => 1434,  1854 => 1409,  1850 => 1407,  1842 => 1401,  1814 => 1376,  1810 => 1374,  1796 => 1361,  1787 => 1354,  1783 => 1353,  1777 => 1350,  1773 => 1349,  1769 => 1348,  1765 => 1347,  1761 => 1346,  1757 => 1345,  1747 => 1338,  1743 => 1337,  1739 => 1336,  1727 => 1327,  1720 => 1323,  1713 => 1319,  1706 => 1315,  1699 => 1311,  1688 => 1303,  1677 => 1294,  1662 => 1280,  1650 => 1270,  1619 => 1242,  1615 => 1241,  1609 => 1238,  1605 => 1236,  1597 => 1230,  1568 => 1204,  1564 => 1202,  1556 => 1196,  1528 => 1171,  1524 => 1169,  1516 => 1163,  1499 => 1149,  1495 => 1147,  1483 => 1137,  1479 => 1136,  1475 => 1135,  1471 => 1134,  1467 => 1133,  1447 => 1116,  1440 => 1112,  1433 => 1108,  1426 => 1104,  1415 => 1096,  1394 => 1078,  1381 => 1068,  1365 => 1055,  1361 => 1053,  1350 => 1044,  1322 => 1019,  1318 => 1017,  1310 => 1011,  1282 => 986,  1278 => 984,  1270 => 978,  1252 => 963,  1248 => 961,  1237 => 952,  1210 => 928,  1206 => 926,  1198 => 920,  1189 => 914,  1163 => 891,  1159 => 889,  1151 => 883,  1141 => 876,  1115 => 853,  1111 => 851,  1100 => 842,  1096 => 840,  1085 => 831,  1081 => 829,  1070 => 820,  1066 => 818,  1055 => 809,  1051 => 807,  1043 => 801,  1033 => 794,  1003 => 767,  999 => 765,  990 => 758,  979 => 750,  968 => 742,  936 => 713,  932 => 712,  926 => 709,  922 => 707,  914 => 701,  902 => 692,  891 => 684,  862 => 658,  858 => 656,  850 => 650,  821 => 624,  817 => 622,  809 => 616,  792 => 602,  788 => 600,  775 => 589,  743 => 560,  739 => 559,  733 => 556,  729 => 554,  721 => 548,  691 => 521,  687 => 519,  679 => 513,  650 => 487,  646 => 485,  638 => 479,  621 => 465,  617 => 463,  602 => 450,  598 => 448,  587 => 439,  583 => 437,  574 => 430,  564 => 423,  546 => 408,  542 => 406,  534 => 400,  524 => 393,  497 => 369,  493 => 367,  485 => 361,  475 => 354,  460 => 342,  456 => 340,  448 => 334,  438 => 327,  411 => 303,  407 => 301,  399 => 295,  389 => 288,  362 => 264,  358 => 262,  350 => 256,  340 => 249,  336 => 248,  332 => 247,  328 => 246,  299 => 220,  293 => 217,  289 => 215,  281 => 209,  271 => 202,  267 => 201,  240 => 177,  236 => 175,  228 => 169,  211 => 155,  206 => 152,  191 => 138,  179 => 128,  166 => 117,  155 => 108,  147 => 103,  139 => 98,  131 => 93,  123 => 88,  115 => 83,  107 => 78,  99 => 73,  91 => 68,  83 => 63,  19 => 1,);
    }
}
/* /****************************************************************************************/
/*  **/
/*  * */
/*  *                  ██████╗██████╗ ███╗   ███╗         Customer*/
/*  *                 ██╔════╝██╔══██╗████╗ ████║         Relations*/
/*  *                 ██║     ██████╔╝██╔████╔██║         Manager*/
/*  *                 ██║     ██╔══██╗██║╚██╔╝██║*/
/*  *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For Magento*/
/*  *                  ╚═════╝╚═╝  ╚═╝╚═╝     ╚═╝*/
/*  * */
/*  *    */
/*  * @author      Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >*/
/*  *              */
/*  *                           */
/*  * @link        http://www.docs.modulesgarden.com/CRM_For_WHMCS for documenation*/
/*  * @link        http://modulesgarden.com ModulesGarden*/
/*  *              Top Quality Custom Software Development*/
/*  * @copyright   Copyright (c) ModulesGarden, INBS Group Brand, */
/*  *              All Rights Reserved (http://modulesgarden.com)*/
/*  * */
/*  * This software is furnished under a license and mxay be used and copied only  in  */
/*  * accordance  with  the  terms  of such  license and with the inclusion of the above */
/*  * copyright notice.  This software  or any other copies thereof may not be provided */
/*  * or otherwise made available to any other person.  No title to and  ownership of */
/*  * the  software is hereby transferred.*/
/*  **/
/*  **************************************************************************************//* */
/* */
/* */
/* /***/
/*  * keep on mind that this file is parsed by twig extension !!!!!!!!!!!*/
/*  * I didnt renamed it as .twig extension since IDE wont highlight syntax (fu**k)*/
/*  * */
/*  * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >*/
/*  *//* */
/* */
/* /////////////////////////////*/
/* // UU-Routes Provide states and routing*/
/* /////////////////////////////*/
/* // Use $urlRouterProvider to configure any redirects (when) and invalid urls (otherwise).*/
/* $urlRouterProvider*/
/*   // redirects, since I dont want to render abstract states, just go to other*/
/*   .when('/settings/fields',     '/settings/fields/list')*/
/*   .when('/settings',            '/settings/personal')*/
/*   .when('/campaigns',           '/campaigns/list')*/
/*   .when('/contacts',            '/contacts/list')*/
/*   .when('/general',             '/general/overview')*/
/*   .when('/utils',               '/utils/statistics')*/
/*   .when('/utils/notifications', '/utils/notifications/list')*/
/*   // If the url is ever invalid, e.g. '/asdf', then redirect to '/' aka the home state*/
/*   .otherwise('dashboard');*/
/* */
/* //////////////////////////*/
/* // State Configurations //*/
/* //////////////////////////*/
/* $stateProvider*/
/* */
/*     ////////////////////*/
/*     // TEST RUTES*/
/*     ////////////////////*/
/*     .state("buttons", {*/
/*         url: "/buttons",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/buttons.html',*/
/*         data: {pageTitle: 'Buttons'}*/
/*     })*/
/*     .state("typography", {*/
/*         url: "/typography",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/typography.html',*/
/*         data: {pageTitle: 'Typography'},*/
/*     })*/
/*     .state("panels", {*/
/*         url: "/panels",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/panels.html',*/
/*         data: {pageTitle: 'Panels'},*/
/*     })*/
/*     .state("icons", {*/
/*         url: "/icons",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/icons.html',*/
/*         data: {pageTitle: 'Icons'},*/
/*     })*/
/*     .state("boxes", {*/
/*         url: "/boxes",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/boxes.html',*/
/*         data: {pageTitle: 'Boxes'},*/
/*     })*/
/*     .state("tables_simple", {*/
/*         url: "/tables_simple",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/tables_simple.html',*/
/*         data: {pageTitle: 'Tables Simple'},*/
/*     })*/
/*     .state("tables_extended", {*/
/*         url: "/tables_extended",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/tables_extended.html',*/
/*         data: {pageTitle: 'Tables Extended'},*/
/*     })*/
/*     .state("tables_datatables", {*/
/*         url: "/tables_datatables",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/tables_datatables.html',*/
/*         data: {pageTitle: 'Tables Datatables'},*/
/*     })*/
/*     .state("form_general", {*/
/*         url: "/form_general",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/form_general.html',*/
/*         data: {pageTitle: 'Tables General'},*/
/*     })*/
/*     .state("form_advanced", {*/
/*         url: "/form_advanced",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/form_advanced.html',*/
/*         data: {pageTitle: 'Tables Advanced'},*/
/*     })*/
/* */
/*     ////////////////////*/
/*     // STATIC PAGES*/
/*     ////////////////////*/
/* */
/*     {# STATICS #}*/
/*     .state("static", {*/
/*         url:        "/static/pages",*/
/*         template:   '<div ui-view></div>',*/
/*         name:       'pages',*/
/*         abstract:   true, // to activate child states*/
/*         data: {*/
/*             pageTitle: 'Static Pages',*/
/*         },*/
/*     })*/
/*     .state("static.icons", {*/
/*         url: "/dashboard",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/views/frontend/icons.html',*/
/*         name: 'pages.icons',*/
/*         data: {*/
/*             pageTitle: 'Icons'*/
/*         },*/
/*     })*/
/*     ////////////////////*/
/*     // SETTINGS FAMILY*/
/*     ////////////////////*/
/*     {# SETTINGS #}*/
/*     .state("settings", {*/
/*         url: "/settings",*/
/*         // Note: abstract still needs a ui-view for its children to populate.*/
/*         template: '<ui-view/>',*/
/*         name: 'settings',*/
/*         redirect: 'settings.personal',*/
/*         abstract: true, // to activate child states*/
/*         data: {*/
/*             pageTitle: 'Settings',*/
/*             proxy:     'settings',*/
/*             navid:     'settings',*/
/*         }*/
/*     })*/
/*     {# SETTINGS > FIELDS  - abstract #}*/
/*     .state("settings.fields", {*/
/*         url: "/fields",*/
/*         // Note: abstract still needs a ui-view for its children to populate.*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/fields/navigationController.html',*/
/*         name: 'settings.fields',*/
/*         redirect: 'settings.fields.list',*/
/*         controller: 'settingsFieldsNavigationController',*/
/*         abstract: true, // to activate child states*/
/*         data: {*/
/*             pageTitle: 'Fields',*/
/*             proxy:     'settings.fields',*/
/*         },*/
/*         resolve: {*/
/*         */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                       // required controllers*/
/*                       '{{ settings.templates.rootDir }}/app/settings/fields/navigationController.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > FIELDS > List #}*/
/*     .state("settings.fields.list", {*/
/*         url: "/list",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/fields/fields/templates/fields.html',*/
/*         name: 'settings.fields.list',*/
/*         data: {*/
/*               pageTitle: 'Fields List',*/
/*               navid:     'navigation-settings-fields',*/
/*         },*/
/*         controller: 'settingsFieldsList',*/
/*         resolve: {*/
/* */
/*             // ACL - based on specific rule*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_fields') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_fields'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             settingsFieldsListServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // required services*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/fields/services/field.js',*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/groups/services/groups.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'settingsFieldsListServices', function($ocLazyLoad, settingsFieldsListServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // required controllers*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/fields/controllers/list.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > FIELDS > Edit #}*/
/*     .state("settings.fields.edit", {*/
/*         url: "/edit/{id:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/fields/fields/templates/edit.html',*/
/*         name: 'settings.fields.edit',*/
/*         data: {*/
/*             pageTitle: 'Edit #{{ '{{ fieldID }}' }}',*/
/*             navid:     'navigation-settings-fields',*/
/*         },*/
/*         controller: 'settingsFieldsEdit',*/
/*         resolve: {*/
/* */
/*             // ACL - based on specific rule*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_fields') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_fields'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             // update title, tricky, but works :) version for breadcrumbs*/
/*             fieldID: function($stateParams) {*/
/*                 return $stateParams.id;*/
/*             },*/
/* */
/*             settingsFieldsEditServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // required services*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/fields/services/field.js',*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/fields/services/validators.js',*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/fields/services/options.js',*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/groups/services/groups.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'settingsFieldsEditServices', function($ocLazyLoad, settingsFieldsEditServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // required controllers*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/fields/controllers/edit.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > FIELDS > Groups #}*/
/*     .state("settings.fields.groups", {*/
/*         url: "/groups",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/fields/groups/templates/groups.html',*/
/*         name: 'settings.fields.groups',*/
/*         data: {*/
/*             pageTitle: 'Fields Groups',*/
/*             navid:     'navigation-settings-fields',*/
/*         },*/
/*         controller: 'settingsFieldsGroups',*/
/*         resolve: {*/
/* */
/*             // ACL - based on specific rule*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_fields') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_fields'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             settingsFieldsGroupsServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // required services*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/groups/services/groups.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'settingsFieldsGroupsServices', function($ocLazyLoad, settingsFieldsGroupsServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // required controllers*/
/*                     '{{ settings.templates.rootDir }}/app/settings/fields/groups/controllers/groups.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > FIELDS > Statuses #}*/
/*     .state("settings.fields.statuses", {*/
/*         url: "/statuses",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/fields/statuses/templates/statuses.html',*/
/*         name: 'settings.fields.statuses',*/
/*         data: {*/
/*             pageTitle: 'Statuses',*/
/*             navid:     'navigation-settings-fields',*/
/*         },*/
/*         controller: 'settingsFieldsStatuses',*/
/*         resolve: {*/
/*             */
/*             // ACL - based on specific rule*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/*                 */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_statuses') ) {*/
/*                     return true;*/
/*                 }*/
/*                 */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_statuses'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/*               */
/*             settingsFieldsStatusesServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required services*/
/*                         '{{ settings.templates.rootDir }}/app/settings/fields/statuses/services/statuses.js',*/
/*                   ]);*/
/*             }],*/
/*         */
/*             deps: ['$ocLazyLoad', 'settingsFieldsStatusesServices', function($ocLazyLoad, settingsFieldsStatusesServices) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required controllers*/
/*                         '{{ settings.templates.rootDir }}/app/settings/fields/statuses/controllers/statuses.js',*/
/*                   ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > FIELDS > Views #}*/
/*     .state("settings.fields.views", {*/
/*         url: "/views",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/fields/views/templates/views.html',*/
/*         name: 'settings.fields.views',*/
/*         data: {*/
/*             pageTitle: 'Views',*/
/*             navid:     'navigation-settings-fields',*/
/*         },*/
/*         controller: 'settingsFieldsViews',*/
/*         resolve: {*/
/*             */
/*             settingsFieldsServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required services*/
/*                         '{{ settings.templates.rootDir }}/app/settings/fields/views/services/fieldViews.js',*/
/*                   ]);*/
/*             }],*/
/*         */
/*             deps: ['$ocLazyLoad', 'settingsFieldsServices', function($ocLazyLoad, settingsFieldsServices) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required controllers*/
/*                         '{{ settings.templates.rootDir }}/app/settings/fields/views/controllers/views.js',*/
/*                   ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > FIELDS > Map #}*/
/*     .state("settings.fields.map", {*/
/*         url: "/map",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/fields/map/templates/map.html',*/
/*         name: 'settings.fields.map',*/
/*         data: {*/
/*             pageTitle: 'Map',*/
/*             navid:     'navigation-settings-fields',*/
/*         },*/
/*         controller: 'settingsFieldsMap',*/
/*         resolve: {*/
/*             */
/*             // ACL - based on specific rule*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/*                 */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_fields_map') ) {*/
/*                     return true;*/
/*                 }*/
/*                 */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_fields_map'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/*             */
/*             settingsFieldsMapServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required services*/
/*                         '{{ settings.templates.rootDir }}/app/settings/personal/services/fieldViews.js',*/
/*                   ]);*/
/*             }],*/
/*         */
/*             deps: ['$ocLazyLoad', 'settingsFieldsMapServices', function($ocLazyLoad, settingsFieldsMapServices) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required controllers*/
/*                         '{{ settings.templates.rootDir }}/app/settings/fields/map/controllers/map.js',*/
/*                   ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > PERSONAL   - abstract #}*/
/*     .state("settings.personal", {*/
/*         url: "/personal",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/personal/abstractTpl.html',*/
/*         name: 'settings.personal',*/
/*         redirect: 'settings.personal.personal',*/
/*         abstract: true,*/
/*         controller: 'settingsPersonalAbstractController',*/
/*         data: {*/
/*             pageTitle: 'Personal',*/
/*             proxy:     'settings.personal',*/
/*             navid:     'navigation-settings-personal',*/
/*         },*/
/*         resolve: {*/
/*         */
/*             settingsPersonalServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required services*/
/*                     '{{ settings.templates.rootDir }}/app/settings/personal/services/fieldViews.js',*/
/*                   ]);*/
/*             }],*/
/*         */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad, settingsPersonalServices) {*/
/*                 return $ocLazyLoad.load([ */
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/settings/personal/controllers/personal.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*         }*/
/*     })*/
/*     {# SETTINGS > PERSONAL #}*/
/*     .state("settings.personal.personal", {*/
/*         url: "/general",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/personal/templates/personalTpl.html',*/
/*         name: 'settings.personal.personal',*/
/*         data: {*/
/*             pageTitle: 'Settings',*/
/*             navid:     'navigation-settings-personal',*/
/*         },*/
/*         controller: 'settingsPersonalController',*/
/*     })*/
/*     {# SETTINGS > FIELD VIEWS #}*/
/*     .state("settings.personal.fieldsview", {*/
/*         url: "/fieldsview",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/personal/templates/fieldsviewTpl.html',*/
/*         name: 'settings.personal.fieldsview',*/
/*         data: {*/
/*             pageTitle: 'Fields\' View',*/
/*             navid:     'navigation-settings-personal',*/
/*         },*/
/*         controller: 'settingsPersonalFieldsviewController',*/
/*     })*/
/*     */
/*     ////////////////////*/
/*     // MAILBOX*/
/*     ////////////////////*/
/*     {# MAILBOX   - abstract #}*/
/*     .state("settings.mailbox", {*/
/*         url: "/mailbox",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/mailbox/abstractTpl.html',*/
/*         name: 'mailbox',*/
/*         redirect: 'mailbox.list',*/
/*         abstract: true,*/
/*         data: {*/
/*             pageTitle: 'Outgoing Mailbox Configuration',*/
/*             proxy: 'mailbox',*/
/*             navid:     'navigation-settings-mailbox',*/
/*         },*/
/*         resolve: {*/
/*             parentDeps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'mailbox',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/settings/mailbox/abstractCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# MAILBOX > List/default state #}*/
/*     .state("settings.mailbox.list", {*/
/*         url: "/list",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/mailbox/list/listTpl.html',*/
/*         name: 'mailbox.list',*/
/*         data: {*/
/*             pageTitle: 'List',*/
/*             navid:     'navigation-settings-mailbox',*/
/*         },*/
/*         controller: 'mailboxListController',*/
/*         resolve: {*/
/* */
/*             // TODO - leave it?*/
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.mailbox') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.mailbox'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'mailbox.list',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/settings/mailbox/list/listCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# MAILBOX > Create #}*/
/*     .state("settings.mailbox.add", {*/
/*         url: "/add",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/mailbox/add/addTpl.html',*/
/*         name: 'mailbox.add',*/
/*         data: {*/
/*             pageTitle: 'Add Outgoing Mailbox',*/
/*             navid:     'navigation-settings-mailbox',*/
/*         },*/
/*         controller: 'mailboxAddController',*/
/*         resolve: {*/
/* */
/*               // TODO - leave it?*/
/*               // ACL - permit only full admins*/
/*               isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                   // check access*/
/*                   if( AclService.can('settings.mailbox') ) {*/
/*                       return true;*/
/*                   }*/
/* */
/*                   $rootScope.$broadcast('AclNoAccess', {rule: 'settings.mailbox'});*/
/*                   return $q.reject('Unauthorized');*/
/*               }],*/
/* */
/* */
/*               deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'mailbox.list',*/
/*                       files: ['{{ settings.templates.rootDir }}/app/settings/mailbox/add/addCtrl.js']*/
/*                   });*/
/*               }],*/
/*         }*/
/*     })*/
/*     {# MAILBOX > Edit #}*/
/*     .state("settings.mailbox.edit", {*/
/*         url: "/edit/{id:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/mailbox/edit/editTpl.html',*/
/*         name: 'mailbox.edit',*/
/*         data: {*/
/*             pageTitle: 'Outgoing Mailbox Configuration #{{ '{{ mailboxID }}' }}',*/
/*             pageTitleTemplate: 'Mailbox #{{ '{{ mailboxID }}' }}',*/
/*             proxy: 'mailbox.list',*/
/*             navid:     'navigation-settings-mailbox',*/
/*         },*/
/*         controller: 'mailboxEditController',*/
/*         resolve: {*/
/* */
/*               // TODO: leave it?*/
/*               // ACL - permit only full admins*/
/*               isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                   // check access*/
/*                   if( AclService.can('settings.mailbox') ) {*/
/*                       return true;*/
/*                   }*/
/* */
/*                   $rootScope.$broadcast('AclNoAccess', {rule: 'settings.mailbox'});*/
/*                   return $q.reject('Unauthorized');*/
/*               }],*/
/* */
/*               // update title, tricky, but works :) version for breadcrumbs*/
/*               mailboxID: function($stateParams) {*/
/*                   return $stateParams.id;*/
/*               },*/
/* */
/*               deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'mailbox.list',*/
/*                       files: ['{{ settings.templates.rootDir }}/app/settings/mailbox/edit/editTpl.js']*/
/*                   });*/
/*               }],*/
/*         }*/
/*     })*/
/*     */
/*     /**//* */
/*     ////////////////////*/
/*     // TEMPLATES*/
/*     ////////////////////*/
/*     {# TEMPLATES   - abstract #}*/
/*     .state("settings.emailtemplates", {*/
/*         url: "/emailtemplates",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/emailtemplates/abstractTpl.html',*/
/*         name: 'emailtemplates',*/
/*         redirect: 'emailtemplates.list',*/
/*         abstract: true,*/
/*         data: {*/
/*             pageTitle: 'Email Templates',*/
/*             proxy: 'emailtemplates',*/
/*             navid:     'navigation-settings-emailtemplates',*/
/*         },*/
/*         resolve: {*/
/*             parentDeps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'emailtemplates',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/settings/emailtemplates/abstractCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# TEMPLATES > List/default state #}*/
/*     .state("settings.emailtemplates.list", {*/
/*         url: "/list",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/emailtemplates/list/listTpl.html',*/
/*         name: 'emailtemplates.list',*/
/*         data: {*/
/*             pageTitle: 'List',*/
/*             navid:     'navigation-settings-emailtemplates',*/
/*         },*/
/*         controller: 'emailTemplatesListController',*/
/*         resolve: {*/
/* */
/*             // TODO - leave it?*/
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.emailtemplates') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.emailtemplates'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'emailtemplates.list',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/settings/emailtemplates/list/listCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# TEMPLATES > Create #}*/
/*     .state("settings.emailtemplates.add", {*/
/*         url: "/add",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/emailtemplates/add/addTpl.html',*/
/*         name: 'emailtemplates.add',*/
/*         data: {*/
/*             pageTitle: 'Add Email Template',*/
/*             navid:     'navigation-settings-emailtemplates',*/
/*         },*/
/*         controller: 'emailTemplatesAddController',*/
/*         resolve: {*/
/* */
/*               // TODO - leave it?*/
/*               // ACL - permit only full admins*/
/*               isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                   // check access*/
/*                   if( AclService.can('settings.emailtemplates') ) {*/
/*                       return true;*/
/*                   }*/
/* */
/*                   $rootScope.$broadcast('AclNoAccess', {rule: 'settings.emailtemplates'});*/
/*                   return $q.reject('Unauthorized');*/
/*               }],*/
/*           */
/*               depsTinymce: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'tinymce.lib',*/
/*                       files: ['{{ settings.templates.rootDir }}/assets/plugins/tinymce/tinymce.min.js']*/
/*                   });*/
/*               }],*/
/* */
/*               depsTinymceIntegration: ['$ocLazyLoad', 'depsTinymce', function($ocLazyLoad, depsTinymce) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'ui.tinymce',*/
/*                       files: ['{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']*/
/*                   });*/
/*               }],*/
/* */
/* */
/*               deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'emailtemplates.list',*/
/*                       files: ['{{ settings.templates.rootDir }}/app/settings/emailtemplates/add/addCtrl.js']*/
/*                   });*/
/*               }],*/
/*         }*/
/*     })*/
/*     {# TEMPLATES > Edit #}*/
/*     .state("settings.emailtemplates.edit", {*/
/*         url: "/edit/{id:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/emailtemplates/edit/editTpl.html',*/
/*         name: 'emailtemplates.edit',*/
/*         data: {*/
/*             pageTitle: 'Email Template #{{ '{{ emailTemplateID }}' }}',*/
/*             pageTitleTemplate: 'Template #{{ '{{ emailTemplateID }}' }}',*/
/*             proxy: 'emailtemplates.list',*/
/*             navid:     'navigation-settings-emailtemplates',*/
/*         },*/
/*         controller: 'emailTemplatesEditController',*/
/*         resolve: {*/
/* */
/*               // TODO: leave it?*/
/*               // ACL - permit only full admins*/
/*               isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                   // check access*/
/*                   if( AclService.can('settings.emailtemplates') ) {*/
/*                       return true;*/
/*                   }*/
/* */
/*                   $rootScope.$broadcast('AclNoAccess', {rule: 'settings.emailtemplates'});*/
/*                   return $q.reject('Unauthorized');*/
/*               }],*/
/* */
/*               // update title, tricky, but works :) version for breadcrumbs*/
/*               emailTemplateID: function($stateParams) {*/
/*                   return $stateParams.id;*/
/*               },*/
/*               */
/*               depsTinymce: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'tinymce.lib',*/
/*                       files: ['{{ settings.templates.rootDir }}/assets/plugins/tinymce/tinymce.min.js']*/
/*                   });*/
/*               }],*/
/* */
/*               depsTinymceIntegration: ['$ocLazyLoad', 'depsTinymce', function($ocLazyLoad, depsTinymce) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'ui.tinymce',*/
/*                       files: ['{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']*/
/*                   });*/
/*               }],*/
/* */
/*               deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'emailtemplates.list',*/
/*                       files: ['{{ settings.templates.rootDir }}/app/settings/emailtemplates/edit/editTpl.js']*/
/*                   });*/
/*               }],*/
/*         }*/
/*     })*/
/*     */
/*     {# SETTINGS > GENERAL - Abstract #}*/
/*     .state("settings.general", {*/
/*         url: "/general",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/general/abstractTpl.html',*/
/* //        template: '<ui-view/>',*/
/*         name: 'settings.general',*/
/*         redirect: 'settings.general.overview',*/
/*         data: {*/
/*             pageTitle: 'General',*/
/*             navid:     'navigation-settings-general',*/
/*             proxy:     'settings.general',*/
/*         },*/
/*         controller: 'settingsAbstractGeneralController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/*                 */
/*                 // check access*/
/*                 if( AclService.can('settings.view_general') ) {*/
/*                     return true;*/
/*                 }*/
/*                 */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.view_general'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/*         */
/*             settingsGeneralServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required services*/
/*                     '{{ settings.templates.rootDir }}/app/settings/general/services/followupTypes.js',*/
/*                   ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'settingsGeneralServices', function($ocLazyLoad, settingsGeneralServices) {*/
/*                 return $ocLazyLoad.load([ */
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/settings/general/controllers/general.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > GENERAL - System Overview #}*/
/*     .state("settings.general.overview", {*/
/*         url: "/overview",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/general/templates/overviewTpl.html',*/
/*         name: 'settings.general.overview',*/
/*         data: {*/
/*             pageTitle: 'System Overview',*/
/*             navid:     'navigation-settings-general',*/
/*         },*/
/*         controller: 'settingsGeneralController',*/
/*     })*/
/*     {# SETTINGS > GENERAL - Options #}*/
/*     .state("settings.general.options", {*/
/*         url: "/options",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/general/templates/settingsTpl.html',*/
/*         name: 'settings.general.options',*/
/*         data: {*/
/*             pageTitle: 'Options',*/
/*             navid:     'navigation-settings-general',*/
/*         },*/
/*         controller: 'settingsGeneralController',*/
/*     })*/
/*     {# SETTINGS > GENERAL - Follow-ups #}*/
/*     .state("settings.general.followups", {*/
/*         url: "/followups",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/general/templates/followupsTpl.html',*/
/*         name: 'settings.general.followups',*/
/*         data: {*/
/*             pageTitle: 'Follow-ups',*/
/*             navid:     'navigation-settings-general',*/
/*         },*/
/*         controller: 'settingsGeneralController',*/
/*     })*/
/*     {# SETTINGS > GENERAL - API #}*/
/*     .state("settings.general.api", {*/
/*         url: "/api",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/general/templates/showApiTpl.html',*/
/*         name: 'settings.general.api',*/
/*         data: {*/
/*             pageTitle: 'API',*/
/*             navid:     'navigation-settings-general',*/
/*         },*/
/*         controller: 'settingsGeneralController',*/
/*     })*/
/*     {# SETTINGS > MIGRATOR #}*/
/*     .state("settings.migrator", {*/
/*         url: "/migrator",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/migrator/templates/migrator.html',*/
/*         name: 'settings.general',*/
/*         data: {*/
/*             pageTitle: 'Migrator',*/
/*             navid:     'navigation-settings-migrator',*/
/*         },*/
/*         controller: 'settingsMigratorController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/*                 */
/*                 // check access*/
/*                 if( AclService.can('other.access_migrator') ) {*/
/*                     return true;*/
/*                 }*/
/*                 */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'other.access_migrator'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/*         */
/*             settingsMigratorServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                       '{{ settings.templates.rootDir }}/app/settings/personal/services/fieldViews.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             deps: ['$ocLazyLoad', 'settingsMigratorServices', function($ocLazyLoad, settingsMigratorServices) {*/
/*                 return $ocLazyLoad.load([ */
/*                     // controller services*/
/*                     '{{ settings.templates.rootDir }}/app/settings/migrator/controllers/migrator.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > PERMISSIONS #}*/
/*     .state("settings.permissions", {*/
/*         url: "/permissions",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/permissions/templates/permissions.html',*/
/*         name: 'settings.permissions',*/
/*         data: {*/
/*             pageTitle: 'Permissions',*/
/*             navid:     'navigation-settings-permissions',*/
/*         },*/
/*         controller: 'settingsPermissionController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', function($q, $rootScope) {*/
/*                 */
/*                 if( $rootScope.acl.isFullAdmin == true ) {*/
/*                     return true;*/
/*                 }*/
/*                 */
/*                 // Does not have permission*/
/*                 $rootScope.$broadcast('AclNoAccess', {msg: 'This page is only for Full Access Admins'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             settingsPermissionServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     '{{ settings.templates.rootDir }}/app/settings/permissions/services/permissionGroups.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'settingsPermissionServices', function($ocLazyLoad, settingsPermissionServices) {*/
/*                 return $ocLazyLoad.load([ */
/*                     '{{ settings.templates.rootDir }}/app/settings/permissions/controllers/permission.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# SETTINGS > CONTACT TYPES #}*/
/*     .state("settings.types", {*/
/*         url: "/types",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/types/list/typesListTpl.html',*/
/*         name: 'settings.types',*/
/*         controller: 'settingsTypesListController',*/
/*         data: {*/
/*             pageTitle: 'Contact Types',*/
/*             navid:     'navigation-settings-types',*/
/*         },*/
/*         resolve: {*/
/* */
/*             // ACL - based on specific rule*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/*                 */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_types') ) {*/
/*                     return true;*/
/*                 }*/
/*                 */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_types'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   return $ocLazyLoad.load([*/
/*                         // required controllers*/
/*                         '{{ settings.templates.rootDir }}/app/settings/types/list/typesListCtrl.js',*/
/*                   ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     ////////////////////*/
/*     // IMPORT/EXPORT*/
/*     ////////////////////*/
/*     {# IMPORT/EXPORT - abstract #}*/
/*     .state("settings.importexport", {*/
/*         url: "/importexport",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/importexport/importexportHeaderTpl.html',*/
/*         name: 'settings.importexport',*/
/*         controller: 'importexportHeaderCtrl',*/
/*         redirect: 'settings.importexport.export',*/
/*         abstract: true,*/
/*         data: {*/
/*             pageTitle: 'Import / Export',*/
/*             proxy:     'settings.importexport',*/
/*             navid:      'navigation-settings-importexport',*/
/*         },*/
/*         resolve: {*/
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'importexportHeaderCtrl',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/settings/importexport/importexportHeaderCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# IMPORT/EXPORT > EXPORT/default state #}*/
/*     .state("settings.importexport.export", {*/
/*         url: "/export",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/importexport/export/exportTpl.html',*/
/*         name: 'settings.importexport.export',*/
/*         data: {*/
/*             pageTitle: 'Export',*/
/*             navid:     'navigation-settings-importexport',*/
/*         },*/
/*         controller: 'exportCtrl',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.importexport') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.importexport'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'exportCtrl',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/settings/importexport/export/exportCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# IMPORT/EXPORT > IMPORT #}*/
/*     .state("settings.importexport.import", {*/
/*         url: "/import",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/settings/importexport/import/importTpl.html',*/
/*         name: 'settings.importexport.import',*/
/*         data: {*/
/*             pageTitle: 'Import',*/
/*             navid:     'navigation-settings-importexport',*/
/*         },*/
/*         controller: 'importCtrl',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.importexport') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.importexport'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'importCtrl',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/settings/importexport/import/importCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     ////////////////////*/
/*     // CALENDAR*/
/*     ////////////////////*/
/*     {# CALENDAR #}*/
/*     .state("calendar", {*/
/*         url: "/calendar",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/calendar/templates/calendar.html',*/
/*         name: 'calendar',*/
/*         data: {*/
/*             pageTitle: 'Calendar',*/
/*             navid:     'navigation-calendar',*/
/*         },*/
/*         controller: 'leadsCalendarController',*/
/*         resolve: {*/
/* */
/*             leadsCalendarServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load({*/
/*                     cache: true,*/
/*                     files: [*/
/*                     '{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.min.js',*/
/*                     ]*/
/*                 });*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'leadsCalendarServices', function($ocLazyLoad, leadsCalendarServices) {*/
/*                 return $ocLazyLoad.load({*/
/*                     cache: true,*/
/*                     files: [*/
/*                     // required controllers*/
/*                     '{{ settings.templates.rootDir }}/app/calendar/controllers/calendar.js',*/
/*                     ]*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     ////////////////////*/
/*     // Dashboard*/
/*     ////////////////////*/
/*     .state("dashboard", {*/
/*         url: "/dashboard",*/
/*         name: 'dashboard',*/
/*         data: {*/
/*             pageTitle: 'Dashboard',*/
/*             navid:     'navigation-dashboard',*/
/*         },*/
/*         views: {*/
/*             '': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/dashboard/templates/_main.html',*/
/* //                template: '<h1>main</h1><div ui-view="topWidgets"></div><div ui-view="followups"></div>'*/
/*                 // we couls use url, but to obtain that simple html ? stsly?*/
/*                 // when add new state views remember to add it here in correct order*/
/*                 controller: 'dashboardController'*/
/*             },*/
/* */
/*             'topWidgets@dashboard': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/dashboard/templates/top_widgets.html',*/
/*                 controller: 'topWidgetsDashboardController'*/
/*             },*/
/*             'followups@dashboard': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/dashboard/templates/followups.html',*/
/*                 controller: 'dashboardFollowupsController'*/
/*             },*/
/*             'resources@dashboard': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/dashboard/templates/resources.html',*/
/*                 controller: 'dashboardResourcesController'*/
/*             },*/
/*             'history@dashboard': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/dashboard/templates/history.html',*/
/*                 controller: 'dashboardHistoryController'*/
/*             },*/
/*         },*/
/*         resolve: {*/
/*           // guess we will need dependiency for personalization settings*/
/*           */
/*             // A function value promises resolved data to controller and child statuses*/
/*             tableColumns: ['$http', '$rootScope', function($http, $rootScope) {*/
/*                 // but $http returns: :D*/
/*                 // Returns a Promise that will be resolved to a response object when the request succeeds or fails.*/
/*                 return $http.get($rootScope.settings.config.apiURL + '/settings/fields/views/for/dashboard/json');*/
/*             }],*/
/*             */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // load main controllers for each view*/
/*                     '{{ settings.templates.rootDir }}/app/dashboard/controllers/_main.js',*/
/*                     '{{ settings.templates.rootDir }}/app/dashboard/controllers/topWidgets.js',*/
/*                     '{{ settings.templates.rootDir }}/app/dashboard/controllers/followups.js',*/
/*                     '{{ settings.templates.rootDir }}/app/dashboard/controllers/resources.js',*/
/*                     '{{ settings.templates.rootDir }}/app/dashboard/controllers/history.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     */
/*     ////////////////////*/
/*     // CAMPAIGNS*/
/*     ////////////////////*/
/*     {# CAMPAIGNS   - abstract #}*/
/*     .state("campaigns", {*/
/*         url: "/campaigns",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/campaigns/abstractTpl.html',*/
/*         name: 'campaigns',*/
/*         redirect: 'campaigns.list',*/
/*         abstract: true,*/
/*         data: {*/
/*             pageTitle: 'Campaigns',*/
/*             proxy: 'campaigns',*/
/*             navid:     'navigation-campaigns',*/
/*         },*/
/*         resolve: {*/
/*             parentDeps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'campaigns',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/campaigns/abstractCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# CAMPAIGNS > List/default state #}*/
/*     .state("campaigns.list", {*/
/*         url: "/list",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/campaigns/list/listTpl.html',*/
/*         name: 'campaigns.list',*/
/*         data: {*/
/*             pageTitle: 'List',*/
/*             navid:     'navigation-campaigns',*/
/*         },*/
/*         controller: 'campaignsListController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.campaigns') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.campaigns'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'campaigns.list',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/campaigns/list/listCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# CAMPAIGNS > Create #}*/
/*     .state("campaigns.add", {*/
/*         url: "/add",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/campaigns/add/addTpl.html',*/
/*         name: 'campaigns.add',*/
/*         data: {*/
/*             pageTitle: 'Add Campaign',*/
/*             navid:     'navigation-campaigns',*/
/*         },*/
/*         controller: 'campaignsAddController',*/
/*         resolve: {*/
/* */
/*               // ACL - permit only full admins*/
/*               isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                   // check access*/
/*                   if( AclService.can('settings.campaigns') ) {*/
/*                       return true;*/
/*                   }*/
/* */
/*                   $rootScope.$broadcast('AclNoAccess', {rule: 'settings.campaigns'});*/
/*                   return $q.reject('Unauthorized');*/
/*               }],*/
/* */
/* */
/*               deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'campaigns.list',*/
/*                       files: ['{{ settings.templates.rootDir }}/app/campaigns/add/addCtrl.js']*/
/*                   });*/
/*               }],*/
/*         }*/
/*     })*/
/*     {# CAMPAIGNS > Edit #}*/
/*     .state("campaigns.edit", {*/
/*         url: "/edit/{id:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/campaigns/edit/editTpl.html',*/
/*         name: 'campaigns.edit',*/
/*         data: {*/
/*             pageTitle: 'Campaign #{{ '{{ campaignID }}' }}',*/
/*             pageTitleTemplate: 'Campaign #{{ '{{ campaignID }}' }}',*/
/*             proxy: 'campaigns.list',*/
/*             navid:     'navigation-campaigns',*/
/*         },*/
/*         controller: 'campaignsEditController',*/
/*         resolve: {*/
/* */
/*               // ACL - permit only full admins*/
/*               isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                   // check access*/
/*                   if( AclService.can('settings.campaigns') ) {*/
/*                       return true;*/
/*                   }*/
/* */
/*                   $rootScope.$broadcast('AclNoAccess', {rule: 'settings.campaigns'});*/
/*                   return $q.reject('Unauthorized');*/
/*               }],*/
/* */
/*               // update title, tricky, but works :) version for breadcrumbs*/
/*               campaignID: function($stateParams) {*/
/*                   return $stateParams.id;*/
/*               },*/
/* */
/*               deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'campaigns.list',*/
/*                       files: ['{{ settings.templates.rootDir }}/app/campaigns/edit/editTpl.js']*/
/*                   });*/
/*               }],*/
/*         }*/
/*     })*/
/* */
/*     ////////////////////*/
/*     // UTILS - abstract*/
/*     ////////////////////*/
/*     {# UTILS - abstract #}*/
/*     .state("utils", {*/
/*         url: "/utils",*/
/*         // Note: abstract still needs a ui-view for its children to populate.*/
/*         template: '<ui-view/>',*/
/*         name: 'utils',*/
/*         redirect: 'utils.statistics',*/
/*         abstract: true, // to activate child states*/
/*         data: {*/
/*             pageTitle: 'Utilities',*/
/*             proxy:     'utils',*/
/*             navid:     'navigation-utils',*/
/*         }*/
/*     })*/
/*     {# UTILS > Statistics /default state #}*/
/*     .state("utils.statistics", {*/
/*         url: "/statistics",*/
/*         name: 'utils.statistics',*/
/*         data: {*/
/*             pageTitle:          'Statistics',*/
/*             navid:              'navigation-utils-statistics',*/
/*         },*/
/*         views: {*/
/*             '': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/utils/statistics/templates/_main.html',*/
/* //                template: '<h1>main</h1><div ui-view="topWidgets"></div><div ui-view="followups"></div>'*/
/*                 // we couls use url, but to obtain that simple html ? stsly?*/
/*                 // when add new state views remember to add it here in correct order*/
/*                 controller: 'statisticsController'*/
/*             },*/
/* */
/*             'perStatus@utils.statistics': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/utils/statistics/templates/perStatus.html',*/
/*                 controller: 'perStatusStatisticsController'*/
/*             },*/
/*             'lastTen@utils.statistics': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/utils/statistics/templates/lastTen.html',*/
/*                 controller: 'lastTenStatisticsController'*/
/*             },*/
/*             'totalPerAdmin@utils.statistics': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/utils/statistics/templates/totalPerAdmin.html',*/
/*                 controller: 'totalPerAdminStatisticsController'*/
/*             },*/
/*             'newPerMonth@utils.statistics': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/utils/statistics/templates/newPerMonth.html',*/
/*                 controller: 'newPerMonthStatisticsController'*/
/*             },*/
/*             'newPerDay@utils.statistics': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/utils/statistics/templates/newPerDay.html',*/
/*                 controller: 'newPerDayStatisticsController'*/
/*             },*/
/*         },*/
/*         resolve: {*/
/*             */
/*             // chart.js dependiences, since charts are used only here*/
/*             loadChartJsLib: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     '{{ settings.templates.rootDir }}/assets/plugins/Chart.js/Chart.min.js',*/
/*                     '{{ settings.templates.rootDir }}/assets/plugins/d3pie/d3pie.min.js',*/
/*                     '{{ settings.templates.rootDir }}/assets/plugins/d3/d3.min.js'*/
/*                 ]);*/
/*             }],*/
/*         */
/*             deps: ['$ocLazyLoad', 'loadChartJsLib', function($ocLazyLoad, loadChartJsLib) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // load main controller*/
/*                     '{{ settings.templates.rootDir }}/app/utils/statistics/controllers/_main.js',*/
/*                     '{{ settings.templates.rootDir }}/app/utils/statistics/controllers/perStatus.js',*/
/*                     '{{ settings.templates.rootDir }}/app/utils/statistics/controllers/lastTen.js',*/
/*                     '{{ settings.templates.rootDir }}/app/utils/statistics/controllers/totalPerAdmin.js',*/
/*                     '{{ settings.templates.rootDir }}/app/utils/statistics/controllers/newPerMonth.js',*/
/*                     '{{ settings.templates.rootDir }}/app/utils/statistics/controllers/newPerDay.js',*/
/* */
/*                     // chart.js dependiences, since charts are used only here*/
/*                     '{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-chart/dist/angular-chart.min.css',*/
/*                     '{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-chart/dist/angular-chart.min.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     // NOTIFICATIONS*/
/*     {# NOTIFICATIONS   - abstract #}*/
/*     .state("utils.notifications", {*/
/*         url: "/notifications",*/
/*         template: '<ui-view/>',*/
/*         name: 'utils.notifications',*/
/*         redirect: 'utils.notifications.list',*/
/*         abstract: true,*/
/*         data: {*/
/*             pageTitle: 'Notifications',*/
/*             proxy:     'utils.notifications',*/
/*             navid:     'navigation-utils-notifications',*/
/*         },*/
/*     })*/
/*     {# NOTIFICATIONS > List/default state #}*/
/*     .state("utils.notifications.list", {*/
/*         url: "/list",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/utils/notifications/list/listTpl.html',*/
/*         name: 'utils.notifications.list',*/
/*         data: {*/
/*             pageTitle: 'List',*/
/*             navid:     'navigation-utils-notifications',*/
/*         },*/
/*         controller: 'notificationsListController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.notifications') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'utils.notifications'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'campaigns.list',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/utils/notifications/list/listCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# NOTIFICATIONS > Create state #}*/
/*     .state("utils.notifications.add", {*/
/*         url: "/add",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/utils/notifications/add/addTpl.html',*/
/*         name: 'utils.notifications.add',*/
/*         data: {*/
/*             pageTitle: 'Add Notification',*/
/*             navid:     'navigation-utils-notifications',*/
/*         },*/
/*         controller: 'notificationsAddController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.notifications') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.notifications'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'campaigns.list',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/utils/notifications/add/addCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# NOTIFICATIONS > Create state #}*/
/*     .state("utils.notifications.edit", {*/
/*         url: "/edit/{id:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/utils/notifications/edit/editTpl.html',*/
/*         name: 'utils.notifications.edit',*/
/*         data: {*/
/*             pageTitle: 'Notification #{{ '{{ notificationID }}' }}',*/
/*             pageTitleTemplate: 'Notification #{{ '{{ notificationID }}' }}',*/
/*             proxy: 'campaigns.list',*/
/*             navid:     'navigation-utils-notifications',*/
/*         },*/
/*         controller: 'notificationsEditController',*/
/*         resolve: {*/
/* */
/*               // ACL - permit only full admins*/
/*               isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                   // check access*/
/*                   if( AclService.can('settings.notifications') ) {*/
/*                       return true;*/
/*                   }*/
/* */
/*                   $rootScope.$broadcast('AclNoAccess', {rule: 'settings.notifications'});*/
/*                   return $q.reject('Unauthorized');*/
/*               }],*/
/* */
/*               // update title, tricky, but works :) version for breadcrumbs*/
/*               notificationID: function($stateParams) {*/
/*                   return $stateParams.id;*/
/*               },*/
/* */
/*               deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                   // required controllers*/
/*                   return $ocLazyLoad.load({*/
/*                       name: 'campaigns.list',*/
/*                       files: ['{{ settings.templates.rootDir }}/app/utils/notifications/edit/editTpl.js']*/
/*                   });*/
/*               }],*/
/*         }*/
/*     })*/
/*     // MASS MAIL*/
/*     {# MASS MAIL   - abstract #}*/
/*     .state("utils.massmessage", {*/
/*         url: "/massmessage",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/utils/massmessage/abstractTpl.html',*/
/*         name: 'utils.massmessage',*/
/*         redirect: 'utils.massmessage.add',*/
/*         abstract: true,*/
/*         data: {*/
/*             proxy:      'utils.massmessage.list',*/
/*             navid:      'navigation-utils-massmessage',*/
/*         },*/
/*         resolve: {*/
/*             parentDeps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'massmessage',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/utils/massmessage/abstractCtrl.js']*/
/*                 });*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# MASS MAIL > List/default state #}*/
/*     .state("utils.massmessage.list", {*/
/*         url: "/list",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/utils/massmessage/list/listTpl.html',*/
/*         name: 'utils.massmessage.list',*/
/*         data: {*/
/*             pageTitle:          '{{ '{{ breadcrumbsTitle }}' }}',*/
/*             pageTitleTemplate:  '{{ '{{ breadcrumbsTitle }}' }}',*/
/*             navid:              'navigation-utils-massmessage',*/
/*         },*/
/*         controller: 'massmessageListController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_massmessage') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_massmessage'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'massmail.list',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/utils/massmessage/list/listCtrl.js']*/
/*                 });*/
/*             }],*/
/*         */
/*             breadcrumbsTitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.massmessage.list').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# MASS MAIL > Create state #}*/
/*     .state("utils.massmessage.add", {*/
/*         url: "/add",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/utils/massmessage/add/addTpl.html',*/
/*         name: 'utils.massmessage.add',*/
/*         data: {*/
/*             pageTitle:          '{{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             pageTitleTemplate:  '{{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:      'navigation-utils-massmessage',*/
/*         },*/
/*         controller: 'massmessageAddController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_massmessage') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_massmessage'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/* */
/*             depsTinymce: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'tinymce.lib',*/
/*                     files: ['{{ settings.templates.rootDir }}/assets/plugins/tinymce/tinymce.min.js']*/
/*                 });*/
/*             }],*/
/* */
/*             depsTinymceIntegration: ['$ocLazyLoad', 'depsTinymce', function($ocLazyLoad, depsTinymce) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'ui.tinymce',*/
/*                     files: ['{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']*/
/*                 });*/
/*             }],*/
/*         */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'massmessage.add',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/utils/massmessage/add/addCtrl.js']*/
/*                 });*/
/*             }],*/
/*           */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.massmessage.add').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         */
/*             breadcrumbsTitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.massmessage.list').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# MASS MAIL > Edit state #}*/
/*     .state("utils.massmessage.edit", {*/
/*         url: "/edit/{id:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/utils/massmessage/edit/editTpl.html',*/
/*         name: 'utils.massmessage.edit',*/
/*         data: {*/
/*             pageTitle: '{{ '{{ breadcrumbsSubtitle }}' }} #{{ '{{ massmessageID }}' }}',*/
/*             pageTitleTemplate: '{{ '{{ breadcrumbsSubtitle }}' }} #{{ '{{ massmessageID }}' }}',*/
/*             navid:     'navigation-massmessage',*/
/*         },*/
/*         controller: 'massmessageEditController',*/
/*         resolve: {*/
/* */
/*             // ACL - permit only full admins*/
/*             isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                 // check access*/
/*                 if( AclService.can('settings.manage_massmessage') ) {*/
/*                     return true;*/
/*                 }*/
/* */
/*                 $rootScope.$broadcast('AclNoAccess', {rule: 'settings.manage_massmessage'});*/
/*                 return $q.reject('Unauthorized');*/
/*             }],*/
/*         */
/*             // update title, tricky, but works :) version for breadcrumbs*/
/*             massmessageID: function($stateParams) {*/
/*                 return $stateParams.id;*/
/*             },*/
/* */
/*             depsTinymceIntegration: ['$ocLazyLoad', 'depsTinymce', function($ocLazyLoad, depsTinymce) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'ui.tinymce',*/
/*                     files: ['{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-ui-tinymce/src/tinymce.js']*/
/*                 });*/
/*             }],*/
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'massmessage.edit',*/
/*                     files: ['{{ settings.templates.rootDir }}/app/utils/massmessage/edit/editTpl.js']*/
/*                 });*/
/*             }],*/
/*         */
/*             depsTinymce: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 // required controllers*/
/*                 return $ocLazyLoad.load({*/
/*                     name: 'tinymce.lib',*/
/*                     files: ['{{ settings.templates.rootDir }}/assets/plugins/tinymce/tinymce.min.js']*/
/*                 });*/
/*             }],*/
/* */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.massmessage.edit').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         */
/*             breadcrumbsTitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.massmessage.list').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     ////////////////////*/
/*     // CONTACTS HAPPEN - Build Contacts Awesome routing*/
/*     ////////////////////*/
/*     {# CONTACTS - abstract state #}*/
/*     .state("contacts", {*/
/*         url: '/contacts',*/
/*         template: '<ui-view/>',*/
/*         name: 'contacts',*/
/*         abstract: true,*/
/*         skipBreadcrumbsUrl: true,*/
/*         redirect: 'contacts.list',*/
/*         params: {*/
/*             contactTypeID: null,*/
/*         },*/
/*         data: {*/
/*             pageTitle: 'Contacts',*/
/*             proxy:     'contacts',*/
/*             navid:     'navigation-campaigns',*/
/*         },*/
/*         resolve: {*/
/* */
/*             // I know, but damm it*/
/*             dynamicType: ['$stateParams', 'ContactTypes', function($stateParams, ContactTypes) {*/
/*                 var type = ContactTypes.getById($stateParams.contactTypeID);*/
/* */
/*                 if(type !== null && type.name !== 'undefined') {*/
/*                     return type;*/
/*                 }*/
/* */
/*                 all = ContactTypes.get();*/
/* */
/*                 // set up correct initial contact type for table*/
/*                 for(i=0; i < all.length; i++) {*/
/*                     if(all[i].active === true) {*/
/*                         return all[i];*/
/*                     }*/
/*                 }*/
/*                 */
/*                 */
/*                 return {};*/
/*             }],*/
/*           */
/*             cachedListData: ['$http', '$rootScope', function($http, $rootScope) {*/
/*                 // Perform actions on initialize these controller*/
/*                 return $http.get($rootScope.settings.config.apiURL + '/helpers/resources/table/json', {cache: true});*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > List #}*/
/*     .state("contacts.list", {*/
/*         url: "/list",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/list/contactsListTpl.html',*/
/*         name: 'contacts.list',*/
/*         data: {*/
/*             pageTitle: '{{ '{{  dynamicType.name }}' }}',*/
/*             navid: 'contacts-list',*/
/*         },*/
/*         controller: 'contactsListCtrl',*/
/*         resolve: {*/
/* */
/*             // since there are few states that load exactly the same files/controllers*/
/*             // configure here config for that controller*/
/*             statesConfig: function() {*/
/*                 return {*/
/*                     IsItArchive: false,*/
/*                 };*/
/*             },*/
/* */
/*             tableColumns: ['$http', '$rootScope', function($http, $rootScope) {*/
/*                 return $http.get($rootScope.settings.config.apiURL + '/settings/fields/views/for/lists.leads/json');*/
/*             }],*/
/* */
/* */
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/list/contactsListCtrl.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > Archive #}*/
/*     .state("contacts.archive", {*/
/*         url: "/archive",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/list/contactsListTpl.html',*/
/*         name: 'contacts.archive',*/
/*         data: {*/
/*             pageTitle: 'Archive',*/
/*             navid: 'navigation-utils-archive',*/
/*         },*/
/*         controller: 'contactsListCtrl',*/
/*         resolve: {*/
/* */
/*             // since there are few states that load exactly the same files/controllers*/
/*             // configure here config for that controller*/
/*             statesConfig: function() {*/
/*                 return {*/
/*                     IsItArchive: true,*/
/*                 };*/
/*             },*/
/* */
/*             tableColumns: ['$http', '$rootScope', function($http, $rootScope) {*/
/*                 return $http.get($rootScope.settings.config.apiURL + '/settings/fields/views/for/lists.leads/json');*/
/*             }],*/
/* */
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/list/contactsListCtrl.js',*/
/*                 ]);*/
/*             }],*/
/*         }*/
/*     })*/
/*     */
/*     {# CONTACTS > Create Form #}*/
/*     .state("contacts.create", {*/
/*           url: "/create",*/
/*           templateUrl: '{{ settings.templates.rootDir }}/app/contacts/create/templates/createForm.html',*/
/*           name: 'contacts.create',*/
/*           data: {*/
/*                 pageTitle: 'Create',*/
/*                 navid: 'navigation-createlead',*/
/*           },*/
/*           controller: 'leadsCreateController',*/
/*           resolve: {*/
/*                 */
/*                 // ACL - permit only full admins*/
/*                 isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                     // check access*/
/*                     if( AclService.can('resources.create_lead') ) {*/
/*                         return true;*/
/*                     }*/
/* */
/*                     $rootScope.$broadcast('AclNoAccess', {rule: 'resources.create_lead'});*/
/*                     return $q.reject('Unauthorized');*/
/*                 }],*/
/*         */
/*                 // no additional params bur required to DI*/
/*                 additionalParams: ['$stateParams', function($stateParams) {*/
/*                     return {*/
/*                         ticket_id: false,*/
/*                         quote_id:  false,*/
/*                         client_id: false*/
/*                     };*/
/*                 }],*/
/*             */
/*                 leadsCreateServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                     return $ocLazyLoad.load([*/
/*                         // required services*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadParams.js',*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadService.js',*/
/*                     ]);*/
/*                 }],*/
/*                 */
/*                 deps: ['$ocLazyLoad', 'leadsCreateServices', function($ocLazyLoad, leadsCreateServices) {*/
/*                     return $ocLazyLoad.load([*/
/*                         // required controllers*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/controllers/createLead.js',*/
/*                     ]);*/
/*                 }],*/
/*           }*/
/*     })*/
/*     {# CONTACTS > Create Form From Ticket #}*/
/*     .state("contacts.createFromTicket", {*/
/*           url: "/create/ticket/{id:int}",*/
/*           templateUrl: '{{ settings.templates.rootDir }}/app/contacts/create/templates/createForm.html',*/
/*           name: 'contacts.createFromTicket',*/
/*           data: {*/
/*                 pageTitle: 'Create Lead',*/
/*                 navid: 'navigation-createlead',*/
/*           },*/
/*           controller: 'leadsCreateController',*/
/*           resolve: {*/
/*                 */
/*                 // ACL - permit only full admins*/
/*                 isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                     // check access*/
/*                     if( AclService.can('resources.create_lead') ) {*/
/*                         return true;*/
/*                     }*/
/* */
/*                     $rootScope.$broadcast('AclNoAccess', {rule: 'resources.create_lead'});*/
/*                     return $q.reject('Unauthorized');*/
/*                 }],*/
/*             */
/*                 additionalParams: ['$stateParams', function($stateParams) {*/
/*                     return {*/
/*                         ticket_id: $stateParams.id,*/
/*                         quote_id:  false,*/
/*                         client_id: false*/
/*                     };*/
/*                 }],*/
/*             */
/*                 leadsCreateServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                     return $ocLazyLoad.load([*/
/*                         // required services*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadParams.js',*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadService.js',*/
/*                     ]);*/
/*                 }],*/
/*             */
/*                 deps: ['$ocLazyLoad', 'leadsCreateServices', function($ocLazyLoad, leadsCreateServices) {*/
/*                     return $ocLazyLoad.load([*/
/*                           // required controllers*/
/*                           '{{ settings.templates.rootDir }}/app/contacts/create/controllers/createLead.js',*/
/*                     ]);*/
/*                 }],*/
/*           }*/
/*     })*/
/*     {# CONTACTS > Create Form From Quote #}*/
/*     .state("contacts.createFromQuote", {*/
/*           url: "/create/quote/{id:int}",*/
/*           templateUrl: '{{ settings.templates.rootDir }}/app/contacts/create/templates/createForm.html',*/
/*           name: 'contacts.createFromQuote',*/
/*           data: {*/
/*                 pageTitle: 'Create Lead',*/
/*                 navid: 'navigation-createlead',*/
/*           },*/
/*           controller: 'leadsCreateController',*/
/*           resolve: {*/
/*                 */
/*                 // ACL - permit only full admins*/
/*                 isAllowed: ['$q', '$rootScope', 'AclService', function($q, $rootScope, AclService) {*/
/* */
/*                     // check access*/
/*                     if( AclService.can('resources.create_lead') ) {*/
/*                         return true;*/
/*                     }*/
/* */
/*                     $rootScope.$broadcast('AclNoAccess', {rule: 'resources.create_lead'});*/
/*                     return $q.reject('Unauthorized');*/
/*                 }],*/
/*             */
/*                 additionalParams: ['$stateParams', function($stateParams) {*/
/*                     return {*/
/*                         ticket_id: false,*/
/*                         quote_id:  $stateParams.id,*/
/*                         client_id: false*/
/*                     };*/
/*                 }],*/
/*             */
/*                 leadsCreateServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                     return $ocLazyLoad.load([*/
/*                         // required services*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadParams.js',*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadService.js',*/
/*                     ]);*/
/*                 }],*/
/*             */
/*                 deps: ['$ocLazyLoad', 'leadsCreateServices', function($ocLazyLoad, leadsCreateServices) {*/
/*                     return $ocLazyLoad.load([*/
/*                         // required controllers*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/controllers/createLead.js',*/
/*                     ]);*/
/*                 }],*/
/*           }*/
/*     })*/
/*     {# CONTACTS > Create Form For Client #}*/
/*     .state("contacts.createFromClient", {*/
/*           url: "/create/client/{id:int}",*/
/*           templateUrl: '{{ settings.templates.rootDir }}/app/contacts/create/templates/createForm.html',*/
/*           name: 'contacts.createFromClient',*/
/*           data: {*/
/*                 pageTitle: 'Create Lead',*/
/*                 navid: 'navigation-createlead',*/
/*           },*/
/*           controller: 'leadsCreateController',*/
/*           resolve: {*/
/*                 */
/*                 additionalParams: ['$stateParams', function($stateParams) {*/
/*                     return {*/
/*                         ticket_id: false,*/
/*                         quote_id:  false,*/
/*                         client_id: $stateParams.id*/
/*                     };*/
/*                 }],*/
/*             */
/*                 leadsCreateServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                     return $ocLazyLoad.load([*/
/*                         // required services*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadParams.js',*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/services/createLeadService.js',*/
/*                     ]);*/
/*                 }],*/
/*             */
/*                 deps: ['$ocLazyLoad', 'leadsCreateServices', function($ocLazyLoad, leadsCreateServices) {*/
/*                     return $ocLazyLoad.load([*/
/*                         // required controllers*/
/*                         '{{ settings.templates.rootDir }}/app/contacts/create/controllers/createLead.js',*/
/*                     ]);*/
/*                 }],*/
/*           }*/
/*     })*/
/*     */
/*     {# CONTACTS > Details - abstract state #}*/
/*     .state("contacts.details", {*/
/*         url: "/{id:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/detailsHeaderTpl.html',*/
/*         name: 'contacts.details',*/
/*         controller: 'detailsHeaderCtrl',*/
/*         abstract: true,*/
/*         data: {*/
/*             proxy: 'contacts.list',*/
/*             navid: 'contacts-list',*/
/*         },*/
/*         resolve: {*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/detailsHeaderCtrl.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             // A function value promises resolved data to controller and child statuses*/
/*             leadMainDetailsData: ['$stateParams', '$resource', '$rootScope', function($stateParams, $resource, $rootScope) {*/
/* */
/*                 // Return a promise to makle sure the customer is completely*/
/*                 // resolved before the controller is instantiated*/
/*                 return $resource($rootScope.settings.config.apiURL + '/lead/:id/getMainDetails/json', {id:'@id'}).get({id: $stateParams.id}).$promise;*/
/*             }],*/
/*         */
/*             // I know, but damm it - this approach works for breadcrumbs so its FINE*/
/*             dynamicType: ['leadMainDetailsData', 'ContactTypes', function(leadMainDetailsData, ContactTypes) {*/
/*                 var type = ContactTypes.getById(leadMainDetailsData.type_id);*/
/* */
/*                 if(type !== null && type.name !== 'undefined') {*/
/*                     return type;*/
/*                 }*/
/* */
/*                 all = ContactTypes.get();*/
/*                 return all[0];*/
/*             }],*/
/*         */
/*           }*/
/*       })*/
/*     {# CONTACTS > SUMMARY #}*/
/*     .state("contacts.details.summary", {*/
/*         url: "/summary",*/
/*         name: 'contacts.details.summary',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}', */
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         views: {*/
/*             '': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/summary/designTpl.html',*/
/*                 controller: 'detailsSummaryMainCtrl'*/
/*             },*/
/* */
/*             'customFields@contacts.details.summary': {*/
/*                 templateProvider: ['$templateFactory', 'leadMainDetailsData', function ($templateFactory, leadMainDetailsData) {*/
/*                     if(leadMainDetailsData.deleted_at) {*/
/*                         return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/archive/customFields.html');*/
/*                     }*/
/*                     return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/customFields.html');*/
/*                 }],*/
/*                 controller: 'detailsSummaryCustomFieldsCtrl'*/
/*             },*/
/*             'followups@contacts.details.summary': {*/
/*                 templateProvider: ['$templateFactory', 'leadMainDetailsData', function ($templateFactory, leadMainDetailsData) {*/
/*                     if(leadMainDetailsData.deleted_at) {*/
/*                         return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/archive/followups.html');*/
/*                     }*/
/*                     return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/followups.html');*/
/*                 }],*/
/*                 controller: 'detailsSummaryFollowupsCtrl'*/
/*             },*/
/*             'mainDetails@contacts.details.summary': {*/
/*                 templateProvider: ['$templateFactory', 'leadMainDetailsData', function ($templateFactory, leadMainDetailsData) {*/
/*                     if(leadMainDetailsData.deleted_at) {*/
/*                         return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/archive/mainDetails.html');*/
/*                     }*/
/*                     return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/mainDetails.html');*/
/*                 }],*/
/*                 controller: 'detailsSummaryMainDetailsCtrl'*/
/*             },*/
/*             'notes@contacts.details.summary': {*/
/*                 templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/notes.html',*/
/*                 controller: 'detailsSummaryNotesCtrl'*/
/*             },*/
/*             'quickActionsTab@contacts.details.summary': {*/
/*                 templateProvider: ['$templateFactory', 'leadMainDetailsData', function ($templateFactory, leadMainDetailsData) {*/
/*                     if(leadMainDetailsData.deleted_at) {*/
/*                         return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/archive/quickActionsTab.html');*/
/*                     }*/
/*                     return $templateFactory.fromUrl('{{ settings.templates.rootDir }}/app/contacts/details/summary/templates/quickActionsTab.html');*/
/*                 }],*/
/*                 controller: 'detailsSummaryQuickActionTabsCtrl'*/
/*             },*/
/*         },*/
/*         resolve: {*/
/* */
/*             leadSummaryServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                     return $ocLazyLoad.load([*/
/*                     // services*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/notes/services/notesService.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             controllersResolve: ['$ocLazyLoad', 'leadSummaryServices', function($ocLazyLoad, leadSummaryServices) {                */
/*                 return $ocLazyLoad.load([*/
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/summary/designTpl.js',*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/summary/controllers/customFields.js',*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/summary/controllers/followups.js',*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/summary/controllers/mainDetails.js',*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/summary/controllers/notes.js',*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/summary/controllers/quickActionsTab.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.summary').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > Follow-ups #}*/
/*     .state("contacts.details.followups", {*/
/*         url: "/followups",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/followups/templates/followups.html',*/
/*         name: 'contacts.details.followups',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         controller: 'leadFollowupController',*/
/*         resolve: {*/
/* */
/*             leadFollowupServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // services*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/followups/services/followupService.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'leadFollowupServices', function($ocLazyLoad, leadFollowupServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/followups/controllers/leadFollowupController.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                     // {{ '{{ breadcrumbsSubtitle }}' }}*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.followups').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > Follow-ups > Edit #}*/
/*     .state("contacts.details.followup", {*/
/*         url: "/followup/{followupID:int}",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/followups/templates/edit.html',*/
/*         name: 'contacts.details.followup',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }} #{{ '{{ followupID }}' }}',*/
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }} #{{ '{{ followupID }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         controller: 'leadEditFollowupController',*/
/*         resolve: {*/
/* */
/*             leadEditFollowupServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // services*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/followups/services/singleFollowuprderService.js',*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/followups/services/singleReminderService.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'leadEditFollowupServices', function($ocLazyLoad, leadEditFollowupServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/followups/controllers/leadEditFollowup.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             // update title, tricky, but works :) version for breadcrumbs*/
/*             followupID: function($stateParams) {*/
/*                 return $stateParams.followupID;*/
/*             },*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.followup.edit').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > NOTES #}*/
/*     .state("contacts.details.notes", {*/
/*         url: "/notes",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/notes/templates/notes.html',*/
/*         name: 'contacts.details.notes',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         controller: 'leadNotesController',*/
/*         resolve: {*/
/* */
/*             leadNotesServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // services*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/notes/services/notesService.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'leadNotesServices', function($ocLazyLoad, leadNotesServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/notes/controllers/leadNotesController.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.followup.notes').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > Emails #}*/
/*     .state("contacts.details.emails", {*/
/*         url: "/emails",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/emails/templates/emails.html',*/
/*         name: 'contacts.details.emails',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         controller: 'leadEmailsController',*/
/*         resolve: {*/
/* */
/*             leadEmailsServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // services*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/emails/services/emailService.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'leadEmailsServices', function($ocLazyLoad, leadEmailsServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // controller*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/emails/controllers/leadEmailsController.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.followup.emails').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > Orders #}*/
/*     .state("contacts.details.orders", {*/
/*         url: "/orders",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/orders/templates/orders.html',*/
/*         name: 'contacts.details.orders',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         controller: 'leadOrdersController',*/
/*         resolve: {*/
/* */
/*             leadOrdersServices: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // services*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/orders/services/orderService.js',*/
/*                 ]);*/
/*             }],*/
/* */
/*             deps: ['$ocLazyLoad', 'leadOrdersServices', function($ocLazyLoad, leadOrdersServices) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/orders/controllers/leadOrdersController.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.followup.orders').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > Quotes #}*/
/* //    .state("contacts.details.quotes", {*/
/* //        url: "/quotes",*/
/* //        templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/quotes/templates/quotes.html',*/
/* //        name: 'contacts.details.quotes',*/
/* //        data: {*/
/* //            pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/* //            pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/* //            navid:             'contacts-list',*/
/* //        },*/
/* //        controller: 'leadQuotesController',*/
/* //        resolve: {*/
/* //*/
/* //            // You can disable quotes in global settings*/
/* //            isEnabled: ['$q', '$rootScope', '$state', function($q, $rootScope, $state) {*/
/* //*/
/* //                if( $rootScope.settings.config.app.quotations_enable == true ) {*/
/* //                    return true;*/
/* //                }*/
/* //*/
/* //                // Does not have permission*/
/* //                $rootScope.$broadcast('AclNoAccess', {msg: 'Quotations integration is disabled'});*/
/* //                return $q.reject('Unauthorized');*/
/* //            }],*/
/* //*/
/* //            deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/* //                return $ocLazyLoad.load([*/
/* //                    '{{ settings.templates.rootDir }}/app/contacts/details/quotes/controllers/leadQuotesController.js',*/
/* //                ]);*/
/* //            }],*/
/* //        */
/* //            breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/* //                var deferred = $q.defer();*/
/* //                $translate('breadcrumbs.followup.quotes').then(function (txt) {*/
/* //                    deferred.resolve(txt);*/
/* //                });*/
/* //                return deferred.promise; */
/* //            }],*/
/* //*/
/* //        }*/
/* //    })*/
/*     {# CONTACTS > Fields #}*/
/*     .state("contacts.details.files", {*/
/*         url: "/files",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/files/templates/files.html',*/
/*         name: 'contacts.details.files',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         controller: 'leadFilesController',*/
/*         resolve: {*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     // controllers*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/files/controllers/leadFilesController.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.followup.files').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
/*     {# CONTACTS > Logs #}*/
/*     .state("contacts.details.logs", {*/
/*         url: "/logs",*/
/*         templateUrl: '{{ settings.templates.rootDir }}/app/contacts/details/logs/templates/logs.html',*/
/*         name: 'contacts.details.logs',*/
/*         data: {*/
/*             pageTitle:         '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             pageTitleTemplate: '#{{ '{{ leadMainDetailsData.id }}' }} {{ '{{ leadMainDetailsData.name }}' }} / {{ '{{ breadcrumbsSubtitle }}' }}',*/
/*             navid:             'contacts-list',*/
/*         },*/
/*         controller: 'leadLogsController',*/
/*         resolve: {*/
/* */
/*             deps: ['$ocLazyLoad', function($ocLazyLoad) {*/
/*                 return $ocLazyLoad.load([*/
/*                     '{{ settings.templates.rootDir }}/app/contacts/details/logs/controllers/leadLogsController.js',*/
/*                 ]);*/
/*             }],*/
/*         */
/*             breadcrumbsSubtitle: ['$translate', '$q', function($translate, $q) {*/
/*                 var deferred = $q.defer();*/
/*                 $translate('breadcrumbs.followup.logs').then(function (txt) {*/
/*                     deferred.resolve(txt);*/
/*                 });*/
/*                 return deferred.promise; */
/*             }],*/
/*         }*/
/*     })*/
