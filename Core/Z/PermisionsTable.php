<?php

class Z_PermisionsTable extends Z_Table {

    public function __construct($table) {
        parent::__construct($table);
    }

    public function getPermitActions($groupId) {
        $this->_db->setQuery("SELECT action FROM " . $this->_table . " WHERE group_id='$groupId'");
        return $this->_db->loadList("action");
    }

    public function addPermitAction($group, $action) {
        $this->add(
                array(
                    "group_id" => $group,
                    "action" => $action
                )
        );
    }
    
    public function removePermitAction($group, $action)
    {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE group_id='$group' AND action='$action'");
    }
    
    public function removeGroupPermisions($group)
    {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE group_id='$group'");        
    }

}

?>
