<?php

class favorites extends Z_Controller {

    protected $_currentUser;
    protected $_favoritesTable;
    protected $_postsTable;
    protected $_usersTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("show_favorites")) {
            if (isset($this->_action) && is_numeric($this->_action)) {
                if ($this->_user->id() != $this->_action) {
                    if ($this->_user->getUserGroup()->isPermitAction("show_users_favorites")) {
                        $usersTable = Z_Factory::Z_UsersTable();
                        $this->_currentUser = $usersTable->loadObject($this->_action);
                    } else {
                        throw new Z_AccessDeniedException("Пользователи вашей группы не могут мотреть избранное других пользователей");
                    }
                } else {
                    $this->_currentUser = $this->_user;
                }
            } else {
                $this->_currentUser = $this->_user;
            }
            $this->_favoritesTable = new Z_FavoritesTable("favorites", $this->_currentUser);
            $this->_postsTable = Z_Factory::Z_PostsTable();
            $this->_usersTable = Z_Factory::Z_UsersTable();
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run() {
        $numPages = 0;
        $page = $this->_getPage();
        $favorites = $this->_favoritesTable->getFavorites($page, $numPages);
        if (count($favorites) == 0) {
            $favoritesTemplate = new Z_TemplateView("empty", "favorite", $this->_view);
        } else {
            foreach ($favorites As $favorite) {
                $favoritesTemplate = new Z_TemplateView("postpreview", "favorite", $this->_view, false);
                $post = $this->_postsTable->loadObject($favorite->post_id());
                $favoritesTemplate->replace("url", URL);
                $favoritesTemplate->replace("id", $post->id());
                $favoritesTemplate->replace("title", $post->name());
                $this->_printTags($favoritesTemplate, $post);
                $favoritesTemplate->replace("description", $post->format_description());
                $infoPanel = new W_InfoPanel("infopanel", "infopanel", $favoritesTemplate);
                $infoPanel->printInfoPanel($post);
            }
        }
        $this->_printPages($page, $numPages);
    }
    
     private function _printTags(Z_TemplateView $template ,Z_Element $post) {
        $tags = explode(",", $post->tags());
        foreach ($tags As $tag) {
            $template->replace("tags", "<a class='tag' href='" . URL . "posts/tags/word/$tag/'> *$tag</a>, ", false);
        }
    }

}

?>
