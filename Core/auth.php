<?php

class auth extends Z_Controller {

    protected $_authController;
    protected $_session;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        $this->_authController = Z_Factory::Z_AuthController();
        $this->_session = Z_Factory::Z_Session();
    }

    public function run() {
        $this->_mainView->setTitle("Авторизация");
        $this->_view->replace("url", URL);
        switch ($this->_action) {
            case "input":
                if ($this->_user->getUserGroup()->id() == 2) {
                    if (isset($this->_params["login"]) && isset($this->_params["password"])) {
                        try {
                            $userCaptcha = strtoupper($this->_params["captcha"]);
                            $rightCaptcha = strtoupper($this->_session->captcha());     
                            if (!strcmp($userCaptcha, $rightCaptcha)) {
                                $this->_session->remove("captcha");
                                $this->_authController->authUser($this->_params["login"], $this->_params["password"]);
                                header("Location: " . URL . "profile/");
                            } else {
                                $this->_view->replace("error", "Код безопасности введен не верно");
                            }
                        } catch (Z_AuthException $e) {
                            $this->_view->replace("error", $e->getMessage());
                       } catch (Z_NotExistException $e) {
                            $this->_view->replace("error", "введите код безопасности");
                        }
                    }
                } else {
                    $this->_view->replace("error", "Вы уже авторизированны");
                }
                if (isset($this->_params["login"]))
                    $this->_view->replace("login", $this->_params["login"]);
                if (isset($this->_params["password"]))
                    $this->_view->replace("password", $this->_params["password"]);
                break;
            case "quit":
                if (!$this->_user->getUserGroup()->id() != 2) {
                    $this->_authController->exitUser();
                    header("Location: /");
                } else {
                    throw new Z_InvalidActionException();
                }
                break;
            default:
                throw new Z_BadRequestException();
        }
    }

}

?>
