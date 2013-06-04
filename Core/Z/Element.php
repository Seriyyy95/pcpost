<?php

class Z_Element extends Z_Model
{

    protected $_db;
    protected $_table;
    protected $_id;

    public function __construct(Z_Table $table, $id)
    {
        parent::__construct();
        if(!is_numeric($id)){
            throw new Z_Exception("Id must be numeric");
        }
        $this->_db = Z_Factory::Z_DB();
        $this->_table = $table;
        $this->_id = $id;
    }

    public function get($field)
    {
        if ($this->_table->isField($field)) {
            $this->_db->setQuery("SELECT $field FROM " . $this->_table . " WHERE id='" . $this->id() . " LIMIT 1'");
            $value = $this->_db->loadResult();
            return stripslashes($value[$field]);
        } else {
            throw new Z_Exception("Field $field not found in table $this->_table");
        }
    }

    public function set($field, $value)
    {
        if ($this->_table->isField($field)) {
            $value = addslashes($value);
            $this->_db->setQuery("UPDATE " . $this->_table . " SET $field='" . $value . "' WHERE id='" . $this->_id . "'");
            return true;
        } else {
            throw new Z_Exception("Can not modify $field reason: field not found in table $this->_table");
        }
    }

    public function id()
    {
        return $this->_id;
    }

    public function created_date()
    {
        return Z_Date::convert($this->created_time());
    }
    
    public function remove()
    {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE id='" . $this->id() . "'");
    }

}

?>
