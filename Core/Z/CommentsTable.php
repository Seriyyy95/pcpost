<?php


class Z_CommentsTable extends Z_Table
{

    public function __construct($table)
    {
        parent::__construct($table);
    }
    
    public function safeRemoveComment($id)
    {
        $comment = $this->_loadObject($id);
        $comment->remove();
    }
    
    public function safeRemoveAutor($id)
    {
        $comments = $this->getList("autor='$id'");
        foreach($comments As $comment){
            $comment->remove();
        }
    }
    
}

?>
