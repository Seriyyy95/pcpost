<?php

class Z_FrontController extends Z_Object {

    protected $_controller;
    protected $_action;
    protected $_params;
    protected $_uri;
    protected $_user;

    public function __construct() {
        parent::__construct();
        $this->_initValues();
        $this->_uri = $this->_getRequest();
        $this->_rewriteRequest();
        $this->_parseRequest();
        $this->_historyTable->saveRequest($this->_uri);
    }

    private function _initValues() {
        $this->_user = Z_Factory::Z_User();
        $this->_historyTable = $this->_user->getHistoryTable();
        if ($this->_user->getUserGroup()->id() == 2) {
            $this->_controller = "posts";
            $this->_action = "all";
        } else {
            $this->_controller = "profile";
            $this->_action = "";            
        }
        $this->_params = array();
    }

    private function _getRequest() {
        return $_SERVER["REQUEST_URI"];
    }

    private function _rewriteRequest() {
        $this->_uri = preg_replace("#[\?\&\=]{1}#", "/", $this->_uri);
    }

    private function _decode($string) {
        $string = urldecode($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    private function _parseRequest() {
        $routes = explode('/', $this->_uri);
        if (!empty($routes[1])) {
            $this->_controller = $this->_decode($routes[1]);
        }
        if (!empty($routes[2])) {
            $this->_action = $this->_decode($routes[2]);
        }
        for ($i = 3; $i < count($routes) - 1; $i+=2) {
            $this->_params[$routes[$i]] = $this->_decode($routes[$i + 1]);
        }
        foreach ($_POST As $key => $value) {
            $this->_params[$key] = htmlspecialchars($value);
        }
    }

    public function getUri() {
        return $this->_uri;
    }

    public function getController() {
        return $this->_controller;
    }

    public function getAction() {
        return $this->_action;
    }

    public function getParams() {
        return $this->_params;
    }

}

?>
