<?php

class Z_Group extends Z_Element{

    protected $_permisionsTable;
    protected $_permitActions = array();
    
    public function __construct(Z_Table $table, $id) {
        parent::__construct($table, $id);
        $this->_permisionsTable = new Z_PermisionsTable("permisions");
        $this->_loadPermitActions();
    }
    
    protected function _loadPermitActions()
    {
        $this->_permitActions = $this->_permisionsTable->getPermitActions($this->id());
    }
    
    public function isPermitAction($action)
    {
        return array_search($action, $this->_permitActions) !== false;
    }
    
    public function addPermitAction($action)
    {
        $this->_permisionsTable->addPermitAction($this->id(), $action);
        $this->_permitActions[] = $action;
    }
    
    public function removePermitAction($action)
    {
        $this->_permisionsTable->removePermitAction($this->id(), $action);
    }
    
    public function remove()
    {
        $this->_permisionsTable->removeGroupPermisions($this->id());
        parent::remove();
    }
    
}

?>
