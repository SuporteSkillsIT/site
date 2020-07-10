<?php

/* standalone.twig */
class __TwigTemplate_11e875984622a456559dee02a6e51161a51ccf611aba2ebea18287934e9f98f1 extends Twig_Template
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
        echo "<!DOCTYPE html>
<!--[if IE 8]> <html lang=\"en\" class=\"ie8 no-js\" data-ng-app=\"mgCRMapp\"> <![endif]-->
<!--[if IE 9]> <html lang=\"en\" class=\"ie9 no-js\" data-ng-app=\"mgCRMapp\"> <![endif]-->
<!--[if !IE]><!-->
<html lang=\"en_US\" data-ng-app=\"mgCRMapp\">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
";
        // line 10
        echo "    <meta charset=\"UTF-8\">
    <title data-ng-bind=\"page.title\"></title>

    <meta charset=\"utf-8\"/>
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <!-- <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"/> -->
    <meta content=\"\" name=\"description\"/>
    <meta content=\"\" name=\"author\"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

            <!-- START GLOBALS -->
                
";
        // line 23
        echo "
                <!-- Header External Includes-->
                ";
        // line 26
        echo "                <link href=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "url_scheme", array()), "html", null, true);
        echo "://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all\" rel=\"stylesheet\" type=\"text/css\"/>
                ";
        // line 28
        echo "                <link href=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/font-awesome/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/simple-line-icons/simple-line-icons.min.css\" rel=\"stylesheet\" type=\"text/css\" />

                <!-- Header Include Libs -->
                <link href=\"";
        // line 32
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/jquery-ui/css/jquery-ui.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 33
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/bootstrap/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\" />

                <link href=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ng-dialog/css/ngDialog.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 36
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ng-dialog/css/ngDialog-theme-default.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-xeditable/css/xeditable.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/block-ui/angular-block-ui.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ui-select/dist/select.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/textAngular/dist/textAngular.min.css\" rel=\"stylesheet\" type=\"text/css\" />
                <link href=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.min.css\" rel=\"stylesheet\" type=\"text/css\" />


            <!-- END GLOBALS -->

            <!-- START DYMANICLY LOADED CSS FILES -->
            <!-- here is a place where angular will put css/js files needed to load dynamically NEEDS TO BE AFTER GLOBALS -->
            <link id=\"ng_load_plugins_before\" />
            <!-- END DYMANICLY LOADED CSS FILES -->


            <!-- Header Include General Style -->
            <link href=\"";
        // line 53
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/global-components.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <!-- Header Include Layout specific colors theme  -->
            <link href=\"";
        // line 55
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/layout.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            ";
        // line 57
        echo "            <!-- Header Include Layout plugins custom styles -->
            <link href=\"";
        // line 58
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/plugins.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <!-- CUSTOM DEFINED !? just for module purpose -->
            <link href=\"";
        // line 60
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/custom.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <!-- possible to mergo above -->

            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
                <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
                <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
            <![endif]-->

            <!-- CSS DIRECTIVES TO PUT IMAGES URL ETC -->


            <!-- Header Include General Style -->
            <link href=\"";
        // line 74
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/global-components.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <!-- Header Include Layout specific colors theme  -->
            <link href=\"";
        // line 76
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/layout.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <link href=\"";
        // line 77
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/default.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <!-- Header Include Layout plugins custom styles -->
            <link href=\"";
        // line 79
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/plugins.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <!-- CUSTOM DEFINED !? just for module purpose -->
            <link href=\"";
        // line 81
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/css/custom.min.css\" rel=\"stylesheet\" type=\"text/css\" />
            <!-- possible to mergo above -->

            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
                <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
                <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
            <![endif]-->

            <!-- site icon -->
            <link rel=\"shortcut icon\" href=\"";
        // line 92
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/img/favicon.png\"/>

            <style>
                .mg-wrapper .mg-loader,
                .mg-wrapper .mg-loader-img {
                    background: url(";
        // line 97
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/img/mg-loader.gif);
                }
                .mg-wrapper .mg-loader-flow {
                    background: url(";
        // line 100
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/img/mg-loader-flow.png);
                }
            </style>
        
  </head>

  <!-- START BODY -->
  <!-- <body class=\"skin-blue sidebar-mini\"> -->
  <body  style=\"margin: 0;\">

  <!-- dont forget \"mg-wrapper\" ! -->
    <div ng-controller=\"AppController\" class=\"full-screen-module-container mg-wrapper body\" data-target=\".body\" data-spy=\"scroll\" data-twttr-rendered=\"true\">




            <!-- PAGE SPINNER will be here -->

            <!-- END PAGE SPINNER -->

            <!-- BEGIN HEADER -->
            <div class=\"page-header\">
               ";
        // line 122
        $this->loadTemplate("partials/header.twig", "standalone.twig", 122)->display($context);
        // line 123
        echo "            </div>
            <!-- END HEADER -->

            <div class=\"clearfix\">
            </div>

            <!-- BEGIN CONTAINER -->
            <div class=\"page-container\">

                <!-- BEGIN PAGE HEAD -->
                <!-- <div {* data-ng-include=\"'modsubpage/head.html'\" data-ng-controller=\"PageHeadController\" *} class=\"page-head\"> -->
    ";
        // line 135
        echo "                    ";
        // line 136
        echo "    ";
        // line 137
        echo "                <!-- END PAGE HEAD -->

                <!-- BEGIN PAGE CONTENT -->
                <!-- usable with class: container-fluid -->
                <div class=\"page-content\" ng-controller=\"ContentController\">

                    <!-- BEGIN PAGE SPINNER -->
                    <div ng-spinner-bar class=\"page-mg-loadingbar\">
                        <div class=\"mg-loader\"></div>
                    </div>
                    <!-- END PAGE SPINNER -->


                    <div class=\"container-fluid fade-in-up\">

                        ";
        // line 153
        echo "                        <div class=\"clearfix\"></div>
                        <div class=\"container-fluid raw\">
                            ";
        // line 156
        echo "                            <ui-breadcrumbs displayname-property=\"data.pageTitle\" abstract-proxy-property=\"data.proxy\" class=\"pull-left\"></ui-breadcrumbs>
                            ";
        // line 158
        echo "                            <loading-notification></loading-notification>
                            <acl-no-access-notification></acl-no-access-notification>
                        </div>
                        <div ng-controller=\"NotificationsController\">
                            <div class=\"row-fluid ng-hide\" ng-show=\"showNotifications\" style=\"display:none;\" ng-style=\"{'display': (showNotifications) ? 'block':'none'}\">
                                <div class=\"box light margin-bottom-10\" ng-class=\"{'toogled': hiddenBox }\">
                                    <div class=\"box-title\">
                                        <div class=\"caption\">
                                            <span class=\"font-red-thunderbird uppercase pull-left\" ng-bind-html=\" ( notifications.length > 1 ? 'notifications.main.widget.many' : 'notifications.main.widget.single') | translate:{ num: notifications.length }\"></span>
                                        </div>
                                        <div class=\"actions\">
                                            <a href=\"#\" ng-show=\"hiddenBox\" ng-click=\"hiddenBox=!hiddenBox\" class=\"btn btn-sm btn-danger btn-circle btn-outline btn-inverse btn-transparent btn-icon-only\">
                                                <i class=\"fa fa-expand\"></i>
                                            </a>
                                            <a href=\"#\" ng-hide=\"hiddenBox\" ng-click=\"hiddenBox=!hiddenBox\" class=\"btn btn-sm btn-danger btn-circle btn-outline btn-inverse btn-transparent btn-icon-only\">
                                                <i class=\"fa fa-compress\"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class=\"box-body notifications\">
                                       <div class=\"note padding-right-15\" ng-class=\"n.class\" ng-repeat=\"n in notifications\">
                                            <span ng-show=\"n.accepted_at && n.confirmation\" class=\"text-muted small pull-right\">";
        // line 179
        echo "{{";
        echo " ::('notifications.main.accepted' | translate) ";
        echo "}}";
        echo " ";
        echo "{{";
        echo " n.accepted_at ";
        echo "}}";
        echo "</span>
                                            <p ng-bind-html=\"n.content\"  style=\"margin-bottom:0;\"></p>
                                            <button ng-show=\"n.confirmation && !n.accepted_at\" style=\"margin-top:10px;\" ng-click=\"acceptNote(n.id)\" class=\"btn btn-sm btn-success btn-inverse\">";
        // line 181
        echo "{{";
        echo " ::('notifications.main.btn.accept' | translate) ";
        echo "}}";
        echo "</button>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ";
        // line 189
        echo "
                        ";
        // line 191
        echo "                        <div ui-view>
                        </div>
                        ";
        // line 194
        echo "
                    </div>
                </div>
                <!-- END PAGE CONTENT -->


                <!-- BEGIN FOOTER -->
                <div class=\"page-footer\">
                    <div class=\"container\">
                        ";
        // line 203
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => "CRM"), "method"), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => "Customer Relationship Manager"), "method"), "html", null, true);
        echo ". 2016 &copy; <a href=\"http://www.modulesgarden.com\" class=\"mgBanner\" target=\"_blank\">ModulesGarden</a>
                    </div>
                </div>
                <div class=\"scroll-to-top\">
                    <i class=\"icon-arrow-up\"></i>
                </div>
                <!-- END FOOTER -->

            </div>
            <!-- END CONTAINER -->

            <!-- BEGIN CORE JQUERY PLUGINS -->
            <!--[if lt IE 9]>
            <script src=\"";
        // line 216
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/respond.min.js\"></script>
            <script src=\"";
        // line 217
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/excanvas.min.js\"></script>
            <![endif]-->

            <!-- JQUERY -->
";
        // line 222
        echo "            <script src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/jquery/jquery-2.1.4.min.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 223
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/jquery-ui/js/jquery-ui.min.js\" type=\"text/javascript\"></script>
                
            <!-- CORE JQUERY PLUGINS -->
            <script src=\"";
        // line 226
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/bootstrap/js/bootstrap.min.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 227
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 228
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/jquery-blockui/jquery.blockui.min.js\" type=\"text/javascript\"></script>
";
        // line 231
        echo "            <script src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js\" type=\"text/javascript\"></script>
            <!-- END CORE JQUERY PLUGINS -->


            ";
        // line 236
        echo "            <script src=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/app.mgCRM.js\" type=\"text/javascript\"></script>


            <!-- BEGIN CORE ANGULARJS PLUGINS -->
            <script src=\"";
        // line 240
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 241
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-sanitize.js\" type=\"text/javascript\"></script> 
            <script src=\"";
        // line 242
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-animate.min.js\" type=\"text/javascript\"></script> 
            <script src=\"";
        // line 243
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ui-router/angular-ui-router.min.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 244
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-resource.js\" type=\"text/javascript\"></script> 
            <script src=\"";
        // line 245
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ocLazyLoad/dist/ocLazyLoad.min.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 246
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-translate/angular-translate.min.js\"></script>
            <script src=\"";
        // line 247
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ui-bootstrap/ui-bootstrap-tpls-0.13.4.min.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 248
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ui.bootstrap.datetimepicker/dist/datetime-picker.min.js\"></script>
            <script src=\"";
        // line 249
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ng-dialog/js/ngDialog.min.js\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 250
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/angular-xeditable/js/xeditable.min.js\"></script>
            <script src=\"";
        // line 251
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/block-ui/angular-block-ui.min.js\"></script>
            <script src=\"";
        // line 252
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ui-sortable/sortable.min.js\"></script>
            <script src=\"";
        // line 253
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/ui-select/dist/select.min.js\"></script>
            <script src=\"";
        // line 254
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/Smart-Table/dist/smart-table.js\"></script>
            <script src=\"";
        // line 255
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/textAngular/dist/textAngular-rangy.min.js\"></script>
            <script src=\"";
        // line 256
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/textAngular/dist/textAngular-sanitize.min.js\"></script>
            <script src=\"";
        // line 257
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/angularjs/textAngular/dist/textAngular.min.js\"></script>
            <script src=\"";
        // line 258
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/plugins/moment/moment.min.js\"></script>
";
        // line 260
        echo "            <!-- END CORE ANGULARJS PLUGINS -->

            <!-- BEGIN APP LEVEL ANGULARJS SCRIPTS -->
            <!-- load CRM module for angular -->
            <script src=\"";
        // line 264
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/app/app.module.CRM.js\" type=\"text/javascript\"></script> 
                ";
        // line 266
        echo "                ";
        // line 267
        echo "                    ";
        $this->loadTemplate("app/app.angular.js.twig", "standalone.twig", 267)->display($context);
        // line 268
        echo "                ";
        // line 269
        echo "            <!-- END APP LEVEL ANGULARJS SCRIPTS -->

            <script type=\"text/javascript\">
            ";
        // line 273
        echo "
                // maybe will be usefull string escape
                function jqescape(str) { return str.replace(/[#;&,\\.\\+\\*~':\"!\\^\\\$\\[\\]\\(\\)=>|\\/\\\\]/g, '\\\\\$&'); }

                // init JavaScript app
                \$(document).ready(function() 
                {
                    // js template wrapper for some nesesary actions
                    // related only with template, nothing more
                    mgCRM.init(true);
                });

            ";
        // line 286
        echo "            </script>
            <!-- END JAVASCRIPTS -->

            <!-- ASTERISK WIDGET TO CALL -->
            ";
        // line 290
        if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "integrations", array()), "asterisk", array())) {
            // line 291
            echo "                ";
            $this->loadTemplate("partials/asteriskjs.twig", "standalone.twig", 291)->display($context);
            // line 292
            echo "            ";
        }
        // line 293
        echo "
        </div><!-- END main angular app wrapper -->



  </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "standalone.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  518 => 293,  515 => 292,  512 => 291,  510 => 290,  504 => 286,  490 => 273,  485 => 269,  483 => 268,  480 => 267,  478 => 266,  474 => 264,  468 => 260,  464 => 258,  460 => 257,  456 => 256,  452 => 255,  448 => 254,  444 => 253,  440 => 252,  436 => 251,  432 => 250,  428 => 249,  424 => 248,  420 => 247,  416 => 246,  412 => 245,  408 => 244,  404 => 243,  400 => 242,  396 => 241,  392 => 240,  384 => 236,  376 => 231,  372 => 228,  368 => 227,  364 => 226,  358 => 223,  353 => 222,  346 => 217,  342 => 216,  324 => 203,  313 => 194,  309 => 191,  306 => 189,  294 => 181,  283 => 179,  260 => 158,  257 => 156,  253 => 153,  236 => 137,  234 => 136,  232 => 135,  219 => 123,  217 => 122,  192 => 100,  186 => 97,  178 => 92,  164 => 81,  159 => 79,  154 => 77,  150 => 76,  145 => 74,  128 => 60,  123 => 58,  120 => 57,  116 => 55,  111 => 53,  96 => 41,  92 => 40,  88 => 39,  84 => 38,  80 => 37,  76 => 36,  72 => 35,  67 => 33,  63 => 32,  57 => 29,  52 => 28,  47 => 26,  43 => 23,  29 => 10,  19 => 1,);
    }
}
/* <!DOCTYPE html>*/
/* <!--[if IE 8]> <html lang="en" class="ie8 no-js" data-ng-app="mgCRMapp"> <![endif]-->*/
/* <!--[if IE 9]> <html lang="en" class="ie9 no-js" data-ng-app="mgCRMapp"> <![endif]-->*/
/* <!--[if !IE]><!-->*/
/* <html lang="en_US" data-ng-app="mgCRMapp">*/
/* <!--<![endif]-->*/
/* <!-- BEGIN HEAD -->*/
/* <head>*/
/* {#  <head>#}*/
/*     <meta charset="UTF-8">*/
/*     <title data-ng-bind="page.title"></title>*/
/* */
/*     <meta charset="utf-8"/>*/
/*     <meta http-equiv="X-UA-Compatible" content="IE=edge">*/
/*     <!-- <meta content="width=device-width, initial-scale=1" name="viewport"/> -->*/
/*     <meta content="" name="description"/>*/
/*     <meta content="" name="author"/>*/
/*     <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>*/
/* */
/*             <!-- START GLOBALS -->*/
/*                 */
/* {#                <link href="{{ settings.templates.whmcsDir }}assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">#}*/
/* */
/*                 <!-- Header External Includes-->*/
/*                 {# font! #}*/
/*                 <link href="{{ settings.url_scheme }}://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>*/
/*                 {# fucking awesome icons #}*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />*/
/* */
/*                 <!-- Header Include Libs -->*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/jquery-ui/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />*/
/* */
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ng-dialog/css/ngDialog.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ng-dialog/css/ngDialog-theme-default.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-xeditable/css/xeditable.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/angularjs/block-ui/angular-block-ui.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ui-select/dist/select.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/angularjs/textAngular/dist/textAngular.min.css" rel="stylesheet" type="text/css" />*/
/*                 <link href="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.min.css" rel="stylesheet" type="text/css" />*/
/* */
/* */
/*             <!-- END GLOBALS -->*/
/* */
/*             <!-- START DYMANICLY LOADED CSS FILES -->*/
/*             <!-- here is a place where angular will put css/js files needed to load dynamically NEEDS TO BE AFTER GLOBALS -->*/
/*             <link id="ng_load_plugins_before" />*/
/*             <!-- END DYMANICLY LOADED CSS FILES -->*/
/* */
/* */
/*             <!-- Header Include General Style -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/global-components.min.css" rel="stylesheet" type="text/css" />*/
/*             <!-- Header Include Layout specific colors theme  -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/layout.min.css" rel="stylesheet" type="text/css" />*/
/*             {# <link href="{{ settings.templates.rootDir }}/assets/css/default.min.css" rel="stylesheet" type="text/css" /> #}*/
/*             <!-- Header Include Layout plugins custom styles -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/plugins.min.css" rel="stylesheet" type="text/css" />*/
/*             <!-- CUSTOM DEFINED !? just for module purpose -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/custom.min.css" rel="stylesheet" type="text/css" />*/
/*             <!-- possible to mergo above -->*/
/* */
/*             <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->*/
/*             <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->*/
/*             <!--[if lt IE 9]>*/
/*                 <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>*/
/*                 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>*/
/*             <![endif]-->*/
/* */
/*             <!-- CSS DIRECTIVES TO PUT IMAGES URL ETC -->*/
/* */
/* */
/*             <!-- Header Include General Style -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/global-components.min.css" rel="stylesheet" type="text/css" />*/
/*             <!-- Header Include Layout specific colors theme  -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/layout.min.css" rel="stylesheet" type="text/css" />*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/default.min.css" rel="stylesheet" type="text/css" />*/
/*             <!-- Header Include Layout plugins custom styles -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/plugins.min.css" rel="stylesheet" type="text/css" />*/
/*             <!-- CUSTOM DEFINED !? just for module purpose -->*/
/*             <link href="{{ settings.templates.rootDir }}/assets/css/custom.min.css" rel="stylesheet" type="text/css" />*/
/*             <!-- possible to mergo above -->*/
/* */
/*             <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->*/
/*             <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->*/
/*             <!--[if lt IE 9]>*/
/*                 <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>*/
/*                 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>*/
/*             <![endif]-->*/
/* */
/*             <!-- site icon -->*/
/*             <link rel="shortcut icon" href="{{ settings.templates.rootDir }}/assets/img/favicon.png"/>*/
/* */
/*             <style>*/
/*                 .mg-wrapper .mg-loader,*/
/*                 .mg-wrapper .mg-loader-img {*/
/*                     background: url({{ settings.templates.rootDir }}/assets/img/mg-loader.gif);*/
/*                 }*/
/*                 .mg-wrapper .mg-loader-flow {*/
/*                     background: url({{ settings.templates.rootDir }}/assets/img/mg-loader-flow.png);*/
/*                 }*/
/*             </style>*/
/*         */
/*   </head>*/
/* */
/*   <!-- START BODY -->*/
/*   <!-- <body class="skin-blue sidebar-mini"> -->*/
/*   <body  style="margin: 0;">*/
/* */
/*   <!-- dont forget "mg-wrapper" ! -->*/
/*     <div ng-controller="AppController" class="full-screen-module-container mg-wrapper body" data-target=".body" data-spy="scroll" data-twttr-rendered="true">*/
/* */
/* */
/* */
/* */
/*             <!-- PAGE SPINNER will be here -->*/
/* */
/*             <!-- END PAGE SPINNER -->*/
/* */
/*             <!-- BEGIN HEADER -->*/
/*             <div class="page-header">*/
/*                {% include 'partials/header.twig' %}*/
/*             </div>*/
/*             <!-- END HEADER -->*/
/* */
/*             <div class="clearfix">*/
/*             </div>*/
/* */
/*             <!-- BEGIN CONTAINER -->*/
/*             <div class="page-container">*/
/* */
/*                 <!-- BEGIN PAGE HEAD -->*/
/*                 <!-- <div {* data-ng-include="'modsubpage/head.html'" data-ng-controller="PageHeadController" *} class="page-head"> -->*/
/*     {#            <div class="page-head">#}*/
/*                     {# {% include 'partials/head.twig' %} #}*/
/*     {#            </div>#}*/
/*                 <!-- END PAGE HEAD -->*/
/* */
/*                 <!-- BEGIN PAGE CONTENT -->*/
/*                 <!-- usable with class: container-fluid -->*/
/*                 <div class="page-content" ng-controller="ContentController">*/
/* */
/*                     <!-- BEGIN PAGE SPINNER -->*/
/*                     <div ng-spinner-bar class="page-mg-loadingbar">*/
/*                         <div class="mg-loader"></div>*/
/*                     </div>*/
/*                     <!-- END PAGE SPINNER -->*/
/* */
/* */
/*                     <div class="container-fluid fade-in-up">*/
/* */
/*                         {# BEGIN GLOBAL CONTENT DIRECTIVES #}*/
/*                         <div class="clearfix"></div>*/
/*                         <div class="container-fluid raw">*/
/*                             {# BREADCRUMBS DIRECTIVES #}*/
/*                             <ui-breadcrumbs displayname-property="data.pageTitle" abstract-proxy-property="data.proxy" class="pull-left"></ui-breadcrumbs>*/
/*                             {# SMART NOTIFCATIONS INDICATOR #}*/
/*                             <loading-notification></loading-notification>*/
/*                             <acl-no-access-notification></acl-no-access-notification>*/
/*                         </div>*/
/*                         <div ng-controller="NotificationsController">*/
/*                             <div class="row-fluid ng-hide" ng-show="showNotifications" style="display:none;" ng-style="{'display': (showNotifications) ? 'block':'none'}">*/
/*                                 <div class="box light margin-bottom-10" ng-class="{'toogled': hiddenBox }">*/
/*                                     <div class="box-title">*/
/*                                         <div class="caption">*/
/*                                             <span class="font-red-thunderbird uppercase pull-left" ng-bind-html=" ( notifications.length > 1 ? 'notifications.main.widget.many' : 'notifications.main.widget.single') | translate:{ num: notifications.length }"></span>*/
/*                                         </div>*/
/*                                         <div class="actions">*/
/*                                             <a href="#" ng-show="hiddenBox" ng-click="hiddenBox=!hiddenBox" class="btn btn-sm btn-danger btn-circle btn-outline btn-inverse btn-transparent btn-icon-only">*/
/*                                                 <i class="fa fa-expand"></i>*/
/*                                             </a>*/
/*                                             <a href="#" ng-hide="hiddenBox" ng-click="hiddenBox=!hiddenBox" class="btn btn-sm btn-danger btn-circle btn-outline btn-inverse btn-transparent btn-icon-only">*/
/*                                                 <i class="fa fa-compress"></i>*/
/*                                             </a>*/
/*                                         </div>*/
/*                                     </div>*/
/*                                     <div class="box-body notifications">*/
/*                                        <div class="note padding-right-15" ng-class="n.class" ng-repeat="n in notifications">*/
/*                                             <span ng-show="n.accepted_at && n.confirmation" class="text-muted small pull-right">{{ '{{' }} ::('notifications.main.accepted' | translate) {{ '}}' }} {{ '{{' }} n.accepted_at {{ '}}' }}</span>*/
/*                                             <p ng-bind-html="n.content"  style="margin-bottom:0;"></p>*/
/*                                             <button ng-show="n.confirmation && !n.accepted_at" style="margin-top:10px;" ng-click="acceptNote(n.id)" class="btn btn-sm btn-success btn-inverse">{{ '{{' }} ::('notifications.main.btn.accept' | translate) {{ '}}' }}</button>*/
/*                                        </div>*/
/*                                     </div>*/
/*                                 </div>*/
/*                             </div>*/
/*                         </div>*/
/* */
/*                         {# END ACTUAL CONTENT #}*/
/* */
/*                         {# BEGIN ACTUAL CONTENT #}*/
/*                         <div ui-view>*/
/*                         </div>*/
/*                         {# END ACTUAL CONTENT #}*/
/* */
/*                     </div>*/
/*                 </div>*/
/*                 <!-- END PAGE CONTENT -->*/
/* */
/* */
/*                 <!-- BEGIN FOOTER -->*/
/*                 <div class="page-footer">*/
/*                     <div class="container">*/
/*                         {{ lang.translate( 'CRM' ) }} - {{ lang.translate( 'Customer Relationship Manager' ) }}. 2016 &copy; <a href="http://www.modulesgarden.com" class="mgBanner" target="_blank">ModulesGarden</a>*/
/*                     </div>*/
/*                 </div>*/
/*                 <div class="scroll-to-top">*/
/*                     <i class="icon-arrow-up"></i>*/
/*                 </div>*/
/*                 <!-- END FOOTER -->*/
/* */
/*             </div>*/
/*             <!-- END CONTAINER -->*/
/* */
/*             <!-- BEGIN CORE JQUERY PLUGINS -->*/
/*             <!--[if lt IE 9]>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/respond.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/excanvas.min.js"></script>*/
/*             <![endif]-->*/
/* */
/*             <!-- JQUERY -->*/
/* {#            <script src="{{ settings.templates.rootDir }}/assets/plugins/jquery/jquery-1.11.2.min.js" type="text/javascript"></script>#}*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/jquery-ui/js/jquery-ui.min.js" type="text/javascript"></script>*/
/*                 */
/*             <!-- CORE JQUERY PLUGINS -->*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/jquery-blockui/jquery.blockui.min.js" type="text/javascript"></script>*/
/* {#            <script src="{{ settings.templates.rootDir }}/assets/plugins/jquery-cokie/jquery.cokie.min.js" type="text/javascript"></script>#}*/
/* {#            <script src="{{ settings.templates.rootDir }}/assets/plugins/jquery-uniform/jquery.uniform.min.js" type="text/javascript"></script>#}*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>*/
/*             <!-- END CORE JQUERY PLUGINS -->*/
/* */
/* */
/*             {# APLICATION #}*/
/*             <script src="{{ settings.templates.rootDir }}/app/app.mgCRM.js" type="text/javascript"></script>*/
/* */
/* */
/*             <!-- BEGIN CORE ANGULARJS PLUGINS -->*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-sanitize.js" type="text/javascript"></script> */
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-animate.min.js" type="text/javascript"></script> */
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ui-router/angular-ui-router.min.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-resource.js" type="text/javascript"></script> */
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ocLazyLoad/dist/ocLazyLoad.min.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-translate/angular-translate.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ui-bootstrap/ui-bootstrap-tpls-0.13.4.min.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ui.bootstrap.datetimepicker/dist/datetime-picker.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ng-dialog/js/ngDialog.min.js" type="text/javascript"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-xeditable/js/xeditable.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/block-ui/angular-block-ui.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ui-sortable/sortable.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/ui-select/dist/select.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/Smart-Table/dist/smart-table.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/textAngular/dist/textAngular-rangy.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/textAngular/dist/textAngular-sanitize.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/textAngular/dist/textAngular.min.js"></script>*/
/*             <script src="{{ settings.templates.rootDir }}/assets/plugins/moment/moment.min.js"></script>*/
/* {#            <script src="{{ settings.templates.rootDir }}/assets/plugins/angularjs/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.min.js"></script>#}*/
/*             <!-- END CORE ANGULARJS PLUGINS -->*/
/* */
/*             <!-- BEGIN APP LEVEL ANGULARJS SCRIPTS -->*/
/*             <!-- load CRM module for angular -->*/
/*             <script src="{{ settings.templates.rootDir }}/app/app.module.CRM.js" type="text/javascript"></script> */
/*                 {# In this way we can push twig wariables directly to angular script initializations #}*/
/*                 {% autoescape false %}*/
/*                     {% include 'app/app.angular.js.twig' %}*/
/*                 {% endautoescape %}*/
/*             <!-- END APP LEVEL ANGULARJS SCRIPTS -->*/
/* */
/*             <script type="text/javascript">*/
/*             {% autoescape false %}*/
/* */
/*                 // maybe will be usefull string escape*/
/*                 function jqescape(str) { return str.replace(/[#;&,\.\+\*~':"!\^\$\[\]\(\)=>|\/\\]/g, '\\$&'); }*/
/* */
/*                 // init JavaScript app*/
/*                 $(document).ready(function() */
/*                 {*/
/*                     // js template wrapper for some nesesary actions*/
/*                     // related only with template, nothing more*/
/*                     mgCRM.init(true);*/
/*                 });*/
/* */
/*             {% endautoescape %}*/
/*             </script>*/
/*             <!-- END JAVASCRIPTS -->*/
/* */
/*             <!-- ASTERISK WIDGET TO CALL -->*/
/*             {% if settings.templates.integrations.asterisk %}*/
/*                 {% include 'partials/asteriskjs.twig' %}*/
/*             {% endif %}*/
/* */
/*         </div><!-- END main angular app wrapper -->*/
/* */
/* */
/* */
/*   </body>*/
/* </html>*/
/* */
