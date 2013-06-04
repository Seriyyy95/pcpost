<?php

class Z_JournalCommentsTable extends Z_JournalTable {

    public function __construct($table) {
        parent::__construct($table);
    }

    public function addRecord($subscriber, $post, $comment) {
        if ($this->isRecord($subscriber, $post)) {
            $record = $this->getRecord($subscriber, $post);
            $record->count($record->count() + 1);
            $record->comment_id($comment);
        } else {
            $params = array(
                "post_id" => $post,
                "comment_id" => $comment,
                "user_id" => $subscriber
            );
            $this->add($params);
        }
    }

    public function safeRemoveComment($id) {
        $this->_db->setQuery("DELETE FROM " . $this->_table . " WHERE comment_id='$id'");
    }

}

?>
