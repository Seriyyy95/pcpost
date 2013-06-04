<?php

class people extends Z_Controller {

    protected $_usersTable;
    protected $_sqlparams = array();
    private $_currentDay;
    private $_currentMonth;
    private $_currentYear;

    public function __construct($action, $params = array()) {
        parent::__construct($action, $params);
        if ($this->_user->getUserGroup()->isPermitAction("show_people")) {
            $this->_usersTable = Z_Factory::Z_UsersTable();
            $this->_currentDay = @date("j", time());
            $this->_currentMonth = @date("m", time());
            $this->_currentYear = @date("Y", time());
        } else {
            throw new Z_AccessDeniedException();
        }
    }

    public function run() {
        $this->_mainView->setTitle("Люди");
        $page = $this->_getPage();
        $numPages = 0;
        $this->_initSqlParams($page);
        if (isset($this->_params["search"])) {
            $word = addslashes($this->_params["search"]);
            $this->_sqlparams["search_field"] = "login";
            $this->_sqlparams["search_text"] = $word;
            $this->_view->replace("word", $this->_params["search"]);
        }
        if (isset($this->_params["lower_reg_day"]) && isset($this->_params["lower_reg_month"]) &&
                isset($this->_params["lower_reg_year"]) && isset($this->_params["upper_reg_day"]) &&
                isset($this->_params["upper_reg_month"]) && isset($this->_params["upper_reg_year"])) {
            $lowerRegDate = strtotime($this->_params["lower_reg_year"] . "-" . $this->_params["lower_reg_month"] . "-" . $this->_params["lower_reg_day"]);
            $upperRegDate = strtotime($this->_params["upper_reg_year"] . "-" . $this->_params["upper_reg_month"] . "-" . $this->_params["upper_reg_day"]);
            $this->_sqlparams["where"] .= " AND created_time > $lowerRegDate AND created_time < $upperRegDate";
        }
        if (isset($this->_params["lower_birth_day"]) && isset($this->_params["lower_birth_month"]) &&
                isset($this->_params["lower_birth_year"]) && isset($this->_params["upper_birth_day"]) &&
                isset($this->_params["upper_birth_month"]) && isset($this->_params["upper_birth_year"])) {
            $lowerRegDate = strtotime($this->_params["lower_birth_year"] . "-" . $this->_params["lower_birth_month"] . "-" . $this->_params["lower_birth_day"]);
            $upperRegDate = strtotime($this->_params["upper_birth_year"] . "-" . $this->_params["upper_birth_month"] . "-" . $this->_params["upper_birth_day"]);
            $this->_sqlparams["where"] .= " AND birth_date > $lowerRegDate AND birth_date < $upperRegDate";
        }
        if (isset($this->_params["country"]) && strlen($this->_params["country"]) > 0) {
            $country = addslashes($this->_params["country"]);
            $this->_sqlparams["where"] .= " AND country='" . $country . "'";
        }
        if (isset($this->_params["city"]) && strlen($this->_params["city"]) > 0) {
            $city = addslashes($this->_params["city"]);
            $this->_sqlparams["where"] .= " AND city='" . $city . "'";
        }
        if (isset($this->_params["pol"]) && ($this->_params["pol"] == "M" || $this->_params["pol"] == "F" )) {
            $this->_sqlparams["where"] .= " AND pol='" . $this->_params["pol"] . "'";
        }
        $users = $this->_usersTable->getListEx($this->_sqlparams, $numPages);
        if (count($users) == 0) {
            $empty = new Z_TemplateView("empty", "user", $this->_view);
        } else {
            foreach ($users As $user) {
                $userTemplate = new Z_TemplateView("peopleuser", "user", $this->_view, false);
                $userTemplate->replace("id", $user->id());
                $userTemplate->replace("username", $user->login());
                $userTemplate->replace("avatar", $user->image());
                $userTemplate->replace("group", $user->getUserGroup()->group_name());
                $userTemplate->replace("karma", $user->karma());
            }
        }
        $this->_printSort();
        $this->_printFilters();
        $this->_replaceUrls();
        $this->_printPages($page, $numPages);
    }

    private function _initSqlParams($page) {
        if (isset($this->_params["sort"])) {
            if ($this->_params["sort"] == "karma") {
                $this->_sqlparams["sort_field"] = "karma";
                $this->_sqlparams["sort_option"] = "DESC";
            } elseif ($this->_params["sort"] == "date") {
                $this->_sqlparams["sort_field"] = "created_time";
                $this->_sqlparams["sort_option"] = "DESC";
            } elseif ($this->_params["sort"] == "login") {
                $this->_sqlparams["sort_field"] = "login";
                $this->_sqlparams["sort_option"] = "ASC";
            }
        } else {
            $this->_sqlparams["sort_field"] = "karma";
            $this->_sqlparams["sort_option"] = "DESC";
        }
        $this->_sqlparams["page"] = $page;
        $this->_sqlparams["page_count"] = $this->_config->page_count();
        $this->_sqlparams["where"] = "1";
    }

    private function _printSort() {
        $url = $this->getUrlWithParams(array("sort"));
        if (isset($this->_params["sort"])) {
            if ($this->_params["sort"] == "karma") {
                $this->_view->replace("karma", "карме");
                $this->_view->replace("date", "<a href='" . $url . "sort/date''>дате регистрации</a>");
                $this->_view->replace("login", "<a href='" . $url . "sort/login''>алфавиту</a>");
            } elseif ($this->_params["sort"] == "date") {
                $this->_view->replace("karma", "<a href='" . $url . "sort/karma''>карме</a>");
                $this->_view->replace("date", "дате регистрации");
                $this->_view->replace("login", "<a href='" . $url . "sort/login''>алфавиту</a>");
            } elseif ($this->_params["sort"] == "login") {
                $this->_view->replace("karma", "<a href='" . $url . "sort/karma''>карме</a>");
                $this->_view->replace("date", "<a href='" . $url . "sort/date''>дате регистрации</a>");
                $this->_view->replace("login", "алфавиту");
            }
        } else {
            $this->_view->replace("karma", "карме");
            $this->_view->replace("date", "<a href='" . $url . "sort/date''>дате регистрации</a>");
            $this->_view->replace("login", "<a href='" . $url . "sort/login''>алфавиту</a>");
        }
    }

    private function _printFilters() {
        $this->_printLowerBirthDate();
        $this->_printUpperBirthDate();
        $this->_printLowerRegistrationDate();
        $this->_printUpperRegistrationDate();
        $this->_printLocation();
        $this->_printPol();
    }

    private function _replaceUrls() {
        $this->_view->replace("search_url", $this->getUrlWithParamsNotSlash(array("search")));
        $this->_view->replace("filters_url", $this->getUrlWithParamsNotSlash(
                        array("lower_reg_day,lower_reg_month,lower_reg_year,upper_reg_day,upper_reg_month,upper_reg_year",
                            "lower_birth_day,lower_birth_month,lower_birth_year,upper_birth_day,upper_birth_month,upper_birth_year",
                            "country", "city", "pol"
                        )
        ));
        $this->_url = $this->getUrlWithParams();
    }

    private function _printLowerRegistrationDate() {
        if (isset($this->_params["lower_reg_day"])) {
            $this->_printDays($this->_params["lower_reg_day"], "lower_reg_day");
        } else {
            $this->_printDays(1, "lower_reg_day");
        }
        if (isset($this->_params["lower_reg_month"])) {
            $this->_printMonths($this->_params["lower_reg_month"], "lower_reg_month");
        } else {
            $this->_printMonths(12, "lower_reg_month");
        }
        if (isset($this->_params["lower_reg_year"])) {
            $this->_printYears($this->_params["lower_reg_year"], "lower_reg_year");
        } else {
            $oldYear = $this->_currentYear - 149;
            $this->_printYears($oldYear, "lower_reg_year");
        }
    }

    private function _printUpperRegistrationDate() {
        if (isset($this->_params["upper_reg_day"])) {
            $this->_printDays($this->_params["upper_reg_day"], "upper_reg_day");
        } else {
            $this->_printDays($this->_currentDay, "upper_reg_day");
        }
        if (isset($this->_params["upper_reg_month"])) {
            $this->_printMonths($this->_params["upper_reg_month"], "upper_reg_month");
        } else {
            $this->_printMonths($this->_currentMonth, "upper_reg_month");
        }
        if (isset($this->_params["upper_reg_year"])) {
            $this->_printYears($this->_params["upper_reg_year"], "upper_reg_year");
        } else {
            $this->_printYears($this->_currentYear, "upper_reg_year");
        }
    }

    private function _printLowerBirthDate() {
        if (isset($this->_params["lower_birth_day"])) {
            $this->_printDays($this->_params["lower_birth_day"], "lower_birth_day");
        } else {
            $this->_printDays(1, "lower_birth_day");
        }
        if (isset($this->_params["lower_birth_month"])) {
            $this->_printMonths($this->_params["lower_birth_month"], "lower_birth_month");
        } else {
            $this->_printMonths(12, "lower_birth_month");
        }
        if (isset($this->_params["lower_birth_year"])) {
            $this->_printYears($this->_params["lower_birth_year"], "lower_birth_year");
        } else {
            $oldYear = $this->_currentYear - 149;
            $this->_printYears($oldYear, "lower_birth_year");
        }
    }

    private function _printUpperBirthDate() {
        if (isset($this->_params["upper_birth_day"])) {
            $this->_printDays($this->_params["upper_birth_day"], "upper_birth_day");
        } else {
            $this->_printDays($this->_currentDay, "upper_birth_day");
        }
        if (isset($this->_params["upper_birth_month"])) {
            $this->_printMonths($this->_params["upper_birth_month"], "upper_birth_month");
        } else {
            $this->_printMonths($this->_currentMonth, "upper_birth_month");
        }
        if (isset($this->_params["upper_birth_year"])) {
            $this->_printYears($this->_params["upper_birth_year"], "upper_birth_year");
        } else {
            $this->_printYears($this->_currentYear, "upper_birth_year");
        }
    }

    private function _printDays($day, $templ) {
        for ($i = 1; $i <= 31; $i++) {
            $i = $i < 10 ? "0" . $i : "$i";
            $this->_view->replace($templ, "<option " . ($day == $i ? "selected" : "") . ">$i</option>", false);
        }
    }

    private function _printMonths($month, $templ) {
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
            $this->_view->replace($templ, "<option value='$key' " . ($key == $month ? "selected" : "") . ">$value</option>", false);
        }
    }

    private function _printYears($year, $templ) {
        $currentYear = @date("Y", time());
        $oldYear = $currentYear - 150;
        for ($i = $currentYear; $i > $oldYear; $i--) {
            $this->_view->replace($templ, "<option value='$i' " . ($i == $year ? "selected" : "") . ">$i</option>", false);
        }
    }

    private function _printLocation() {
        if (isset($this->_params["country"])) {
            $this->_view->replace("country", $this->_params["country"]);
        }
        if (isset($this->_params["city"])) {
            $this->_view->replace("city", $this->_params["city"]);
        }
    }

    private function _printPol() {
        if (isset($this->_params["pol"])) {
            $this->_view->replace($this->_params["pol"], "checked");
        } else {
            $this->_view->replace("A", "checked");
        }
    }

}

?>
