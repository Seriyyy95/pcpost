<?php

include ("Core/Z/AutoLoader.php");

define('APPLICATION_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR));
define('LIBRARY_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Core'));
define('CONFIG_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Settings') . DIRECTORY_SEPARATOR);
define('TEMPLATES_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Templates' ) . DIRECTORY_SEPARATOR);
define('URL', "http://" . $_SERVER['SERVER_NAME'] . "/");
error_reporting(E_ALL);
ini_set('date.timezone', 'Europe/Kiev');

new Z_AutoLoader();
new Z_MainController();

?>
