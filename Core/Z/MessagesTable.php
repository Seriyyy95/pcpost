<?php

class Z_MessagesTable extends Z_Table {

    protected $_user;
            protected$_config;

    public function __construct($table, Z_User $user) {
        parent::__construct($table);
        $this->_user = $user;
        $this->_config = Z_Factory::Z_Config();
    }

    public function listAutors() {
        $this->_db->setQuery("SELECT DISTINCT autor_id FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' AND adresat_id='" . $this->_user->id() . "'");
        $autors = $this->_db->loadList("autor_id");
        $this->_db->setQuery("SELECT DISTINCT adresat_id FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' AND autor_id='" . $this->_user->id() . "'");
        $adresats = $this->_db->loadList("adresat_id");
        $messages = array_merge($autors, $adresats);
        return array_unique($messages);
    }

    public function numNotReadedMessages($autor) {
        return $this->count("user_id='" . $this->_user->id() . "' AND adresat_id='" . $this->_user->id() . "' AND autor_id='$autor' AND is_read='0'");
    }

    public function send($adresat, $text) {
        $mymessage = array(
            "user_id" => $this->_user->id(),
            "autor_id" => $this->_user->id(),
            "adresat_id" => $adresat,
            "text" => $text
        );
        $hismessage = array(
            "user_id" => $adresat,
            "autor_id" => $this->_user->id(),
            "adresat_id" => $adresat,
            "text" => $text
        );
        $this->add($mymessage);
        $this->add($hismessage);
    }

    public function getMessages($adresat, $page, &$numPages) {
        $autor = $this->_user->id();
        $params = array(
            "page" => $page,
            "page_count" => $this->_config->limit_pages(),
            "where" => "((adresat_id='$adresat' AND autor_id='$autor') OR (adresat_id='$autor' AND autor_id='$adresat')) AND user_id='$autor'",
            "sort_field" => "created_time",
            "sort_option" => "DESC"
        );
        $this->_db->setQuery("UPDATE " . $this->_table . " SET is_read='1' WHERE adresat_id='$autor' AND autor_id='$adresat'");
        return $this->getListEx($params, $numPages);
    }

    public function deleteAutor($autor) {
        $adresat = $this->_user->id();
        $this->_db->setQuery("DELETE FROM $this->_table WHERE adresat_id='$adresat' AND autor_id='$autor' AND user_id='$adresat'");
        $this->_db->setQuery("DELETE FROM $this->_table WHERE adresat_id='$autor' AND autor_id='$adresat' AND user_id='$adresat'");
    }

    public function getNotReadedMessages() {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE adresat_id='" . $this->_user->id() . "' AND is_read='0' AND user_id='" . $this->_user->id() . "'");
        return $this->_db->countResult();
    }

    public function safeRemoveUser($id) {
        $this->_db->setQuery("DELETE FROM $this->_table WHERE adresat_id='$id' OR autor_id='$id'");
    }

}

?>
