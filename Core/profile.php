<?php

class profile extends Z_Controller {

    protected $_authHistoryTable;
    protected $_autorSubscriptionTable;
    protected $_usersTable;
    protected $_currentUser;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        $this->_usersTable = Z_Factory::Z_UsersTable();
        if (isset($this->_action) && is_numeric($this->_action)) {
            $this->_currentUser = $this->_usersTable->loadObject($this->_action);
        } else {
            $this->_currentUser = Z_Factory::Z_User();
        }
        $this->_authHistoryTable = new Z_AuthHistoryTable("auth_history", $this->_currentUser);
        $this->_autorSubscriptionTable = new Z_SubscriptionTable("autors_subscription", $this->_currentUser);
    }

    public function run() {
        $this->_view->replace("username", $this->_currentUser->login());
        $this->_view->replace("avatar", $this->_currentUser->image());
        $this->_view->replace("id", $this->_currentUser->id());
        $this->_view->replace("name", $this->_currentUser->name());
        $this->_view->replace("group", $this->_currentUser->getUserGroup()->group_name());
        $this->_view->replace("reg_date", Z_Date::convert($this->_currentUser->created_time()));
        $this->_view->replace("online_time", $this->_currentUser->format_online_time());
        $this->_view->replace("birth_date", Z_Date::convert($this->_currentUser->birth_date()));
        $this->_view->replace("country", $this->_currentUser->country());
        $this->_view->replace("city", $this->_currentUser->city());
        $this->_view->replace("pol", $this->_currentUser->pol() == 'M' ? "Мужской" : "Женский");
        $this->_view->replace("about_me", $this->_currentUser->format_about_me());
        $this->_view->replace("auth_history", ($this->_currentUser->id() == $this->_user->id() || 
                $this->_user->getUserGroup()->isPermitAction("show_users_authhistory"))? 
                "<a href='" . URL . "authhistory/<!--id-->'>(История входов)</a>" : "");
        $this->_printKarma();
        $this->_printSubscribes();
        $this->_printAutors();
        $this->_printLinks();

        $this->_view->replace("lastvisit", $this->_authHistoryTable->getLastAuth());
    }

    public function _initView() {
        if ($this->_user->getUserGroup()->isPermitAction("show_userprofile")) {
            if ($this->_user->getUserGroup()->isPermitAction("administrate_user")) {
                $this->_view = new Z_TemplateView("profileadmin", "page", $this->_mainView);
            }else{
                parent::_initView();
            }
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    private function _printKarma() {
        $karma = $this->_currentUser->karma();
        if ($karma > 0) {
            $this->_view->replace("karma", "<span style='color: green'>+$karma</span>");
        } elseif ($karma < 0) {
            $this->_view->replace("karma", "<span style='color: red'>$karma</span>");
        } else {
            $this->_view->replace("karma", "<span style='color: dimgray'>$karma</span>");
        }
    }

    private function _printLinks() {
        if ($this->_user->id() != $this->_currentUser->id()) {
            $userSubscriptionTable = new Z_SubscriptionTable("autors_subscription", $this->_user);
            if (!$userSubscriptionTable->isSign($this->_currentUser->id())) {
                $this->_view->replace("sign", "<a href='" . URL . "autorssubscription/signup/autor/" . $this->_currentUser->id() . "'>Подписатся на автора</a>");
            } else {
                $this->_view->replace("sign", "<a href='" . URL . "autorssubscription/signdown/autor/" . $this->_currentUser->id() . "'>Отменить подписку</a>");
            }
            $this->_view->replace("new_message", "<a href='<!--url-->messageswork/new/adresat/". $this->_currentUser->id() ."''> Написать сообщение</a>");
        }
    }

    private function _printSubscribes() {
        $subscribes = $this->_autorSubscriptionTable->getSubscribers($this->_currentUser->id());
        if (count($subscribes) != 0) {
            foreach ($subscribes As $id) {
                $subscribe = $this->_usersTable->loadObject($id->user_id());
                $this->_view->replace("subscribe", "<img src='" . $subscribe->icon() . "'> <a href='" . URL . "profile/" . $subscribe->id() . "'>" . $subscribe->login() . "</a>, ", false);
            }
        } else {
            $this->_view->replace("subscribe", "- ");
        }
    }

    private function _printAutors() {
        $autors = $this->_autorSubscriptionTable->getTargets();
        if (count($autors) != 0) {
            foreach ($autors As $id) {
                $autor = $this->_usersTable->loadObject($id->target_id());
                $this->_view->replace("autor", "<img src='" . $autor->icon() . "'> <a href='" . URL . "profile/" . $autor->id() . "'>" . $autor->login() . "</a>, ", false);
            }
        } else {
            $this->_view->replace("autor", "- ");
        }
    }

}

?>
