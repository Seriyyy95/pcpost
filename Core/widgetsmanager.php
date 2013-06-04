<?php

class widgetsmanager extends Z_Controller
{

    protected $_widgetsTable;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("edit_widgets")) {
            $this->_widgetsTable = Z_Factory::Z_WidgetsTable();
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run()
    {
        switch ($this->_action) {
            case "add":
                if (isset($this->_params["title"]) && isset($this->_params["text"])) {
                    $title = addslashes($this->_params["title"]);
                    $text = addslashes($this->_params["text"]);
                    $widget = array(
                        "title" => $title,
                        "text" => $text
                    );
                    $this->_widgetsTable->add($widget);
                    header("Location: " . URL . "widgetsmanager");
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "save":
                if (isset($this->_params["title"]) && isset($this->_params["text"]) &&
                        isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                    $widget = $this->_widgetsTable->loadObject($this->_params["id"]);
                    $title = addslashes($this->_params["title"]);
                    $text = addslashes($this->_params["text"]);
                    $widget->title($title);
                    $widget->text($text);
                    header("Location: " . URL . "widgetsmanager");                    
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "delete":
                if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                    $widget = $this->_widgetsTable->loadObject($this->_params["id"]);
                    $widget->remove();
                    header("Location: " . URL . "widgetsmanager");
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "modify":
                if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                    $widget = $this->_widgetsTable->loadObject($this->_params["id"]);
                    $this->_view->replace("title", $widget->title());
                    $this->_view->replace("text", $widget->text());
                    $this->_view->replace("id", $widget->id());
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            default:
                $list = $this->_widgetsTable->getList();
                foreach ($list As $element) {
                    $widgetTemplate = new Z_TemplateView("widgetmanagerelement", "widget", $this->_view, false);
                    $widgetTemplate->replace("id", $element->id());
                    $widgetTemplate->replace("title", $element->title());
                    $widgetTemplate->replace("text", $element->format_text());
                }
                break;
        }
    }

    protected function _initView()
    {
        if ($this->_action == "modify") {
            $this->_view = new Z_TemplateView("widgetsmanagermodify", "page", $this->_mainView);
        } else {
            parent::_initView();
        }
    }

}

?>
