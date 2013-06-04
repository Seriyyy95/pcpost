<?php

class Z_ExceptionView extends Z_TemplateView
{

    public function __construct($code, $message=null)
    {
        $mainView = Z_Factory::Z_MainView();
        parent::__construct($code, "page", $mainView);
        $this->replace("message", $message);
        $mainView->setException($this);

    }

}

?>
