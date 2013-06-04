<?php

class registration extends Z_Controller
{

    protected $_session;

    public function __construct($action, $params = array())
    {
        parent::__construct($action, $params);
        $this->_session = Z_Factory::Z_Session();
    }

    public function run()
    {
        if ($this->_action == "save") {
            $usersTable = Z_Factory::Z_UsersTable();
            if (isset($this->_params["login"]) && isset($this->_params["name"]) && isset($this->_params["password"]) &&
                    isset($this->_params["confirm"]) && isset($this->_params["email"]) &&
                    isset($this->_params["pol"]) && isset($this->_params["captcha"])) {
                $captcha = strtoupper($this->_params["captcha"]);
                $login = htmlspecialchars($this->_params["login"]);
                $login = addslashes($login);
                $name = htmlspecialchars($this->_params["name"]);
                $name = addslashes($name);
                $password = addslashes($this->_params["password"]);
                $confirm = htmlspecialchars($this->_params["confirm"]);
                $confirm = addslashes($confirm);
                $email = htmlspecialchars($this->_params["email"]);
                $email = addslashes($email);
                $pol = $this->_params["pol"];
                $country = htmlspecialchars($this->_params["country"]);
                $country = addslashes($country);
                $city = htmlspecialchars($this->_params["city"]);
                $city = addslashes($city);
                if (isset($this->_params["day"]) && isset($this->_params["month"]) && isset($this->_params["year"])) {
                    $date = $this->_params["year"] . "-" . $this->_params["month"] . "-" . $this->_params["day"];
                    $date = strtotime($date);
                } else {
                    $this->view->replace("error", "Дата рождения введена неверно");
                }
                if ($captcha != $this->_session->captcha()) {
                    $this->_view->replace("error", "Код безопасности введен неверно");
                } elseif (strlen($name) == 0) {
                    $this->_view->replace("error", "Введите ваше полное имя");
                } elseif (strlen($name) > 255) {
                    $this->_view->replace("error", "У вас слишком длинное имя, однако");
                } elseif (strlen($login) == 0) {
                    $this->_view->replace("error", "Введите логин");
                } elseif (strlen($login) > 255) {
                    $this->_view->replace("error", "Логин слишком длинный, пожалуйста придумайте логин покороче");
                } elseif ($password != $confirm) {
                    $this->_view->replace("error", "Пароли не совпадают");
                } elseif (strlen($password) > 255) {
                    $this->_view->replace("error", "Нет ну я понимаю безопаснось превыше всего, не это уже параноя, придумайте пароль покороче");
                } elseif (!preg_match('#(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])[-_a-zA-Z0-9]{6,}#', $password)) {
                    $this->_view->replace("error", "Пароль должен содержать не меньше 6 символов цифры и буквы верхнего и нижнего регистра");
                } elseif (!preg_match("#^[^@ ]+@[^@ ]+\.[^@ \.]+$#", $email)) {
                    $this->_view->replace("error", "Вы ввели нкорректный адрес электронной почты");
                } elseif (!preg_match("#^[MF]$#", $pol)) {
                    $this->_view->replace("error", "Пол указан неверно");
                } elseif (strlen($country) == 0) {
                    $this->_view->replace("error", "Название страны не может быть пустым");
                } elseif (strlen($country) > 255) {
                    $this->_view->replace("error", "Название страны слишком длинное");
                } elseif (strlen($city) == 0) {
                    $this->_view->replace("error", "Название города не может быть пустым");
                } elseif (strlen($city) > 255) {
                    $this->_view->replace("error", "Название города слишком длинное");
                } elseif ($usersTable->isUser($login)) {
                    $this->_view->replace("error", "Пользователь с таким логином уже зарегистрирован");
                } elseif ($usersTable->checkRegistrationIp()) {
                    $this->_view->replace("error", "С этого IP адресса уже зарегистриован аккаунт");
                } else {
                    $userStruct = array(
                        "login" => $login,
                        "name" => $name,
                        "password" => $password,
                        "email" => $email,
                        "pol" => $pol,
                        "birth_date" => $date,
                        "country" => $country,
                        "city" => $city,
                        "reg_ip" => $_SERVER["REMOTE_ADDR"]
                    );
                    $usersTable->add($userStruct);
                    try {
                        $authController = Z_Factory::Z_AuthController();
                        $authController->authUser($login, $password);
                        header("Location: ". URL . "profile");
                    } catch (Z_Exception $e) {
                        $this->_view->replace("error", "Ошибка автоматической авторизации, 
                            причина:" . $e->getMessage() . "попробуйте авторизироватся вручную 
                                <a href='<!-url-->auth/input'>здесь</a>");
                    }
                }
                $this->_view->replace("login", $login);
                $this->_view->replace("name", $name);
                $this->_view->replace("password", $password);
                $this->_view->replace("confirm", $confirm);
                $this->_view->replace("email", $email);
                $this->_view->replace($pol, "checked");
                $this->_view->replace("country", $country);
                $this->_view->replace("city", $city);
            } else {
                throw new Z_BadRequestException();
            }
            $this->_session->remove("captcha");
        }

        $this->_printDays();
        $this->_printMonths();
        $this->_printYears();
    }

    private function _printDays()
    {
        for ($i = 1; $i <= 31; $i++) {
            $i = $i<10? "0" . $i: "$i";
            $this->_view->replace("day", "<option>$i</option>", false);
        }
    }

    private function _printMonths()
    {
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
            $this->_view->replace("month", "<option value='$key'>$value</option>", false);
        }
    }

    private function _printYears()
    {
        $currentYear = @date("Y", time());
        $oldYear = $currentYear - 150;
        for ($i = $currentYear; $i > $oldYear; $i--) {
            $this->_view->replace("year", "<option value='$i'>$i</option>", false);
        }
    }

}

?>
