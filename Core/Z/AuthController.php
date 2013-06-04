<?php

class Z_AuthController extends Z_Object {

    protected $_users;
    protected $_user;
    protected $_session;

    public function __construct() {
        parent::__construct();
        $this->_session = Z_Factory::Z_Session();
        $this->_users = Z_Factory::Z_UsersTable();
        if ($this->_session->exists("auth_id")) {
            $userid = $this->_users->getUserIdForAuth($this->_session->auth_id());
            if ($userid != 0) {
                $this->_user = $this->_users->loadObject($userid);
                $historyTable = $this->_user->getHistoryTable();
                $actionTime = time() - $historyTable->getLastActionTime();
                if ($actionTime < 300) {
                    $this->_user->online_time($this->_user->online_time() + $actionTime);
                }
            } else {
                $this->_user = $this->_users->getDefaultUser();
            }
        } else {
            $this->_user = $this->_users->getDefaultUser();
        }
    }

    public function getUser() {
        return $this->_user;
    }

    public function isAuth() {
        return !$this->_user->getUserGroup()->id() == 2;
    }

    public function authUser($login, $password) {
        $login = addslashes($login);
        $password = addslashes($password);
//        $password = md5($password);
        $id = $this->_users->getUserIdForLogin($login, $password);
        if ($id != 0) {
            $user = $this->_users->loadObject($id);
            if ($user->getUserGroup()->isPermitAction("auth")) {
                if ($user->auth_id() != 0) {
                    $this->_session->auth_id($user->auth_id());
                } else {
                    $auth_id = rand(1, 1000000);
                    while (!$this->_users->checkAuthId($auth_id)) {
                        $auth_id = rand(1, 1000000);
                    }
                    $user->auth_id($auth_id);
                    $this->_session->auth_id($auth_id);
                }
                $authHistoryTable = new Z_AuthHistoryTable("auth_history", $user);
                $authHistoryTable->saveAuth();
            }else{
                 throw new Z_AuthException("Пользователи группы " . $user->getUserGroup()->group_name() . " не могут авторизироватся");
            }
        } else {
            throw new Z_AuthException("Неверный логин или пароль");
        }
    }

    public function exitUser() {
        $this->_user->auth_id(0);
        $this->_session->remove("auth_id");
    }

}

?>
