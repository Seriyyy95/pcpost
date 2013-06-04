<div class='title'><!--title--></div>
<br />
<div class='page'>
    <span class='warning'><!--error--></span>
    <form method='post' action='<!--url-->settings/upload' enctype="multipart/form-data">
        <div class='bolder'>Аватар</div>
        <dl>
            <dt class='settings'>
            <b>Изменить аватар </b>
            </dt>
            <dd>
                <input type='file' name='avatar'>
                <input type='submit' value='Изменить'>  
            </dd>
        </dl>
    </form>
    <div class='bolder'>Карма</div>
    <br />
    <!--reset-->
    <br />
    <br />
    <div class='bolder'>Основные настройки</div>
    <form method='post' action='<!--url-->settings/save/'>
        <dl>
            <dt class='settings'>
            <b>Польное имя:</b>
            </dt>
            <dd>
                <input type='text' name='name' value='<!--name-->'>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>
            <b>Адрес Ел. почты:</b>
            </dt>
            <dd>
                <input type='text' name='email' value='<!--email-->'>                
            </dd>
        </dl>        
        <dl>
            <dt class='settings'>
            <b>Дата рождения:</b>
            </dt>
            <dd>
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
            <b>Пол:</b>
            </dt>
            <dd>
                <input type='radio' name='pol' value='M' <!--M-->>
                       Мужской
                       <input type='radio' name='pol' value='F' <!--F-->>
                       Женский
            </dd>
        </dl>        
        <dl>
            <dt class='settings'>
            <b>Страна:</b>
            </dt>
            <dd>
                <input type='text' name='country' value='<!--country-->'>
            </dd>
        </dl>   
        <dl>
            <dt class='settings'>
            <b>Город:</b>
            </dt>
            <dd>
                <input type='text' name='city' value='<!--city-->'>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>
            <b>О себе:</b>
            </dt>
            <dd>
                <textarea name='about_me' style='width: 200%; height: 200px'><!--about_me--></textarea>
            </dd>
        </dl>
        <div class='bolder'>Изменить пароль</div>
        <dl>
            <dt class='settings'>
            <b>Старый пароль:</b>
            </dt>
            <dd>
                <input type='password' name='old_password'>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>
            <b>Новый пароль:</b>
            </dt>
            <dd>
                <input type='password' name='newpassword'>
            </dd>
        </dl>
        <dl>
            <dt class='settings'>
            <b>Еще раз:</b>
            </dt>
            <dd>
                <input type='password' name='confirm_password'>
            </dd>
        </dl>
        <input type="submit" class='recommended_button' value="Сохранить изменения">
        <input type="reset" value="Сбросить">
    </form>
</div>
