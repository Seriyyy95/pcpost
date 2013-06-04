<?php

class Z_Table extends Z_Object {

    protected $_db;
    protected $_table;
    protected $_fields;

    public function __construct($table) {
        parent::__construct();
        if (!is_string($table)) {
            throw new Z_Exception("Argument of table must be string");
        }
        $this->_db = Z_Factory::Z_DB();
        $this->_table = $table;
        $this->_checkTable();
        $this->_loadFields();
        $this->_validTable();
    }

    private function _checkTable() {
        if (!$this->_db->isTable($this->_table)) {
            $this->_db->createTable($this->_table);
        }
    }

    private function _loadFields() {
        $this->_db->setQuery("DESC " . $this->_table);
        $this->_fields = $this->_db->loadList(0);
    }

    private function _validTable() {
        if (!$this->isField("id") || !$this->isField("class") || !$this->isField("created_time")) {
            throw new Z_Exception("Table $this->_table is invalid");
        }
    }

    public function isField($field) {
        return array_search($field, $this->_fields) !== false;
    }

    public function count($where = 1) {
        $this->_db->setQuery("SELECT COUNT(1) FROM " . $this->_table . " WHERE $where");
        $size = $this->_db->loadResult();
        return $size[0];
    }

    public function exists($id) {
        $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE id='$id' LIMIT 1");
        if ($this->_db->countResult()) {
            return true;
        } else {
            return false;
        }
    }

    public function add($elements = array()) {
        $fields = "created_time";
        $values = "'" . time() . "'";
        foreach ($elements As $field => $value) {
            if (!$this->isField($field))
                throw new Z_Exception("Field $field not found in " . $this->_table);
            $fields.=",$field";
            $values.=",'$value'";
        }
        $this->_db->setQuery("INSERT INTO " . $this->_table . " ($fields) VALUES ($values)");
    }

    public function getId($field, $value, $where = '1') {
        if (!$this->isField($field)) {
            throw new Z_Exception("Field $field not found");
        } else {
            $this->_db->setQuery("SELECT id FROM " . $this->_table . " WHERE $field='$value' AND $where");
            return $this->_db->loadResult()["id"];
        }
    }

    public function getList($where = "1") {
        $this->_db->setQuery("SELECT id, class FROM " . $this->_table . " WHERE $where");
        return $this->_loadObjects();
    }

    public function getListEx($params = array(), &$num_pages = null) {
        $where = isset($params["where"]) ? $params["where"] : "1";
        $page = isset($params["page"]) ? $params["page"] : "1";
        $page_count = isset($params["page_count"]) ? $params["page_count"] : $this->count($where);
        $sortfield = isset($params["sort_field"]) ? $params["sort_field"] : "id";
        $sortopt = isset($params["sort_option"]) ? $params["sort_option"] : "ASC";
        $district = isset($params["distinct_field"]) ? "DISTINCT " . $params["distinct_field"] : "";
        if (isset($params["search_field"]) && isset($params["search_text"])) {
            $where.=" AND ( 0 ";
            $words = explode(" ", $params["search_text"]);
            foreach ($words As $word) {
                $where.="OR (" . $params["search_field"] . " LIKE '%$word%') ";
            }
            $where.=" )";
        }
        if ($num_pages !== null) {
            $num_pages = ceil($this->count($where) / $page_count);
        }
        $this->_db->setQuery("SELECT id, class $district FROM " . $this->_table . " WHERE $where ORDER BY $sortfield $sortopt LIMIT " . ($page - 1) * $page_count . ", " . $page_count);
        return $this->_loadObjects();
    }

    public function getElementPage($id, $params=array()) {
        $where = isset($params["where"]) ? $params["where"] : "1";
        $page_count = isset($params["page_count"]) ? $params["page_count"] : $this->count($where);
        $sortfield = isset($params["sort_field"]) ? $params["sort_field"] : "id";
        $sortopt = isset($params["sort_option"]) ? $params["sort_option"] : "ASC";
        $district = isset($params["distinct_field"]) ? "DISTINCT " . $params["distinct_field"] : "";
        $this->_db->setQuery("SELECT id $district FROM ". $this->_table ."  WHERE $where ORDER BY $sortfield $sortopt");
        $list = $this->_db->loadList("id");
        $pos = array_search($id, $list);
        return ceil(($pos+1) / $page_count);
     }

    private function _loadObjects() {
        $elements = array();
        $list = $this->_db->loadArray();
        foreach ($list As $item) {
            $elements[] = $this->loadObject($item["id"], $item["class"]);
        }
        return $elements;
    }

    public function loadObject($id, $class = null) {
        if (!$this->exists($id)) {
            throw new Z_NotExistException("Element $id not exist");
        }
        if ($class == null) {
            $this->_db->setQuery("SELECT class FROM " . $this->_table . " WHERE id='$id'");
            $class = $this->_db->loadResult()["class"];
        }
        return new $class($this, $id);
    }

    public function loadNewObject() {
        $params = array(
            "sort_field" => "created_time",
            "sort_option" => "DESC",
            "page_count" => 1,
        );
        $list = $this->getListEx($params);
        return $list[0];
    }

    public function __toString() {
        return $this->_table;
    }

}

?>
