<?php

class groupeditor extends Z_Controller {

    protected $_groupsTable;
    protected $_usersTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("edit_groups")) {
            $this->_groupsTable = Z_Factory::Z_GroupsTable();
            $this->_usersTable = Z_Factory::Z_UsersTable();
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run() {
        switch ($this->_action) {
            case "add":
                if (isset($this->_params["group_name"])) {
                    $groupName = addslashes($this->_params["group_name"]);
                    $this->_groupsTable->addGroup($groupName);
                    header("Location: " . URL . "groupeditor");
                } else {
                    throw new Z_BadRequestException();
                }
                break;
            case "save":
                $this->_save();
                break;
            case "delete":
                if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
                    $group = $this->_groupsTable->loadObject($this->_params["id"]);
                    if ($group->id() > 3) {
                        $users = $this->_usersTable->countUsersInGroup($group->id());
                        if ($users == 0) {
                            $group->remove();
                            header("Location: " . URL . "groupeditor");
                        } else {
                            throw new Z_InvalidActionException("Группа \"" . $group->group_name() . "\" не пустаая");
                        }
                    } else {
                        throw new Z_InvalidActionException("Это системная группа, ее нельзя удалить");
                    }
                } else {
                    throw new Z_BadRequestException("Должен быть указан параметр id и он должен быть цифрой");
                }
                break;
            case "modify":
                $this->_modify();
                break;
            default:
                $groupsList = $this->_groupsTable->getList();
                foreach ($groupsList As $element) {
                    $groupTemplate = new Z_TemplateView("groupeditorelement", "group", $this->_view, false);
                    $groupTemplate->replace("group_name", $element->group_name());
                    $groupTemplate->replace("id", $element->id());
                }
                break;
        }
    }

    protected function _initView() {
        if ($this->_action == "modify") {
            $this->_view = new Z_TemplateView("groupeditormodify", "page", $this->_mainView);
        } else {
            parent::_initView();
        }
    }

    protected function _save() {
        if (isset($this->_params["group_name"]) && isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
            $group = $this->_groupsTable->loadObject($this->_params["id"]);
            $groupName = addslashes($this->_params["group_name"]);
            $group->group_name($groupName);
            if (isset($this->_params["show_post"]) && !$group->isPermitAction("show_post")) {
                $group->addPermitAction("show_post");
            } elseif (!isset($this->_params["show_post"]) && $group->isPermitAction("show_post")) {
                $group->removePermitAction("show_post");
            }
            if (isset($this->_params["edit_post"]) && !$group->isPermitAction("edit_post")) {
                $group->addPermitAction("edit_post");
            } elseif (!isset($this->_params["edit_post"]) && $group->isPermitAction("edit_post")) {
                $group->removePermitAction("edit_post");
            }
            if (isset($this->_params["add_post"]) && !$group->isPermitAction("add_post")) {
                $group->addPermitAction("add_post");
            } elseif (!isset($this->_params["add_post"]) && $group->isPermitAction("add_post")) {
                $group->removePermitAction("add_post");
            }
            if (isset($this->_params["add_comment"]) && !$group->isPermitAction("add_comment")) {
                $group->addPermitAction("add_comment");
            } elseif (!isset($this->_params["add_comment"]) && $group->isPermitAction("add_comment")) {
                $group->removePermitAction("add_comment");
            }
            if (isset($this->_params["reply_comment"]) && !$group->isPermitAction("reply_comment")) {
                $group->addPermitAction("reply_comment");
            } elseif (!isset($this->_params["reply_comment"]) && $group->isPermitAction("reply_comment")) {
                $group->removePermitAction("reply_comment");
            }
            if (isset($this->_params["modify_comment"]) && !$group->isPermitAction("modify_comment")) {
                $group->addPermitAction("modify_comment");
            } elseif (!isset($this->_params["modify_comment"]) && $group->isPermitAction("modify_comment")) {
                $group->removePermitAction("modify_comment");
            }
            if (isset($this->_params["hide_comment"]) && !$group->isPermitAction("hide_comment")) {
                $group->addPermitAction("hide_comment");
            } elseif (!isset($this->_params["hide_comment"]) && $group->isPermitAction("hide_comment")) {
                $group->removePermitAction("hide_comment");
            }
            if (isset($this->_params["vote"]) && !$group->isPermitAction("vote")) {
                $group->addPermitAction("vote");
            } elseif (!isset($this->_params["vote"]) && $group->isPermitAction("vote")) {
                $group->removePermitAction("vote");
            }
            if (isset($this->_params["auth"]) && !$group->isPermitAction("auth")) {
                $group->addPermitAction("auth");
            } elseif (!isset($this->_params["auth"]) && $group->isPermitAction("auth")) {
                $group->removePermitAction("auth");
            }
            if (isset($this->_params["show_authhistory"]) && !$group->isPermitAction("show_authhistory")) {
                $group->addPermitAction("show_authhistory");
            } elseif (!isset($this->_params["show_authhistory"]) && $group->isPermitAction("show_authhistory")) {
                $group->removePermitAction("show_authhistory");
            }
            if (isset($this->_params["show_users_authhistory"]) && !$group->isPermitAction("show_users_authhistory")) {
                $group->addPermitAction("show_users_authhistory");
            } elseif (!isset($this->_params["show_users_authhistory"]) && $group->isPermitAction("show_users_authhistory")) {
                $group->removePermitAction("show_users_authhistory");
            }
            if (isset($this->_params["show_userinfo"]) && !$group->isPermitAction("show_userinfo")) {
                $group->addPermitAction("show_userinfo");
            } elseif (!isset($this->_params["show_userinfo"]) && $group->isPermitAction("show_userinfo")) {
                $group->removePermitAction("show_userinfo");
            }
            if (isset($this->_params["show_more_userinfo"]) && !$group->isPermitAction("show_more_userinfo")) {
                $group->addPermitAction("show_more_userinfo");
            } elseif (!isset($this->_params["show_more_userinfo"]) && $group->isPermitAction("show_more_userinfo")) {
                $group->removePermitAction("show_more_userinfo");
            }
            if (isset($this->_params["show_userprofile"]) && !$group->isPermitAction("show_userprofile")) {
                $group->addPermitAction("show_userprofile");
            } elseif (!isset($this->_params["show_userprofile"]) && $group->isPermitAction("show_userprofile")) {
                $group->removePermitAction("show_userprofile");
            }
            if (isset($this->_params["show_journal"]) && !$group->isPermitAction("show_journal")) {
                $group->addPermitAction("show_journal");
            } elseif (!isset($this->_params["show_journal"]) && $group->isPermitAction("show_journal")) {
                $group->removePermitAction("show_journal");
            }
            if (isset($this->_params["show_messages"]) && !$group->isPermitAction("show_messages")) {
                $group->addPermitAction("show_messages");
            } elseif (!isset($this->_params["show_messages"]) && $group->isPermitAction("show_messages")) {
                $group->removePermitAction("show_messages");
            }
            if (isset($this->_params["edit_settings"]) && !$group->isPermitAction("edit_settings")) {
                $group->addPermitAction("edit_settings");
            } elseif (!isset($this->_params["edit_settings"]) && $group->isPermitAction("edit_settings")) {
                $group->removePermitAction("edit_settings");
            }
            if (isset($this->_params["work_messages"]) && !$group->isPermitAction("work_messages")) {
                $group->addPermitAction("work_messages");
            } elseif (!isset($this->_params["work_messages"]) && $group->isPermitAction("work_messages")) {
                $group->removePermitAction("work_messages");
            }
            if (isset($this->_params["subscription_work"]) && !$group->isPermitAction("subscription_work")) {
                $group->addPermitAction("subscription_work");
            } elseif (!isset($this->_params["subscription_work"]) && $group->isPermitAction("subscription_work")) {
                $group->removePermitAction("subscription_work");
            }
            if (isset($this->_params["administrate_user"]) && !$group->isPermitAction("administrate_user")) {
                $group->addPermitAction("administrate_user");
            } elseif (!isset($this->_params["administrate_user"]) && $group->isPermitAction("administrate_user")) {
                $group->removePermitAction("administrate_user");
            }
            if (isset($this->_params["safe_remove_post"]) && !$group->isPermitAction("safe_remove_post")) {
                $group->addPermitAction("safe_remove_post");
            } elseif (!isset($this->_params["safe_remove_post"]) && $group->isPermitAction("safe_remove_post")) {
                $group->removePermitAction("safe_remove_post");
            }
            if (isset($this->_params["safe_remove_user"]) && !$group->isPermitAction("safe_remove_user")) {
                $group->addPermitAction("safe_remove_user");
            } elseif (!isset($this->_params["safe_remove_user"]) && $group->isPermitAction("safe_remove_user")) {
                $group->removePermitAction("safe_remove_user");
            }
            if (isset($this->_params["edit_sections"]) && !$group->isPermitAction("edit_sections")) {
                $group->addPermitAction("edit_sections");
            } elseif (!isset($this->_params["edit_sections"]) && $group->isPermitAction("edit_sections")) {
                $group->removePermitAction("edit_sections");
            }
            if (isset($this->_params["edit_sitesettings"]) && !$group->isPermitAction("edit_sitesettings")) {
                $group->addPermitAction("edit_sitesettings");
            } elseif (!isset($this->_params["edit_sitesettings"]) && $group->isPermitAction("edit_sitesettings")) {
                $group->removePermitAction("edit_sitesettings");
            }
            if (isset($this->_params["show_statistic"]) && !$group->isPermitAction("show_statistic")) {
                $group->addPermitAction("show_statistic");
            } elseif (!isset($this->_params["show_statistic"]) && $group->isPermitAction("show_statistic")) {
                $group->removePermitAction("show_statistic");
            }
            if (isset($this->_params["show_users"]) && !$group->isPermitAction("show_users")) {
                $group->addPermitAction("show_users");
            } elseif (!isset($this->_params["show_users"]) && $group->isPermitAction("show_users")) {
                $group->removePermitAction("show_users");
            }
            if (isset($this->_params["edit_widgets"]) && !$group->isPermitAction("edit_widgets")) {
                $group->addPermitAction("edit_widgets");
            } elseif (!isset($this->_params["edit_widgets"]) && $group->isPermitAction("edit_widgets")) {
                $group->removePermitAction("edit_widgets");
            }
            if (isset($this->_params["service_works"]) && !$group->isPermitAction("service_works")) {
                $group->addPermitAction("service_works");
            } elseif (!isset($this->_params["service_works"]) && $group->isPermitAction("service_works")) {
                $group->removePermitAction("service_works");
            }
            if (isset($this->_params["edit_groups"]) && !$group->isPermitAction("edit_groups")) {
                $group->addPermitAction("edit_groups");
            } elseif (!isset($this->_params["edit_groups"]) && $group->isPermitAction("edit_groups")) {
                $group->removePermitAction("edit_groups");
            }
            if (isset($this->_params["show_adminpanel"]) && !$group->isPermitAction("show_adminpanel")) {
                $group->addPermitAction("show_adminpanel");
            } elseif (!isset($this->_params["show_adminpanel"]) && $group->isPermitAction("show_adminpanel")) {
                $group->removePermitAction("show_adminpanel");
            }
            if (isset($this->_params["autors_subscription_work"]) && !$group->isPermitAction("autors_subscription_work")) {
                $group->addPermitAction("autors_subscription_work");
            } elseif (!isset($this->_params["autors_subscription_work"]) && $group->isPermitAction("autors_subscription_work")) {
                $group->removePermitAction("autors_subscription_work");
            }
            if (isset($this->_params["show_favorites"]) && !$group->isPermitAction("show_favorites")) {
                $group->addPermitAction("show_favorites");
            } elseif (!isset($this->_params["show_favorites"]) && $group->isPermitAction("show_favorites")) {
                $group->removePermitAction("show_favorites");
            }
            if (isset($this->_params["show_users_favorites"]) && !$group->isPermitAction("show_users_favorites")) {
                $group->addPermitAction("show_users_favorites");
            } elseif (!isset($this->_params["show_users_favorites"]) && $group->isPermitAction("show_users_favorites")) {
                $group->removePermitAction("show_users_favorites");
            }
            if (isset($this->_params["work_favorites"]) && !$group->isPermitAction("work_favorites")) {
                $group->addPermitAction("work_favorites");
            } elseif (!isset($this->_params["work_favorites"]) && $group->isPermitAction("work_favorites")) {
                $group->removePermitAction("work_favorites");
            }
            header("Location: " . URL . "groupeditor");
        } else {
            throw new Z_BadRequestException();
        }
    }

    protected function _modify() {
        if (isset($this->_params["id"]) && is_numeric($this->_params["id"])) {
            $group = $this->_groupsTable->loadObject($this->_params["id"]);
            $this->_view->replace("group_name", $group->group_name());
            $this->_view->replace("id", $group->id());
            $this->_view->replace("show_post", $group->isPermitAction("show_post") ? "checked" : "");
            $this->_view->replace("edit_post", $group->isPermitAction("edit_post") ? "checked" : "");
            $this->_view->replace("add_post", $group->isPermitAction("add_post") ? "checked" : "");
            $this->_view->replace("add_comment", $group->isPermitAction("add_comment") ? "checked" : "");
            $this->_view->replace("reply_comment", $group->isPermitAction("reply_comment") ? "checked" : "");
            $this->_view->replace("modify_comment", $group->isPermitAction("modify_comment") ? "checked" : "");
            $this->_view->replace("hide_comment", $group->isPermitAction("hide_comment") ? "checked" : "");
            $this->_view->replace("vote", $group->isPermitAction("vote") ? "checked" : "");
            $this->_view->replace("auth", $group->isPermitAction("auth") ? "checked" : "");
            $this->_view->replace("show_authhistory", $group->isPermitAction("show_authhistory") ? "checked" : "");
            $this->_view->replace("show_users_authhistory", $group->isPermitAction("show_users_authhistory") ? "checked" : "");
            $this->_view->replace("show_userinfo", $group->isPermitAction("show_userinfo") ? "checked" : "");
            $this->_view->replace("show_more_userinfo", $group->isPermitAction("show_more_userinfo") ? "checked" : "");
            $this->_view->replace("show_userprofile", $group->isPermitAction("show_userprofile") ? "checked" : "");
            $this->_view->replace("show_journal", $group->isPermitAction("show_journal") ? "checked" : "");
            $this->_view->replace("show_messages", $group->isPermitAction("show_messages") ? "checked" : "");
            $this->_view->replace("edit_settings", $group->isPermitAction("edit_settings") ? "checked" : "");
            $this->_view->replace("work_messages", $group->isPermitAction("work_messages") ? "checked" : "");
            $this->_view->replace("subscription_work", $group->isPermitAction("subscription_work") ? "checked" : "");
            $this->_view->replace("administrate_user", $group->isPermitAction("administrate_user") ? "checked" : "");
            $this->_view->replace("safe_remove_post", $group->isPermitAction("safe_remove_post") ? "checked" : "");
            $this->_view->replace("safe_remove_user", $group->isPermitAction("safe_remove_user") ? "checked" : "");
            $this->_view->replace("edit_sections", $group->isPermitAction("edit_sections") ? "checked" : "");
            $this->_view->replace("edit_sitesettings", $group->isPermitAction("edit_sitesettings") ? "checked" : "");
            $this->_view->replace("show_statistic", $group->isPermitAction("show_statistic") ? "checked" : "");
            $this->_view->replace("show_users", $group->isPermitAction("show_users") ? "checked" : "");
            $this->_view->replace("edit_widgets", $group->isPermitAction("edit_widgets") ? "checked" : "");
            $this->_view->replace("service_works", $group->isPermitAction("service_works") ? "checked" : "");
            $this->_view->replace("edit_groups", $group->isPermitAction("edit_groups") ? "checked" : "");
            $this->_view->replace("show_adminpanel", $group->isPermitAction("show_adminpanel") ? "checked" : "");
            $this->_view->replace("autors_subscription_work", $group->isPermitAction("autors_subscription_work") ? "checked" : "");
            $this->_view->replace("show_favorites", $group->isPermitAction("show_favorites") ? "checked" : "");
            $this->_view->replace("show_users_favorites", $group->isPermitAction("show_users_favorites") ? "checked" : "");
            $this->_view->replace("work_favorites", $group->isPermitAction("work_favorites") ? "checked" : "");
        } else {
            throw new Z_BadRequestException();
        }
    }

}

?>
