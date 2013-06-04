<?php

class Z_TreeElement extends Z_BbCodeElement
{

    protected $_childTable;

    public function __construct(Z_Table $table, $id)
    {
        parent::__construct($table, $id);
        $this->_childTable = new Z_Table(parent::child_table());
    }

    public function count()
    {
        return $this->_childTable->count("parent_id='" . $this->id() . "'");
    }

    public function child_table($table = false)
    {
        if ($table != false) {
            if (($this->_childTable = new Z_Table((string) $table))) {
                parent::child_table($table);
            }
        } else {
            return $this->_childTable;
        }
    }

    public function add($elements = array(), Z_Table $table = null)
    {
        $table = $table != null ? $table : $this->_childTable;
        $elements["parent_id"] = $this->id();
        $elements["parent_table"] = $this->_table;
        if ($this->count() == 0)
            $this->child_table($table);
        else if ($table != $this->child_table())
            throw new Z_Exception($this->_table . " is not empty dont modify child table!");
        $this->_childTable->add($elements, $table);
    }

    public function getList($where="1")
    {
        return $this->_childTable->getList($where . " AND parent_id='" . $this->id() . "' AND parent_table='" . $this->_table . "'");
    }

    public function getListEx($params, &$numPages)
    {
        $params["where"] = isset($params["where"]) ? $params["where"] . " AND parent_id='" . $this->id() . "' AND parent_table='$this->_table'" :
                "parent_id='" . $this->id() . "' AND parent_table='$this->_table'";
        return $this->_childTable->getListEx($params, $numPages);
    }

    public function getElementPage($params = array())
    {
        $params["where"] = isset($params["where"]) ? $params["where"] . " AND parent_id='" . $this->parent_id() . "' AND parent_table='" . $this->parent_table() ."'" :
               "parent_id='" . $this->parent_id() . "' AND parent_table='" . $this->parent_table() ."'";
        return $this->_childTable->getElementPage($this->id(), $params);        
    }
    
    public function remove()
    {
        $list = $this->getList();
        foreach ($list As $item) {
            $item->remove();
        }
        parent::remove();
    }

    public function isChild($id)
    {
        $count = $this->_childTable->count("id='$id' AND parent_id='" . $this->id() . "' AND parent_table='" . $this->_table . "'");
        return $count;
    }

}

?>
