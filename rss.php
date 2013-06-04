<?php

include ("Core/Z/AutoLoader.php");

define('APPLICATION_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR));
define('LIBRARY_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Core'));
define('CONFIG_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Settings') . DIRECTORY_SEPARATOR);
define('TEMPLATES_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Templates' ) . DIRECTORY_SEPARATOR);
define('URL', "http://" . $_SERVER['SERVER_NAME'] . "/");
error_reporting(E_ALL);

new Z_AutoLoader();

header("Content-Type: application/rss+xml"); 

$config = new Z_Config("site.ini");
$db = new Z_DB($config->host(), $config->user(), $config->password(),  $config->dbname());
$mainView = new Z_MainView("rss");
$postsTable = new Z_Table("posts");

$list = $postsTable->getList();

if  (count($list) > 0){

foreach($list As $value){
      $item = "<item>".
      "<title>" . strip_tags($value->name()) . "</title>
      <link>http://postshub.tk/post/".$value->id() . "</link>
      <description><![CDATA[" . $value->format_description() . "]]></description>
      <pubDate>".@date("r", $value->created_time())."</pubDate>
      <guid>http://postshub.tk/post/" . $value->id() . "</guid>
      </item>";
         $mainView->replace("items", $item, false);
   }
}

?>
