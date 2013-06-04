<div class='title'>Обратная связь</div>
<div class='page'>
    <form method='post' action='<!--url-->feedback/send'>
        <br />
        <div class='warning'><!--error--></div>
        <dl>
            <dt class='settings'>
            <b>Адрес Ел почты</b>
            </dt>
            <dd>
                <input type='text' name='email' value='<!--email-->' required />
            </dd>
        </dl>
        <dl>
            <dt class='settings'>
            <b>Текст сообщения:</b>
            </dt>
            <dd>
                <textarea name='text' style='width: 400px; height: 100px'><!--text--></textarea>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>
            <img src='<!--url-->captcha.php' alt='captcha' />
            </dt>
            <dd>
                <input type='text' name='captcha' required />
            </dd>
        </dl>
        <dl>
            <dt class='settings'> 
            </dt>
            <dd>
                <input type='submit' class='recommended_button' value='Отправить' />
            </dd>
        </dl>
    </form>
</div>