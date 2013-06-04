<?php

class W_SitePanel extends Z_TemplateView {

    protected $_user;

    public function __construct() {
        $mainView = Z_Factory::Z_MainView();
        parent::__construct("sitepanel", "sitepanel", $mainView);
        $this->_user = Z_Factory::Z_User();
        $this->_printPanel();
    }

    protected function _printPanel() {
        if ($this->_user->getUserGroup()->isPermitAction("show_people")) {
            $this->replace("people", "<a href='" . URL . "people' class='panellink'>Люди</a>");
        }
        if ($this->_user->getUserGroup()->isPermitAction("show_adminpanel")) {
            $this->replace("adminpanel", "<a href='" . URL . "adminpanel' class='panellink'>Панель администратора</a>");
        }
    }

}

?>
