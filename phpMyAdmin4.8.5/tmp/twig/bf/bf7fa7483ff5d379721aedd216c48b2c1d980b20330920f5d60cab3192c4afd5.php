<?php

/* database/structure/search_table.twig */
class __TwigTemplate_2b51dba6fb92230d9c0a03e10122374a423fc803a49c16b0afb97f90bb61aed3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<a href=\"tbl_select.php";
        echo (isset($context["tbl_url_query"]) ? $context["tbl_url_query"] : null);
        echo "\">
    ";
        // line 2
        echo (isset($context["title"]) ? $context["title"] : null);
        echo "
</a>
";
    }

    public function getTemplateName()
    {
        return "database/structure/search_table.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "database/structure/search_table.twig", "C:\\phpstudy_pro\\WWW\\openapi\\phpMyAdmin4.8.5\\templates\\database\\structure\\search_table.twig");
    }
}
