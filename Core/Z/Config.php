<?php

class Z_Config extends Z_Model
{

    protected $_fileName;
    protected $_config;
    protected $_modifed = false;

    public function __construct($fileName)
    {
        parent::__construct();
        $this->_fileName = $fileName;
        if (file_exists(CONFIG_PATH . $fileName) && is_readable(CONFIG_PATH . $fileName)) {
            $this->_config = parse_ini_file(CONFIG_PATH . $fileName);
        } else {
            throw new Z_Exception("File ".CONFIG_PATH."$fileName not exist");
        }
    }

    public function get($field)
    {
        if (isset($this->_config[$field])) {
            return $this->_config[$field];
        } else {
            throw new Z_Exception("Field $field not found");
        }
    }

    public function set($field, $value)
    {
        $this->_config[$field] = $value;
        $this->_modifed = true;
    }

    public function __destruct()
    {
        if ($this->_modifed) {
            $str = '';
            foreach ($this->_config As $key => $value) {
                $str.= $key . "=" . $value . ";\n";
            }
            $file = fopen(CONFIG_PATH . $this->_fileName, 'w');
            fwrite($file, $str);
        }
    }

}

?>
