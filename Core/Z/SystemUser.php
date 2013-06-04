<?php


class Z_SystemUser extends Z_User{

    protected $_messagesTable;
    
    public function __construct($table, $id) {
        parent::__construct($table, $id);
        $this->_messagesTable = new Z_MessagesTable("messages", $this);
    }
    
    public function sendMessage($adresat, $text)
    {
        $this->_messagesTable->send($adresat, $text);
    }
    
    public function sendFeedbackMessage($email, $text){
        $text = "Пользователь $email оставил сообщение в форме обратной связи: $text";
        $this->sendMessage(1, $text);
    }
    
}

?>
