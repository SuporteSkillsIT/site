<?php

/* app/app.angular.js.twig */
class __TwigTemplate_e134e90df98e29d8a6df0d27e7aa208506ec9b165f9496abd519fe7c095ebae6 extends Twig_Template
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
        echo "<script type=\"text/javascript\">


    /**
     * Polyfill for IE8
     *
     * http://stackoverflow.com/a/1181586
     */
    if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (needle) {
    var l = this.length;
            for (; l--; ) {
    if (this[l] === needle) {
    return l;
    }
    }
    return - 1;
    };
    };
// remove from array
            if (!Array.prototype.remove) {
    Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
    what = a[--L];
            while ((ax = this.indexOf(what)) !== - 1) {
    this.splice(ax, 1);
    }
    }
    return this;
    };
    };
// insert in array at certain index
            if (!Array.prototype.insert) {
    Array.prototype.insert = function (index, item) {
    this.splice(index, 0, item);
    };
    };
            /**
             * Angular App
             * With main dependiences for angular
             */

            var mgCRMapp = angular.module(\"mgCRMapp\", [
                    \"ui.router\", // obliviously 
                    \"oc.lazyLoad\", // dymanic load files
                    \"pascalprecht.translate\", // translation library
                    \"ngResource\", // manage with data objects and DI
                    \"ui.bootstrap\",
                    \"ui.bootstrap.datetimepicker\",
                    \"ngDialog\",
                    //\"mwl.calendar\",
                    \"xeditable\",
                    \"blockUI\",
                    \"ngSanitize\",
                    \"ui.sortable\",
                    \"ui.select\",
                    \"smart-table\",
                    \"textAngular\",
                    // App lever modules
                    \"CRM\",
            ]);
/////////////////////////////////////////////
// PROVIDER
// 
// This handle things related to dynamic contact types
// and provide nesesary operations for routing (since provider can be accesed here)
/////////////////////////////////////////////
            mgCRMapp.provider('ContactTypes', [ function ()
            {
            // keep here all possible contact types
            var types = ";
        // line 72
        echo twig_jsonencode_filter($this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "dynamicTypes", array()), "routing", array()));
        echo ";
                    /**
                     * Get single role by ID
                     */
                    var getById = function(id)
                    {
                    for (i = 0; i < types.length; i++) {
                    if (types[i].id == id) {
                    return types[i];
                    }
                    }

                    return null;
                    };
                    var ContactTypes = {};
                    ContactTypes.getById = getById;
                    /**
                     * Return me all types
                     *
                     * @param array
                     */
                    ContactTypes.get = function() {
                    return types;
                    };
                    return {
                    all: function() {
                    return types;
                    },
                            getById: getById,
                            \$get: function () {
                            return ContactTypes;
                            }
                    };
            }]);
            /** 
             * Setup global settings 
             */
            mgCRMapp.factory('settings', ['\$rootScope', function(\$rootScope) {

            // set config
            mgCRM.setConfig(";
        // line 112
        echo twig_jsonencode_filter($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()));
        echo ");
                    // global scope settings
                    var settings =
            {
            frontend:
            {
            pageAutoScrollOnLoad: 1000, // auto scroll to top on page load
                    dismissNotesAfter:    8000, // ms after when dismiss note
            },
                    config: mgCRM.getConfig(),
            };
                    // assign this settings for global scoope
                    \$rootScope.settings = settings;
                    // console.log(acl);
                    var acl = {
                    currentAdmin:";
        // line 127
        echo twig_jsonencode_filter($this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "getCurrentAdminRulesFlat", array(), "method"));
        echo ", // currently logged admin all defined rules with flags allowed/not (as bolean)
                            parsedRules:";
        // line 128
        echo twig_jsonencode_filter($this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "getRules", array(), "method"));
        echo ", // all possible rules
                            flattenRules:";
        // line 129
        echo twig_jsonencode_filter($this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "getRulesFlat", array(), "method"));
        echo ", // all possible rules
                            rulesConfig:";
        // line 130
        echo twig_jsonencode_filter($this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "getRulesConfig", array(), "method"));
        echo ", // copnfig rules
                            isFullAdmin:";
        // line 131
        echo twig_jsonencode_filter($this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "isFullAdmin", array(), "method"));
        echo ", // if full admin
                    };
                    // assing acl settings for global scope
                    \$rootScope.acl = acl;
                    \$rootScope.currentAdminID = ";
        // line 135
        echo twig_escape_filter($this->env, (isset($context["currentAdmin"]) ? $context["currentAdmin"] : null), "html", null, true);
        echo ";
                    return settings;
            }]);
//Http Intercpetor to check auth failures for xhr requests
            mgCRMapp.config(['\$httpProvider', function(\$httpProvider) {
            \$httpProvider.interceptors.push('httpResponseInterceptor');
                    //initialize get if not there
                    if (!\$httpProvider.defaults.headers.get) {
            \$httpProvider.defaults.headers.get = {};
            }

            // Answer edited to include suggestions from comments
            // because previous version of code introduced browser-related errors

            //disable IE ajax request caching
            \$httpProvider.defaults.headers.get['If-Modified-Since'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
                    \$httpProvider.defaults.headers.common[\"If-Modified-Since\"] = \"0\";
                    // extra
                    \$httpProvider.defaults.headers.get['Cache-Control'] = 'no-cache';
                    \$httpProvider.defaults.headers.common[\"Cache-Control\"] = \"no-cache\";
                    \$httpProvider.defaults.headers.get['Pragma'] = 'no-cache';
                    \$httpProvider.defaults.headers.common.Pragma = \"no-cache\";
            }]);
            /**
             *  rutes configs
             */
            mgCRMapp.config([
                    '\$stateProvider', '\$urlRouterProvider', '\$locationProvider',
                    function(\$stateProvider, \$urlRouterProvider, \$locationProvider) {

                    // basic providers
                    \$locationProvider.html5Mode(false);
                            \$locationProvider.hashPrefix('!');
                            // load all states and routes for it from other file to not mess here
    ";
        // line 169
        $this->loadTemplate("app/app.routes.js", "app/app.angular.js.twig", 169)->display($context);
        // line 170
        echo "                        }]);
                /**
                 * 
                 *  Setup Rounting For All Pages 
                 */
                mgCRMapp.config([
                        '\$stateProvider', '\$urlRouterProvider', '\$locationProvider', 'blockUIConfig', 'uiSelectConfig', '\$provide',
                        function(\$stateProvider, \$urlRouterProvider, \$locationProvider, blockUIConfig, uiSelectConfig, \$provide) {

                        // configure block ui module
                        blockUIConfig.message = '<img src=\"";
        // line 180
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/img/mg-loader.gif\" />';
                                blockUIConfig.delay = 100;
                                blockUIConfig.template = \"<div class=\\\"block-ui-overlay\\\"></div><div class=\\\"block-ui-message-container\\\" aria-live=\\\"assertive\\\" aria-atomic=\\\"true\\\"><div class=\\\"block-ui-message\\\" ng-class=\\\"\$_blockUiMessageClass\\\" ng-bind-html=\\\"state.message\\\"></div></div>\";
                                blockUIConfig.autoBlock = false;
                                // ui-select theme
                                uiSelectConfig.theme = 'bootstrap';
                                // this demonstrates how to register a new tool and add it to the default toolbar
                                \$provide.decorator('taOptions', ['taRegisterTool', '\$delegate', function(taRegisterTool, taOptions) { // \$delegate is the taOptions we are decorating
                                taOptions.toolbar = [
                                        ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'quote'],
                                        ['bold', 'italics', 'underline', 'strikeThrough', 'ul', 'ol', 'redo', 'undo', 'clear'],
                                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'indent', 'outdent'],
                                        ['html', 'insertImage', 'insertLink']
                                ];
                                        return taOptions;
                                }]);
                        }]);
// 
                /**
                 * register the http interceptor as a service
                 * basically it is a wrapper to hangle ajax queries :D
                 * 
                 * @docs https://code.angularjs.org/1.3.16/docs/api/ng/service/\$http
                 */
                mgCRMapp.factory(
                        'httpResponseInterceptor',
                        ['\$q', '\$location',
                                function(\$q, \$location)
                                {
                                return {
    ";
        // line 226
        echo "

                                // optional method
                                'response': function(response)
                                {
                                // console.log('httpResponseInterceptor: success');
                                // console.log(response);
                                // do something on success
                                return response;
                                },
                                        // optional method
                                        'responseError': function(rejection)
                                        {
                                        console.log('HTTP STATUS: ' + rejection.status + ' - ' + rejection.statusText + ' - ' + rejection.config.url);
                                                // do something on error
    ";
        // line 244
        echo "                                                                return \$q.reject(rejection);
                                                        }
                                                };
                                                }
                                        ]);
                                /* Configure ocLazyLoader(refer: https://github.com/ocombe/ocLazyLoad) */
                                mgCRMapp.config(['\$ocLazyLoadProvider', function(\$ocLazyLoadProvider) {
                                \$ocLazyLoadProvider.config({
                                // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files
                                cssFilesInsertBefore: 'ng_load_plugins_before',
                                        debug: false,
                                        events: true,
                                });
                                }]);
// Configuring \$translateProvider
// Bascally we will put here translations from PHP, etc
                                mgCRMapp.config(['\$translateProvider', function (\$translateProvider) {

                                // Simply register translation table as object hash
                                \$translateProvider
                                        .translations('";
        // line 264
        echo $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "getLang", array(), "method");
        echo "', ";
        echo twig_jsonencode_filter($this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "getTranslations", array(), "method"));
        echo ")
                                        .preferredLanguage('";
        // line 265
        echo $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "getLang", array(), "method");
        echo "')
                                        .useSanitizeValueStrategy('sanitize');
                                }]);
                                /**
                                 * Main Controller
                                 */
                                mgCRMapp.controller(
                                        'AppController',
                                        ['\$scope', 'ContactTypes', '\$timeout',
                                                function(\$scope, ContactTypes, \$timeout)
                                                {
                                                \$scope.contactTypes = ContactTypes.get();
                                                        \$scope.convertMessages = [];
                                                        \$scope.addConvertMessages = function(type, title, content) {

                                                        // show message
                                                        \$scope.convertMessages.push({
                                                        type: type,
                                                                title: title,
                                                                content: content,
                                                        });
                                                                \$timeout(function() {
                                                                \$scope.convertMessages.splice(\$scope.convertMessages.length - 1, 1);
                                                                }, 8000);
                                                        };
                                                }]);
                                /**
                                 * Main Content Controller
                                 */
                                mgCRMapp.controller(
                                        'ContentController',
                                        ['\$scope',
                                                function(\$scope)
                                                {
                                                \$scope.updating = false;
                                                        // can we provide to do sth here
                                                }]);
                                /**
                                 * Navigation Controller
                                 */
                                mgCRMapp.controller(
                                        'NotificationsController',
                                        ['\$scope', '\$rootScope', '\$http', '\$interval',
                                                function(\$scope, \$rootScope, \$http, \$interval)
                                                {
                                                \$scope.interval = 60000; // 10 sek za szybko, niech bedzie minuta
                                                        \$scope.notifications = [];
                                                        \$scope.showNotifications = false;
                                                        \$scope.loadNotifications = function()
                                                        {
                                                        \$http.get(\$rootScope.settings.config.apiURL + '/notifications/mine/json', {cache: false, isArray: true}).then(function(response) {
                                                        \$scope.notifications = response.data;
                                                                if (\$scope.notifications.length > 0) {
                                                        \$scope.showNotifications = true;
                                                        } else {
                                                        \$scope.showNotifications = false;
                                                        }
                                                        });
                                                        };
                                                        \$scope.acceptNote = function(id)
                                                        {
                                                        \$http.post(\$rootScope.settings.config.apiURL + '/notifications/accept/json', {note: id}).then(function(response) {
                                                        \$scope.loadNotifications();
                                                        });
                                                        };
                                                        //Put in interval, first trigger after 1 seconds 
                                                        \$interval(function(){
                                                        \$scope.loadNotifications();
                                                        }.bind(this), \$scope.interval);
                                                        //invoke initialy
                                                        \$scope.loadNotifications();
                                                }]);
                                /**
                                 * Main Models/resources
                                 */

                                mgCRMapp.factory(
                                        \"leadResource\",
                                        ['\$resource', '\$rootScope',
                                                function(\$resource, \$rootScope)
                                                {
                                                return \$resource(\$rootScope.settings.config.apiURL + '/lead/:id/getLeadHeaderData/json', {},
                                                {
                                                getLeadHeaderData: {
                                                method: \"GET\",
                                                        isArray: false,
                                                        cache: false,
                                                        responseType: 'json',
                                                }
                                                });
                                                }]);
                                /**
                                 * Init global settings and run the app 
                                 */
                                mgCRMapp.run(
                                        [          '\$rootScope', 'settings', '\$state', '\$stateParams', 'editableOptions', 'editableThemes', 'blockUIConfig', '\$templateCache', 'AclService', '\$location',
                                                function (\$rootScope, \$settings, \$state, \$stateParams, editableOptions, editableThemes, blockUIConfig, \$templateCache, AclService, \$location) {


                                                // It's very handy to add references to \$state and \$stateParams to the \$rootScope
                                                // so that you can access them from any scope within your applications.For example,
                                                // <li ng-class=\"{ active: \$state.includes('contacts.list') }\"> will set the <li>
                                                // to active whenever 'contacts.list' or one of its decendents is active.
                                                \$rootScope.\$state = \$state;
                                                        \$rootScope.\$stateParams = \$stateParams;
                                                        // set up xeditable template
                                                        editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
                                                        editableOptions.icon_set = 'font-awesome'; // bootstrap3 theme. Can be also 'bs2', 'default'
                                                        editableThemes.bs3.inputClass = 'input-sm';
                                                        editableThemes.bs3.buttonsClass = 'btn-sm';
                                                        // Set the ACL data. Normally, you'd fetch this from an API or something.
                                                        // The data should have the roles as the property names,
                                                        // with arrays listing their permissions as their value.
                                                        var aclData = {
                                                        admin:";
        // line 379
        echo twig_jsonencode_filter($this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "getCurrentAdminRulesFlatRules", array(), "method"));
        echo ",
                                                        }
                                                AclService.setAbilities(aclData);
                                                        // Attach the member role to the current user
                                                        AclService.attachRole('admin');
                                                        \$rootScope.hasAccess = function(role) {
                                                        return AclService.can(role);
                                                        };
                                                        \$rootScope.page = {
                                                        setTitle: function(title) {
                                                        this.title = 'CRM | ' + title;
                                                        }
                                                        }


                                                // handle errors
                                                \$rootScope.\$on('\$stateNotFound', function() {
                                                // to implement later
                                                // when something went wrong on state change = not found
                                                console.log('state change = not found');
                                                });
                                                        // handle errors
                                                        \$rootScope.\$on('\$stateChangeError', function() {
                                                        // to implement later
                                                        // when something went wrong on state change = some error occured
                                                        console.log('state change = some error occured');
                                                        });
                                                        \$rootScope.\$on('\$routeChangeSuccess', function(event, current, previous) {
                                                        \$rootScope.settings.setTitle(current.\$\$route.data.pageTitle || ';)');
                                                        });
                                                        \$rootScope.\$on('\$stateChangeError', function(event, toState, toParams, fromState, fromParams, rejection) {
                                                        if (rejection === 'Unauthorized') {
                                                        console.log(rejection);
                                                                return \$state.go('dashboard');
                                                        }
                                                        });
                                                }
                                        ]
                                        );


    ";
        // line 475
        echo "
</script>";
    }

    public function getTemplateName()
    {
        return "app/app.angular.js.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  466 => 475,  422 => 379,  305 => 265,  299 => 264,  277 => 244,  260 => 226,  227 => 180,  215 => 170,  213 => 169,  176 => 135,  169 => 131,  165 => 130,  161 => 129,  157 => 128,  153 => 127,  135 => 112,  92 => 72,  19 => 1,);
    }
}
/* <script type="text/javascript">*/
/* */
/* */
/*     /***/
/*      * Polyfill for IE8*/
/*      **/
/*      * http://stackoverflow.com/a/1181586*/
/*      *//* */
/*     if (!Array.prototype.indexOf) {*/
/*     Array.prototype.indexOf = function (needle) {*/
/*     var l = this.length;*/
/*             for (; l--; ) {*/
/*     if (this[l] === needle) {*/
/*     return l;*/
/*     }*/
/*     }*/
/*     return - 1;*/
/*     };*/
/*     };*/
/* // remove from array*/
/*             if (!Array.prototype.remove) {*/
/*     Array.prototype.remove = function() {*/
/*     var what, a = arguments, L = a.length, ax;*/
/*             while (L && this.length) {*/
/*     what = a[--L];*/
/*             while ((ax = this.indexOf(what)) !== - 1) {*/
/*     this.splice(ax, 1);*/
/*     }*/
/*     }*/
/*     return this;*/
/*     };*/
/*     };*/
/* // insert in array at certain index*/
/*             if (!Array.prototype.insert) {*/
/*     Array.prototype.insert = function (index, item) {*/
/*     this.splice(index, 0, item);*/
/*     };*/
/*     };*/
/*             /***/
/*              * Angular App*/
/*              * With main dependiences for angular*/
/*              *//* */
/* */
/*             var mgCRMapp = angular.module("mgCRMapp", [*/
/*                     "ui.router", // obliviously */
/*                     "oc.lazyLoad", // dymanic load files*/
/*                     "pascalprecht.translate", // translation library*/
/*                     "ngResource", // manage with data objects and DI*/
/*                     "ui.bootstrap",*/
/*                     "ui.bootstrap.datetimepicker",*/
/*                     "ngDialog",*/
/*                     //"mwl.calendar",*/
/*                     "xeditable",*/
/*                     "blockUI",*/
/*                     "ngSanitize",*/
/*                     "ui.sortable",*/
/*                     "ui.select",*/
/*                     "smart-table",*/
/*                     "textAngular",*/
/*                     // App lever modules*/
/*                     "CRM",*/
/*             ]);*/
/* /////////////////////////////////////////////*/
/* // PROVIDER*/
/* // */
/* // This handle things related to dynamic contact types*/
/* // and provide nesesary operations for routing (since provider can be accesed here)*/
/* /////////////////////////////////////////////*/
/*             mgCRMapp.provider('ContactTypes', [ function ()*/
/*             {*/
/*             // keep here all possible contact types*/
/*             var types = {{ settings.dynamicTypes.routing | json_encode|raw }};*/
/*                     /***/
/*                      * Get single role by ID*/
/*                      *//* */
/*                     var getById = function(id)*/
/*                     {*/
/*                     for (i = 0; i < types.length; i++) {*/
/*                     if (types[i].id == id) {*/
/*                     return types[i];*/
/*                     }*/
/*                     }*/
/* */
/*                     return null;*/
/*                     };*/
/*                     var ContactTypes = {};*/
/*                     ContactTypes.getById = getById;*/
/*                     /***/
/*                      * Return me all types*/
/*                      **/
/*                      * @param array*/
/*                      *//* */
/*                     ContactTypes.get = function() {*/
/*                     return types;*/
/*                     };*/
/*                     return {*/
/*                     all: function() {*/
/*                     return types;*/
/*                     },*/
/*                             getById: getById,*/
/*                             $get: function () {*/
/*                             return ContactTypes;*/
/*                             }*/
/*                     };*/
/*             }]);*/
/*             /** */
/*              * Setup global settings */
/*              *//* */
/*             mgCRMapp.factory('settings', ['$rootScope', function($rootScope) {*/
/* */
/*             // set config*/
/*             mgCRM.setConfig({{ settings.templates |json_encode|raw }});*/
/*                     // global scope settings*/
/*                     var settings =*/
/*             {*/
/*             frontend:*/
/*             {*/
/*             pageAutoScrollOnLoad: 1000, // auto scroll to top on page load*/
/*                     dismissNotesAfter:    8000, // ms after when dismiss note*/
/*             },*/
/*                     config: mgCRM.getConfig(),*/
/*             };*/
/*                     // assign this settings for global scoope*/
/*                     $rootScope.settings = settings;*/
/*                     // console.log(acl);*/
/*                     var acl = {*/
/*                     currentAdmin:{{ acl.getCurrentAdminRulesFlat() | json_encode|raw }}, // currently logged admin all defined rules with flags allowed/not (as bolean)*/
/*                             parsedRules:{{ acl.getRules()                 | json_encode|raw }}, // all possible rules*/
/*                             flattenRules:{{ acl.getRulesFlat()             | json_encode|raw }}, // all possible rules*/
/*                             rulesConfig:{{ acl.getRulesConfig()           | json_encode|raw }}, // copnfig rules*/
/*                             isFullAdmin:{{ acl.isFullAdmin()              | json_encode|raw }}, // if full admin*/
/*                     };*/
/*                     // assing acl settings for global scope*/
/*                     $rootScope.acl = acl;*/
/*                     $rootScope.currentAdminID = {{ currentAdmin  }};*/
/*                     return settings;*/
/*             }]);*/
/* //Http Intercpetor to check auth failures for xhr requests*/
/*             mgCRMapp.config(['$httpProvider', function($httpProvider) {*/
/*             $httpProvider.interceptors.push('httpResponseInterceptor');*/
/*                     //initialize get if not there*/
/*                     if (!$httpProvider.defaults.headers.get) {*/
/*             $httpProvider.defaults.headers.get = {};*/
/*             }*/
/* */
/*             // Answer edited to include suggestions from comments*/
/*             // because previous version of code introduced browser-related errors*/
/* */
/*             //disable IE ajax request caching*/
/*             $httpProvider.defaults.headers.get['If-Modified-Since'] = 'Mon, 26 Jul 1997 05:00:00 GMT';*/
/*                     $httpProvider.defaults.headers.common["If-Modified-Since"] = "0";*/
/*                     // extra*/
/*                     $httpProvider.defaults.headers.get['Cache-Control'] = 'no-cache';*/
/*                     $httpProvider.defaults.headers.common["Cache-Control"] = "no-cache";*/
/*                     $httpProvider.defaults.headers.get['Pragma'] = 'no-cache';*/
/*                     $httpProvider.defaults.headers.common.Pragma = "no-cache";*/
/*             }]);*/
/*             /***/
/*              *  rutes configs*/
/*              *//* */
/*             mgCRMapp.config([*/
/*                     '$stateProvider', '$urlRouterProvider', '$locationProvider',*/
/*                     function($stateProvider, $urlRouterProvider, $locationProvider) {*/
/* */
/*                     // basic providers*/
/*                     $locationProvider.html5Mode(false);*/
/*                             $locationProvider.hashPrefix('!');*/
/*                             // load all states and routes for it from other file to not mess here*/
/*     {% include 'app/app.routes.js' %}*/
/*                         }]);*/
/*                 /***/
/*                  * */
/*                  *  Setup Rounting For All Pages */
/*                  *//* */
/*                 mgCRMapp.config([*/
/*                         '$stateProvider', '$urlRouterProvider', '$locationProvider', 'blockUIConfig', 'uiSelectConfig', '$provide',*/
/*                         function($stateProvider, $urlRouterProvider, $locationProvider, blockUIConfig, uiSelectConfig, $provide) {*/
/* */
/*                         // configure block ui module*/
/*                         blockUIConfig.message = '<img src="{{ settings.templates.rootDir }}/assets/img/mg-loader.gif" />';*/
/*                                 blockUIConfig.delay = 100;*/
/*                                 blockUIConfig.template = "<div class=\"block-ui-overlay\"></div><div class=\"block-ui-message-container\" aria-live=\"assertive\" aria-atomic=\"true\"><div class=\"block-ui-message\" ng-class=\"$_blockUiMessageClass\" ng-bind-html=\"state.message\"></div></div>";*/
/*                                 blockUIConfig.autoBlock = false;*/
/*                                 // ui-select theme*/
/*                                 uiSelectConfig.theme = 'bootstrap';*/
/*                                 // this demonstrates how to register a new tool and add it to the default toolbar*/
/*                                 $provide.decorator('taOptions', ['taRegisterTool', '$delegate', function(taRegisterTool, taOptions) { // $delegate is the taOptions we are decorating*/
/*                                 taOptions.toolbar = [*/
/*                                         ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'quote'],*/
/*                                         ['bold', 'italics', 'underline', 'strikeThrough', 'ul', 'ol', 'redo', 'undo', 'clear'],*/
/*                                         ['justifyLeft', 'justifyCenter', 'justifyRight', 'indent', 'outdent'],*/
/*                                         ['html', 'insertImage', 'insertLink']*/
/*                                 ];*/
/*                                         return taOptions;*/
/*                                 }]);*/
/*                         }]);*/
/* // */
/*                 /***/
/*                  * register the http interceptor as a service*/
/*                  * basically it is a wrapper to hangle ajax queries :D*/
/*                  * */
/*                  * @docs https://code.angularjs.org/1.3.16/docs/api/ng/service/$http*/
/*                  *//* */
/*                 mgCRMapp.factory(*/
/*                         'httpResponseInterceptor',*/
/*                         ['$q', '$location',*/
/*                                 function($q, $location)*/
/*                                 {*/
/*                                 return {*/
/*     {#*/
/*     // optional method*/
/*     'request': function(config) {*/
/*         // do something on success*/
/*         return config;*/
/*     },*/
/* */
/*     // optional method*/
/*     'requestError': function(rejection) {*/
/*         // do something on error*/
/*         if (canRecover(rejection)) {*/
/*             return responseOrNewPromise*/
/*         }*/
/*         return $q.reject(rejection);*/
/*     },*/
/*     #}*/
/* */
/* */
/*                                 // optional method*/
/*                                 'response': function(response)*/
/*                                 {*/
/*                                 // console.log('httpResponseInterceptor: success');*/
/*                                 // console.log(response);*/
/*                                 // do something on success*/
/*                                 return response;*/
/*                                 },*/
/*                                         // optional method*/
/*                                         'responseError': function(rejection)*/
/*                                         {*/
/*                                         console.log('HTTP STATUS: ' + rejection.status + ' - ' + rejection.statusText + ' - ' + rejection.config.url);*/
/*                                                 // do something on error*/
/*     {#                if (canRecover(rejection)) {*/
/*                         return responseOrNewPromise*/
/*                     }#}*/
/*                                                                 return $q.reject(rejection);*/
/*                                                         }*/
/*                                                 };*/
/*                                                 }*/
/*                                         ]);*/
/*                                 /* Configure ocLazyLoader(refer: https://github.com/ocombe/ocLazyLoad) *//* */
/*                                 mgCRMapp.config(['$ocLazyLoadProvider', function($ocLazyLoadProvider) {*/
/*                                 $ocLazyLoadProvider.config({*/
/*                                 // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files*/
/*                                 cssFilesInsertBefore: 'ng_load_plugins_before',*/
/*                                         debug: false,*/
/*                                         events: true,*/
/*                                 });*/
/*                                 }]);*/
/* // Configuring $translateProvider*/
/* // Bascally we will put here translations from PHP, etc*/
/*                                 mgCRMapp.config(['$translateProvider', function ($translateProvider) {*/
/* */
/*                                 // Simply register translation table as object hash*/
/*                                 $translateProvider*/
/*                                         .translations('{{ lang.getLang() |raw }}', {{ lang.getTranslations() |json_encode|raw }})*/
/*                                         .preferredLanguage('{{ lang.getLang() |raw }}')*/
/*                                         .useSanitizeValueStrategy('sanitize');*/
/*                                 }]);*/
/*                                 /***/
/*                                  * Main Controller*/
/*                                  *//* */
/*                                 mgCRMapp.controller(*/
/*                                         'AppController',*/
/*                                         ['$scope', 'ContactTypes', '$timeout',*/
/*                                                 function($scope, ContactTypes, $timeout)*/
/*                                                 {*/
/*                                                 $scope.contactTypes = ContactTypes.get();*/
/*                                                         $scope.convertMessages = [];*/
/*                                                         $scope.addConvertMessages = function(type, title, content) {*/
/* */
/*                                                         // show message*/
/*                                                         $scope.convertMessages.push({*/
/*                                                         type: type,*/
/*                                                                 title: title,*/
/*                                                                 content: content,*/
/*                                                         });*/
/*                                                                 $timeout(function() {*/
/*                                                                 $scope.convertMessages.splice($scope.convertMessages.length - 1, 1);*/
/*                                                                 }, 8000);*/
/*                                                         };*/
/*                                                 }]);*/
/*                                 /***/
/*                                  * Main Content Controller*/
/*                                  *//* */
/*                                 mgCRMapp.controller(*/
/*                                         'ContentController',*/
/*                                         ['$scope',*/
/*                                                 function($scope)*/
/*                                                 {*/
/*                                                 $scope.updating = false;*/
/*                                                         // can we provide to do sth here*/
/*                                                 }]);*/
/*                                 /***/
/*                                  * Navigation Controller*/
/*                                  *//* */
/*                                 mgCRMapp.controller(*/
/*                                         'NotificationsController',*/
/*                                         ['$scope', '$rootScope', '$http', '$interval',*/
/*                                                 function($scope, $rootScope, $http, $interval)*/
/*                                                 {*/
/*                                                 $scope.interval = 60000; // 10 sek za szybko, niech bedzie minuta*/
/*                                                         $scope.notifications = [];*/
/*                                                         $scope.showNotifications = false;*/
/*                                                         $scope.loadNotifications = function()*/
/*                                                         {*/
/*                                                         $http.get($rootScope.settings.config.apiURL + '/notifications/mine/json', {cache: false, isArray: true}).then(function(response) {*/
/*                                                         $scope.notifications = response.data;*/
/*                                                                 if ($scope.notifications.length > 0) {*/
/*                                                         $scope.showNotifications = true;*/
/*                                                         } else {*/
/*                                                         $scope.showNotifications = false;*/
/*                                                         }*/
/*                                                         });*/
/*                                                         };*/
/*                                                         $scope.acceptNote = function(id)*/
/*                                                         {*/
/*                                                         $http.post($rootScope.settings.config.apiURL + '/notifications/accept/json', {note: id}).then(function(response) {*/
/*                                                         $scope.loadNotifications();*/
/*                                                         });*/
/*                                                         };*/
/*                                                         //Put in interval, first trigger after 1 seconds */
/*                                                         $interval(function(){*/
/*                                                         $scope.loadNotifications();*/
/*                                                         }.bind(this), $scope.interval);*/
/*                                                         //invoke initialy*/
/*                                                         $scope.loadNotifications();*/
/*                                                 }]);*/
/*                                 /***/
/*                                  * Main Models/resources*/
/*                                  *//* */
/* */
/*                                 mgCRMapp.factory(*/
/*                                         "leadResource",*/
/*                                         ['$resource', '$rootScope',*/
/*                                                 function($resource, $rootScope)*/
/*                                                 {*/
/*                                                 return $resource($rootScope.settings.config.apiURL + '/lead/:id/getLeadHeaderData/json', {},*/
/*                                                 {*/
/*                                                 getLeadHeaderData: {*/
/*                                                 method: "GET",*/
/*                                                         isArray: false,*/
/*                                                         cache: false,*/
/*                                                         responseType: 'json',*/
/*                                                 }*/
/*                                                 });*/
/*                                                 }]);*/
/*                                 /***/
/*                                  * Init global settings and run the app */
/*                                  *//* */
/*                                 mgCRMapp.run(*/
/*                                         [          '$rootScope', 'settings', '$state', '$stateParams', 'editableOptions', 'editableThemes', 'blockUIConfig', '$templateCache', 'AclService', '$location',*/
/*                                                 function ($rootScope, $settings, $state, $stateParams, editableOptions, editableThemes, blockUIConfig, $templateCache, AclService, $location) {*/
/* */
/* */
/*                                                 // It's very handy to add references to $state and $stateParams to the $rootScope*/
/*                                                 // so that you can access them from any scope within your applications.For example,*/
/*                                                 // <li ng-class="{ active: $state.includes('contacts.list') }"> will set the <li>*/
/*                                                 // to active whenever 'contacts.list' or one of its decendents is active.*/
/*                                                 $rootScope.$state = $state;*/
/*                                                         $rootScope.$stateParams = $stateParams;*/
/*                                                         // set up xeditable template*/
/*                                                         editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'*/
/*                                                         editableOptions.icon_set = 'font-awesome'; // bootstrap3 theme. Can be also 'bs2', 'default'*/
/*                                                         editableThemes.bs3.inputClass = 'input-sm';*/
/*                                                         editableThemes.bs3.buttonsClass = 'btn-sm';*/
/*                                                         // Set the ACL data. Normally, you'd fetch this from an API or something.*/
/*                                                         // The data should have the roles as the property names,*/
/*                                                         // with arrays listing their permissions as their value.*/
/*                                                         var aclData = {*/
/*                                                         admin:{{ acl.getCurrentAdminRulesFlatRules() | json_encode|raw }},*/
/*                                                         }*/
/*                                                 AclService.setAbilities(aclData);*/
/*                                                         // Attach the member role to the current user*/
/*                                                         AclService.attachRole('admin');*/
/*                                                         $rootScope.hasAccess = function(role) {*/
/*                                                         return AclService.can(role);*/
/*                                                         };*/
/*                                                         $rootScope.page = {*/
/*                                                         setTitle: function(title) {*/
/*                                                         this.title = 'CRM | ' + title;*/
/*                                                         }*/
/*                                                         }*/
/* */
/* */
/*                                                 // handle errors*/
/*                                                 $rootScope.$on('$stateNotFound', function() {*/
/*                                                 // to implement later*/
/*                                                 // when something went wrong on state change = not found*/
/*                                                 console.log('state change = not found');*/
/*                                                 });*/
/*                                                         // handle errors*/
/*                                                         $rootScope.$on('$stateChangeError', function() {*/
/*                                                         // to implement later*/
/*                                                         // when something went wrong on state change = some error occured*/
/*                                                         console.log('state change = some error occured');*/
/*                                                         });*/
/*                                                         $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {*/
/*                                                         $rootScope.settings.setTitle(current.$$route.data.pageTitle || ';)');*/
/*                                                         });*/
/*                                                         $rootScope.$on('$stateChangeError', function(event, toState, toParams, fromState, fromParams, rejection) {*/
/*                                                         if (rejection === 'Unauthorized') {*/
/*                                                         console.log(rejection);*/
/*                                                                 return $state.go('dashboard');*/
/*                                                         }*/
/*                                                         });*/
/*                                                 }*/
/*                                         ]*/
/*                                         );*/
/* */
/* */
/*     {#*/
/*     /***/
/*      * Init global settings and run the app */
/*      *//* */
/*     mgCRMapp.run(*/
/*       [          '$rootScope', 'settings', '$state', '$stateParams','$templateCache',  '$location',*/
/*         function ($rootScope,   $settings,  $state,   $stateParams,  $templateCache,      $location) {*/
/*         */
/* */
/*             // It's very handy to add references to $state and $stateParams to the $rootScope*/
/*             // so that you can access them from any scope within your applications.For example,*/
/*             // <li ng-class="{ active: $state.includes('contacts.list') }"> will set the <li>*/
/*             // to active whenever 'contacts.list' or one of its decendents is active.*/
/*             $rootScope.$state       = $state;*/
/*             $rootScope.$stateParams = $stateParams;*/
/*         */
/*         */
/*         */
/*             $rootScope.page = {*/
/*                 setTitle: function(title) {*/
/*                     this.title = 'crm' + title;*/
/*                 }*/
/*             }*/
/*         */
/* */
/*             // handle errors*/
/*             $rootScope.$on('$stateNotFound', function() {*/
/*                 // to implement later*/
/*                 // when something went wrong on state change = not found*/
/*                 console.log('state change = not found');*/
/*             });*/
/* */
/* */
/*             // handle errors*/
/*             $rootScope.$on('$stateChangeError', function() {*/
/*                 // to implement later*/
/*                 // when something went wrong on state change = some error occured*/
/*                 console.log('state change = some error occured');*/
/*             });*/
/* */
/*                     */
/*             $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {*/
/*                 $rootScope.settings.setTitle(current.$$route.data.pageTitle || ';)');*/
/*             });*/
/*         */
/*             $rootScope.$on('$stateChangeError', function(event, toState, toParams, fromState, fromParams, rejection) {*/
/*                 if(rejection === 'Unauthorized') {*/
/*                     console.log(rejection);*/
/*                     return $state.go('dashboard');*/
/*                 }*/
/*             });*/
/*         */
/*         }*/
/*       ]*/
/*     );#}*/
/* */
/* </script>*/
