<?php

class adminpanel extends Z_Controller
{

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        if (!$this->_user->getUserGroup()->isPermitAction("show_adminpanel")) {
            throw new Z_AccessDeniedException("У вас нет доступа к панели администратора");
        }
    }

    public function run()
    {
        
    }

}

?>
