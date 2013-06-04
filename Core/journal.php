<?php

class journal extends Z_Controller {

    protected $_journalTable;
    protected $_postsTable;
    protected $_commentsTable;
    protected $_usersTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("show_journal")) {
            if ($this->_action == "comments") {
                $this->_journalTable = Z_Factory::Z_JournalCommentsTable();
            } elseif ($this->_action == "posts") {
                $this->_journalTable = Z_Factory::Z_JournalPostsTable();
            } else {
                throw new Z_BadRequestException();
            }
            $this->_postsTable = Z_Factory::Z_PostsTable();
            $this->_commentsTable = new Z_Table("comments");
            $this->_usersTable = Z_Factory::Z_UsersTable();

        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run() {
        $page = $this->_getPage();
        $numPages = 0;
        if (isset($this->_params["off"]) && is_numeric($this->_params["off"])) {
            $this->_journalTable->deleteRecord($this->_params["off"]);
            $subscriptionTable = new Z_SubscriptionTable(($this->_action == "comments" ? "subscription" : "autors_subscription"), $this->_user);
            $subscriptionTable->signDown($this->_params["off"]);
        }
        if (isset($this->_params["cleer"])) {
            $this->_journalTable->deleteAllRecords();
        }
        switch ($this->_params["view"]) {
            case "new":
                $list = $this->_journalTable->getNewRecords($page, $numPages);
                break;
            case "all":
                $list = $this->_journalTable->getAllRecords($page, $numPages);
                break;
            default:
                throw new Z_BadRequestException();
                break;
        }
        if ($this->_action == "comments") {
            foreach ($list As $record) {
                $record->new(0);
                $journalTemplate = new Z_TemplateView("journalcommentrecord", "record", $this->_view, false);
                $post = $this->_postsTable->loadObject($record->post_id());
                $comment = $this->_commentsTable->loadObject($record->comment_id());
                $autor = $this->_usersTable->loadObject($comment->autor());
                $journalTemplate->replace("name", $post->name());
                $journalTemplate->replace("id", $post->id());
                $journalTemplate->replace("time", $comment->created_date());
                $journalTemplate->replace("autor", $autor->login());
                $journalTemplate->replace("count", $record->count());
                $journalTemplate->replace("action", $this->_params["view"]);
            }
        } elseif($this->_action == "posts"){
            foreach ($list As $record) {
                $record->new(0);
                $journalTemplate = new Z_TemplateView("postpreview", "record", $this->_view, false);
                $post = $this->_postsTable->loadObject($record->post_id());
                $journalTemplate->replace("id", $post->id());
                $journalTemplate->replace("title", $post->name());
                $journalTemplate->replace("description", $post->format_description());
                $this->_printTags($journalTemplate, $post);
                $infoPanel = new W_InfoPanel("infopanel", "infopanel", $journalTemplate);
                $infoPanel->printInfoPanel($post);
            }
        } else {
            throw new Z_BadRequestException();
        }
        $this->_view->replace("view", $this->_params["view"]);
        $this->_view->replace("action", $this->_action);
        $this->_printLinks();
        $this->_printPages($page, $numPages);
    }

    private function _printLinks() {
        switch ($this->_params["view"]) {
            case "new":
                $this->_view->replace("new", "новые");
                $this->_view->replace("all", "<a href='" . $this->_url . "view/all'>все</a>");
                break;
            case "all":
                $this->_view->replace("new", "<a href='" . $this->_url . "view/new'>новые</a>");
                $this->_view->replace("all", "все");
                break;
        }
    }
    
    private function _printTags(Z_TemplateView $template ,Z_Element $post) {
        $tags = explode(",", $post->tags());
        foreach ($tags As $tag) {
            $template->replace("tags", "<a class='tag' href='" . URL . "posts/tags/word/$tag/'> *$tag</a>, ", false);
        }
    }



}

?>
