<?php

class saferemoveuser extends Z_Controller {

    protected $_usersTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("safe_remove_users")) {
            throw new Z_AccessDeniedException("Вы не можете удалять пользователей");
        } else {
            $this->_usersTable = Z_Factory::Z_UsersTable();
        }
    }

    public function run() {
        if ($this->_action == "submit") {
            if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                if ($this->_params["id"] > 3) {
                    $this->_usersTable->safeRemoveUser($this->_params["id"]);
                } else {
                    throw new Z_InvalidActionException("Это системный пользователь, эго нельзя удалить");
                }
            } else {
                throw new Z_BadRequestException("Должен быть указан параметр id, и он должен быть цифрой");
            }
        }
    }

}

?>
