<?php

class Z_SubscriptionTable extends Z_Table
{

    protected $_user;

    public function __construct($table, $user)
    {
        parent::__construct($table);
        $this->_user = $user;
    }

    public function signUp($target)
    {
        if (!$this->isSign($target)) {
            $record = array(
                "user_id" => $this->_user->id(),
                "target_id" => $target
            );
            $this->add($record);
        }
    }

    public function signDown($target)
    {
        if ($this->isSign($target)) {
            $this->_db->setQuery("DELETE FROM $this->_table WHERE user_id='" . $this->_user->id() . "' AND target_id='" . $target . "'");
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function isSign($target)
    {
        return $this->count("user_id='" . $this->_user->id() . "' AND target_id='" . $target . "'");
    }

    public function getSubscribers($target)
    {
        $params = array(
            "where" => "target_id='" . $target . "' AND user_id != '" . $this->_user->id() . "'"
        );
        return $this->getListEx($params);
    }

    public function getTargets()
    {
        $params = array(
            "where" => "user_id = '" . $this->_user->id() . "'"
        );
        return $this->getListEx($params);
    }
    
    public function safeRemoveSubscribe($id)
    {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE user_id='" . $id . "'");
    }

    public function safeRemoveTarget($target)
    {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE target_id='" . $target . "'");
    }

}

?>
