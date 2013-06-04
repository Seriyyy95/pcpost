<?php

abstract class Z_Controller extends Z_Object {

    protected $_action;
    protected $_params;
    protected $_mainView;
    protected $_view;
    protected $_url;
    protected $_user;
    protected $_config;

    public function __construct($action, $params = array()) {
        $this->_url = URL . "" . get_class($this) . "/" . $action . "/";
        $this->_action = $action;
        $this->_params = $params;
        $this->_mainView = Z_Factory::Z_MainView();
        $this->_user = Z_Factory::Z_User();
        $this->_config = Z_Factory::Z_Config();
        $this->_initView();
    }

    public abstract function run();

    protected function _initView() {
        $this->_view = new Z_TemplateView(get_class($this), "page", $this->_mainView);
    }

    protected function _getPage() {
        if (isset($this->_params["page"]) && is_numeric($this->_params["page"])) {
            return $this->_params["page"];
        } else {
            return 1;
        }
    }

    public function getUrlWithParams($execute = array()) {
        $url = $this->_url;
        foreach ($this->_params As $name => $param) {
            if (!$this->_isExecute($name, $execute)) {
                $url .= "$name/$param/";
            }
        }
        return $url;
    }
    
    public function getUrlWithParamsNotSlash($execute = array()) {
        $url = substr($this->_url, 0, -1);
        foreach ($this->_params As $name => $param) {
            if (!$this->_isExecute($name, $execute)) {
                $url .= "/$name/$param";
            }
        }
        return $url;
    }    

    private function _isExecute($name, $execute){
        foreach($execute As $value){
            if($name == $value){
                return 1;
            }
        }
        return 0;
    }
    
    protected function _printPages($page, $numpages) {
        if ($page > 1) {
            $this->_view->replace("prev", "<a href='" . $this->_url . "page/" . ($page - 1) . " '> <-Сюда</a>");
        } else {
            $this->_view->replace("prev", "<-Сюда");
        }
        if ($page < $numpages) {
            $this->_view->replace("next", "<a href='" . $this->_url . "page/" . ($page + 1) . "'>Туда-></a>");
        } else {
            $this->_view->replace("next", " Туда->");
        }
        for ($i = $page <= $this->_config->limit_pages() ? 1 : $page - $this->_config->limit_pages(); $i <= $numpages && $i <= $page + $this->_config->limit_pages(); $i++) {
            if ($i == $page) {
                $this->_view->replace("pages", $i, false);
            } else {
                $this->_view->replace("pages", "<a href='" . $this->_url . "page/$i'>$i</a> ", false);
            }
        }
    }

}

?>
