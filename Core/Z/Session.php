<?php

class Z_Session extends Z_Model
{

    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            throw new Z_Exception("Field $name not fount in session");
        }
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function exists($name)
    {
        return isset($_SESSION[$name]);
    }
    
    public function remove($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        } else {
            throw new Z_Exception("Field $name not fount in session");
        }
    }

}

?>
