<?php

class info extends Z_Controller
{

    protected $_currentUser;
    protected $_historyTable;
    protected $_authHistoryTable;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("show_userinfo")) {
            if (isset($this->_action) && is_numeric($this->_action) &&
                    $this->_user->id() != $this->_action) {
                $usersTable = Z_Factory::Z_UsersTable();
                $this->_currentUser = $usersTable->loadObject($this->_action);
                $this->_historyTable = new Z_HistoryTable("history", $this->_currentUser);
            } else {
                $this->_currentUser = Z_Factory::Z_User();
                $this->_historyTable = Z_Factory::Z_HistoryTable();
            }
            $this->_authHistoryTable = new Z_AuthHistoryTable("auth_history", $this->_currentUser);
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run()
    {
        $this->_mainView->setTitle("Информация");
        $this->_view->replace("title", "Информация");
        $this->_view->replace("lastvisit", $this->_authHistoryTable->getLastAuth());
        $this->_view->replace("regdate", $this->_currentUser->created_date());
        $this->_view->replace("online_time", $this->_currentUser->format_online_time());
        $this->_view->replace("karma", $this->_currentUser->karma());
        $this->_view->replace("group", $this->_currentUser->format_user_group());
        $this->_view->replace("avatar", $this->_currentUser->image());
        $this->_view->replace("name", $this->_currentUser->name());
        $this->_view->replace("age", @date("Y") - $this->_currentUser->birth_date());
        $this->_view->replace("country", $this->_currentUser->country());
        $this->_view->replace("city", $this->_currentUser->city());
        $this->_view->replace("pol", $this->_currentUser->pol() == "M" ? "Мужской" : "Женский");
        if ($this->_user->getUserGroup()->isPermitAction("show_more_userinfo")) {
            $this->_view->replace("reg_ip", $this->_currentUser->reg_ip());
            $this->_view->replace("last_action", Z_Date::convert($this->_historyTable->getLastActionTime()));
            $this->_view->replace("last_ip", $this->_historyTable->getLastIp());
            $this->_view->replace("last_agent", $this->_historyTable->getLastAgent());
            $this->_view->replace("last_uri", $this->_historyTable->getLastUri());
        }
    }

    protected function _initView()
    {
        if ($this->_user->getUserGroup()->isPermitAction("show_more_userinfo")) {
            $this->_view = new Z_TemplateView("infoadmin", "page", $this->_mainView);
        } else {
            parent::_initView();
        }
    }

}

?>
