<?php

/* partials/header.twig */
class __TwigTemplate_ba5013eda04a7d25e787718aa8a2c367f5790271999c6e6e0c51dbe5b12be3f8 extends Twig_Template
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
        // line 2
        echo "
<div class=\"top-menu\">
    <div class=\"page-container\">
        <div class=\"modulename\">
            <div><span>";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => "CRM"), "method"), "html", null, true);
        echo "</span></div>
            <small>";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => "Customer Relationship Manager"), "method"), "html", null, true);
        echo "</small>
        </div>


        ";
        // line 12
        echo "        <div class=\"nav-menu\">
            ";
        // line 14
        echo "            <ul class=\"nav navbar-nav mg-navbar\">
                ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["navigation"]) ? $context["navigation"] : null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["navKey"] => $context["nav"]) {
            // line 16
            echo "                    
                    ";
            // line 17
            $context["render"] = true;
            // line 18
            echo "                    
                    ";
            // line 19
            if (($context["navKey"] == "dynamicTypes")) {
                // line 20
                echo "                        
                        ";
                // line 21
                if (($context["nav"] && (twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "dynamicTypes", array()), "navigation", array())) > 0))) {
                    // line 22
                    echo "                            ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "dynamicTypes", array()), "navigation", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["dnav"]) {
                        // line 23
                        echo "                                <li id=\"contacts-list-";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["dnav"], "id", array()), "html", null, true);
                        echo "\">
                                    <a 
                                        ui-sref=\"contacts.list({contactTypeID: ";
                        // line 25
                        echo twig_escape_filter($this->env, $this->getAttribute($context["dnav"], "id", array()), "html", null, true);
                        echo "})\"
                                        data-target=\"#\">
                                        ";
                        // line 27
                        if ($this->getAttribute($context["dnav"], "icon", array())) {
                            echo "<i class=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["dnav"], "icon", array()), "html", null, true);
                            echo "\"></i> ";
                        }
                        // line 28
                        echo "                                        <span>";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["dnav"], "name", array()), "html", null, true);
                        echo "</span></a>
                                </li>
                                <li class=\"dropdown-separator\">
                                    <span class=\"separator\"></span>
                                </li>
                            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dnav'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 34
                    echo "                        ";
                }
                // line 35
                echo "                        ";
                $context["render"] = false;
                // line 36
                echo "                        
                    ";
            }
            // line 38
            echo "                    ";
            if (($context["navKey"] == "dynamicTypesSubmenu")) {
                // line 39
                echo "                        
                        ";
                // line 40
                if (($this->getAttribute($context["nav"], "display", array()) && (twig_length_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "dynamicTypes", array()), "submenu", array())) > 0))) {
                    // line 41
                    echo "                            <li class=\"menu-dropdown \">
                                <a data-hover=\"dropdown\" data-delay=\"0\" data-close-others=\"true\" data-toggle=\"dropdown\" href=\"javascript:;\">
                                    <i class=\"";
                    // line 43
                    echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "icon", array()), "html", null, true);
                    echo "\"></i> <span>";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => $this->getAttribute($context["nav"], "translate", array())), "method"), "html", null, true);
                    echo "</span> <i class=\"fa fa-angle-down dropdown-angle\"></i>
                                </a>
                                <ul class=\"dropdown-menu contacts-list-dropdown\">
                                ";
                    // line 46
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "dynamicTypes", array()), "submenu", array()));
                    foreach ($context['_seq'] as $context["_key"] => $context["sub"]) {
                        // line 47
                        echo "                                    <li id=\"contacts-list-sub-";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "id", array()), "html", null, true);
                        echo "\">
                                        <a 
                                            ui-sref=\"contacts.list({contactTypeID: ";
                        // line 49
                        echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "id", array()), "html", null, true);
                        echo "})\"
                                            data-target=\"#\"
                                        >
                                            ";
                        // line 52
                        if ($this->getAttribute($context["sub"], "icon", array())) {
                            // line 53
                            echo "                                                <i class=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "icon", array()), "html", null, true);
                            echo "\"></i>
                                            ";
                        }
                        // line 55
                        echo "                                            ";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "name", array()), "html", null, true);
                        echo "
                                        </a>
                                    </li>
                                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sub'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 59
                    echo "                                </ul>
                            </li>
                            <li class=\"dropdown-separator\">
                                <span class=\"separator\"></span>
                            </li>
                        ";
                }
                // line 65
                echo "                        
                        ";
                // line 66
                $context["render"] = false;
                // line 67
                echo "                    ";
            }
            // line 68
            echo "                    
                    ";
            // line 69
            if ($this->getAttribute($context["nav"], "acl", array())) {
                // line 70
                echo "                        ";
                if ( !$this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "hasAccess", array(0 => $this->getAttribute($context["nav"], "acl", array())), "method")) {
                    // line 71
                    echo "                            ";
                    $context["render"] = false;
                    // line 72
                    echo "                        ";
                }
                // line 73
                echo "                    ";
            }
            // line 74
            echo "                    
                    
                    ";
            // line 76
            if ((twig_test_empty($this->getAttribute($context["nav"], "submenu", array())) && (isset($context["render"]) ? $context["render"] : null))) {
                // line 77
                echo "                        <li id=\"";
                echo twig_escape_filter($this->env, twig_replace_filter($this->getAttribute($context["nav"], "translate", array()), array("." => "-")), "html", null, true);
                echo "\">
                            
                            <a  
                                ";
                // line 80
                if ($this->getAttribute($context["nav"], "sref", array())) {
                    // line 81
                    echo "                                    ui-sref=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "sref", array()), "html", null, true);
                    echo "\"
                                ";
                } elseif ($this->getAttribute(                // line 82
$context["nav"], "href", array())) {
                    // line 83
                    echo "                                    href=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "href", array()), "html", null, true);
                    echo "\"
                                ";
                } else {
                    // line 85
                    echo "                                    href=\"#\"
                                ";
                }
                // line 87
                echo "                                data-target=\"";
                echo twig_escape_filter($this->env, twig_replace_filter($this->getAttribute($context["nav"], "translate", array()), array("." => "-")), "html", null, true);
                echo " > a\"
                                ";
                // line 88
                if ($this->getAttribute($context["nav"], "color", array())) {
                    echo "style=\"color: ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "color", array()), "html", null, true);
                    echo ";\"";
                }
                echo "><i class=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "icon", array()), "html", null, true);
                echo "\" ";
                if ($this->getAttribute($context["nav"], "color", array())) {
                    echo "style=\"color: ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "color", array()), "html", null, true);
                    echo ";\"";
                }
                echo "></i> <span>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => $this->getAttribute($context["nav"], "translate", array())), "method"), "html", null, true);
                echo "</span></a>
                        </li>
                    ";
            } elseif (            // line 90
(isset($context["render"]) ? $context["render"] : null)) {
                // line 91
                echo "                        <li class=\"menu-dropdown \">
                            <a data-hover=\"dropdown\" data-delay=\"0\" data-close-others=\"true\" data-toggle=\"dropdown\" href=\"javascript:;\" ";
                // line 92
                if ($this->getAttribute($context["nav"], "color", array())) {
                    echo "style=\"color: ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "color", array()), "html", null, true);
                    echo ";\"";
                }
                echo ">
                                <i class=\"";
                // line 93
                echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "icon", array()), "html", null, true);
                echo "\" ";
                if ($this->getAttribute($context["nav"], "color", array())) {
                    echo "style=\"color: ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "color", array()), "html", null, true);
                    echo ";\"";
                }
                echo "></i> <span>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => $this->getAttribute($context["nav"], "translate", array())), "method"), "html", null, true);
                echo "</span> <i class=\"fa fa-angle-down dropdown-angle\"></i>
                            </a>
                            
                            <ul class=\"dropdown-menu\">
                                
                            ";
                // line 98
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["nav"], "submenu", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["sub"]) {
                    // line 99
                    echo "                                
                                ";
                    // line 100
                    $context["render"] = true;
                    // line 101
                    echo "                                ";
                    if ($this->getAttribute($context["sub"], "acl", array())) {
                        // line 102
                        echo "                                    ";
                        if ( !$this->getAttribute((isset($context["acl"]) ? $context["acl"] : null), "hasAccess", array(0 => $this->getAttribute($context["sub"], "acl", array())), "method")) {
                            // line 103
                            echo "                                        ";
                            $context["render"] = false;
                            // line 104
                            echo "                                    ";
                        }
                        // line 105
                        echo "                                ";
                    }
                    // line 106
                    echo "                                
                                ";
                    // line 107
                    if ((($this->getAttribute($context["sub"], "acl", array()) == "other.access_migrator") &&  !$this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "isMigrationAvailable", array()))) {
                        // line 108
                        echo "                                    ";
                        $context["render"] = false;
                        // line 109
                        echo "                                ";
                    }
                    // line 110
                    echo "                                
                                ";
                    // line 111
                    if ((isset($context["render"]) ? $context["render"] : null)) {
                        // line 112
                        echo "                                    <li id=\"";
                        echo twig_escape_filter($this->env, twig_replace_filter($this->getAttribute($context["sub"], "translate", array()), array("." => "-")), "html", null, true);
                        echo "\">
                                        <a 
                                            ";
                        // line 114
                        if ($this->getAttribute($context["sub"], "sref", array())) {
                            // line 115
                            echo "                                                ui-sref=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "sref", array()), "html", null, true);
                            echo "\"
                                            ";
                        } elseif ($this->getAttribute(                        // line 116
$context["sub"], "href", array())) {
                            // line 117
                            echo "                                                href=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "href", array()), "html", null, true);
                            echo "\"
                                            ";
                        } else {
                            // line 119
                            echo "                                                href=\"#\"
                                            ";
                        }
                        // line 121
                        echo "                                            data-target=\"#";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["nav"], "sref", array()), "html", null, true);
                        echo "\"
                                            ";
                        // line 122
                        if ($this->getAttribute($context["sub"], "target", array())) {
                            echo "target=\"_blank\"";
                        }
                        // line 123
                        echo "                                            ";
                        if ($this->getAttribute($context["sub"], "color", array())) {
                            echo "style=\"color: ";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "color", array()), "html", null, true);
                            echo ";\"";
                        }
                        // line 124
                        echo "                                        >
                                            ";
                        // line 125
                        if ($this->getAttribute($context["sub"], "icon", array())) {
                            // line 126
                            echo "                                                <i class=\"";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "icon", array()), "html", null, true);
                            echo "\" ";
                            if ($this->getAttribute($context["sub"], "color", array())) {
                                echo "style=\"color: ";
                                echo twig_escape_filter($this->env, $this->getAttribute($context["sub"], "color", array()), "html", null, true);
                                echo ";\"";
                            }
                            echo "></i>
                                            ";
                        }
                        // line 128
                        echo "                                            ";
                        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["lang"]) ? $context["lang"] : null), "translate", array(0 => $this->getAttribute($context["sub"], "translate", array())), "method"), "html", null, true);
                        echo "
                                        </a>
                                    </li>
                                ";
                    }
                    // line 132
                    echo "                                
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sub'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 134
                echo "                            </ul>
                        </li>
                    ";
            }
            // line 137
            echo "
                    
                    
                    
                    ";
            // line 141
            if (( !$this->getAttribute($context["loop"], "last", array()) && (isset($context["render"]) ? $context["render"] : null))) {
                // line 142
                echo "                        <li class=\"dropdown-separator\">
                            <span class=\"separator\"></span>
                        </li>
                    ";
            }
            // line 146
            echo "                ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['navKey'], $context['nav'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 147
        echo "                
                
            </ul>

        </div>
        ";
        // line 153
        echo "        
        
        ";
        // line 156
        echo "        <div class=\"nav-menu nav-menu-right\">
            ";
        // line 158
        echo "            <div class=\"modulename-logo\">
                <a href=\"http://www.modulesgarden.com\" target=\"_blank\">
                    <img src=\"";
        // line 160
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/img/mg-logo.png\" alt=\"logo\" class=\"logo-default\">
                    <img src=\"";
        // line 161
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
        echo "/assets/img/mg-logo-cog.png\" alt=\"logo\" height=\"29\" width=\"29\" class=\"logo-default-cog\">
                </a>
            </div>
            ";
        // line 165
        echo "            <ul class=\"nav navbar-nav navbar-right\">
                
                ";
        // line 167
        if ($this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "renderStandalone", array())) {
            // line 168
            echo "                    <li>
                        <a href=\"";
            // line 169
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "appAdminUrl", array()), "html", null, true);
            echo "\" style=\"padding-top: 15px;\">
                            <img src=\"";
            // line 170
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "rootDir", array()), "html", null, true);
            echo "/assets/img/magento-2.png\" alt=\"Back to Magento\" height=29\" style=\"height: 29px;\">
                        </a>
                    </li>
                ";
        }
        // line 174
        echo "                
                ";
        // line 288
        echo "                ";
        if ( !$this->getAttribute($this->getAttribute((isset($context["settings"]) ? $context["settings"] : null), "templates", array()), "renderStandalone", array())) {
            // line 289
            echo "                    <li><a href=\"#\" class=\"full-screen-module-toogle\"><i class=\"icon-size-fullscreen\"></i>&nbsp;</a></li>
                ";
        }
        // line 291
        echo "                
            </ul>
        </div>
        ";
        // line 295
        echo "    
        <div class=\"clearfix\"></div>
    </div>
</div>
    
";
    }

    public function getTemplateName()
    {
        return "partials/header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  501 => 295,  496 => 291,  492 => 289,  489 => 288,  486 => 174,  479 => 170,  475 => 169,  472 => 168,  470 => 167,  466 => 165,  460 => 161,  456 => 160,  452 => 158,  449 => 156,  445 => 153,  438 => 147,  424 => 146,  418 => 142,  416 => 141,  410 => 137,  405 => 134,  398 => 132,  390 => 128,  378 => 126,  376 => 125,  373 => 124,  366 => 123,  362 => 122,  357 => 121,  353 => 119,  347 => 117,  345 => 116,  340 => 115,  338 => 114,  332 => 112,  330 => 111,  327 => 110,  324 => 109,  321 => 108,  319 => 107,  316 => 106,  313 => 105,  310 => 104,  307 => 103,  304 => 102,  301 => 101,  299 => 100,  296 => 99,  292 => 98,  276 => 93,  268 => 92,  265 => 91,  263 => 90,  244 => 88,  239 => 87,  235 => 85,  229 => 83,  227 => 82,  222 => 81,  220 => 80,  213 => 77,  211 => 76,  207 => 74,  204 => 73,  201 => 72,  198 => 71,  195 => 70,  193 => 69,  190 => 68,  187 => 67,  185 => 66,  182 => 65,  174 => 59,  163 => 55,  157 => 53,  155 => 52,  149 => 49,  143 => 47,  139 => 46,  131 => 43,  127 => 41,  125 => 40,  122 => 39,  119 => 38,  115 => 36,  112 => 35,  109 => 34,  96 => 28,  90 => 27,  85 => 25,  79 => 23,  74 => 22,  72 => 21,  69 => 20,  67 => 19,  64 => 18,  62 => 17,  59 => 16,  42 => 15,  39 => 14,  36 => 12,  29 => 7,  25 => 6,  19 => 2,);
    }
}
/* {# BEGIN HEADER MENU #}*/
/* */
/* <div class="top-menu">*/
/*     <div class="page-container">*/
/*         <div class="modulename">*/
/*             <div><span>{{ lang.translate( 'CRM' ) }}</span></div>*/
/*             <small>{{ lang.translate( 'Customer Relationship Manager' ) }}</small>*/
/*         </div>*/
/* */
/* */
/*         {# BEGIN LEFT SIDE MAIN NAVIGATION #}*/
/*         <div class="nav-menu">*/
/*             {# mg-navbar is main selector used to work on this navigation #}*/
/*             <ul class="nav navbar-nav mg-navbar">*/
/*                 {% for navKey, nav in navigation %}*/
/*                     */
/*                     {% set render = true %}*/
/*                     */
/*                     {% if navKey == 'dynamicTypes' %}*/
/*                         */
/*                         {% if nav and settings.dynamicTypes.navigation|length > 0 %}*/
/*                             {% for dnav in settings.dynamicTypes.navigation %}*/
/*                                 <li id="contacts-list-{{ dnav.id }}">*/
/*                                     <a */
/*                                         ui-sref="contacts.list({contactTypeID: {{ dnav.id }}})"*/
/*                                         data-target="#">*/
/*                                         {% if dnav.icon %}<i class="{{ dnav.icon }}"></i> {% endif %}*/
/*                                         <span>{{ dnav.name }}</span></a>*/
/*                                 </li>*/
/*                                 <li class="dropdown-separator">*/
/*                                     <span class="separator"></span>*/
/*                                 </li>*/
/*                             {% endfor %}*/
/*                         {% endif %}*/
/*                         {% set render = false %}*/
/*                         */
/*                     {% endif %}*/
/*                     {% if navKey == 'dynamicTypesSubmenu' %}*/
/*                         */
/*                         {% if nav.display and settings.dynamicTypes.submenu|length > 0  %}*/
/*                             <li class="menu-dropdown ">*/
/*                                 <a data-hover="dropdown" data-delay="0" data-close-others="true" data-toggle="dropdown" href="javascript:;">*/
/*                                     <i class="{{ nav.icon }}"></i> <span>{{ lang.translate( nav.translate ) }}</span> <i class="fa fa-angle-down dropdown-angle"></i>*/
/*                                 </a>*/
/*                                 <ul class="dropdown-menu contacts-list-dropdown">*/
/*                                 {% for sub in settings.dynamicTypes.submenu %}*/
/*                                     <li id="contacts-list-sub-{{ sub.id }}">*/
/*                                         <a */
/*                                             ui-sref="contacts.list({contactTypeID: {{ sub.id }}})"*/
/*                                             data-target="#"*/
/*                                         >*/
/*                                             {% if sub.icon %}*/
/*                                                 <i class="{{ sub.icon }}"></i>*/
/*                                             {% endif %}*/
/*                                             {{ sub.name }}*/
/*                                         </a>*/
/*                                     </li>*/
/*                                 {% endfor %}*/
/*                                 </ul>*/
/*                             </li>*/
/*                             <li class="dropdown-separator">*/
/*                                 <span class="separator"></span>*/
/*                             </li>*/
/*                         {% endif %}*/
/*                         */
/*                         {% set render = false %}*/
/*                     {% endif %}*/
/*                     */
/*                     {% if nav.acl %}*/
/*                         {% if not acl.hasAccess(nav.acl) %}*/
/*                             {% set render = false %}*/
/*                         {% endif %}*/
/*                     {% endif %}*/
/*                     */
/*                     */
/*                     {% if nav.submenu is empty and render %}*/
/*                         <li id="{{ nav.translate | replace({'.':'-'}) }}">*/
/*                             */
/*                             <a  */
/*                                 {% if nav.sref %}*/
/*                                     ui-sref="{{ nav.sref }}"*/
/*                                 {% elseif nav.href %}*/
/*                                     href="{{ nav.href }}"*/
/*                                 {% else %}*/
/*                                     href="#"*/
/*                                 {% endif %}*/
/*                                 data-target="{{ nav.translate | replace({'.':'-'}) }} > a"*/
/*                                 {% if nav.color %}style="color: {{ nav.color }};"{% endif %}><i class="{{ nav.icon }}" {% if nav.color %}style="color: {{ nav.color }};"{% endif %}></i> <span>{{ lang.translate( nav.translate ) }}</span></a>*/
/*                         </li>*/
/*                     {% elseif render %}*/
/*                         <li class="menu-dropdown ">*/
/*                             <a data-hover="dropdown" data-delay="0" data-close-others="true" data-toggle="dropdown" href="javascript:;" {% if nav.color %}style="color: {{ nav.color }};"{% endif %}>*/
/*                                 <i class="{{ nav.icon }}" {% if nav.color %}style="color: {{ nav.color }};"{% endif %}></i> <span>{{ lang.translate( nav.translate ) }}</span> <i class="fa fa-angle-down dropdown-angle"></i>*/
/*                             </a>*/
/*                             */
/*                             <ul class="dropdown-menu">*/
/*                                 */
/*                             {% for sub in nav.submenu %}*/
/*                                 */
/*                                 {% set render = true %}*/
/*                                 {% if sub.acl %}*/
/*                                     {% if not acl.hasAccess(sub.acl) %}*/
/*                                         {% set render = false %}*/
/*                                     {% endif %}*/
/*                                 {% endif %}*/
/*                                 */
/*                                 {% if sub.acl == 'other.access_migrator' and not settings.isMigrationAvailable %}*/
/*                                     {% set render = false %}*/
/*                                 {% endif %}*/
/*                                 */
/*                                 {% if render %}*/
/*                                     <li id="{{ sub.translate | replace({'.': '-'}) }}">*/
/*                                         <a */
/*                                             {% if sub.sref %}*/
/*                                                 ui-sref="{{ sub.sref }}"*/
/*                                             {% elseif sub.href %}*/
/*                                                 href="{{ sub.href }}"*/
/*                                             {% else %}*/
/*                                                 href="#"*/
/*                                             {% endif %}*/
/*                                             data-target="#{{ nav.sref }}"*/
/*                                             {% if sub.target %}target="_blank"{% endif %}*/
/*                                             {% if sub.color %}style="color: {{ sub.color }};"{% endif %}*/
/*                                         >*/
/*                                             {% if sub.icon %}*/
/*                                                 <i class="{{ sub.icon }}" {% if sub.color %}style="color: {{ sub.color }};"{% endif %}></i>*/
/*                                             {% endif %}*/
/*                                             {{ lang.translate( sub.translate ) }}*/
/*                                         </a>*/
/*                                     </li>*/
/*                                 {% endif %}*/
/*                                 */
/*                             {% endfor %}*/
/*                             </ul>*/
/*                         </li>*/
/*                     {% endif %}*/
/* */
/*                     */
/*                     */
/*                     */
/*                     {% if not loop.last and render  %}*/
/*                         <li class="dropdown-separator">*/
/*                             <span class="separator"></span>*/
/*                         </li>*/
/*                     {% endif %}*/
/*                 {% endfor %}*/
/*                 */
/*                 */
/*             </ul>*/
/* */
/*         </div>*/
/*         {# END LEFT SIDE MAIN NAVIGATION #}*/
/*         */
/*         */
/*         {# BEGIN RIGHT SIDE MENU #}*/
/*         <div class="nav-menu nav-menu-right">*/
/*             {# BEGIN MODULES GARDEN LOGO #}*/
/*             <div class="modulename-logo">*/
/*                 <a href="http://www.modulesgarden.com" target="_blank">*/
/*                     <img src="{{ settings.templates.rootDir }}/assets/img/mg-logo.png" alt="logo" class="logo-default">*/
/*                     <img src="{{ settings.templates.rootDir }}/assets/img/mg-logo-cog.png" alt="logo" height="29" width="29" class="logo-default-cog">*/
/*                 </a>*/
/*             </div>*/
/*             {# END MODULES GARDEN LOGO #}*/
/*             <ul class="nav navbar-nav navbar-right">*/
/*                 */
/*                 {% if settings.templates.renderStandalone  %}*/
/*                     <li>*/
/*                         <a href="{{ settings.templates.appAdminUrl }}" style="padding-top: 15px;">*/
/*                             <img src="{{ settings.templates.rootDir }}/assets/img/magento-2.png" alt="Back to Magento" height=29" style="height: 29px;">*/
/*                         </a>*/
/*                     </li>*/
/*                 {% endif %}*/
/*                 */
/*                 {#*/
/*                 <!-- BEGIN NOTIFICATION DROPDOWN -->*/
/*                 <li class="dropdown dropdown-extended dropdown-notification">*/
/*                     <a href="javascript:;" class="dropdown-toggle" dropdown-menu-hover data-toggle="dropdown" data-close-others="true">*/
/*                         <i class="icon-bell"></i>*/
/*                         <span class="badge badge-default">3</span>*/
/*                     </a>*/
/*                     <ul class="dropdown-menu pull-right">*/
/*                         <li class="external">*/
/*                             <h3>You have <strong>3</strong>/10 notifications</h3>*/
/*                             <a href="javascript:;">Mark as read</a>*/
/*                         </li>*/
/*                         <li>*/
/*                             <ul class="dropdown-menu-list scroller" style="height: 250px;">*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">read</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-success">*/
/*                                     <i class="fa fa-plus"></i>*/
/*                                     </span>*/
/*                                     odbierz TEL </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">read</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-danger">*/
/*                                     <i class="fa fa-bolt"></i>*/
/*                                     </span>*/
/*                                     pierdol sie. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">read</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-warning">*/
/*                                     <i class="fa fa-bell-o"></i>*/
/*                                     </span>*/
/*                                     Server #2 not responding. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">14 hrs</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-info">*/
/*                                     <i class="fa fa-bullhorn"></i>*/
/*                                     </span>*/
/*                                     Application error. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">2 days</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-danger">*/
/*                                     <i class="fa fa-bolt"></i>*/
/*                                     </span>*/
/*                                     Database overloaded 68%. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">3 days</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-danger">*/
/*                                     <i class="fa fa-bolt"></i>*/
/*                                     </span>*/
/*                                     A user IP blocked. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">4 days</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-warning">*/
/*                                     <i class="fa fa-bell-o"></i>*/
/*                                     </span>*/
/*                                     Storage Server #4 not responding dfdfdfd. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">5 days</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-info">*/
/*                                     <i class="fa fa-bullhorn"></i>*/
/*                                     </span>*/
/*                                     System Error. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                                 <li>*/
/*                                     <a href="javascript:;">*/
/*                                     <span class="time">9 days</span>*/
/*                                     <span class="details">*/
/*                                     <span class="label label-sm label-icon label-danger">*/
/*                                     <i class="fa fa-bolt"></i>*/
/*                                     </span>*/
/*                                     Storage server failed. </span>*/
/*                                     </a>*/
/*                                 </li>*/
/*                             </ul>*/
/*                         </li>*/
/*                     </ul>*/
/*                 </li> */
/*                 <!-- END TODO DROPDOWN -->*/
/*                 <li class="droddown dropdown-separator">*/
/*                     <span class="separator"></span>*/
/*                 </li>*/
/*                 #}*/
/*                 {% if not settings.templates.renderStandalone  %}*/
/*                     <li><a href="#" class="full-screen-module-toogle"><i class="icon-size-fullscreen"></i>&nbsp;</a></li>*/
/*                 {% endif %}*/
/*                 */
/*             </ul>*/
/*         </div>*/
/*         {# END RIGHT SIDE MENU #}*/
/*     */
/*         <div class="clearfix"></div>*/
/*     </div>*/
/* </div>*/
/*     */
/* {# END HEADER MENU #}*/
/* */
