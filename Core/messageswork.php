<?php

class messageswork extends Z_Controller
{

    protected $_usersTable;
    protected $_messagesTable;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        $this->_usersTable = Z_Factory::Z_UsersTable();
        $this->_messagesTable = Z_Factory::Z_MessagesTable();
    }

    public function run()
    {
        switch ($this->_action) {
            case "new":
                if ($this->_user->getUserGroup()->isPermitAction("work_messages")) {
                    if (isset($this->_params["adresat"])) {
                        $adresat = $this->_usersTable->loadObject($this->_params["adresat"]);
                        $this->_view->replace("adresat", $adresat->login());
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
            case "send":
                if ($this->_user->getUserGroup()->isPermitAction("work_messages")) {
                    if (isset($this->_params["adresat"]) && isset($this->_params["text"])) {
                        $adresatLogin = addslashes($this->_params["adresat"]);
                        $text = addslashes($this->_params["text"]);
                        $adresat = $this->_usersTable->getUserIdOnlyLogin($adresatLogin);
                        $this->_messagesTable->send($adresat, $text);
                        header("Location: " . URL . "messages/autor/id/$adresat");
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
            case "delete":
                if ($this->_user->getUserGroup()->isPermitAction("work_messages")) {
                    if (isset($_GET["list"]) && is_array($_GET["list"])) {
                        foreach ($_GET["list"] as $autor) {
                            if (is_numeric($autor)) {
                                $this->_messagesTable->deleteAutor($autor);
                                header("Location: " . URL . "messages/autors");
                            } else {
                                throw new Z_BadRequestException();
                            }
                        }
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
        }
    }

    public function _initView()
    {
        switch ($this->_action) {
            case "new":
                $this->_view = new Z_TemplateView("messagenew", "page", $this->_mainView);
                $this->_view->replace("title", "Отправить сообщение");
                $this->_mainView->setTitle("Отправить сообщение");
                break;
        }
    }

}

?>
