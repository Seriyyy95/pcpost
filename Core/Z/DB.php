<?php

class Z_DB extends Z_Object
{

    protected $_hdb;
    protected $_result;
    protected $_tables;

    public function __construct($host, $user, $password, $db)
    {
        parent::__construct();
        $this->_hdb = @mysql_connect($host, $user, $password);
        if ($this->_hdb == null)
            throw new Z_SqlException("Can not connect to database");
        $this->setQuery("USE $db");
        $this->_loadTables();
    }

    private function __clone()
    {
        
    }

    public function setQuery($query)
    {
        $this->_result = mysql_query($query, $this->_hdb);
        if (mysql_errno() != 0) {
            throw new Z_SqlException("MySQL error: " . mysql_errno() . " " . mysql_error() . " in query: " . $query);
        }
    }

    public function setSql($filename)
    {
        $query = file_get_contents($filename);
        if ($query == null)
            throw new Z_Exception("File $filename not found!");
        $q = explode(";", $query);
        foreach ($q As $value) {
            $this->setQuery($value);
        }
    }

    public function loadResult()
    {
        return mysql_fetch_array($this->_result);
    }

    public function loadArray()
    {
        $array = array();
        while ($element = $this->loadResult()) {
            $array[] = $element;
        }
        return $array;
    }

    public function loadList($field)
    {
        $array = array();
        while ($element = $this->loadResult()) {
            $array[] = $element[$field];
        }
        return $array;
    }

    public function countResult()
    {
        return @mysql_num_rows($this->_result);
    }

    public function isTable($table)
    {
        return array_search($table, $this->_tables) !== false;
    }

    public function createTable($table)
    {
        $this->setSql(CONFIG_PATH . "tables/$table.sql");
        $this->_tables[] = $table;
    }

    private function _loadTables()
    {
        $this->setQuery("SHOW TABLES");
        $this->_tables = $this->loadList(0);
    }

    public function __destruct()
    {
        @mysql_close($this->_hdb);
    }

}

?>
