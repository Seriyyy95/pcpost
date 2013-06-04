<?php

class W_Statistics extends Z_TemplateView
{

    protected $_historyTable;

    public function __construct()
    {
        $mainView = Z_Factory::Z_MainView();
        parent::__construct("statistics", "statistics", $mainView);
        $this->_historyTable = Z_Factory::Z_HistoryTable();
        $this->_initView();
    }

    private function _initView()
    {
        $this->replace("online_users", $this->_historyTable->getOnlineUsers());
        $this->replace("online_guests", $this->_historyTable->getOnlineGuests());
        $this->replace("hits", $this->_historyTable->getHits(Z_HistoryTable::TIME_TODAY));
        $this->replace("hosts", $this->_historyTable->getHosts(Z_HistoryTable::TIME_TODAY));
    }

}

?>
