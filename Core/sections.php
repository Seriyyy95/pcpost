<?php

class sections extends Z_Controller {

    protected $_sectionsTable;
    protected $_config;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        $this->_sectionsTable = new Z_Table("sections");
        $this->_config = Z_Factory::Z_Config();
    }

    public function run() {
        if ($this->_action == "show") {
            $page = $this->_getPage();
            $params = array(
                "where" => "parent_id='1'",
                "page_count" => $this->_config->limit_pages(),
                "page" => $page
            );
            $numPages = 0;
            $sections = $this->_sectionsTable->getListEx($params, $numPages);
            foreach ($sections As $section) {
                $sectionTemplate = $this->_getSectionTemplate();
                $sectionTemplate->replace("name", $section->name());
                $sectionTemplate->replace("id", $section->id());
            }
            $this->_printPages($page, $numPages);
        } else {
            throw new Z_BadRequestException();
        }
    }

    protected function _initView() {
        if ($this->_user->getUserGroup()->isPermitAction("edit_sections")) {
            $this->_view = new Z_TemplateView("sectionsadmin", "page", $this->_mainView);
        } else {
            parent::_initView();
        }
    }

    private function _getSectionTemplate() {
        if ($this->_user->getUserGroup()->isPermitAction("edit_sections")) {
            return new Z_TemplateView("sectionadmin", "section", $this->_view, false);
        } else {
            return new Z_TemplateView("section", "section", $this->_view, false);
        }
    }

}

?>
