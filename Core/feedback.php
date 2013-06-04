<?php

class feedback extends Z_Controller {

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
    }

    public function run() {
        if ($this->_action == "send") {
            if (isset($this->_params["email"]) && isset($this->_params["text"]) && $this->_params["captcha"]) {
                $session = Z_Factory::Z_Session();
                $userCaptcha = strtoupper($this->_params["captcha"]);
                $rightCaptcha = strtoupper($session->captcha());
                if(!preg_match("#^[^@ ]+@[^@ ]+\.[^@ \.]+$#", $this->_params["email"])) {
                    $this->_view->replace("error", "Вы ввели нкорректный адрес электронной почты");
                    $this->_view->replace("text", $this->_params["text"]);
                }elseif (!strcmp($userCaptcha, $rightCaptcha)) {
                    $session->remove("captcha");
                    $usersTable = Z_Factory::Z_UsersTable();
                    $systemUser = $usersTable->getSystemUser();
                    $email = htmlspecialchars($this->_params["email"]);
                    $email = addslashes($email);
                    $text = htmlspecialchars($this->_params["text"]);
                    $text = addslashes($text);
                    $systemUser-> sendFeedbackMessage($email, $text);
                    $this->_view->replace("error", "Ваше сообщение отправиленно успешно, спасибо, мы ценим ваше мнение!");
                } else {
                    $this->_view->replace("error", "Код безопасности введен неверно");
                    $this->_view->replace("email", $this->_params["email"]);
                    $this->_view->replace("text", $this->_params["text"]);
                }
            } else {
                throw new Z_BadRequestException();
            }
        }
    }

}

?>
