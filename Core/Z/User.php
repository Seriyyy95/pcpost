<?php

class Z_User extends Z_BbCodeElement
{

    protected $_historyTable;
    protected $_groupsTable;
    protected $_group;
    
    public function __construct($table, $id)
    {
        parent::__construct($table, $id);
        $this->_historyTable = new Z_HistoryTable("history", $this);
        $this->_groupsTable = Z_Factory::Z_GroupsTable();
        $this->_group = $this->_groupsTable->loadObject($this->user_group());
    }
    
    public function getHistoryTable()
    {
        return $this->_historyTable;
    }
    
    
    
    public function getUserGroup()
    {
        return $this->_group;
    }
    
    public function format_online_time($time = false)
    {
        $time = $this->online_time();
        return floor($time / 3600) . " часов, " . floor(($time % 3600) / 60) . " минут";
    }

    public function format_user_group()
    {
        return $this->_group->group_name();
    }

    public function icon()
    {
        if ($this->pol() == 'M') {
            return URL . ($this->isOnline() ?  "Images/man_on.gif" : "Images/man_off.gif");
        } else {
            return URL . ($this->isOnline() ? "Images/woman_on.gif" : "Images/woman_off.gif");
        }
    }

    public function image()
    {
        return URL . parent::image();
    }
    
    public function isOnline()
    {
        return $this->_historyTable->isOnline();
    }

}

?>
