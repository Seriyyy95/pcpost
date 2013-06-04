<?php

class commentwork extends Z_Controller {

    protected $_postsTable;
    protected $_commentsTable;
    protected $_usersTable;

    public function __construct($action, $params = array()) {
        $this->_usersTable = Z_Factory::Z_UsersTable();
        $this->_postsTable = Z_Factory::Z_PostsTable();
        $this->_commentsTable = new Z_CommentsTable("comments");
        parent::__construct($action, $params);
    }

    public function run() {
        switch ($this->_action) {
            case "add":
                $this->_addComment();
                break;
            case "reply":
                $this->_replyComment();
                break;
            case "modify":
                $this->_modifyComment();
                break;
            case "save":
                $this->_saveComment();
                break;
            case "hide":
                $this->_hideComment();
                break;
            case "up":
                if (isset($this->_params["post"]) && is_numeric($this->_params["post"]) &&
                        isset($this->_params["comment"]) && is_numeric($this->_params["comment"])) {
                    $karmaTable = new Z_KarmaTable($this->_user);
                    $comment = $this->_commnetsTable->loadObject($this->_params["comment"]);
                    $karmaTable->upKarma($comment, "comment");
                    $this->_openPost($this->_params["post"], $this->params["comment"]);
                }
                break;
            case "down":
                if (isset($this->_params["comment"]) && is_numeric($this->_params["comment"]) &&
                        isset($this->_params["post"]) && is_numeric($this->_params["post"])) {
                    $karmaTable = new Z_KarmaTable($this->_user);
                    $commnetsTable = new Z_Table("comments");
                    $comment = $commnetsTable->loadObject($this->_params["comment"]);
                    $karmaTable->downKarma($comment, "comment");
                    $this->_openPost($this->_params["post"], $this->params["comment"]);
                }
                break;
        }
    }

    protected function _initView() {
        switch ($this->_action) {
            case "reply":
                $this->_view = new Z_TemplateView("addreply", "page", $this->_mainView);
                break;
            case "modify":
                $this->_view = new Z_TemplateView("modifycomment", "page", $this->_mainView);
                break;
        }
    }

    private function _addComment() {
        if ($this->_user->getUserGroup()->isPermitAction("add_comment") && $this->_user->karma() > -10) {
            if (isset($this->_params["post"]) && is_numeric($this->_params["post"]) && isset($this->_params["text"])) {
                $post = $this->_postsTable->loadObject($this->_params["post"]);
                $text = rtrim($this->_params["text"]);
                $text = htmlspecialchars($text);
                $text = addslashes($text);
                $commentStruct = array(
                    "autor" => $this->_user->id(),
                    "text" => $text
                );
                if (isset($this->_params["comment"])) {
                    if (is_numeric($this->_params["comment"])) {
                        $comment = $this->_commentsTable->loadObject($this->_params["comment"]);
                        $autor = $this->_usersTable->loadObject($comment->autor());
                        $commentStruct["reply"] = $autor->id();
                        $comment->add($commentStruct);
                    }
                } elseif (isset($this->_params["post"])) {
                    $autor = $this->_usersTable->loadObject($post->autor());
                    $commentStruct["reply"] = $autor->id();
                    $post->add($commentStruct);
                }
                $subscriptionTable = new Z_SubscriptionTable("subscription", $this->_user);
                $journalTable = Z_Factory::Z_JournalCommentsTable();
                if (!$subscriptionTable->isSign($this->_params["post"])) {
                    $subscriptionTable->signUp($this->_params["post"]);
                }
                $newComment = $this->_commentsTable->loadNewObject();
                $subscribes = $subscriptionTable->getSubscribers($this->_params["post"]);
                foreach ($subscribes As $subscribe) {
                    $journalTable->addRecord($subscribe->user_id(), $post->id(), $newComment->id());
                }
                $this->_openPost($post->id(), $newComment->id());
            } else {
                throw new Z_BadRequestException();
            }
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    private function _replyComment() {
        if ($this->_user->getUserGroup()->isPermitAction("reply_comment") && $this->_user->karma() > -10) {
            if (isset($this->_params["post"]) && is_numeric($this->_params["post"]) &&
                    isset($this->_params["comment"]) && is_numeric($this->_params["comment"])) {
                $post = $this->_postsTable->loadObject($this->_params["post"]);
                $comment = $this->_commentsTable->loadObject($this->_params["comment"]);
                $autor = $this->_usersTable->loadObject($comment->autor());
                $this->_mainView->setTitle("Добавить комментарий");
                $this->_view->replace("avatar", $autor->image());
                $this->_view->replace("online", $autor->icon());
                $this->_view->replace("comment_autor", $autor->id());
                $this->_view->replace("comment_autor_login", $autor->login());
                $this->_view->replace("text", $comment->text());
                $this->_view->replace("post", $post->id());
                $this->_view->replace("comment_id", $comment->id());
            } else {
                throw new Z_BadRequestException();
            }
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    private function _modifyComment() {
        if (isset($this->_params["post"]) && is_numeric($this->_params["post"]) &&
                isset($this->_params["comment"]) && is_numeric($this->_params["comment"])) {
            $comment = $this->_commentsTable->loadObject($this->_params["comment"]);
            if ((time() - $comment->created_time() < 600 && $this->_user->id() == $comment->autor() ||
                    $this->_user->getUserGroup()->isPermitAction("modify_comment"))) {
                $this->_mainView->setTitle("Редактировать комментарий");
                $this->_view->replace("text", $comment->text());
                $this->_view->replace("comment", $comment->id());
                $this->_view->replace("post", $this->_params["post"]);
            } else {
                throw new Z_AccessDeniedException();
            }
        } else {
            throw new Z_BadRequestException();
        }
    }

    private function _saveComment() {
        if (isset($this->_params["post"]) && is_numeric($this->_params["post"]) &&
                isset($this->_params["comment"]) && is_numeric($this->_params["comment"]) &&
                isset($this->_params["text"])) {
            $comment = $this->_commentsTable->loadObject($this->_params["comment"]);
            if ((time() - $comment->created_time() < 600 && $this->_user->id() == $comment->autor() ||
                    $this->_user->getUserGroup()->isPermitAction("modify_comment"))) {
                $text = rtrim($this->_params["text"]);
                $text = htmlspecialchars($text);
                $text = addslashes($text);
                $comment->text($text);
                $this->_openPost($this->_params["post"], $this->_params["comment"]);
            } else {
                throw new Z_AccessDeniedException();
            }
        } else {
            throw new Z_BadRequestException();
        }
    }

    private function _hideComment() {
        if (isset($this->_params["post"]) && is_numeric($this->_params["post"]) &&
                isset($this->_params["comment"]) && is_numeric($this->_params["comment"])) {
            $comment = $this->_commentsTable->loadObject($this->_params["comment"]);
            if ($this->_user->id() == $comment->autor() ||
                    $this->_user->getUserGroup()->isPermitAction("hide_comment")) {
                $comment->hide(1);
                $this->_openPost($this->_params["post"], $this->_params["comment"]);
            } else {
                throw new Z_AccessDeniedException();
            }
        } else {
            throw new Z_BadRequestException();
        }
    }

    private function _openPost($post, $commentId) {
        $params = array(
            "page_count" => $this->_config->page_count()
        );
        $comment = $this->_commentsTable->loadObject($commentId);
        $rootComment = $comment->getRootComment($commentId);
        $rootComment = $this->_commentsTable->loadObject($rootComment);
        $page = $rootComment->getElementPage($params);
        header("Location: " . URL . "post/" . $post . "/page/" . $page);
    }

}

?>
