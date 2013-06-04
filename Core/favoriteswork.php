<?php

class favoriteswork extends Z_Controller {

    protected $_favoritesTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        $this->_favoritesTable = new Z_FavoritesTable("favorites", $this->_user);
    }

    public function run() {
        if ($this->_user->getUserGroup()->isPermitAction("work_favorites")) {
            switch ($this->_action) {
                case "add":
                    if (isset($this->_params["post"]) && is_numeric($this->_params["post"])) {
                        $this->_favoritesTable->addFavorite($this->_params["post"]);
                        header("Location: " . URL . "favorites/");
                    }
                    break;
                case "remove":
                    $this->_favoritesTable->removeFavorite($this->_params["post"]);
                    header("Location: " . URL . "favorites/");
                    break;
            }
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    protected function _initView() {
        
    }

}

?>
