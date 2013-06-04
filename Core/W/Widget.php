<?php

class W_Widget extends Z_TemplateView
{

    protected $_widgetElement;
    
    public function __construct(Z_Element $widgetElement)
    {
        $view = Z_Factory::Z_MainView();
        parent::__construct("widget", "widget", $view, false);
        $this->_widgetElement = $widgetElement;
        $this->_initWidget();
    }

    private function _initWidget()
    {
        $this->replace("title", $this->_widgetElement->title());
        $this->replace("text", $this->_widgetElement->format_text());
    }
    
}

?>
