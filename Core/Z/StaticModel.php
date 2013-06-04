<?php


abstract class Z_StaticModel extends Z_Object
{
    
    public static function __callStatic($name, $arguments)
    {
        if (count($arguments) == 0){
            return static::get($name);
        }else{
            return static::set($name, $arguments[0]);
        }
    }

    public static function get($name){}

    public static function set($name, $value){}
    
}


?>
