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
 * So this is a core of whole module
 *
 * Main implementations for module
 * This contain jQuery integration part, reinitialize stuff etc
 *
 * Try to not use angular directives here
 * 
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 */
var mgCRM = (function()
{

/**
 * START: CORE CONTAINERS
 */
var appConfig = {}; // this is automatically injected from TWIG
                    // MODULE config array > PHP > TWIG > and injected here as an js object
                    // sinde we want have access to most important settings/variables here directly

var assetsPath        = '';
var globalImgPath     = 'img/';
var globalPluginsPath = 'plugins/';
var globalCssPath     = 'css/';
var globalViewsPath   = 'views/';
var renderStandalone  = false;

/**
 * END: CORE CONTAINERS
 */

// containers
var settings = {
    gotoSession : 'gotoSession',
    refresh     : 'refresh',
    search      : 'search',
    sessions    : [],
    title       : 'Sessions',
};

////////////////////////////////////////////////////
////////////////////////////////////////////////////
//
/*             UI FEATURES                         */

// Handles the go to top button at the footer
var handleGoTop = function () 
{
    var offset = 300;
    var duration = 500;

    if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {  // ios supported
        jQuery(window).bind("touchend touchcancel touchleave", function(e){
           if (jQuery(this).scrollTop() > offset) {
                jQuery('.scroll-to-top').fadeIn(duration);
            } else {
                jQuery('.scroll-to-top').fadeOut(duration);
            }
        });
    } else {  // general
        jQuery(window).scroll(function() {
            if (jQuery(this).scrollTop() > offset) {
                jQuery('.scroll-to-top').fadeIn(duration);
            } else {
                jQuery('.scroll-to-top').fadeOut(duration);
            }
        });
    }

    jQuery('.scroll-to-top').click(function(e) {
        e.preventDefault();
        mgCRM.scrollTop();
        return false;
    });
};


// Handles box tools & actions
var handleBoxFullscreen= function() 
{
    // handle box fullscreen
    jQuery('body').on('click', '.box > .box-title .fullscreen', function(e) {
        e.preventDefault();
        var box = jQuery(this).closest(".box");
        if (box.hasClass('box-fullscreen')) {
            jQuery(this).removeClass('on');
            box.removeClass('box-fullscreen');
            jQuery('body').removeClass('page-box-fullscreen');
            box.children('.box-body').css('height', 'auto');
        } else {
            var height = mgCRM.getViewPort().height -
                box.children('.box-title').outerHeight() -
                parseInt(box.children('.box-body').css('padding-top')) -
                parseInt(box.children('.box-body').css('padding-bottom'));

            jQuery(this).addClass('on');
            box.addClass('box-fullscreen');
            jQuery('body').addClass('page-box-fullscreen');
            box.children('.box-body').css('height', height);
        }
    });
};




var handleFullScreenCRMAppButton = function() 
{

    jQuery('body').on('click', '.full-screen-module-toogle', function(e) {
        e.preventDefault();

        var container = jQuery(".full-screen-module-container");
        if (container.hasClass('full-screen-module-on'))
        {
            container.removeClass('full-screen-module-on');
            container.find('i.icon-size-actual').removeClass('icon-size-actual').addClass('icon-size-fullscreen');
            jQuery('body').removeClass('box-fullscreen').css('height', '');
        }
        else
        {
//            var height = mgCRM.getViewPort().height -
//                box.children('.box-title').outerHeight() -
//                parseInt(box.children('.box-body').css('padding-top')) -
//                parseInt(box.children('.box-body').css('padding-bottom'));
        
            container.addClass('full-screen-module-on');
            container.find('i.icon-size-fullscreen').removeClass('icon-size-fullscreen').addClass('icon-size-actual');
            jQuery('body').addClass('box-fullscreen').css('height', container.height());
        
        }
    });
}


    // Handles scrollable contents using jQuery SlimScroll plugin.
    var handleScrollers = function () {
        jQuery('.scroller').each(function () {
            var height;
            if (jQuery(this).attr("data-height")) {
                height = jQuery(this).attr("data-height");
            } else {
                height = jQuery(this).css('height');
            }
            
            jQuery(this).slimScroll({
                allowPageScroll: true, // allow page scroll when the element scroll is ended
                size: '7px',
                color: (jQuery(this).attr("data-handle-color")  ? jQuery(this).attr("data-handle-color") : '#bbb'),
                railColor: (jQuery(this).attr("data-rail-color")  ? jQuery(this).attr("data-rail-color") : '#eaeaea'),
                position: 'right',
                height: height,
                alwaysVisible: (jQuery(this).attr("data-always-visible") == "1" ? true : false),
                railVisible: (jQuery(this).attr("data-rail-visible") == "1" ? true : false),
                disableFadeOut: true
            });
        });
    }



// Handle sidebar menu links
var handleMainMenuActiveLink = function(mode, id, data) 
{
    var menu = jQuery('.top-menu ul.nav.mg-navbar');

    if (mode === 'set') {
        el = jQuery('#'+id);
    } else if (mode === 'dynamic') {
        el = jQuery('#contacts-list-sub-'+data);
        
        if (jQuery('#contacts-list-'+data).size() === 1) {
            el = jQuery('#contacts-list-'+data);
            jQuery('#contacts-list-sub-'+data).addClass('active');
        } else {
            el = jQuery('#contacts-list-sub-'+data);
        }
    }
    
    if (!el || el.size() == 0) {
        return;
    }
    
    menu.find('li.open').removeClass('open');
    menu.find('li.active').removeClass('active');

    el.addClass('active');
    if (el.parent('ul.dropdown-menu:not(ul.navbar-nav)').size() === 1) {
        el.parents('li.menu-dropdown').addClass('active');
    }
};

var standaloneAppFixes = function()
{
    renderStandalone = true;
    handleNavigationCollapseOnResize();
};


// Handle window resize
var handleNavigationCollapseOnResize = function() 
{
    var navbarInTwoLines        = false;                
    var navbarOnlyIcons         = false;                
    var moduleLogoSmall         = false;
    var hideModuleLogo          = false;
    var buffor                  = 30;
    
    var navFullWidth            = jQuery('.top-menu .nav-menu:not(.nav-menu-right)').width();
    var navModuleNameWidth      = jQuery('.modulename small').width() + 36;
    
    // determinate ui type
    if(renderStandalone) {
        // wider, since additional WHMCS image
        var navModuleLogoWidth      = 200; //jQuery('.logo-default').width();
        var navModuleLogoCogWidth   = 173; //jQuery('.logo-default-cog').width();
        var navMagentoLogoWidth     = 100;
    } else {
        var navModuleLogoWidth      = 200; //jQuery('.logo-default').width();
        var navModuleLogoCogWidth   = 120; //jQuery('.logo-default-cog').width();
    }


    function NavigationSet() 
    {
        var navMaxWidth             = jQuery('.page-header').width();
        var navRightBannerWidth     = jQuery('.top-menu .nav-menu.nav-menu-right').width();
        var navWidth                = jQuery('.top-menu .nav-menu:not(.nav-menu-right)').width();
        
        // calculate conditions
        if(navMaxWidth         <= (navFullWidth + navRightBannerWidth + navModuleNameWidth + buffor) && !navbarInTwoLines) {
            navbarInTwoLines  = true;
            jQuery('.top-menu .modulename').addClass('centerred');
        } else if(navMaxWidth   > (navFullWidth + navRightBannerWidth + navModuleNameWidth + buffor) && navbarInTwoLines) {
            navbarInTwoLines  = false;
            jQuery('.top-menu .modulename').removeClass('centerred');
        }
        
        if(navMaxWidth         <= (navWidth + navModuleLogoWidth + buffor + navMagentoLogoWidth) && !moduleLogoSmall) {
            moduleLogoSmall = true;
            jQuery('.top-menu .modulename-logo').addClass('only-cog');
        } else if(navMaxWidth   > (navWidth + navModuleLogoWidth + buffor + navMagentoLogoWidth) && moduleLogoSmall) {
            moduleLogoSmall = false;
            jQuery('.top-menu .modulename-logo').removeClass('only-cog');
        }
            
        if(navMaxWidth         <= (navFullWidth + navModuleLogoCogWidth + buffor + navMagentoLogoWidth) && !navbarOnlyIcons) {
            navbarOnlyIcons = true;
            jQuery('.top-menu .nav-menu:not(.nav-menu-right)').addClass('only-icons');
        } else if(navMaxWidth   > (navFullWidth + navModuleLogoCogWidth + buffor + navMagentoLogoWidth) && navbarOnlyIcons) {
            navbarOnlyIcons = false;
            jQuery('.top-menu .nav-menu:not(.nav-menu-right)').removeClass('only-icons');
        }
        
        if(navMaxWidth         <= (navWidth + navRightBannerWidth) && !hideModuleLogo) {
            hideModuleLogo = true;
            jQuery('.top-menu .nav-menu-right').hide();
        } else if(navMaxWidth   > (navWidth + navRightBannerWidth) && hideModuleLogo) {
            hideModuleLogo = false;
            jQuery('.top-menu .nav-menu-right').show();
        }
    }
    NavigationSet();
    jQuery(document).ready(NavigationSet);
    jQuery(window).resize(NavigationSet);
};

/**
 * START: AVAILABLE METHOD DIRECTIVES
 */
return {
    // set config
    setConfig: function(config) {
        this.appConfig = config;

        this.setAssetsPath(this.getConfig('rootDir') + '/assets/');
        globalViewsPath = this.getConfig('rootDir') + '/views/';
    },

    // get config or single variable FROM config
    getConfig: function(what){
        if(typeof what === 'undefined') {
            return this.appConfig;
        } else if(typeof what === 'string') {
            if(typeof this.appConfig[what] != 'undefined') {
                return this.appConfig[what];
            } else return false;
        } else
            return false;
    },

    // Most important function !
    init: function(standalone) {
        // console.clear();
        // IMPORTANT!!!: Do not modify the core handlers order. I'll kill you

        //Core handlers
        // handleInit(); // initialize core variables
        this.initFooter();
        if(standalone === true) {
            standaloneAppFixes();
        }
    },

    //public helper function to get actual input value(used in IE9 and IE8 due to placeholder attribute not supported)
    getActualVal: function(el) {
        el = jQuery(el);
        if (el.val() === el.attr("placeholder")) {
            return "";
        }
        return el.val();
    },


    //public function to get a paremeter by name from URL
    getURLParameter: function(paramName) {
        var searchString = window.location.search.substring(1),
            i, val, params = searchString.split("&");

        for (i = 0; i < params.length; i++) {
            val = params[i].split("=");
            if (val[0] == paramName) {
                return unescape(val[1]);
            }
        }
        return null;
    },

    // assets
    setAssetsPath: function(path) {
        assetsPath = path;
    },
    getAssetsPath: function() {
        return assetsPath;
    },

    // img path
    getImgPath: function() {
        return assetsPath + globalImgPath;
    },

    // img plugins
    getPluginsPath: function() {
        return assetsPath + globalPluginsPath;
    },

    // css plugins
    getCssPath: function() {
        return assetsPath + globalCssPath;
    },

    // views
    globalViewsPath: function() {
        return globalViewsPath;
    },
    // views
    templateViewsPath: function(name) {
        return globalViewsPath + name;
    },


    ////////////////////////////////////////////////////
    ////////////////////////////////////////////////////
    //
    /*           ASTERISK INTEGRATION                  */



    // views
    openAsterishWidget: function(destination) {
        if(jQuery("#originateCallForm").length) {
            jQuery("#originateCallForm input[name=destination]").val(destination);
            jQuery("#calloutwidget").dialog({minWidth: 350,close: function(){}});
        }
    },



    ////////////////////////////////////////////////////
    ////////////////////////////////////////////////////
    //
    /*             UI FEATURES                         */

    initFooter: function() {
        //handles scroll to top functionality in the footer
        handleGoTop();
        handleBoxFullscreen();
        handleFullScreenCRMAppButton();
        handleScrollers();
        handleNavigationCollapseOnResize();
    },


//        startPageLoading: function(options) {
//            if (options && options.animate) {
//                jQuery('.page-spinner-bar').remove();
//                jQuery('body').append('<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
//            } else {
//                jQuery('.page-loading').remove();
//                jQuery('body').append('<div class="page-loading"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif"/>&nbsp;&nbsp;<span>' + (options && options.message ? options.message : 'Loading...') + '</span></div>');
//            }
//        },
//
//        stopPageLoading: function() {
//            jQuery('.page-loading, .page-spinner-bar').remove();
//        },


    // set active element in main navigation
    setMainMenuActiveLink: function(mode, id, data) {
        handleMainMenuActiveLink(mode, id, data);
    },

    
    // function to scroll to the top
    scrollTop: function() {
        this.scrollTo();
    },
    
    // reinitiate scroolers
    handleScrollers: function() {
        handleScrollers();
    },
    
    // things to do after ajax change
    // things to reinitialize etc
    reInitializeAfterAjax: function() {
        handleScrollers();
    },
        
    // scrool to selected element
    scrollTo: function(el, offeset) {
        var pos = (el && el.size() > 0) ? el.offset().top : ((this.getConfig('scroolModule') && this.getConfig('scroolModule') === true) ? jQuery('.mg-wrapper.body').offset().top : 0);
        
        if (el) {
            if (jQuery('.mg-wrapper.body').hasClass('page-header-fixed')) {
                pos = pos - jQuery('.page-header').height();
            }
            pos = pos + (offeset ? offeset : -1 * el.height());
        }

        
        jQuery('html, body').animate({
            scrollTop: pos
        }, 'fast');
    },
        
    // To get the correct viewport width based on  http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
    getViewPort: function() {
        var e = window,
            a = 'inner';
        if (!('innerWidth' in window)) {
            a = 'client';
            e = document.documentElement || document.body;
        }

        return {
            width: e[a + 'Width'],
            height: e[a + 'Height']
        };
    },

};
/**
 * END: AVAILABLE METHOD DIRECTIVES
 */

}());
