<?php

class Z_JournalPostsTable extends Z_JournalTable {

    public function __construct($table) {
        parent::__construct($table);
    }

    public function addRecord($subscriber, $post) {
        $params = array(
            "post_id" => $post,
            "user_id" => $subscriber
        );
        $this->add($params);
    }

}

?>
