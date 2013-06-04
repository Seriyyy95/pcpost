<?php

class settings extends Z_Controller {

    protected $_currentUser;
    protected $_usersTable;
    protected $_groupsTable;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        $this->_usersTable = Z_Factory::Z_UsersTable();
        $this->_groupsTable = Z_Factory::Z_GroupsTable();
        if (isset($this->_params["user"]) && is_numeric($this->_params["user"]) &&
                $this->_user->id() != $this->_params["user"] && $this->_user->getUserGroup()->isPermitAction("administrate_user")) {
            $this->_currentUser = $this->_usersTable->loadObject($this->_params["user"]);
        } else {
            $this->_currentUser = $this->_user;
        }
    }

    public function run() {
        if ($this->_user->getUserGroup()->isPermitAction("edit_settings")) {
            switch ($this->_action) {
                case "save":
                    $this->_saveSettings();
                    break;
                case "upload":
                    $this->_uploadAvatar();
                    break;
                case "reset":
                    if (!$this->_currentUser->reseted()) {
                        $this->_currentUser->karma(0);
                        $this->_currentUser->reseted(1);
                    } else {
                        throw new Z_AccessDeniedException();
                    }
                    break;
            }
            $birth_date = $this->_currentUser->birth_date();
            $day = @date("j", $birth_date);
            $month = @date("m", $birth_date);
            $year = @date("Y", $birth_date);
            $this->_view->replace("title", "Настройки");
            $this->_view->replace("user", $this->_currentUser->id());
            $this->_view->replace("reset", $this->_currentUser->reseted() ? "" : "<a href='<!--url-->settings/reset/user/" . $this->_currentUser->id() . "'>Сбросить карму</a>");
            $this->_view->replace("name", $this->_currentUser->name());
            $this->_view->replace("email", $this->_currentUser->email());
            $this->_view->replace("country", $this->_currentUser->country());
            $this->_view->replace("about_me", $this->_currentUser->about_me());
            $this->_view->replace("city", $this->_currentUser->city());
            $this->_view->replace($this->_currentUser->pol(), "checked");
            $this->_printDays($day);
            $this->_printMonths($month);
            $this->_printYears($year);
            if ($this->_user->getUserGroup("administrate_user")) {
                $this->_view->replace("karma", $this->_currentUser->karma());
                $this->_printGroups($this->_currentUser->user_group());
            }
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    protected function _initView() {
        if (isset($this->_params["user"]) && is_numeric($this->_params["user"]) &&
                $this->_user->id() != $this->_params["user"] && $this->_user->getUserGroup()->isPermitAction("administrate_user")) {
            $this->_view = new Z_TemplateView("settingsadmin", "page", $this->_mainView);
        } else {
            $this->_view = new Z_TemplateView("settings", "page", $this->_mainView);
        }
    }

    private function _saveSettings() {
        if (isset($this->_params["name"]) && $this->_params["name"] != $this->_currentUser->name()) {
            if (strlen($this->_params["name"]) > 255) {
                $this->_view->replace("error", "Извените, но имя слишком длинное");
            } elseif (strlen($this->_params["name"]) == 0) {
                $this->_view->replace("error", "Поле \"Полное имя\" не должно быть пустым");
            } else {
                $name = htmlspecialchars($this->_params["name"]);
                $name = addslashes($name);
                $this->_currentUser->name($name);
            }
        }
        if (isset($this->_params["email"]) && $this->_params["email"] != $this->_currentUser->email()) {
            if (!preg_match("#^[^@ ]+@[^@ ]+\.[^@ \.]+$#", $this->_params["email"])) {
                $this->_view->replace("error", "Вы ввели некорректный Адрес електронной почты");
            } else {
                $email = htmlspecialchars($this->_params["email"]);
                $email = addslashes($email);
                $this->_currentUser->email($email);
            }
        }
        if (isset($this->_params["country"]) && $this->_params["country"] != $this->_currentUser->country()) {
            if (strlen($this->_params["country"]) > 255) {
                $this->_view->replace("error", "Извените, но название странны слишком длинное");
            } elseif (strlen($this->_params["country"]) == 0) {
                $this->_view->replace("error", "Поле \"Страна\" не должно быть пустым");
            } else {
                $country = htmlspecialchars($this->_params["country"]);
                $country = addslashes($country);
                $this->_currentUser->country($country);
            }
        }
        if (isset($this->_params["city"]) && $this->_params["city"] != $this->_currentUser->city()) {
            if (strlen($this->_params["city"]) > 255) {
                $this->_view->replace("error", "Извените, но название города слишком длинное");
            } elseif (strlen($this->_params["city"]) == 0) {
                $this->_view->replace("error", "Поле \"Город\" не должно быть пустым");
            } else {
                $city = htmlspecialchars($this->_params["city"]);
                $city = addslashes($city);
                $this->_currentUser->city($city);
            }
        }
         if (isset($this->_params["about_me"]) && $this->_params["about_me"] != $this->_currentUser->about_me()) {
            if (strlen($this->_params["about_me"]) > 10000) {
                $this->_view->replace("error", "Извените, но размер поля \"О себе\" не должен превышать 10000 символов");
            } else {
                $aboutMe = htmlspecialchars($this->_params["about_me"]);
                $aboutMe = addslashes($aboutMe);
                $this->_currentUser->about_me($aboutMe);
            }
        }
        if (isset($this->_params["day"]) && isset($this->_params["month"]) && isset($this->_params["year"])) {
            $date = $this->_params["year"] . "-" . $this->_params["month"] . "-" . $this->_params["day"];
            if (!preg_match("#^[0-9]{4}-[0-9]{2}-[0-9]{2}$#", $date)) {
                $this->_view->replace("error", "Неверный формат даты рождения");
            } else {
                $this->_currentUser->birth_date(strtotime($date));
            }
        }
        if (isset($this->_params["pol"])) {
            if (!preg_match("#^[MF]$#", $this->_params["pol"])) {
                $this->_view->replace("error", "Пол указан неверно");
            } else {
                $this->_currentUser->pol($this->_params["pol"]);
            }
        }
        if (isset($this->_params["old_password"]) && isset($this->_params["new_password"]) &&
                isset($this->_params["confirm_password"])) {
            if ($this->_params["new_password"] == $this->_currentUser->password()) {
                $this->_view->replace("error", "Ваш текущий пароль совпадает с новым");
            } elseif ($this->_params["old_password"] != $this->_currentUser->password()) {
                $this->_view->replace("error", "Вы ввели неверный текущий пароль");
            } elseif (!preg_match('#(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])[-_a-zA-Z0-9]{6,}#', $this->_params["new_password"])) {
                $this->_view->replace("error", "Пароль должен содержать не меньше 6 символов цифры и буквы верхнего и нижнего регистра");
            } elseif (strlen($this->_params["new_password"]) > 255) {
                $this->_view->replace("error", "Ваш новый пароль слишком длинный");                
            } elseif($this->_params["new_password"] != $this->_params["confirm_password"]){
                $this->_view->replace("error", "Введенные пароли не совпадают");                       
            }else{
                $password = addslashes($this->_params["new_password"]);
                $this->_currentUser->password($password);
            }
        }
        if ($this->_currentUser->id() != $this->_user->id()) {
            if ($this->_user->getUserGroup()->isPermitAction("administrate_user")) {
                if (isset($this->_params["karma"]) && is_numeric($this->_params["karma"]) &&
                        $this->_params["karma"] != $this->_currentUser->karma()) {
                    $this->_currentUser->karma($this->_params["karma"]);
                }
                if (isset($this->_params["group"]) && is_numeric($this->_params["group"])) {
                    $this->_currentUser->user_group($this->_params["group"]);
                }
            } else {
                throw new Z_AccessDeniedException();
            }
        }
    }

    private function _uploadAvatar() {
        if (Z_FileUploader::isDownloadFile("avatar")) {
            $fileloader = new Z_FileUploader("avatar");
            if (strcmp($fileloader->getFileType(), "image") > 0) {
                $img = getimagesize($fileloader->getFilePath());
                if ($img[0] > 50 || $img[1] > 50) {
                    $this->_view->replace("error", "Высота и шырина изображения не должна превышать 50х50");
                } else {
                    $fileloader->moveFile("Images/" . $this->_currentUser->login() . "avatar.jpg");
                    $this->_currentUser->image("Images/" . $this->_currentUser->login() . "avatar.jpg");
                }
            } else {
                $this->_view->replace("error", "Это не изображение!");
            }
        }
    }

    private function _printDays($day) {
        for ($i = 1; $i <= 31; $i++) {
            $i = $i < 10 ? "0" . $i : "$i";
            $this->_view->replace("day", "<option " . ($day == $i ? "selected" : "") . ">$i</option>", false);
        }
    }

    private function _printMonths($month) {
        $months = array(
            "01" => "Январь",
            "02" => "Февраль",
            "03" => "Март",
            "04" => "Апрель",
            "05" => "Май",
            "06" => "Июнь",
            "07" => "Июль",
            "08" => "Август",
            "09" => "Сентябрь",
            "10" => "Октябрь",
            "11" => "Ноябрь",
            "12" => "Декабрь",
        );
        foreach ($months As $key => $value) {
            $this->_view->replace("month", "<option value='$key' " . ($key == $month ? "selected" : "") . ">$value</option>", false);
        }
    }

    private function _printYears($year) {
        $currentYear = @date("Y", time());
        $oldYear = $currentYear - 150;
        for ($i = $currentYear; $i > $oldYear; $i--) {
            $this->_view->replace("year", "<option value='$i' " . ($i == $year ? "selected" : "") . ">$i</option>", false);
        }
    }

    private function _printGroups($group) {
        $groups = $this->_groupsTable->getList();
        foreach ($groups As $value) {
            $this->_view->replace("group", "<option value='" . $value->id() . "' " . ($value->id() == $group ? "selected" : "") . ">" . $value->group_name() . "</option>", false);
        }
    }

}
?>
