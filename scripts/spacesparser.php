<?php

$pageid = 1; //pageid номер страницы форума
$numpages = 20469; //количество странц


$fp = fopen('users.txt', 'a');
   for (; $pageid < $numpages; $pageid++) {
      $page = file_get_contents("http://spaces.ru/forums/?sid=;r=2964069&tp=1&p=$pageid");
      while ($page = strstr($page, "<span style=\"color:#79358c\"><b>")) {
         $page = substr($page, 31);
         $pos = strpos($page, "<");
         $name = substr($page, 0, $pos);
         $name = $name . "@spaces.ru pageid=". $pageid ."\n";
         fwrite($fp, $name);
      }
}

fclose($fp);

?>
