<?php

class statistic extends Z_Controller
{

    protected $_historyTable;
    protected $_usersTable;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("show_statistic")) {
            $this->_historyTable = $this->_user->getHistoryTable();
            $this->_usersTable = Z_Factory::Z_UsersTable();
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run()
    {
        $this->_view->replace("day_hosts", $this->_historyTable->getHosts(Z_HistoryTable::TIME_TODAY));
        $this->_view->replace("day_hits", $this->_historyTable->getHits(Z_HistoryTable::TIME_TODAY));
        $this->_view->replace("month_hosts", $this->_historyTable->getHosts());
        $this->_view->replace("month_hits", $this->_historyTable->getHits());
        $page = $this->_getPage();
        $numPages = 0;
        $params = array(
            "page" => $page,
            "page_count" => $this->_config->page_count(),
            "sort_field" => "created_time",
            "sort_option" => "DESC"
        );
        $list = $this->_historyTable->getListEx($params, $numPages);
        foreach ($list As $item) {
            $elementTemplate = new Z_TemplateView("statisticelement", "element", $this->_view, false);
            $currentUser = $this->_usersTable->loadObject($item->user_id());
            $elementTemplate->replace("date", $item->created_date());
            $elementTemplate->replace("id", $currentUser->id());
            $elementTemplate->replace("online", $currentUser->icon());
            $elementTemplate->replace("login", $currentUser->login());
            $elementTemplate->replace("group", $currentUser->format_user_group());
            $elementTemplate->replace("browser", $item->user_info());
            $elementTemplate->replace("request", $item->request_url());
            $elementTemplate->replace("referer", $item->referer_url());
        }

        $this->_printPages($page, $numPages);
    }

}

?>
