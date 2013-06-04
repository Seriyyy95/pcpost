<?php

class saferemovepost extends Z_Controller {

    protected $_postsTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("safe_remove_post")) {
            $this->_postsTable = Z_Factory::Z_PostsTable();
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run() {
        if ($this->_action == "submit") {
            if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                $this->_postsTable->safeRemovePost($this->_params["id"]);
            } else {
                throw new Z_BadRequestException();
            }
        }
    }

}

?>
