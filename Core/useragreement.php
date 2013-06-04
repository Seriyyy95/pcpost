<?php

class useragreement extends Z_Controller
{

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
    }
    
    public function run()
    {
        $this->_mainView->setTitle("Пользовательское соглашение");
    }
}

?>
