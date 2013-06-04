<?php

class post extends Z_Controller {

    private $_postsTable;
    private $_karmaTable;
    private $_usersTable;
    private $_subscriptionTable;
    private $_commentsTable;
    private $_historyTable;
    private $_journalCommentsTable;
    private $_journalPostsTable;
    private $_post;
    private $_autor;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        $this->_postsTable = Z_Factory::Z_PostsTable();
        $this->_commentsTable = new Z_Table("comments");
        $this->_historyTable = $this->_user->getHistoryTable();
        $this->_journalCommentsTable = Z_Factory::Z_JournalCommentsTable();
        $this->_journalPostsTable = Z_Factory::Z_JournalPostsTable();
    }

    public function run() {
        if ($this->_user->getUserGroup()->isPermitAction("show_post")) {
            if (is_numeric($this->_action)) {
                $this->_post = $this->_postsTable->loadObject($this->_action);
                $this->_subscriptionTable = new Z_SubscriptionTable("subscription", $this->_user);
                $this->_mainView->setTitle($this->_post->name());
                $this->_usersTable = Z_Factory::Z_UsersTable();
                $this->_autor = $this->_usersTable->loadObject($this->_post->autor());
                $this->_karmaTable = new Z_KarmaTable($this->_user);
                $this->_addShows();
                $this->_setViewedRecord();
                $this->_printPost();
                $this->_printTags();
                $this->_printRootComments();
                $this->_printLinks();
                $this->_printSubscription();
            } else {
                throw new Z_BadRequestException();
            }
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    private function _printPost() {
        $this->_view->replace("title", $this->_post->name());
        $this->_view->replace("text", $this->_post->format_text());
        $infoPanel = new W_InfoPanel("postinfopanel", "infopanel", $this->_view);
        $infoPanel->printInfoPanel($this->_post);
        $this->_printSocialButtons($infoPanel);
        $this->_printKarma($infoPanel);
    }

    private function _printKarma($infoPanel) {
        $karma = $this->_post->karma();
        if ($karma < 0) {
            $infoPanel->replace("karma", "<span style='color: #FF0000'>$karma</span>");
        } elseif ($karma > 0) {
            $infoPanel->replace("karma", "<span style='color: #00CC00'>+$karma</span>");
        } else {
            $infoPanel->replace("karma", "<span style='color: darkgrey'>$karma</span>");
        }
        if ($this->_karmaTable->canUpKarma($this->_post, Z_KarmaTable::TYPE_POST)) {
            $infoPanel->replace("up", "<a href='" . URL . "postwork/up/post/$this->_action'><img src='" . URL . "Images/up_on.png'></a>");
        } else {
            $infoPanel->replace("up", "<img src='" . URL . "Images/up_off.png'>");
        }
        if ($this->_karmaTable->canDownKarma($this->_post, Z_KarmaTable::TYPE_POST)) {
            $infoPanel->replace("down", "<a href='" . URL . "postwork/down/post/$this->_action'><img src='" . URL . "Images/down_on.png'></a>");
        } else {
            echo "";
            $infoPanel->replace("down", "<img src='" . URL . "Images/down_off.png'>");
        }
    }

    private function _printRootComments() {
        $page = $this->_getPage();
        $numPages = 0;
        $params = array(
            "page" => $page,
            "page_count" => $this->_config->page_count()
        );
        $comments = $this->_post->getListEx($params, $numPages);
        foreach ($comments As $comment) {
            $this->_printComment($comment, 0);
            $this->_printComments($comment, 30);
        }
        $this->_printPages($page, $numPages);
    }

    private function _printComments(Z_TreeElement $item, $level) {
        $comments = $item->getList();
        foreach ($comments As $comment) {
            $this->_printComment($comment, $level);
            $this->_printComments($comment, $level + 30);
        }
    }

    private function _printComment(Z_Comment $comment, $level) {
        $commentView = new Z_TemplateView("comment", "comment", $this->_view, false);
        $autor = $this->_usersTable->loadObject($comment->autor());
        $commentView->replace("online", $autor->icon());
        $commentView->replace("avatar", $autor->image());
        $commentView->replace("autor", $autor->login());
        $commentView->replace("autor_id", $autor->id());
        $commentView->replace("date", $comment->created_date());
        $commentView->replace("eply", $comment->reply());
        $commentView->replace("comment_id", $comment->id());
        $commentView->replace("level", $level);
        $commentView->replace("post", $this->_action);
        if ($comment->hide()) {
            $commentView->replace("text", "<span style='color: dimgray'>Комментарий скрыт</span>");
        } else {
            $commentView->replace("text", $comment->format_text());
        }
        $commentView->replace("add", ($this->_user->getUserGroup()->isPermitAction("reply_comment") && $this->_user->karma() > -10) ? "
                <a href='" . URL . "commentwork/reply/post/" . $this->_post->id() . "/comment/" . $comment->id() . "'>ответ</a>" : "");
        $commentView->replace("hide", ($this->_user->id() == $comment->autor() || $this->_user->getUserGroup()->isPermitAction("hide_comment")) ?
                        "<a href='" . URL . "commentwork/hide/post/" . $this->_post->id() . "/comment/" . $comment->id() . "'>скрыть</a>" : "");
        $commentView->replace("modify", (time() - $comment->created_time() < 600 && $this->_user->id() == $comment->autor() || $this->_user->getUserGroup()->isPermitAction("modify_comment")) ?
                        "<a href='" . URL . "commentwork/modify/post/" . $this->_post->id() . "/comment/" . $comment->id() . "'>редактировать</a>" : "");
        $this->_printCommentKarma($comment, $commentView);
    }

    private function _printCommentKarma($comment, $commentView) {
        $karma = $comment->karma();
        if ($karma < 0) {
            $commentView->replace("karma", "<span style='color: #FF0000'>$karma</span>");
        } elseif ($karma > 0) {
            $commentView->replace("karma", "<span style='color: #00CC00'>+$karma</span>");
        } else {
            $commentView->replace("karma", "<span style='color: darkgrey'>$karma</span>");
        }
        if ($this->_karmaTable->canUpKarma($comment, Z_KarmaTable::TYPE_COMMENT)) {
            $commentView->replace("up", "<a href='" . URL . "commentwork/up/post/$this->_action/comment/" . $comment->id() . "'><img src='" . URL . "Images/up_on.png'></a>");
        } else {
            $commentView->replace("up", "<img src='" . URL . "Images/up_off.png'>");
        }
        if ($this->_karmaTable->canDownKarma($comment, Z_KarmaTable::TYPE_COMMENT)) {
            $commentView->replace("down", "<a href='" . URL . "commentwork/down/post/$this->_action/comment/" . $comment->id() . "'><img src='" . URL . "Images/down_on.png'></a>");
        } else {
            $commentView->replace("down", "<img src='" . URL . "Images/down_off.png'>");
        }
    }

    private function _printLinks() {
        if ($this->_user->getUserGroup()->isPermitAction("add_comment")) {
            $addcomment = new Z_TemplateView("addcomment", "addcomment", $this->_view);
            $addcomment->replace("post", $this->_post->id());
        }else{
            $this->_view->replace("addcomment", "<span style='font-size: 20px;'>
                Добавлять комментарии могут только зарегистрированные пользователи
                </span>");
        }
        if ($this->_post->autor() == $this->_user->id() || $this->_user->getUserGroup()->isPermitAction("edit_post")) {
            $this->_view->replace("edit", "<a href='" . URL . "postwork/edit/post/" . $this->_post->id() . "/'>Редактировать</a>");
        }
    }

    private function _printSubscription() {
        if ($this->_user->getUserGroup()->isPermitAction("subscription_work")) {
            $subscriptionTemplate = new Z_TemplateView("subscriptionlinks", "subscription_links", $this->_view);
            if ($this->_subscriptionTable->isSign($this->_post->id())) {
                $subscriptionTemplate->replace("yes", "да");
                $subscriptionTemplate->replace("no", "<a href='" . URL . "postwork/signdown/post/" . $this->_post->id() . "'>нет</a>");
            } else {
                $subscriptionTemplate->replace("yes", "<a href='" . URL . "postwork/signup/post/" . $this->_post->id() . "'>да</a>");
                $subscriptionTemplate->replace("no", "нет");
            }
        }else{
            $this->_view->replace("subscription_links", "За новыми комментариями могут следить только зарегистрированные пользователи");
        }
    }

    private function _addShows() {
        if (!$this->_historyTable->isShow()) {
            $this->_post->num_open($this->_post->num_open() + 1);
        }
    }

    private function _setViewedRecord() {
        $this->_journalCommentsTable->setViewedRecord($this->_post->id());
        $this->_journalPostsTable->setViewedRecord($this->_post->id());
    }

    private function _printTags() {
        $tags = explode(",", $this->_post->tags());
        foreach ($tags As $tag) {
            $this->_view->replace("tags", "<a class='tag' href='" . URL . "posts/tags/word/$tag/'> *$tag</a>, ", false);
        }
    }
    
    public function _printSocialButtons($template)
    {
        $template->replace("title", $this->_post->name());
        $template->replace("link", URL . "post/" . $this->_post->id());
    }

}

?>
