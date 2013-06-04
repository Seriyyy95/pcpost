<div class='title'>Вход</div>
<form name='auth' method='post' action='<!--url-->auth/input'>
    <div class='page'>
        <br />
        <div class='warning'><!--error--></div>
        <dl>
            <dt class='auth'>
            Логин
            </dt>
            <dd>
                <input type='text' name='login' value='<!--login-->' required />
            </dd>
        </dl>
        <dl>
            <dt class='auth'>
            Пароль
            </dt>
            <dd>
                <input type='password' name='password' value='<!--password-->' required />
            </dd>
        </dl>
        <dl>
            <dt class='auth'>
            <img src='<!--url-->captcha.php' alt='captcha' />
            </dt>
            <dd>
                <input type='text' name='captcha' required />
            </dd>
        </dl>
        <dl>
            <dt class='auth'>
            </dt>
            <dd>
                <input type='submit' class='recommended_button' value='Вход' />
            </dd>
        </dl>
</form>
</div>