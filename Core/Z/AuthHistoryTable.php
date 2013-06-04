<?php

class Z_AuthHistoryTable extends Z_Table {

    protected $_user;
    
    public function __construct($table, Z_User $user) {
        parent::__construct($table);
        $this->_user = $user;
    }

    public function getLastAuth() {
        $this->_db->setQuery("SELECT created_time FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' ORDER BY created_time DESC LIMIT 1");
        return Z_Date::convert($this->_db->loadResult()["created_time"]);
    }

    public function getAuthList() {

        $params = array(
            "user_id" => $this->_user->id(),
            "sort_field" => "created_time",
            "sort_option" => "DESC",
            "page" => 1,
            "page_count" => 20
        );
        return $this->getListEx($params);
    }

    public function saveAuth() {
        $params = array(
            "user_id" => $this->_user->id(),
            "user_ip" => $_SERVER["REMOTE_ADDR"],
            "user_info" => $_SERVER["HTTP_USER_AGENT"],
        );
        $this->add($params);
    }

}

?>
