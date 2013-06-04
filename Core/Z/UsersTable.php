<?php

class Z_UsersTable extends Z_Table
{

    public function __construct()
    {
        parent::__construct("users");
    }

    public function addUser($login, $password, $name, $pol, $date, $email, $country, $city)
    {
        parent::add(array("login" => $login, "password" => $password, "name" => $name, "pol" => $pol, "birth_date" => $date, "email" => $email, "country" => $country, "city" => $city, "reg_ip" => $_SERVER["REMOTE_ADDR"]));
    }

    public function safeRemoveUser($id)
    {
        $user = $this->loadObject($id);
        $subscribeTable = new Z_SubscriptionTable("subscription", 1);
        $autorsSubscriptionTable = new Z_SubscriptionTable("autors_subscription", 1);
        $journalCommentsTable = Z_Factory::Z_JournalCommentsTable();
        $journalPostsTable = Z_Factory::Z_JournalPostsTable();
        $karmaTable = new Z_KarmaTable($user);
        $postsTable = Z_Factory::Z_PostsTable();
        $commentsTable = new Z_CommentsTable("comments");
        $messagesTable = Z_Factory::Z_MessagesTable();
        $subscribeTable->safeRemoveSubscribe($user->id());
        $autorsSubscriptionTable->safeRemoveSubscribe($id);
        $autorsSubscriptionTable->safeRemoveTarget($id);
        $journalCommentsTable->safeRemoveUser($user->id());
        $journalPostsTable->safeRemoveUser($user->id());
        $karmaTable->safeRemoveUser();
        $postsTable->safeRemoveAutor($user->id());
        $commentsTable->safeRemoveAutor($user->id());
        $messagesTable->safeRemoveUser($user->id());
        $user->remove();
    }

    public function getDefaultUser()
    {
        return $this->loadObject(2);
    }

    public function getSystemUser(){
        return $this->loadObject(3);
    }
    
    public function getUserIdForAuth($authId)
    {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE auth_id='" . $authId . "' LIMIT 1");
        return $this->_db->loadResult()[0];
    }

    public function getUserIdForLogin($login, $password)
    {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE login='$login' AND password='$password' LIMIT 1");
        return $this->_db->loadResult()[0];
    }

    public function checkAuthId($authId)
    {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE auth_id='$authId'");
        return !$this->_db->countResult();
    }

    public function isUser($login)
    {
        return $this->count("login='" . $login . "'");
    }

    public function checkRegistrationIp()
    {
        return $this->count("reg_ip='" . $_SERVER["REMOTE_ADDR"] . "'");
    }

    public function getUserIdOnlyLogin($login)
    {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE login='$login' LIMIT 1");
        if ($this->_db->countResult() == 0) {
            throw new Z_UserNotFoundException();
        } else {
            return $this->_db->loadResult()[0];
        }
    }
    
    public function countUsersInGroup($group)
    {
        return $this->count("user_group='$group'");
    }

}

?>
