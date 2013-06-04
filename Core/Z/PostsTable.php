<?php

class Z_PostsTable extends Z_Table {

    protected $_user;

    public function __construct($table) {
        parent::__construct($table);
        $this->_user = Z_Factory::Z_User();
    }

    public function getList($where = 1) {
        if (!$this->_user->getUserGroup()->isPermitAction("show_hide_posts")) {
             return parent::getList($where . " AND hide != '1' OR (hide = '1' AND autor = '" . $this->_user->id() . "')");
        } else {
            return parent::getList($where);
        }
    }

    public function getListEx($params = array(), &$numPages = null) {
        if (!$this->_user->getUserGroup()->isPermitAction("show_hide_posts")) {
            if (isset($params["where"])) {
                $params["where"] .= "AND hide != '1' OR (hide = '1' AND autor = '" . $this->_user->id() . "')";
            } else {
                $params["where"] = "hide != '1' OR (hide = '1' AND autor = '" . $this->_user->id() . "')";
            }
        }
        return parent::getListEx($params, $numPages);
    }

    public function getBestPosts() {
        $params = array(
            "sort_field" => "karma",
            "sort_option" => "DESC",
            "page_count" => 10,
        );
        return $this->getListEx($params);
    }

    public function getNewPosts() {
        $params = array(
            "sort_field" => "created_time",
            "sort_option" => "DESC",
            "page_count" => 10,
        );
        return $this->getListEx($params);
    }

    public function getPublicPosts()
    {
       return parent::getList("hide != '1'");
    }
    
    public function loadObject($id, $class = null) {
        $object = parent::loadObject($id, $class);
        if ($object->hide() && ($object->autor() != $this->_user->id() && !$this->_user->getUserGroup()->isPermitAction("show_hide_posts"))) {
            throw new Z_HiddenException("Пост $id скрыт автором или администрацией");
        } else {
            return $object;
        }
    }

    public function getNewPost() {
        return $this->loadNewObject();
    }

    public function safeRemoveAutor($id) {
        $posts = $this->getList("autor='" . $id . "'");
        foreach ($posts As $post) {
            $this->safeRemovePost($post->id());
        }
    }

    public function safeRemovePost($id) {
        $post = $this->loadObject($id);
        $subscribeTable = new Z_SubscriptionTable("subscription", $post->id());
        $favoritesTable = new Z_FavoritesTable("favorites", $this->_user);
        $journalCommentsTable = Z_Factory::Z_JournalCommentsTable();
        $journalPostsTable = Z_Factory::Z_JournalPostsTable();
        $tagsTable = Z_Factory::Z_TagsTable();
        $subscribeTable->safeRemoveTarget($post->id());
        $journalCommentsTable->safeRemovePost($post->id());
        $journalPostsTable->safeRemovePost($post->id());
        $tagsTable->earseTags($post->tags(), "");
        $favoritesTable->safeRemoveFavorite($id);
        $post->remove();
    }

}

?>
