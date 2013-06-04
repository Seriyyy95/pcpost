<?php

class W_UserPanel extends Z_TemplateView {

    protected $_user;
    protected $_messagesTable;
    protected $_journalCommentsTable;
    protected $_journalPostsTable;

    public function __construct() {
        $this->_user = Z_Factory::Z_User();
        $view = Z_Factory::Z_MainView();
        if ($this->_user->getUserGroup()->id() == 2) {
            parent::__construct("authpanel", "userpanel", $view);
        } else {
            parent::__construct("userpanel", "userpanel", $view);
            $this->_messagesTable = Z_Factory::Z_MessagesTable();
            $this->_journalCommentsTable = Z_Factory::Z_JournalCommentsTable();
            $this->_journalPostsTable = Z_Factory::Z_JournalPostsTable();
            $this->_printInfo();
        }
    }

    public function _printInfo() {
        $this->replace("username", $this->_user->login());
        $this->replace("group", $this->_user->getUserGroup()->group_name());
        $notReadedMessags = $this->_messagesTable->getNotReadedMessages();
        $notReadedJournal = $this->_journalCommentsTable->getNotReadedJournal() + $this->_journalPostsTable->getNotReadedJournal();
        $this->replace("messages", $notReadedMessags ? "(+$notReadedMessags)" : "");
        $this->replace("journal", $notReadedJournal ? "(+$notReadedJournal)" : "");
    }

}

?>
