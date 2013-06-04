<?php

class W_Best extends Z_TemplateView
{
    
    protected $_postsTable;
    
    public function __construct(){
        $mainView = Z_Factory::Z_MainView();
        $this->_postsTable = Z_Factory::Z_PostsTable();
        parent::__construct("best", "best", $mainView);
        $this->_run();
    }
    
    private function _run()
    {
        $bestList = $this->_postsTable->getBestPosts();
        foreach($bestList As $object){
            $item = new Z_TemplateView("listitem", "list", $this, false);
            $item->replace("url", URL . "post/");
            $item->replace("text", $object->name());
            $item->replace("id", $object->id());
        }
    }
    
}

?>
