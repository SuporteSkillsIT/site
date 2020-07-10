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
 * Definition of CRM module for angular
 * dont forget about And add CRM as a dependency for app
 * 
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 */
(function() {
    
    /**
     * CRM Module for angular
     * used for global helpers, etc
     *
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    angular.module('CRM', [])
    
    /////////////////////////////////////////////
    // NOTES DIRECTIVE and required controller
    /////////////////////////////////////////////

    /**
     * Notes section build for directive
     * This is mainly for override standard bootstrap alerts
     */
    .controller('NoteController', ['$rootScope', '$scope', '$attrs', '$timeout', function($rootScope, $scope, $attrs, $timeout) {
        $scope.closeable = !!$attrs.close;
        $scope.dismiss = !!$attrs.dismiss;
      
        if($scope.dismiss) {
            $timeout(function(){
                $scope.closeNote();
            }, $rootScope.settings.frontend.dismissNotesAfter); //wait 10 sec before hide message (10s is configurd by app settings)
        }
      
        $scope.closeNote = function() {
            if($scope.$parent.scopeMessages) {
                $scope.$parent.scopeMessages.splice($attrs.close, 1);
            }
        };
    
        this.close = $scope.close;
    }])

    .directive('note', function() {
      return {
        restrict: 'EA',
        controller: 'NoteController',
        controllerAs: 'note',
        template:       "<div class=\"note\" ng-class=\"['note-' + (type || 'warning'), closeable ? 'note-dismissible' : null]\" role=\"note\">\n" +
                        "    <button ng-show=\"closeable\" type=\"button\" class=\"close\" ng-click=\"closeNote()\">\n" +
                        "        <span aria-hidden=\"true\">&times;</span>\n" +
                        "        <span class=\"sr-only\">Close</span>\n" +
                        "    </button>\n" +
                        "    <div ng-transclude></div>\n" +
                        "</div>\n",
        transclude: true,
        replace: true,
        scope: {
          type: '@',
          closeNote: '&'
        }
      };
    })
    
    
    
    /////////////////////////////////////////////
    // DIRECTIVE for spinner bar
    /////////////////////////////////////////////
    
    /**
     * Directive to show spinner bar and generally loader on state change
     * Route State Load Spinner(used on page or content load)
     * 
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('ngSpinnerBar', ['$rootScope',
        function($rootScope) {
            return {
                link: function(scope, element, attrs) {
                    // by defult hide the spinner bar
                    element.addClass('hide'); // hide spinner bar by default

                    // display the spinner bar whenever the route changes(the content part started loading)
                    $rootScope.$on('$stateChangeStart', function() {
                        element.removeClass('hide'); // show spinner bar
                        element.next().addClass('hide');

                        // mgCRM.closeMainMenu();
                    });

                    // hide the spinner bar on rounte change success(after the content loaded)
                    $rootScope.$on('$stateChangeSuccess', function() {
                        element.addClass('hide'); // hide spinner bar
                        element.next().removeClass('hide');

                        // we cant trigger these before
                        // since it match for current state!
                        // activate selected link in the sidebar menu
                        if(scope.$state.$current.data.navid)
                        {
                            if(scope.$state.$current.data.navid == 'contacts-list') {
                                mgCRM.setMainMenuActiveLink('dynamic', scope.$state.$current.data.navid, scope.$state.$current.locals.globals.dynamicType.id); 
                            } else {
                                mgCRM.setMainMenuActiveLink('set', scope.$state.$current.data.navid); 
                            }
                        }
                            
                        $("html, body").removeClass('page-box-fullscreen');
                        if($rootScope.settings.config.pageAutoScrollOnLoad !== false)
                        {
                            // auto scorll to page top
                            setTimeout(function () {
                                mgCRM.scrollTop(); // scroll to the top on content load
                            }, $rootScope.settings.config.pageAutoScrollOnLoad);                    
                        }

                    });

                }
            };
        }
    ])

    /////////////////////////////////////////////
    // DIRECTIVE for Breadcrumbs (taken from ui-bootstrap)
    /////////////////////////////////////////////
    /**
     * uiBreadcrumbs automatic breadcrumbs directive for AngularJS & Angular ui-router.
     *
     * https://github.com/michaelbromley/angularUtils/tree/master/src/directives/uiBreadcrumbs
     *
     * Copyright 2014 Michael Bromley <michael@michaelbromley.co.uk>
     */
    .directive('uiBreadcrumbs', ['$interpolate', '$state', '$rootScope', function($interpolate, $state, $rootScope) {
        return {
            restrict: 'E',
            template: '<ul class="breadcrumb"> ' +
                      '      <li><a href="#!/"><i class="fa fa-home"></i></a></li> ' +
                      '      <li ng-repeat="crumb in breadcrumbs" ng-class="{ active: $last || !!!crumb.route }"> ' +
                      '             <a ui-sref="{{ crumb.route }}" ng-if="!$last && crumb.route">{{ crumb.displayName }}</a> ' +
                      '             <span ng-show="$last || !!!crumb.route">{{ crumb.displayName }}</span>' +
                      '      </li>'+
                      '  </ul>',
            scope: {
                displaynameProperty: '@',
                abstractProxyProperty: '@?'
            },
            link: function(scope) {
                scope.breadcrumbs = [];
                if ($state.$current.name !== '') {
                    updateBreadcrumbsArray();
                }
                
                function build(scope){
                    updateBreadcrumbsArray();

                    // insane tricky helper
                    // notice that it have to be state param, and title template defined here
                    title = getDisplayName($state.$current);
                    scope.targetScope.page.setTitle(title);
                };
                
                scope.$on('$stateChangeSuccess', function(scope) {
                    build(scope);
                });
                scope.$on('forceReloadBreadcrumbs', function(scope) {
                    build(scope);
                });

                /**
                 * Start with the current state and traverse up the path to build the
                 * array of breadcrumbs that can be used in an ng-repeat in the template.
                 */
                function updateBreadcrumbsArray() {
                    var workingState;
                    var displayName;
                    var breadcrumbs = [];
                    var currentState = $state.$current;

                    while(currentState && currentState.name !== '') {
                        workingState = getWorkingState(currentState);
                        if (workingState) {
                            displayName = getDisplayName(workingState);

                            if (displayName !== false && !stateAlreadyInBreadcrumbs(workingState, breadcrumbs) ) {
                                toPush = {
                                    displayName: displayName,
                                    route: workingState.name
                                };
                                
                                if ( typeof(workingState.redirect) != 'undefined' ) {
                                    toPush.route = workingState.redirect;
                                }
                                
                                if ( typeof(workingState.skipBreadcrumbsUrl) != 'undefined' && workingState.skipBreadcrumbsUrl === true ) {
                                    delete toPush.route
                                }
                                
                                breadcrumbs.push(toPush);
                            }
                        }
                        currentState = currentState.parent;
                    }
                    breadcrumbs.reverse();
                    scope.breadcrumbs = breadcrumbs;
                }

                /**
                 * Get the state to put in the breadcrumbs array, taking into account that if the current state is abstract,
                 * we need to either substitute it with the state named in the `scope.abstractProxyProperty` property, or
                 * set it to `false` which means this breadcrumb level will be skipped entirely.
                 * @param currentState
                 * @returns {*}
                 */
                function getWorkingState(currentState) {
                    var proxyStateName;
                    var workingState = currentState;
                    if (currentState.abstract === true) {
                        if (typeof scope.abstractProxyProperty !== 'undefined') {
                            proxyStateName = getObjectValue(scope.abstractProxyProperty, currentState);
                            if (proxyStateName) {
                                workingState = $state.get(proxyStateName);
                            } else {
                                workingState = false;
                            }
                        } else {
                            workingState = false;
                        }
                    }
                    return workingState;
                }

                /**
                 * Resolve the displayName of the specified state. Take the property specified by the `displayname-property`
                 * attribute and look up the corresponding property on the state's config object. The specified string can be interpolated against any resolved
                 * properties on the state config object, by using the usual {{ }} syntax.
                 * @param currentState
                 * @returns {*}
                 */
                function getDisplayName(currentState) {
                    var interpolationContext;
                    var propertyReference;
                    var displayName;

                    if (!scope.displaynameProperty) {
                        // if the displayname-property attribute was not specified, default to the state's name
                        return currentState.name;
                    }
                    propertyReference = getObjectValue(scope.displaynameProperty, currentState);

                    if (propertyReference === false) {
                        return false;
                    } else if (typeof propertyReference === 'undefined') {
                        return currentState.name;
                    } else {
                        // use the $interpolate service to handle any bindings in the propertyReference string.
                        // interpolationContext =  (typeof currentState.locals !== 'undefined') ? currentState.locals.globals : currentState;
                        interpolationContext =  (typeof $state.$current.locals !== 'undefined') ? $state.$current.locals.globals : currentState;
                        displayName = $interpolate(propertyReference)(interpolationContext);
                        return displayName;
                    }
                }

                /**
                 * Given a string of the type 'object.property.property', traverse the given context (eg the current $state object) and return the
                 * value found at that path.
                 *
                 * @param objectPath
                 * @param context
                 * @returns {*}
                 */
                function getObjectValue(objectPath, context) {
                    var i;
                    var propertyArray = objectPath.split('.');
                    var propertyReference = context;

                    for (i = 0; i < propertyArray.length; i ++) {
                        if (angular.isDefined(propertyReference[propertyArray[i]])) {
                            propertyReference = propertyReference[propertyArray[i]];
                        } else {
                            // if the specified property was not found, default to the state's name
                            return undefined;
                        }
                    }
                    return propertyReference;
                }

                /**
                 * Check whether the current `state` has already appeared in the current breadcrumbs array. This check is necessary
                 * when using abstract states that might specify a proxy that is already there in the breadcrumbs.
                 * @param state
                 * @param breadcrumbs
                 * @returns {boolean}
                 */
                function stateAlreadyInBreadcrumbs(state, breadcrumbs) {
                    var i;
                    var alreadyUsed = false;
                    for(i = 0; i < breadcrumbs.length; i++) {
                        if (breadcrumbs[i].route === state.name) {
                            alreadyUsed = true;
                        }
                    }
                    return alreadyUsed;
                }
            }
        };
    }])

    /////////////////////////////////////////////
    // DIRECTIVE to display and handle pretty loader
    // under ModulesGarden logo, once something works in background
    /////////////////////////////////////////////
    
    /**
     * Show loading indicator
     * depending on catched event parameters
     * 
     * Animations are handled by pure CSS code
     *
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('loadingNotification', ['$compile', '$timeout', function ($compile, $timeout) 
    {	
        var hideAfter   = 3000;
        var classes     = 
        {
            finished: {
                icon: 'font-green icon-check pull-right',
                text: 'font-green',
                msg:  'Done',
            },
            progress: {
                icon: 'mg-loader-flow active',
                text: 'font-grey-mint',
                msg:  'Updating...',
            },
        };
        
        return {
            restrict: 'AE',
            template: '',
            replace:  true,
            link:     function (scope, elm, attr) 
            {
                scope.$on('loadingNotification', function(event, o) 
                {
                    if ( typeof(o) != 'object' ) {
                        return;
                    }
                    
                    var cfg = (o.type == 'finished') ? classes.finished : classes.progress;
  
                    scope.cfg       = cfg;
                    scope.cfg.msg   = (typeof(msg) == 'string') ? o.msg : cfg.msg;
                    scope.hidden    = false;

                    // kill previously timeout! (not double flash)
                    if(typeof hideTimeout == 'object') {
                        $timeout.cancel(hideTimeout);
                    }

                    // if task is finished, hide this after rimeout
                    if(o.type == 'finished' === true) 
                    {
                        // hide after defined time
                        hideTimeout = $timeout(function() {
                            scope.hidden = true;
                        }, hideAfter);
                    } 
                
                    elm.html(template);
                    $compile(elm.contents())(scope);

                });
                
                var template = '<div class="pull-right under-header-message-container ng-hide loadingNotificationIn loadingNotificationOut" ng-hide="hidden">' +
                                    '<span ng-class="cfg.icon"></span>' +
                                    '<style>.message:before {content: "";}</style><div class="message" style="background: transparent; ">' +
                                        '<span ng-class="cfg.text">{{cfg.msg}}</span>' +
                                    '</div>' +
                                '</div>';
            }
        };
        
    }])


    
    /**
     * Show ACL messages that user have no permision
     *
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('aclNoAccessNotification', ['$compile', '$rootScope', function ($compile, $rootScope) 
    {	
        return {
            restrict: 'AE',
            template: '',
            replace:  true,
            link:     function (scope, elm, attr) 
            {
                scope.$on('AclNoAccess', function(event, o) 
                {
                    if ( typeof(o) != 'object' ) {
                        return;
                    }
                    
                    if (typeof(o.rule) == 'string') {
                        scope.rule = $rootScope.acl.flattenRules[o.rule];
                    } else {
                        scope.rule = false;
                    }

                    scope.msg       = (typeof(o.msg) == 'string')  ? o.msg : false;
                    scope.hidden    = false;

                    scope.show = true;
                
                    elm.html(template);
                    $compile(elm.contents())(scope);

                });
                
                var template = '<div class="clearfix"></div><div class="note note-danger ng-hide" ng-show="show">' +
                                    '<button type="button" class="close" ng-click="show = false">' +
                                        '<span aria-hidden="true">&times;</span>' +
                                        '<span class="sr-only">Close</span>' +
                                    '</button>' +
                                    '<h4 class="note-title">You Have No Access</h4>' +
                                    '<span ng-show="rule">This action require permission: <b>{{ rule }}</b>.</span>' +
                                    '<span ng-show="msg">{{ msg }}</span>' +
                                '</div>';
            }
        };
        
    }])
    
    /////////////////////////////////////////////
    // DIRECTIVE for checklistModel model
    // for editable
    /////////////////////////////////////////////
    
    
   /**
    * Checklist-model
    * AngularJS directive for list of checkboxes
    * https://github.com/vitalets/checklist-model
    * License: MIT http://opensource.org/licenses/MIT
    */
   .directive('checklistModel', ['$parse', '$compile', function($parse, $compile) 
    {
        // contains
        function contains(arr, item, comparator) {
            if (angular.isArray(arr)) {
                for (var i = arr.length; i--;) {
                    if (comparator(arr[i], item)) {
                        return true;
                    }
                }
            }
            return false;
        }

        // add
        function add(arr, item, comparator) {
           arr = angular.isArray(arr) ? arr : [];
               if(!contains(arr, item, comparator)) {
                   arr.push(item);
               }
           return arr;
        }  

        // remove
        function remove(arr, item, comparator) {
           if (angular.isArray(arr)) {
               for (var i = arr.length; i--;) {
                   if (comparator(arr[i], item)) {
                       arr.splice(i, 1);
                       break;
                   }
               }
           }
           return arr;
        }

        // http://stackoverflow.com/a/19228302/1458162
        function postLinkFn(scope, elem, attrs) 
        {
           // exclude recursion, but still keep the model
           var checklistModel = attrs.checklistModel;
           attrs.$set("checklistModel", null);
           // compile with `ng-model` pointing to `checked`
           $compile(elem)(scope);
           attrs.$set("checklistModel", checklistModel);

           // getter / setter for original model
           var getter = $parse(checklistModel);
           var setter = getter.assign;
           var checklistChange = $parse(attrs.checklistChange);

           // value added to list
           var value = attrs.checklistValue ? $parse(attrs.checklistValue)(scope.$parent) : attrs.value;


           var comparator = angular.equals;

           if (attrs.hasOwnProperty('checklistComparator')){
               if (attrs.checklistComparator[0] == '.') {
                   var comparatorExpression = attrs.checklistComparator.substring(1);
                   comparator = function (a, b) {
                       return a[comparatorExpression] === b[comparatorExpression];
                   }

               } else {
                   comparator = $parse(attrs.checklistComparator)(scope.$parent);
               }
           }

           // watch UI checked change
           scope.$watch(attrs.ngModel, function(newValue, oldValue) {
               if (newValue === oldValue) { 
                   return;
               } 
               var current = getter(scope.$parent);
               if (angular.isFunction(setter)) {
                   if (newValue === true) {
                       setter(scope.$parent, add(current, value, comparator));
                   } else {
                       setter(scope.$parent, remove(current, value, comparator));
                   }
               }

               if (checklistChange) {
                   checklistChange(scope);
               }
           });

           // declare one function to be used for both $watch functions
           function setChecked(newArr, oldArr) {
               scope[attrs.ngModel] = contains(newArr, value, comparator);
           }

           // watch original model change
           // use the faster $watchCollection method if it's available
           if (angular.isFunction(scope.$parent.$watchCollection)) {
               scope.$parent.$watchCollection(checklistModel, setChecked);
           } else {
               scope.$parent.$watch(checklistModel, setChecked, true);
           }
        }

        return {
            restrict: 'A',
            priority: 1000,
            terminal: true,
            scope: true,
            compile: function(tElement, tAttrs) {
                if ((tElement[0].tagName !== 'INPUT' || tAttrs.type !== 'checkbox')
                    && (tElement[0].tagName !== 'MD-CHECKBOX')
                    && (!tAttrs.btnCheckbox)) {
                    throw 'checklist-model should be applied to `input[type="checkbox"]` or `md-checkbox`.';
                }

                if (!tAttrs.checklistValue && !tAttrs.value) {
                    throw 'You should provide `value` or `checklist-value`.';
                }

                // by default ngModel is 'checked', so we set it if not specified
                if (!tAttrs.ngModel) {
                    // local scope var storing individual checkbox model
                    tAttrs.$set("ngModel", "checked");
                }

                return postLinkFn;
            }
        };
    }])



    /////////////////////////////////////////////
    // 
    // PROVIDER
    // 
    // MODULE ACL provider
    // used in many cases to provide permision checks
    // 
    // https://github.com/mikemclin/angular-acl
    /////////////////////////////////////////////
    .provider('AclService', [ function () 
    {
        var config = {
            storage: 'sessionStorage',
            storageKey: 'AclService'
        };

        var data = {
            roles: [],
            abilities: {}
        };

        /**
         * Does the given role have abilities granted to it?
         *
         * @param role
         * @returns {boolean}
         */
        var roleHasAbilities = function (role) {
            return (typeof data.abilities[role] === 'object');
        };

        /**
         * Retrieve the abilities array for the given role
         *
         * @param role
         * @returns {Array}
         */
        var getRoleAbilities = function (role) {
            return (roleHasAbilities(role)) ? data.abilities[role] : [];
        };

        /**
         * Persist data to storage based on config
         */
        var save = function () {
            switch (config.storage) {
                case 'sessionStorage':
                    saveToStorage('sessionStorage');
                    break;
                case 'localStorage':
                    saveToStorage('localStorage');
                    break;
                default:
                    // Don't save
                    return;
            }
        };

        /**
         * Persist data to web storage
         */
        var saveToStorage = function (storagetype) {
            window[storagetype].setItem(config.storageKey, JSON.stringify(data));
        };

        /**
         * Retrieve data from web storage
         */
        var fetchFromStorage = function (storagetype) {
            var data = window[storagetype].getItem(config.storageKey);
            return (data) ? JSON.parse(data) : false;
        };

        var AclService = {};
        AclService.resume = resume;


        /**
         * Restore data from web storage.
         *
         * Returns true if web storage exists and false if it doesn't.
         *
         * @returns {boolean}
         */
        function resume() {
            var storedData;

            switch (config.storage) {
                case 'sessionStorage':
                    storedData = fetchFromStorage('sessionStorage');
                    break;
                case 'localStorage':
                    storedData = fetchFromStorage('localStorage');
                    break;
                default:
                    storedData = null;
            }
            if (storedData) {
                angular.extend(data, storedData);
                return true;
            }

            return false;
        }

        /**
         * Attach a role to the current user
         *
         * @param role
         */
        AclService.attachRole = function (role) {
            if (data.roles.indexOf(role) === -1) {
                data.roles.push(role);
                save();
            }
        };

        /**
         * Remove role from current user
         *
         * @param role
         */
        AclService.detachRole = function (role) {
            var i = data.roles.indexOf(role);
            if (i > -1) {
                data.roles.splice(i, 1);
                save();
            }
        };

        /**
         * Remove all roles from current user
         */
        AclService.flushRoles = function () {
            data.roles = [];
            save();
        };

        /**
         * Check if the current user has role attached
         *
         * @param role
         * @returns {boolean}
         */
        AclService.hasRole = function (role) {
            return (data.roles.indexOf(role) > -1);
        };

        /**
         * Returns the current user roles
         * @returns {Array}
         */
        AclService.getRoles = function () {
            return data.roles;
        };

        /**
         * Set the abilities object (overwriting previous abilities)
         *
         * Each property on the abilities object should be a role.
         * Each role should have a value of an array. The array should contain
         * a list of all of the roles abilities.
         *
         * Example:
         *
         *    {
         *        guest: ['login'],
         *        user: ['logout', 'view_content'],
         *        admin: ['logout', 'view_content', 'manage_users']
         *    }
         *
         * @param abilities
         */
        AclService.setAbilities = function (abilities) {
            data.abilities = abilities;
            save();
        };

        /**
         * Add an ability to a role
         *
         * @param role
         * @param ability
         */
        AclService.addAbility = function (role, ability) {
            if (!data.abilities[role]) {
                data.abilities[role] = [];
            }
            data.abilities[role].push(ability);
            save();
        };

        /**
         * Does current user have permission to do something?
         *
         * @param ability
         * @returns {boolean}
         */
        AclService.can = function (ability) {
            var role, abilities;
            // Loop through roles
            var l = data.roles.length;

            for (; l--;) {
                // Grab the the current role
                role = data.roles[l];
                abilities = getRoleAbilities(role);
                if (abilities.indexOf(ability) > -1) {
                    // Ability is in role abilities
                    return true;
                }
            }
            // We made it here, so the ability wasn't found in attached roles
            return false;
        };

        return {
            config: function (userConfig) {
                angular.extend(config, userConfig);
            },
            resume: resume,
            $get: function () {
                return AclService;
            }
        };

    }])


    
    
    /**
     * Handle global LINK click
     * just to skip jquery to halndle links, etc, since whole routing is done via angular
     * also to prevent to empty route for browser
     * 
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('a',
        function() {
            return {
                restrict: 'E',
                link: function(scope, elem, attrs) {
                    if (attrs.ngClick || attrs.href === '' || attrs.href === '#') {
                        elem.on('click', function(e) {
                            // prevent link click for above criteria
                            e.preventDefault(); 
                        });
                    }
                }
            };
        }
    )
    
    
    /**
     * Awesome highiighting for tables
     * Couldnt find any pure CSS way, so single for events
     * 
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('highlightActiveTableHeader', function(){
        return function(scope, element, attrs){
            var classname = element.attr('highlight-active-table-header');
            var table = element;
            
            table.on('mouseenter mouseleave', 'td', function(e){
                table.find('thead tr:first th').eq(jQuery(this).index())
                  .toggleClass(classname, e.type === 'mouseenter');
            });
            
            // old version, slightly slower
            // 
            //table.delegate('td','mouseover mouseout', function(e) {
            //    if (e.type == 'mouseover') {
            //        table.find('thead tr:first th').eq($(this).index()).addClass(classname);
            //    } else {
            //        table.find('thead tr:first th').eq($(this).index()).removeClass(classname);
            //    }
            //});
        }
    })
    
    
    /**
     * Validator in more native way to check if input is integer
     * 
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('validateInteger', function () 
    {

        var REGEX = /^\-?\d+$/;

        return {
            require: 'ngModel',
            link: function (scope, element, attrs, ctrl) {

                ctrl.$validators.integer = function (modelValue, viewValue) {

                    if (REGEX.test(viewValue)) {
                        return true
                    }
                    return false;
                };
            }
        };
    })
    
    
    /**
     * Validator to check if user put valid regex expresion
     * not so awesome but work
     * 
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('validateRegex', function () 
    {
        return {
            require: 'ngModel',
            link: function (scope, element, attrs, ctrl) {

                ctrl.$validators.isRegex = function (modelValue, viewValue) {
                    viewValue = '/' + viewValue + '/';
                    try {
                        var match = viewValue.match(new RegExp('^/(.*?)/([gimy]*)$'));
                        var regex = new RegExp(match[1], match[2]);
                        
                        regex.test("Hello");
                        return true
                    }
                    catch(e) {
                        return false;
                    }

                    return false;
                };
            }
        };
    })
    
    /**
     * Custom directive to highlight active state in navigation links
     * 
     * 
     * based on
     * https://github.com/angular-ui/ui-router/issues/1431#issuecomment-121929944
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('uiSrefActiveIf', ['$state', function($state) {
        return {
            restrict: "A",
            controller: ['$scope', '$element', '$attrs', function ($scope, $element, $attrs) {
                var state = $attrs.uiSrefActiveIf;
                var arrayStates = state.split(";");

                function update() 
                {
                    found = false;

                    for(i=0; i <= arrayStates.length; i++ )
                    {
                        if ( $state.includes(arrayStates[i]) || $state.is(arrayStates[i]) ) {
                            found = true;
                            break;
                        }
                    }


                    if ( found === true ) {
                        $element.addClass("active");
                    } else {
                        $element.removeClass("active");
                    }
                }

                $scope.$on('$stateChangeSuccess', update);
                update();
            }]
        };
    }])



    /**
     * directive for simple calendar in dashboard
     * 
     * based on
     * https://github.com/angular-ui/ui-router/issues/1431#issuecomment-121929944
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
    .directive('simpleCalendar', [
        '$locale',
        function ($locale) {
            return {
                restrict: 'E',
                template:   '<div class="simple-calendar">'
                                +'<div class="week-header">'
                                    +'<span ng-repeat="day in days track by $index" class="day-name" ng-class="{first: $first, last: $last}">{{day}}</span>'
                                +'</div>'
                                +'<div class="simple-calendar-body">'
                                    +'<div ng-repeat="week in weeks track by $index" class="week" ng-class="{first: $first, last: $last, odd: $odd, even: $even, active: isWeekActive($index+1)}">'
                                        +'<span ng-repeat="day in week.days track by $index" class="day" ng-class="getClassess(day, $first, $last)" ng-click="clickDay(day)">'
                                            +'<span class="number">{{day}}</span>'
                                            +'<div class="events"><span class="badge">{{ getNumberOfFollowups(day) }}</span></div>'
                                            +'&nbsp;'
                                        +'</span>'
                                    +'</div>'
                                +'</div>'
                            +'</div>',
                scope: {
                    'for': '=',
                    selectedDay: '=',
                    options: '=',
                    followups: '=',
                    onClick: '&',
                    activeDay: '&',
                    activeWeek: '&'
                },
                link: function (scope) {
                    scope.$watch('for', function () {
                        var date  = scope['for'];
                        var start = date instanceof Date && new Date(date.getTime()) || new Date();
                        
                        start.setDate(1);
                        var nextMonth = (start.getMonth() + 1) % 12;
                        
                        scope.days = {};
                        if (angular.isDefined(scope.options)) {
                            scope.month = scope.options.shortmonth ? $locale.DATETIME_FORMATS.SHORTMONTH[start.getMonth()] : $locale.DATETIME_FORMATS.MONTH[start.getMonth()];
                            if (scope.options.supershortday) {
                                angular.forEach($locale.DATETIME_FORMATS.SHORTDAY, function (value, key) {
                                    scope.days[key] = value.substring(0, 2);
                                });
                            } else {
                                scope.days = scope.options.shortday ? $locale.DATETIME_FORMATS.SHORTDAY : $locale.DATETIME_FORMATS.DAY;
                            }
                        } else {
                            scope.month = $locale.DATETIME_FORMATS.MONTH[start.getMonth()];
                            scope.days = $locale.DATETIME_FORMATS.DAY;
                        }
                        scope.year  = start.getFullYear();
                        scope.weeks = [{ days: [] }];
                        var week = scope.weeks[0];
                        do {
                            if (start.getDay() === 0 && start.getDate() !== 1) {
                                week = { days: [] };
                                scope.weeks.push(week);
                            }
                            week.days[start.getDay()] = start.getDate();
                            start.setDate(start.getDate() + 1);
                        } while (start.getMonth() !== nextMonth);
                        
                        while (week.days.length < 7) {
                            week.days.push(undefined);
                        }
                    });
                    
                    
                    scope.clickDay = function (day) {
                        if (day !== undefined) {
                            scope.selectedDay = new Date(scope['for'].getFullYear(), scope['for'].getMonth(), day);
                            return scope.onClick({ day: day });
                        }
                    };
                    
                    scope.now = new Date();
                    
                    scope.getNumberOfFollowups = function(day) 
                    {
                        day = day - 1;
                        if(typeof scope.followups[day] != 'number') {
                            return false;
                        }
                        
                        if(scope.followups[day] <= 0) {
                            return false;
                        }
                        
                        return scope.followups[day];
                    };
                    
                    scope.getClassess = function (day, $first, $last) 
                    {
                        clases = [];
                        
                        if (day !== undefined) {
                            clases.push('validDay');
                        }
                        
                        if($first) { clases.push('first'); }
                        if($last)  { clases.push('last'); }
                        
                        
                        if (day == scope.selectedDay.getDate() && scope.selectedDay.getMonth() == scope['for'].getMonth() && scope.selectedDay.getFullYear() == scope['for'].getFullYear() ) {
                            clases.push('activeday');
                        }
                        
                        if (day == scope.now.getDate() && scope.now.getMonth() == scope['for'].getMonth() && scope.selectedDay.getFullYear() == scope['for'].getFullYear() ) {
                            clases.push('today');
                        }
                        
                        number = scope.getNumberOfFollowups(day);
                        if (number)
                        {
                            clases.push('hasEvents');
                            if ( (
                                    day >= scope.now.getDate() && 
                                    scope.now.getMonth() == scope['for'].getMonth() && 
                                    scope.selectedDay.getFullYear() == scope['for'].getFullYear()
                                 ) || (
                                    scope.now.getMonth() < scope['for'].getMonth() && 
                                    scope.selectedDay.getFullYear() <= scope['for'].getFullYear()
                                 ))  {
                                clases.push('aftertoday');
                            } else {
                                clases.push('beforetoday');
                            }
                        }
                        
                        return clases;
                    };
                    
                    scope.isWeekActive = function (week) {
                        return scope.activeWeek({ week: week });
                    };
                    
                }
            };
        }
    ])
    
    .directive('stReset', ['stConfig', '$timeout','$parse', function (stConfig, $timeout, $parse) {
        return {
            require: '^stTable',
            scope: {
                stReset: '='
            },
            link: function (scope, element, attr, ctrl) 
            {
                var tableCtrl   = ctrl;
                var promise     = null;
                var throttle    = attr.stDelay || stConfig.search.delay;
                var event       = attr.stInputEvent || stConfig.search.inputEvent;
                var stReset     = attr.stReset;
                

                //table state -> view
                scope.$watch('stReset', function (newValue, oldValue) {
                    if (newValue !== oldValue && newValue === true) {
                        console.log(element[0]);
                        element[0].value = '';
                        angular.element(element).trigger(event);
//                        ctrl.tableState().search = {};
//                        tableCtrl.search(input, newValue);
                    }
                }, true);

                // view -> table state
//                element.bind(event, function (evt) {
//                    evt = evt.originalEvent || evt;
//                    console.log(evt.target.value);
////                    if (promise !== null) {
////                        $timeout.cancel(promise);
////                    }
////
////                    promise = $timeout(function () {
////                        tableCtrl.search(evt.target.value, attr.stSearch || '');
////                        promise = null;
////                    }, throttle);
//                });
            }
        };
    }])
  
  
  
    .filter('toNumber', function() {
        return function(input) {
          return parseInt(input, 10);
        };
    })
    
    
    .directive('convertToNumber', function() {
        return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function(val) {
                    return parseInt(val, 10);
                });
                ngModel.$formatters.push(function(val) {
                    return '' + val;
                });
            }
        };
    })
    
    .directive('uiSelectRequired', function() {
        return {
            require: 'ngModel',
            link: function(scope, elm, attrs, ctrl) {
                ctrl.$validators.uiSelectRequired = function(modelValue, viewValue) {
                    var determineVal;
                    if (angular.isArray(modelValue)) {
                        determineVal = modelValue;
                    } else if (angular.isArray(viewValue)) {
                        determineVal = viewValue;
                    } else {
                        return false;
                    }

                    return determineVal.length > 0;
                };
            }
        };
    })
    
    .directive('csSelect', function () {
        return {
            require: '^stTable',
            template: '<input type="checkbox"/>',
            scope: {
                row: '=csSelect'
            },
            link: function (scope, element, attr, ctrl) {

                element.bind('change', function (evt) {
                    $timeout(function () {
                        ctrl.select(scope.row, 'multiple');
                    });
                });

                scope.$watch('row.isSelected', function (newValue, oldValue) {
                    if (newValue === true) {
                        element.parent().addClass('info');
                    } else {
                        element.parent().removeClass('info');
                    }
                });
            }
        };
    })
    .directive('validSubmit', ['$parse', function ($parse) {
        return {
            compile: function compile(tElement, tAttrs, transclude) {
                return {
                    post: function postLink(scope, element, iAttrs, controller) {
                        var form = element.controller('form');
                        form.$submitted = false;
                        var fn = $parse(iAttrs.validSubmit);
                        element.on('submit', function(event) {
                            scope.$apply(function() {
                                element.addClass('ng-submitted');
                                form.$submitted = true;
                                if(form.$valid) {
                                    fn(scope, {$event:event});
                                }
                            });
                        });
                        
                        scope.$watch(function() { return form.$valid}, function(isValid) {
                            if(form.$submitted == false) {
                                element.removeClass('has-success').removeClass('has-error');
                                return;
                            };
                            if(isValid) {
                                element.removeClass('has-error').addClass('has-success');
                            } else {
                                element.removeClass('has-success').addClass('has-error');
                            }
                        });
                    }
                }
            }
        }
    }])

    /*!
    * angular-vertilize 1.0.1
    * Christopher Collins
    * https://github.com/Sixthdim/angular-vertilize.git
    * License: MIT
    */
    // Vertilize Container
    .directive('vertilizeContainer', [
      function(){
        return {
          restrict: 'EA',
          controller: [
            '$scope', '$window',
            function($scope, $window){
              // Alias this
              var _this = this;

              // Array of children heights
              _this.childrenHeights = [];

              // API: Allocate child, return index for tracking.
              _this.allocateMe = function(){
                _this.childrenHeights.push(0);
                return (_this.childrenHeights.length - 1);
              };

              // API: Update a child's height
              _this.updateMyHeight = function(index, height){
                _this.childrenHeights[index] = height;
              };

              // API: Get tallest height
              _this.getTallestHeight = function(){
                var height = 0;
                for (var i=0; i < _this.childrenHeights.length; i=i+1){
                  height = Math.max(height, _this.childrenHeights[i]);
                }
                return height;
              };

              // Add window resize to digest cycle
              angular.element($window).bind('resize', function(){
                return $scope.$apply();
              });
            }
          ]
        };
      }
    ])
    // Vertilize Item
    .directive('vertilize', [
      function(){
        return {
          restrict: 'EA',
          require: '^vertilizeContainer',
          link: function(scope, element, attrs, parent){
            // My index allocation
            var myIndex = parent.allocateMe();

            // Get my real height by cloning so my height is not affected.
            var getMyRealHeight = function(){
              var clone = element.clone()
                .removeAttr('vertilize')
                .css({
                  height: '',
                  width: element.outerWidth(),
                  position: 'fixed',
                  top: 0,
                  left: 0,
                  visibility: 'hidden'
                });
              element.after(clone);
              var realHeight = clone.height();
              clone['remove']();
              return realHeight;
            };

            // Watch my height
            scope.$watch(getMyRealHeight, function(myNewHeight){
              if (myNewHeight){
                parent.updateMyHeight(myIndex, myNewHeight);
              }
            });

            // Watch for tallest height change
            scope.$watch(parent.getTallestHeight, function(tallestHeight){
              if (tallestHeight){
                element.css('height', tallestHeight);
              }
            });
          }
        };
      }
    ]);
    
    /**
     * Add asterisk to label when input is required or sth
     * 
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
     */
//    .directive("required", function() {
//    return {
//            restrict: 'A', // only for attributes
//            compile: function(element) {
//                // insert asterisk after elment 
////                element.closest('.form-group').find('.control-label').after("<small class='required'>This field is required!</small>");
////                element.after("<small class='required'>This field is required!</small>");
//            }
//        };
//    });
    
    
    
})();