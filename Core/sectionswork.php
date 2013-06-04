<?php

class sectionswork extends Z_Controller
{

    protected $_sectionsTable;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        $this->_sectionsTable = new Z_Table("sections");
    }

    public function run()
    {
        switch ($this->_action) {
            case "add":
                if ($this->_user->getUserGroup()->isPermitAction("edit_sections")) {
                    if (isset($this->_params["name"])) {
                        $name = htmlspecialchars($this->_params["name"]);
                        $name = addslashes($name);
                        $this->_sectionsTable->add(array("name" => $name));
                        header("Location: " . URL . "sections/show");
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
            case "remove":
                if ($this->_user->getUserGroup()->isPermitAction("edit_sections")) {
                    if (isset($this->_params["section"]) && is_numeric($this->_params["section"])) {
                        $section = $this->_sectionsTable->loadObject($this->_params["section"]);
                        $section->remove();
                        header("Location: " . URL . "sections/show");
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
            default:
                throw new Z_BadRequestException();
                break;
        }
    }

    protected function _initView()
    {
        
    }

}

?>
