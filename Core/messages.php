<?php

class messages extends Z_Controller
{

    protected $_messagesTable;
    protected $_usersTable;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        $this->_messagesTable = Z_Factory::Z_MessagesTable();
        $this->_usersTable = Z_Factory::Z_UsersTable();
    }

    public function run()
    {
        if ($this->_user->getUserGroup()->isPermitAction("show_messages")) {
            switch ($this->_action) {
                case "autors":
                    $list = $this->_messagesTable->listAutors();
                    if (count($list) == 0) {
                        $empty = new Z_TemplateView("empty", "autor", $this->_view);
                    } else {
                        foreach ($list As $autor) {
                            $userAutor = $this->_usersTable->loadObject($autor);
                            $autorTemplate = new Z_TemplateView("messagesautor", "autor", $this->_view, false);
                            $autorTemplate->replace("id", $userAutor->id());
                            $autorTemplate->replace("autor", $userAutor->login());
                            $autorTemplate->replace("online", $userAutor->icon());
                            $autorTemplate->replace("avatar", $userAutor->image());
                            $notRead = $this->_messagesTable->numNotReadedMessages($userAutor->id());
                            $autorTemplate->replace("not_read", $notRead ? "<span style='color: red'> (+$notRead)</span>" : "");
                        }
                        $this->_view->replace("delete", "<input type='submit' value='Удалить'>");
                    }
                    break;
                case "autor":
                    if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                        $autor = $this->_usersTable->loadObject($this->_params["id"]);
                        $this->_view->replace("autor", $autor->login());
                        $page = $this->_getPage();
                        $numPages = 0;
                        $list = $this->_messagesTable->getMessages($autor->id(), $page, $numPages);
                        foreach ($list As $message) {
                            $messageTemplate = new Z_TemplateView("message", "message", $this->_view, false);
                            $autor = $this->_usersTable->loadObject($message->autor_id());
                            $messageTemplate->replace("autor_login", $autor->id() == $this->_user->id() ?
                                            "<span style='color:  darkgreen'><b>Я </b></span>" : $autor->login());
                            if ($message->is_read() == 0) {
                                $messageTemplate->replace("not_read", "<span style='color: #FF0000'>(Не прочитано)</span>");
                            }
                            $messageTemplate->replace("autor_id", $autor->id());
                            $messageTemplate->replace("avatar", $autor->image());
                            $messageTemplate->replace("online", $autor->icon());
                            $messageTemplate->replace("date", $message->created_date());
                            $messageTemplate->replace("text", $message->format_text());
                        }
                        $this->_url .= "id/" . $this->_params["id"] . "/";
                        $this->_printPages($page, $numPages);
                    } else {
                        throw new Z_AccessDeniedException();
                    }
                    break;
            }
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function _initView()
    {
        switch ($this->_action) {
            case "autors":
                $this->_view = new Z_TemplateView("messagesautors", "page", $this->_mainView);
                $this->_mainView->setTitle("Сообщения");
                $this->_view->replace("title", "Сообщения");
                break;
            case "autor":
                parent::_initView();
                $this->_mainView->setTitle("Сообщения");
                $this->_view->replace("title", "Сообщения");
                break;
        }
    }

}

?>
