<?php


class Z_FavoritesTable extends Z_Table{

    protected $_user;
    protected $_config;
    
    public function __construct($table, $user) {
        parent::__construct($table);
        $this->_config = Z_Factory::Z_Config();
        $this->_user = $user;
    }
    
    public function getFavorites($page, &$numPages)
    {
        $params = array(
            "page" => $page,
            "page_count" => $this->_config->page_count(),
            "where" => "user_id='" . $this->_user->id() . "'"
        );
        return $this->getListEx($params, $numPages);
    }
    
    public function addFavorite($post)
    {
        $favorite =array(
            "user_id" => $this->_user->id(),
            "post_id" => $post
        );
        $this->add($favorite);
    }
    
    public function removeFavorite($post)
    {
        $this->_db->setQuery("DELETE FROM " . $this->_table .  " WHERE post_id='$post' AND user_id='". $this->_user->id() ."'");
    }
    
    public function isFavorite($post){
        return $this->count("post_id=$post AND user_id='" . $this->_user->id() . "'") > 0;
    }
    
    public function getNumToFavorite($post)
    {
        return $this->count("post_id='$post'");
    }
    
    public function safeRemoveFavorite($post)
    {
        $this->_db->setQuery("DELETE FRIM " . $this->_table . " WHERE post_id='$post'");
    }
    
    public function safeRemoveUser($userID)
    {
        $this->_db->setQuery("DELETE FRIM " . $this->_table . " WHERE user_id='$userID'");
    }
    
}

?>
