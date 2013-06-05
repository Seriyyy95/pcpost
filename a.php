<?php

echo "<form method='get' action='a.php'>";
echo "<input type=\"text\" name='data' onkeyup=\"this.value = this.value.replace (/\D/, '')\" />";
echo "<input type='submit' name='save'>";
echo "</form>";

if(isset($_GET["save"])){
    echo $_GET["data"];
}

?>
