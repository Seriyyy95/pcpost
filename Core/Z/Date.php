<?php

class Z_Date
{
   
    public static function convert($time)
    {
        $year = @date("Y", $time);
        $month = @date("M", $time);
        $day = @date("j", $time);
        $hour = @date("G", $time);
        $minuts = @date("i", $time);
        if(static::_isToDay($time)){
            return "Сегодня в " . $hour . ":" . $minuts;
        }elseif(static::_isYesterday($time)){
            return "Вчера в " . $hour . ":" . $minuts;
        }else{
            static::_translateMonth($month);
            return $day . " " . $month . " " . $year . " в " . $hour . ":" . $minuts;
        }
    }
    
    private static function _isToDay($time)
    {
        if((time()-$time) < 86400) return true;
    }
    
    private static function _isYesterday($time)
    {
        if(time()-$time > 86400 && time()-$time < 172800) return true;
    }
    
    private static function _translateMonth(&$month)
    {
        switch($month){
            case "Jan":
                $month = "Января";
                break;
            case "Feb":
                $month = "Февраля";
                case "Mar":
                    $month = "Марта";
                    break;
            case "Apr":
                $month = "Апреля";
                break;
            case "May":
                $month = "Мая";
                break;
            case "Jun":
                $month = "Июня";
                break;
            case "Jul":
                $month = "Июля";                
                break;
            case "Aug":
                $month = "Августа";
                break;
            case "Sep":
                $month = "Сентября";
                break;
            case "Oct":
                $month = "Октября";
                break;
            case "Nov":
                $month = "Ноября";
                break;
            case "Dec":
                $month = "Декабря";
                break;
        }
    }
    
}

?>
