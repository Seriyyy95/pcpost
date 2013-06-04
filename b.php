<?php

$headers = getallheaders();

print_r($headers);

echo "<br><br><br>";

print_r($_COOKIE);

echo "<br><br><br>";

echo $_SERVER["HTTP_USER_AGENT"];

?>
