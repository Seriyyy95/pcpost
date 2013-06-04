<?php

class Z_GroupsTable extends Z_Table {

    protected $_config;
    
    public function __construct($table) {
        parent::__construct($table);
        $this->_config = Z_Factory::Z_Config();
    }

    public function addGroup($groupName) {
        $this->add(
                array(
                    "group_name" => $groupName)
                );
        $this->_addDefaultPermisions($this->loadNewObject());
    }
    
    protected function _addDefaultPermisions(Z_Group $group)
    {
        $defaultPermisions = explode(",", $this->_config->default_permisions());
        foreach($defaultPermisions As $action){
            $group->addPermitAction($action);
        }
    }

}

?>
