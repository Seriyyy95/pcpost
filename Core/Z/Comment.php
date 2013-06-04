<?php

class Z_Comment extends Z_TreeElement {

    public function __construct(Z_Table $table, $id) {
        parent::__construct($table, $id);
    }

    public function remove() {
        parent::remove();
        $journalTable = Z_Factory::Z_JournalCommentsTable();
        $journalTable->safeRemoveComment($this->id());
    }

    public function getRootComment($id) {
        $comment = $this->_childTable->loadObject($id);
        if((string)$comment->parent_table() == (string)$comment->_table){
            $id = $comment->parent_id();
            $id = $comment->getRootComment($id);
        }
        return $id;
    }

}

?>
