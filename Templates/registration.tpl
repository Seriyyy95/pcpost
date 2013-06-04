<div class='title'>Регистрация</div>
<div class='page'>
    <div class='warning'><!--error--></div>
    <br />
    <form method='post' action='<!--url-->registration/save'>
        <dl>
            <dt class='settings'>   
            <b>Полное имя</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='text' name='name' required value= <!--name-->>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>   
            <b>Логин</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='text' name='login' required value=<!--login-->>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>   
            <b>Пароль</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='password' name='password' required value=<!--password-->>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>   
            <b>Повторите пароль</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='password' name='confirm' required value=<!--confirm-->>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>   
            <b>Адрес Email</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='text' name='email' required value=<!--email-->>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>   
            <b>Дата рождения</b><br>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <select name='day'>
                    <!--day-->
                </select>
                <select name='month'>
                    <!--month-->
                </select>
                <select name='year'>
                    <!--year-->
                </select>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>   
            <b>Пол</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='radio' name='pol' value='M' <!--M-->>
                       Мужской
                       <input type='radio' name='pol' value='F'<!--F-->>
                       Женский
            </dd>
        </dl>
       <dl>
            <dt class='settings'>   
            <b>Страна</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='text' name='country' required value='<!--country-->'>
            </dd>
        </dl>
       <dl>
            <dt class='settings'>   
            <b>Город</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='text' name='city' required value='<!--city-->'>
            </dd>
        </dl>
       <dl>
            <dt class='settings'>   
            <b>Соглашение</b>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='checkbox' name='licenze' required>
                 Я прочитал и принимаю <a href='<!--url-->useragreement'>пользовательское соглашение</a>
            </dd>
        </dl>
       <dl>
            <dt class='settings'>   
            <img src='<!--url-->captcha.php'>
            </dt>
            <dd>
                <span class='warning'>*</span>
                <input type='text' name='captcha' required>
            </dd>
        </dl>
        <span style='position: relative; left: 10%'>
        <input type='submit' class='recommended_button' value='Регистрация'>
        <input type='reset' value='Сбросить'>
        </span>
    </form>
</div>
