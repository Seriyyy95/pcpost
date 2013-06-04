<?php

class sitesettings extends Z_Controller
{

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        if (!$this->_user->getUserGroup()->isPermitAction("edit_sitesettings")) {
            throw new Z_AccessDeniedException("У вас нет разрешения редактировать настройки сайта");
        }
    }

    public function run()
    {
        if ($this->_action == "save") {
            if (isset($this->_params["logo"]) && $this->_params["logo"] != $this->_config->logo()) {
                $this->_config->logo($this->_params["logo"]);
            }
            if (isset($this->_params["style"]) && $this->_params["style"] != $this->_config->style()) {
                $this->_config->style($this->_params["style"]);
            }
            if (isset($this->_params["bloks"]) && $this->_params["bloks"] != $this->_config->bloks()) {
                $this->_config->bloks($this->_params["bloks"]);
            }
            if (isset($this->_params["rss"]) && $this->_params["rss"] != $this->_config->rss()) {
                $this->_config->rss($this->_params["rss"]);
            }
            if (isset($this->_params["rss_icon"]) && $this->_params["rss_icon"] != $this->_config->rss_icon()) {
                $this->_config->rss_icon($this->_params["rss_icon"]);
            }
            if (isset($this->_params["page_count"]) && $this->_params["page_count"] != $this->_config->page_count()) {
                $this->_config->page_count($this->_params["page_count"]);
            }
            if (isset($this->_params["limit_pages"]) && $this->_params["limit_pages"] != $this->_config->limit_pages()) {
                $this->_config->limit_pages($this->_params["limit_pages"]);
            }
            if (isset($this->_params["serviceworks"]) && isset($this->_params["serviceworks"]) != $this->_config->service_works()) {
                $this->_config->service_works($this->_params["serviceworks"]);
            }
        }
        $this->_view->replace("logo", $this->_config->logo());
        $this->_view->replace("favicon", $this->_config->favicon());
        $this->_view->replace("style", $this->_config->style());
        $this->_view->replace("bloks", $this->_config->bloks());
        $this->_view->replace("rss", $this->_config->rss());
        $this->_view->replace("rss_icon", $this->_config->rss_icon());
        $this->_view->replace("page_count", $this->_config->page_count());
        $this->_view->replace("limit_pages", $this->_config->limit_pages());
        $this->_view->replace("service_" . $this->_config->service_works(), "selected");
    }

}

?>
