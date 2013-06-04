<?php

class authhistory extends Z_Controller {

    protected $_authHistoryTable;
    protected $_currentUser;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("show_authhistory")) {
            if (is_numeric($this->_action)) {
                if ($this->_user->getUserGroup()->isPermitAction("show_users_authhistory")) {
                    if ($this->_action != $this->_user->id()) {
                        $usersTable = Z_Factory::Z_UsersTable();
                        $this->_currentUser = $usersTable->loadObject($this->_action);
                    } else {
                        $this->_currentUser = $this->_user;
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
            } else {
                $this->_currentUser = $this->_user;
            }
            $this->_authHistoryTable = new Z_AuthHistoryTable("auth_history", $this->_currentUser);
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run() {
        $list = $this->_authHistoryTable->getAuthList();
        foreach ($list As $record) {
            $historyTemplate = new Z_TemplateView("authhistoryitem", "item", $this->_view, false);
            $historyTemplate->replace("date", $record->created_date());
            $historyTemplate->replace("ip", $record->user_ip());
            $historyTemplate->replace("browser", $record->user_info());
        }
    }

}
?>
