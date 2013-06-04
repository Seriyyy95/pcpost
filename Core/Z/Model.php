<?php

abstract class Z_Model extends Z_Object
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function __call($name, $arguments)
    {
        if (count($arguments) == 0){
            return $this->get($name);
        }else{
            return $this->set($name, $arguments[0]);
        }
    }

    public abstract function get($name);

    public abstract function set($name, $value);
}
?>
