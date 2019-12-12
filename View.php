<?php

namespace utils;

class View
{
    public static function render($vars = [], $view = '', $template = 'default')
    {
        extract($vars);
        $template_path = "views/{$template}_template.php";
        ob_start();
        if (is_file($template_path))
            require_once $template_path;
        else
            require_once "views/default_template.php";
        $result = ob_get_contents();
        ob_end_clean();
        echo $result;
    }
}