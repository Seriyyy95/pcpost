<?php

class Z_HistoryTable extends Z_Table
{

    const TIME_TODAY = 1;

    protected $_user;
    protected $_uri;
    protected static $_saved = false;

    public function __construct($table, Z_User $user)
    {
        parent::__construct($table);
        $this->_user = $user;
    }

    public function setUser(Z_User &$user){
        $user->setHistoryTable($this);
        $this->_user = $user;
    }
    
    public function saveRequest($uri)
    {
        $this->_uri = $uri;
        if (self::$_saved == false) {
            $uri = addslashes($uri);
            $params = array(
                "user_id" => $this->_user->id(),
                "user_group" => $this->_user->user_group(),
                "user_ip" => $_SERVER["REMOTE_ADDR"],
                "user_info" => $_SERVER["HTTP_USER_AGENT"],
                "request_url" => $uri,
                "referer_url" => isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "----------"
            );
            $this->add($params);
            self::$_saved = true;
        }
    }

    public function getLastActionTime()
    {
        $this->_db->setQuery("SELECT created_time FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' ORDER BY created_time DESC LIMIT 1, 2");
        return $this->_db->loadResult()[0];
    }

    public function getLastIp()
    {
        $this->_db->setQuery("SELECT user_ip FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' ORDER BY created_time DESC LIMIT 1");
        return $this->_db->loadResult()[0];
    }

    public function getLastAgent()
    {
        $this->_db->setQuery("SELECT user_info FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' ORDER BY created_time DESC LIMIT 1");
        return $this->_db->loadResult()[0];
    }

    public function getLastUri()
    {
        $this->_db->setQuery("SELECT request_url FROM " . $this->_table . " WHERE user_id='" . $this->_user->id() . "' ORDER BY created_time DESC LIMIT 1");
        return $this->_db->loadResult()[0];
    }

    public function isOnline()
    {
        return $this->count("user_id='" . $this->_user->id() . "' AND created_time > " . (time() - 300));
    }

    public function getHits($time=null)
    {
        if ($time == Z_HistoryTable::TIME_TODAY) {
            $hits = $this->count("created_time > '" . @strtotime(@date("d.m.Y")) . "'");
        } else {
            $hits = $this->count();
        }
        return $hits;
    }

    public function getHosts($time=null)
    {
        if ($time == Z_HistoryTable::TIME_TODAY) {
            $this->_db->setQuery("SELECT DISTINCT user_ip FROM " . $this->_table . " WHERE created_time > '" . @strtotime(@date("d.m.Y")) . "'");
        } else {
            $this->_db->setQuery("SELECT DISTINCT user_ip FROM " . $this->_table);
        }
        return $this->_db->countResult();
    }

    public function isShow()
    {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE user_ip='" . $_SERVER["REMOTE_ADDR"] . "' AND request_url='" . $this->_uri . "'
            AND user_id='" . $this->_user->id() . "' AND user_id != '2'");
       return $this->_db->countResult()-1;
    }

    public function getOnlineUsers()
    {
        $this->_db->setQuery("SELECT DISTINCT user_ip, user_id FROM " . $this->_table . " WHERE created_time > '" . (time() - 300) . "' AND user_id != '2'");
        return $this->_db->countResult();
    }

    public function getOnlineGuests()
    {
        $this->_db->setQuery("SELECT DISTINCT user_ip FROM " . $this->_table . " WHERE created_time > '" . (time() - 300) . "' AND user_id = '2'");
        return $this->_db->countResult();
    }

}

?>
