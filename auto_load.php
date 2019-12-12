<?php
    
    require_once 'config.php';

    spl_autoload_register(function ($class_name) {
        $file = dirname(__FILE__) . '\\..\\' . str_replace('\\', DIRECTORY_SEPARATOR, ltrim($class_name, '\\')) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }, true, true);