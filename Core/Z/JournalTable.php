<?php

class Z_JournalTable extends Z_Table {

    protected $_user;

    public function __construct($table) {
        parent::__construct($table);
        $this->_user = Z_Factory::Z_User();
    }

    public function isRecord($subscriber, $post) {
        return $this->count("user_id='" . $subscriber . "' AND post_id='$post'") > 0;
    }

    public function getRecord($subscriber, $post) {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE user_id='" . $subscriber . "' AND post_id='$post'");
        $id = $this->_db->loadResult()["id"];
        return $this->loadObject($id);
    }

    public function getNewRecords($page, &$numPages) {
        $config = Z_Factory::Z_Config();
        $params = array(
            "where" => "user_id = '" . $this->_user->id() . "' AND showed='0'",
            "page_count" => $config->limit_pages(),
            "page" => $page
        );
        $list = $this->getListEx($params, $numPages);
        return $list;
    }

    public function getAllRecords($page, &$numPages) {
        $config = Z_Factory::Z_Config();
        $params = array(
            "where" => "user_id = '" . $this->_user->id() . "'",
            "page_count" => $config->limit_pages(),
            "page" => $page
        );
        return $this->getListEx($params, $numPages);
    }

    public function deleteRecord($post) {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE post_id='$post'");
    }

    public function deleteAllRecords() {
        $this->_db->setQuery("DELETE FROM" . $this->_table . " WHERE user_id='" . $this->_user->id() . "'");
    }

    public function getNotReadedJournal() {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' AND new='1'");
        return $this->_db->countResult();
    }

    public function setViewedRecord($post) {
        if ($this->isRecord($this->_user->id(), $post)) {
            $record = $this->getRecord($this->_user->id(), $post);
            $record->showed(1);
        }
    }

    public function safeRemoveUser() {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "'");
    }

    public function safeRemovePost($id) {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE post_id='$id'");
    }

}

?>
