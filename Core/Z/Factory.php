<?php

class Z_Factory extends Z_StaticModel
{

    private static $_resources = array();
    private static $_counts = array();

    public static function get($name)
    {
        if (isset(self::$_resources[$name])) {
            return self::$_resources[$name];
        } else {
            throw new Z_Exception("Class name $name not register");
        }
    }

    public static function set($name, $value)
    {
        if(!isset(self::$_resources[$name])){
            self::$_resources[$name] = $value;
            self::$_counts[$name] = 0;
        }else{
            self::$_resources[$name . self::$_counts[$name]] = $value;
        }
        self::$_counts[$name]++;
    }

}

?>
