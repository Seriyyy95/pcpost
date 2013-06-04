<?php

/*
$data = ;

//задаем контекст
$context = stream_context_create(
        array(
            http => array(
                header => "User-Agent: Opera/9.80 (X11; Linux x86_64) Presto/2.12.388 Version/12.15\r\nConnection: Close\r\n".
                "Cookie: __utma=168332643.1893255605.1366064437.1366306657.1366308543.21; __utmb=168332643.7.10.1366308543; __utmc=168332643; __utmv=; __utmz=168332643.1366308543.21.2.utmcsr=seriyyy95.spaces.ru|utmccn=(referral)|utmcmd=referral|utmcct=/pictures/; ldp=1; sid=5499255025108583; user_id=6759530\r\n" .
                "Content-Type: application/x-www-form-urlencoded\r\n",
                method => POST,
                content => $data
            )
        )
);


//http://upload03.spaces.ru/pics_upload/?sid=&amp;sig=859955798&amp;tm=1366292285&amp;user_id=6759530
$contents = file_get_contents("http://upload03.spaces.ru/", false, $context);
echo $contents;
*/

/*
$data = ;

//задаем контекст
$context = stream_context_create(
        array(
            http => array(
                header => "User-Agent: Opera/9.80 (X11; Linux x86_64) Presto/2.12.388 Version/12.15\r\nConnection: Close\r\n".
//                ."Content-Type: multipart/form-data\r\n".
                "Cookie: __utma=168332643.1893255605.1366064437.1366306657.1366308543.21; __utmb=168332643.7.10.1366308543; __utmc=168332643; __utmv=; __utmz=168332643.1366308543.21.2.utmcsr=seriyyy95.spaces.ru|utmccn=(referral)|utmcmd=referral|utmcct=/pictures/; ldp=1; sid=5499255025108583; user_id=6759530\r\n",
                method => POST,
                content => $data
            )
        )
);


//http://upload03.spaces.ru/pics_upload/?sid=&amp;sig=859955798&amp;tm=1366292285&amp;user_id=6759530
$contents = file_get_contents($_GET["url"], false, $context);

$contents = preg_replace("#<a(.*)href=\"#", "<a \\1 href=\"http://localhost/a.php?url=", $contents);
echo $contents;

*/
/*
$str="show_post,edit_post,add_post,add_comment,reply_comment,modify_comment,hide_comment,vote,auth,registration,show_authhistory,show_users_authhistory,show_userinfo,show_more_userinfo,show_userprofile,show_journal,show_messages,edit_settings,work_messages,subscription_work,administrate_user,safe_remove_post,safe_remove_user,edit_sections,edit_sitesettings,show_statistic,show_users,edit_widgets,service_works,edit_groups,show_adminpanel";

$array = explode(",", $str);
foreach($array As $value){
    echo "                    \$this->_view->replace(\"$value\", \$group->isPermitAction(\"$value\")? \"checked\" :\"\");<br />";
}
*/
/*
$str="show_post,edit_post,add_post,add_comment,reply_comment,modify_comment,hide_comment,vote,auth,registration,show_authhistory,show_users_authhistory,show_userinfo,show_more_userinfo,show_userprofile,show_journal,show_messages,edit_settings,work_messages,subscription_work,administrate_user,safe_remove_post,safe_remove_user,edit_sections,edit_sitesettings,show_statistic,show_users,edit_widgets,service_works,edit_groups,show_adminpanel,autors_subscription_work,show_favorites,show_users_favorites,work_favorites";

$array = explode(",", $str);
foreach($array As $value){
    echo " if (isset(\$this->_params[\"$value\"]) && !\$group->isPermitAction(\"$value\")) {<br />
                        \$group->addPermitAction(\"$value\");<br />
                    } elseif (!isset(\$this->_params[\"$value\"]) && \$group->isPermitAction(\"$value\")) {<br/>
                        \$group->removePermitAction(\"$value\"); <br />
                    }<br />"; 
}

echo "----------------------------------------------------------------------------------------------------------<br /><br />";

$array = explode(",", $str);
foreach($array As $value){
    echo "                    \$this->_view->replace(\"$value\", \$group->isPermitAction(\"$value\")? \"checked\" :\"\");<br />";
}
*/
/*
$str = "lower_reg_day,lower_reg_month,lower_reg_year,upper_reg_day,upper_reg_month,upper_reg_year";
$arr = explode(",", $str);
foreach($arr As $value){
    echo "\"$value/ \" . \$this->_params[\"$value\"] . \"/\" . ";
}*/
/*
class Pixel{
    
    public $x;
    public $y;
    public $color;
    public $topPixel;
    public $bottomPixel;
    public $leftPixel;
    public $rightPixel;
    
    public function __construct($x, $y, $color) {
        $this->x = $x;
        $this->y = $y;
        $this->color = $color;
    }
    
}

$fileInput = $_SERVER["DOCUMENT_ROOT"] . "/img.png";
$image = imagecreatefrompng($fileInput);
list($width, $height, $type) = getimagesize($fileInput);

$pixels = array();


if(imagecolorat($image, 1, 2) == 2){
    $yTop = 3;
}else{
    $yTop = 2;
}
if(imagecolorat($image, $height-1, 2) == 2){
    $yBottom = 3;
}else{
    $yBottom = 2;
}

$imageNew =  imagecreatetruecolor($width-6, $height-4);
imagecopy($imageNew, $image, 0, 0, 3, $yTop, $width-3, $height-$yBottom);
$width = $width-6;
$height = $height-$yBottom;

$alpha = 0;

for($x = 0; $x < $width; $x++){
    $countColor = 0;
    $countLessColor = 0;
    for($y = 0; $y < $height; $y++){
        $color = imagecolorat($imageNew, $x, $y);
        if($color != 2){
            $countColor++;
            $countLessColor++;
        }else{
            $countLessColor = 0;
        }
    }
}



 // Header("Content-type: image/jpeg");
 // imagepng($imageNew);

#666699
#ffffaa
*/

?>



