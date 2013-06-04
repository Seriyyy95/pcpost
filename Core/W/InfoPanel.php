<?php

class W_InfoPanel extends Z_TemplateView {

    protected $_usersTable;
    protected $_favoritesTable;
    protected $_user;

    public function __construct($fileName, $key = null, &$parent = null, $replace = true) {
        parent::__construct($fileName, $key, $parent, $replace);
        $this->_usersTable = Z_Factory::Z_UsersTable();
        $this->_user = Z_Factory::Z_User();
        $this->_favoritesTable = new Z_FavoritesTable("favorites", $this->_user);
    }

    public function printInfoPanel(Z_BbCodeElement $post) {
        $autor = $this->_usersTable->loadObject($post->autor());
        $this->replace("autor", $autor->login());
        $this->replace("autor_id", $autor->id());
        $this->replace("icon", $autor->icon());
        $this->replace("shows", $post->num_open());
        $this->replace("date", $post->created_date());
        $this->_printAutorKarma($autor->karma());
        $this->_printPostKarma($post->karma());
        $this->_printFavorites($post->id());
    }

    private function _printAutorKarma($karma) {
        if ($karma < 0) {
            $this->replace("autor_karma", "<span style='color: #FF0000'>$karma</span>");
        } elseif ($karma > 0) {
            $this->replace("autor_karma", "<span style='color: #00CC00'>+$karma</span>");
        } else {
            $this->replace("autor_karma", "<span style='color: darkgrey'>$karma</span>");
        }
    }

    private function _printPostKarma($karma) {
        if ($karma < 0) {
            $this->replace("karma", "<span style='color: #FF0000'>$karma</span>");
        } elseif ($karma > 0) {
            $this->replace("karma", "<span style='color: #00CC00'>+$karma</span>");
        } else {
            $this->replace("karma", "<span style='color: darkgrey'>$karma</span>");
        }
    }

    private function _printFavorites($post) {
        $this->replace("favorites", $this->_favoritesTable->getNumToFavorite($post));
        if ($this->_user->getUserGroup()->isPermitAction("work_favorites")) {
            if (!$this->_favoritesTable->isFavorite($post)) {
                $this->replace("add_to_favorites", "<a href='" . URL . "favoriteswork/add/post/$post'>В избранное</a>");
            } else {
                $this->replace("add_to_favorites", "<a href='" . URL . "favoriteswork/remove/post/$post'>Убрать из избранного</a>");
            }
        }
    }

}

?>
