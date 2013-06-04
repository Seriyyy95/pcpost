<?php

class autorssubscription extends Z_Controller {

    protected $_autorsSubscriptionTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        $this->_autorsSubscriptionTable = new Z_SubscriptionTable("autors_subscription", $this->_user);
    }

    public function run() {
        switch ($this->_action) {
            case "signup":
                if ($this->_user->getUserGroup()->isPermitAction("autors_subscription_work")) {
                    if (isset($this->_params["autor"]) && is_numeric($this->_params["autor"])) {
                        $this->_autorsSubscriptionTable->signUp($this->_params["autor"]);
                        header("Location: " . URL . "profile/" . $this->_params["autor"]);
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException("Пользователи вашей группы не могут подписыватся на посты пользователей");
                }
                break;
            case "signdown":
                if ($this->_user->getUserGroup()->isPermitAction("autors_subscription_work")) {
                    if (isset($this->_params["autor"]) && is_numeric($this->_params["autor"])) {
                        $this->_autorsSubscriptionTable->signDown($this->_params["autor"]);
                        header("Location: " . URL . "profile/" . $this->_params["autor"]);
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException("Пользователи вашей группы не могут подписыватся на посты пользователей");
                }
                break;
            default:
                throw new Z_BadRequestException("Действие не указано или указано не верно");
                break;
        }
    }
    
    protected function _initView(){
        
    }

}

?>
