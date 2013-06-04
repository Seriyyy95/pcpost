<?php

class postwork extends Z_Controller {

    protected $_sectionsTable;
    protected $_postsTable;

    public function __construct($action, $params = array()) {
        $this->_sectionsTable = new Z_Table("sections");
        $this->_postsTable = Z_Factory::Z_PostsTable();
        parent::__construct($action, $params);
    }

    public function run() {
        $tagsTable = Z_Factory::Z_TagsTable();
        switch ($this->_action) {
            case "send":
                if ($this->_user->getUserGroup()->isPermitAction("add_post") && $this->_user->karma() > $this->_config->add_post_karma_limit()) {
                    if (isset($this->_params["name"]) && isset($this->_params["section"]) && isset($this->_params["description"]) &&
                            isset($this->_params["text"]) && isset($this->_params["tags"])) {
                        $name = addslashes($this->_params["name"]);
                        $description = rtrim($this->_params["description"]);
                        $description = addslashes($description);
                        $text = $this->_params["text"];
                        $text = addslashes($text);
                        $tags = htmlspecialchars($this->_params["tags"]);
                        $tags = preg_replace(" *, *", ",", $tags);
                        $tags = addslashes($tags);
                        $section = is_numeric($this->_params["section"]) ? $this->_params["section"] : "2";
                        $hide = Isset($this->_params["hide"]) ? '1' : '0';
                        $post = array(
                            "name" => $name,
                            "parent_id" => $section,
                            "description" => $description,
                            "text" => $text,
                            "tags" => $tags,
                            "hide" => $hide,
                            "autor" => $this->_user->id()
                        );
                        $this->_postsTable->add($post);
                        $tagsList = explode(",", $tags);
                        foreach ($tagsList As $tag) {
                            $tagsTable->addTag($tag);
                        }
                        $newPost = $this->_postsTable->getNewPost();
                        $this->_workSubscription($newPost);
                        header("Location: " . URL . "post/" . $newPost->id());
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
            case "save":
                if (isset($this->_params["name"]) && isset($this->_params["section"]) && isset($this->_params["description"]) &&
                        isset($this->_params["text"]) && isset($this->_params["tags"]) && isset($this->_params["post"]) &&
                        is_numeric($this->_params["post"])) {
                    $post = $this->_postsTable->loadObject($this->_params["post"]);
                    if ($post->autor() == $this->_user->id() || $this->_user->getUserGroup()->isPermitAction("edit_post")) {
                        $name = htmlspecialchars($this->_params["name"]);
                        $name = addslashes($name);
                        $description = rtrim($this->_params["description"]);
                        $description = addslashes($description);
                        $text = rtrim($this->_params["text"]);
                        $text = addslashes($text);
                        $tags = preg_replace(" *, *", ",", $this->_params["tags"]);
                        $tags = addslashes($tags);
                        $section = is_numeric($this->_params["section"]) ? $this->_params["section"] : "2";
                        $hide = Isset($this->_params["hide"]) ? '1' : '0';
                        $post->name($name);
                        $post->description($description);
                        $post->text($text);
                        $tagsTable->earseTags($post->tags(), $tags);
                        $post->tags($tags);
                        $post->parent_id($section);
                        $post->hide($hide);
                        header("Location: " . URL . "post/" . $post->id());
                    } else {
                        throw new Z_AccessDeniedException();
                    }
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "up":
                if (isset($this->_params["post"]) && is_numeric($this->_params["post"])) {
                    $karmaTable = new Z_KarmaTable($this->_user);
                    $post = $this->_postsTable->loadObject($this->_params["post"]);
                    $karmaTable->upKarma($post, "post");
                    header("Location: " . URL . "post/" . $this->_params["post"]);
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "down":
                if (isset($this->_params["post"]) && is_numeric($this->_params["post"])) {
                    $karmaTable = new Z_KarmaTable($this->_user);
                    $post = $this->_postsTable->loadObject($this->_params["post"]);
                    $karmaTable->downKarma($post, "post");
                    header("Location: " . URL . "post/" . $this->_params["post"]);
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "signup":
                if ($this->_user->getUserGroup()->isPermitAction("subscription_work")) {
                    if (isset($this->_params["post"]) && is_numeric($this->_params["post"])) {
                        $subscriptionTable = new Z_SubscriptionTable("subscription", $this->_user);
                        $subscriptionTable->signUp($this->_params["post"]);
                        header("Location: " . URL . "post/" . $this->_params["post"]);
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
            case "signdown":
                if ($this->_user->getUserGroup()->isPermitAction("subscription_work")) {
                    if (isset($this->_params["post"]) && is_numeric($this->_params["post"])) {
                        $subscriptionTable = new Z_SubscriptionTable("subscription", $this->_user);
                        $subscriptionTable->signDown($this->_params["post"]);
                        header("Location: " . URL . "post/" . $this->_params["post"]);
                    } else {
                        throw new Z_BadRequestException();
                    }
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
        }
    }

    protected function _initView() {
        switch ($this->_action) {
            case "add":
                if ($this->_user->getUserGroup()->isPermitAction("add_post") &&
                        $this->_user->karma() > $this->_config->add_post_karma_limit()) {
                    $this->_view = new Z_TemplateView("addpost", "page", $this->_mainView);
                    $this->_printSections(2);
                } else {
                    throw new Z_AccessDeniedException();
                }
                break;
            case "edit":
                if (isset($this->_params["post"]) && is_numeric($this->_params["post"])) {
                    $post = $this->_postsTable->loadObject($this->_params["post"]);
                    if ($post->autor() == $this->_user->id() || $this->_user->getUserGroup()->isPermitAction("edit_post")) {
                        $this->_view = new Z_TemplateView("editpost", "page", $this->_mainView);
                        $this->_view->replace("post", $post->id());
                        $this->_view->replace("name", $post->name());
                        $this->_view->replace("description", $post->description());
                        $this->_view->replace("text", $post->text());
                        $this->_view->replace("tags", $post->tags());
                        $this->_view->replace("hide", $post->hide() ? "checked" : "");
                        $this->_printSections($post->parent_id());
                    } else {
                        throw new Z_AccessDeniedException();
                    }
                } else {
                    throw new Z_BadRequestException();
                }
                break;
        }
    }

    private function _printSections($select) {
        $sections = $this->_sectionsTable->getList("parent_id=1");
        foreach ($sections As $value) {
            $this->_view->replace("option", "<option value=" . $value->id() . " " . ($value->id() == $select ?
                            "selected" : "") . ">" . $value->name() . "</option><br>", false);
        }
    }

    private function _workSubscription($newPost) {
        $subscriptionTable = new Z_SubscriptionTable("subscription", $this->_user);
        $subscriptionAutorsTable = new Z_SubscriptionTable("autors_subscription", $this->_user);
        $journalPostsTable = Z_Factory::Z_JournalPostsTable();
        
        $subscriptionTable->signUp($newPost->id());
        $subscribes = $subscriptionAutorsTable->getSubscribers($this->_user->id());
        foreach ($subscribes As $subscribe) {
            $journalPostsTable->addRecord($subscribe->user_id(), $newPost->id());
        }
    }

}

?>
