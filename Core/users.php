<?php

class users extends Z_Controller
{

    protected $_usersTable;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("show_users")) {
            $this->_usersTable = Z_Factory::Z_UsersTable();
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run()
    {
        $page = $this->_getPage();
        $numPages = 0;
        $params = array(
            "page" => $page,
            "page_count" => $this->_config->page_count(),
        );
        $list = $this->_usersTable->getListEx($params, $numPages);
        foreach($list As $item){
            $elementTemplate = new Z_TemplateView("userselement", "element", $this->_view, false);
            $currentUser = $this->_usersTable->loadObject($item->id());
            $elementTemplate->replace("id", $currentUser->id());
            $elementTemplate->replace("avatar", $currentUser->image());
            $elementTemplate->replace("group", $currentUser->format_user_group());
            $elementTemplate->replace("login", $currentUser->login());
            $elementTemplate->replace("icon", $currentUser->icon());
            $elementTemplate->replace("reg_date", $currentUser->created_date());
            $elementTemplate->replace("reg_ip", $currentUser->reg_ip());
        }
        $this->_printPages($page, $numPages);
    }

}

?>
