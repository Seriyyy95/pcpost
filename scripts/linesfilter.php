<?php

$fp = fopen('users.txt', 'r');
$mails = array();

while($str = fgets($fp)){
   $mail = explode(" ", $str);
   echo $mail[0]. "<br>";
   $mails[$mail[0]] = 1;
}

$fp1 = fopen("users1.txt", "a");
foreach($mails As $key=>$value){
   fwrite($fp1, $key."\n");
}

fclose($fp);
fclose($fp1);

?>
