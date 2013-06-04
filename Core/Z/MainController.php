<?php

class Z_MainController extends Z_Object {

    protected $_db;
    protected $_config;
    protected $_view;
    protected $_authController;
    protected $_user;
    protected $_usersTable;
    protected $_postsTable;
    protected $_tagsTable;
    protected $_widgetsTable;
    protected $_messagesTable;
    protected $_journalCommentsTable;
    protected $_journalPostsTable;
    protected $_GroupsTable;
    protected $_session;

    public function __construct() {
        $this->_initConfigs();
        $this->_initView();
        $this->_initDB();
        $this->_initTables();
        $this->_initUser();
        $this->_frontController = new Z_FrontController();
        $this->_initOtherTables();
        $this->_checkServiceWorks();
        $this->_initSitebar();
        $this->_initWidgets();
        $this->_initController();
    }

    private function __clone() {
        
    }

    private function _initDB() {
        try {
            $this->_db = new Z_DB($this->_config->host(), $this->_config->user(), $this->_config->password(), $this->_config->dbname());
        } catch (Z_Exception $e) {
            $exception = new Z_ExceptionView("dberror");
        }
    }

    private function _initConfigs() {
        $this->_session = new Z_Session();
        $this->_config = new Z_Config("site.ini");
    }

    private function _initTables() {
        $this->_groupsTable = new Z_GroupsTable("groups");
        $this->_usersTable = new Z_UsersTable();
    }

    public function _initOtherTables() {
        $this->_postsTable = new Z_PostsTable("posts");
        $this->_tagsTable = new Z_TagsTable("tags");
        $this->_widgetsTable = new Z_WidgetsTable("widgets");
        $this->_messagesTable = new Z_MessagesTable("messages", $this->_user);
        $this->_journalCommentsTable = new Z_JournalCommentsTable("journal_comments");
        $this->_journalPostsTable = new Z_JournalPostsTable("journal_posts");
    }

    private function _initView() {
        $this->_view = new Z_MainView("page");
        $this->_view->setStyle($this->_config->style());
        $this->_view->setStyle($this->_config->bloks());
        $this->_view->setFavicon($this->_config->favicon());
        $this->_view->setLogo($this->_config->logo());
        $this->_view->setRss($this->_config->rss());
        $this->_view->setRssIcon($this->_config->rss_icon());
    }

    private function _initUser() {
        $this->_authController = new Z_AuthController();
        $this->_user = $this->_authController->getUser();
    }

    private function _initSitebar() {
        $sitepanel = new W_SitePanel();
        $usrpanel = new W_UserPanel();
        $best = new W_Best();
        $new = new W_New();
        $search = new Z_TemplateView("search", "search", $this->_view);
        $skyTags = new W_SkyTags();
        if ($this->_user->getUserGroup()->isPermitAction("show_statistic")) {
            $statistics = new W_Statistics();
        }
    }

    private function _initWidgets() {
        $list = $this->_widgetsTable->getList();
        foreach ($list As $widgetElement) {
            $widget = new W_Widget($widgetElement);
        }
    }

    private function _initController() {
        try {
            $class = $this->_frontController->getController();
            $controller = new $class($this->_frontController->getAction(), $this->_frontController->getParams());
            $controller->run();
        } catch (Z_LoadClassException $e) {
            $exception = new Z_ExceptionView("404", $e->getMessage());
        } catch (Z_BadRequestException $e) {
            $exception = new Z_ExceptionView("400", $e->getMessage());
        } catch (Z_NotExistException $e) {
            $exception = new Z_ExceptionView("404", $e->getMessage());
        } catch (Z_HiddenException $e) {
            $exception = new Z_ExceptionView("404", $e->getMessage());
        } catch (Z_InvalidActionException $e) {
            $exception = new Z_ExceptionView("invalidaction", $e->getMessage());
        } catch (Z_AccessDeniedException $e) {
            $exception = new Z_ExceptionView("403", $e->getMessage());
        } catch (Z_UserNotFoundException $e) {
            $exception = new Z_ExceptionView("404user", $e->getMessage());
        } catch (Z_Exception $e) {
            $exception = new Z_ExceptionView("500", $e->getMessage());
        }
    }

    private function _checkServiceWorks() {
        if ($this->_config->service_works() == 1 && $this->_user->getUserGroup()->isPermitAction("service_work")) {
            $exception = new Z_ExceptionView("serviceworks");
        }
    }

}

?>
