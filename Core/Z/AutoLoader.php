<?php

/**
 * Description of ZAutoLoader
 *
 * @author user
 */
class Z_AutoLoader
{

    public function __construct()
    {
        spl_autoload_register(array('Z_AutoLoader', 'load'));
    }

    public static function load($className)
    {
        $className = str_replace('_', '/', $className);
        $classPath = LIBRARY_PATH . DIRECTORY_SEPARATOR . $className . '.php';
        if (file_exists($classPath) && is_readable($classPath)) {
            require_once $classPath;
        } else {
            throw new Z_LoadClassException("Class name $classPath not fuond");
        }
    }    

    public function __destruct()
    {
        spl_autoload_unregister(array('ZAutoLoader', 'load'));
    }

}

?>
