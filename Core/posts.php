<?php

class posts extends Z_Controller {

    protected $_sqlparams = array();
    protected $_config;
    protected $_page;

    public function __construct($action, $params) {
        parent::__construct($action, $params);
    }

    public function run() {
        $this->_config = Z_Factory::Z_Config();
        $this->_page = $this->_getPage();
        $poststab = Z_Factory::Z_PostsTable();
        if (strlen($this->_action) == 0) {
            $this->_action = "all";
        }
        switch ($this->_action) {
            case "all":
                $this->_sqlparams["where"] = "parent_id !=2";
                $this->_view->replace("title", "Посты");
                $this->_mainView->setTitle("Посты");
                break;
            case "tags":
                if (isset($this->_params["word"])) {
                    $word = addslashes($this->_params["word"]);
                    $this->_sqlparams["search_field"] = "tags";
                    $this->_sqlparams["search_text"] = $word;
                    $this->_sqlparams["where"] = '1';
                    $this->_view->replace("title", $this->_params["word"]);
                    $this->_mainView->setTitle($this->_params["word"]);
                    $this->_url .= "tags/word/" . $this->_params["word"] . "/";
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "search":
                if (isset($this->_params["word"])) {
                    $word = addslashes($this->_params["word"]);
                    $this->_sqlparams["search_field"] = "text";
                    $this->_sqlparams["search_text"] = $word;
                    $this->_sqlparams["where"] = '1';
                    $this->_view->replace("title", "Поиск");
                    $this->_mainView->setTitle("Поиск");
                    $this->_url .= "word/" . $this->_params["word"] . "/";
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "user":
                if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                    $this->_sqlparams["where"] = "autor='" . $this->_params["id"] . "'";
                    $title = $this->_user->id() == $this->_params["id"] ? "Мои посты" : "Посты пользователья";
                    $this->_view->replace("title", $title);
                    $this->_mainView->setTitle($title);
                    $this->_url .= "user/id/" . $this->_params["id"] . "/";
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            default:
                if (is_numeric($this->_action)) {
                    $this->_sqlparams["where"] = "parent_id='" . $this->_action . "'";
                    $sectiontab = new Z_Table("sections");
                    $section = $sectiontab->loadObject($this->_action);
                    $this->_view->replace("title", $section->name());
                    $this->_mainView->setTitle($section->name());
                } else {
                    throw new Z_BadRequestException();
                }
                break;
        }
        $this->_view->replace("urlpage", $this->_url);
        $this->_initSqlParams();
        $numpages = 0;
        $list = $poststab->getListEx($this->_sqlparams, $numpages);
        if (count($list) == 0) {
            $empty = new Z_TemplateView("empty", "post", $this->_view);
        } else {
            foreach ($list As $object) {
                $post = new Z_TemplateView("postpreview", "post", $this->_view, false);
                $post->replace("url", URL);
                $post->replace("id", $object->id());
                $post->replace("title", $object->name());
                $post->replace("description", $object->format_description());
                $this->_printTags($post, $object);
                $infoPanel = new W_InfoPanel("infopanel", "infopanel", $post);
                $infoPanel->printInfoPanel($object);
            }
        }
        $this->_printSort();
        $this->_printLinks();
        $this->_printPages($this->_page, $numpages);
    }

    private function _initSqlParams() {
        if (isset($this->_params["sort"])) {
            if ($this->_params["sort"] == "best") {
                $this->_sqlparams["sort_field"] = "karma";
                $this->_sqlparams["sort_option"] = "DESC";
            } elseif ($this->_params["sort"] == "new") {
                $this->_sqlparams["sort_field"] = "created_time";
                $this->_sqlparams["sort_option"] = "DESC";
            } elseif ($this->_params["sort"] == "name") {
                $this->_sqlparams["sort_field"] = "name";
                $this->_sqlparams["sort_option"] = "ASC";
            }
        } else {
            $this->_sqlparams["sort_field"] = "karma";
            $this->_sqlparams["sort_option"] = "DESC";
        }

        $this->_sqlparams["page"] = $this->_page;
        $this->_sqlparams["where"] .= " AND (hide != 1 OR autor='" . $this->_user->id() . "')";
        $this->_sqlparams["page_count"] = $this->_config->page_count();
    }

    private function _printSort() {
        if (isset($this->_params["sort"])) {
            if ($this->_params["sort"] == "best") {
                $this->_view->replace("best", "лучшие");
                $this->_view->replace("new", "<a href='" . $this->_url . "sort/new''>новые</a>");
                $this->_view->replace("name", "<a href='" . $this->_url . "sort/name''>по имени</a>");
            } elseif ($this->_params["sort"] == "new") {
                $this->_view->replace("best", "<a href='" . $this->_url . "sort/best''>лучшие</a>");
                $this->_view->replace("new", "новые");
                $this->_view->replace("name", "<a href='" . $this->_url . "sort/name''>по имени</a>");
            } elseif ($this->_params["sort"] == "name") {
                $this->_view->replace("best", "<a href='" . $this->_url . "sort/best''>лучшие</a>");
                $this->_view->replace("new", "<a href='" . $this->_url . "sort/new''>новые</a>");
                $this->_view->replace("name", "по имени");
            }
            $this->_url .= "sort/" . $this->_params["sort"] . "/";
        } else {
            $this->_view->replace("best", "лучшие");
            $this->_view->replace("new", "<a href='" . $this->_url . "sort/new''>новие</a>");
            $this->_view->replace("name", "<a href='" . $this->_url . "sort/name''>по имени</a>");
        }
    }

    private function _printLinks() {
        if ($this->_user->getUserGroup()->isPermitAction("add_post")) {
            $this->_view->replace("add_post", "
            <a href='<!--url-->postwork/add'>Добавить пост</a>
            ");
        }
    }

    private function _printTags(Z_TemplateView $template, Z_Element $post) {
        $tags = explode(",", $post->tags());
        foreach ($tags As $tag) {
            $template->replace("tags", "<a class='tag' href='" . URL . "posts/tags/word/$tag/'> *$tag</a>, ", false);
        }
    }

}

?>
