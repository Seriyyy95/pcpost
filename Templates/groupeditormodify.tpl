<div class='title'>
    Редактировать группу
</div>
<br />
<div class='page'>
    <form method='get' action='<!--url-->groupeditor/save'>
        <b>Название группы:</b>
        <br />
        <input type='text' name='group_name' value='<!--group_name-->' size='70%' required>
        <br />
        <br />
        <b>Полномочия:</b>
        <br />
        <br />
        <input type='checkbox' name='show_post' <!--show_post-->>
        Просматривать посты<br />
        <input type='checkbox' name='edit_post' <!--edit_post-->> 
        Редактировать посты</br>
        <input type='checkbox' name='add_post' <!--add_post-->>
        Добавлять посты <br />
        <br />
        <input type='checkbox' name='add_comment' <!--add_comment-->>
        Добавлять комментарии<br />
        <input type='checkbox' name='reply_comment' <!--reply_comment-->>
        Отвечать на комментарии<br />        
        <input type='checkbox' name='modify_comment' <!--modify_comment-->>
        Редактировать комментарии<br />
        <input type='checkbox' name='hide_comment' <!--hide_comment-->>
        Скрывать комментарии<br />
        <input type='checkbox' name='vote' <!--vote-->>
        Голосовать<br />
        <br />
        <input type='checkbox' name='auth' <!--auth-->>
        Авторизироватся<br />      
        <br />
        <input type='checkbox' name='show_authhistory' <!--show_authhistory-->>
        Просматривать историю входов<br /> 
        <input type='checkbox' name='show_users_authhistory' <!--show_users_authhistory-->>
        Просматривать историю входов других пользователей<br /> 
        <input type='checkbox' name='show_userinfo' <!--show_userinfo-->>
        Просматривать информацию о пользователе<br /> 
        <input type='checkbox' name='show_more_userinfo' <!--show_more_userinfo-->>
        Просматривать дополнительную информацию о пользователе<br /> 
        <input type='checkbox' name='show_userprofile' <!--show_userprofile-->>
        Просматривать профили пользователей<br />
        <input type='checkbox' name='show_jouranl' <!--show_journal-->>
        Просматривать журнал<br />
        <input type='checkbox' name='show_messages' <!--show_messages-->>
        Просматривать сообщения<br />
        <input type='checkbox' name='edit_settings' <!--edit_settings-->>
        Редактировать свои настройки<br /> 
        <input type='checkbox' name='show_messages' <!--work_messages-->>
        Отправлять/Удалять сообщения<br />
        <input type='checkbox' name='subscription_work' <!--subscription_work-->>
        Подписыватся на коментарии к постам<br />
        <input type='checkbox' name='autors_subscription_work' <!--autors_subscription_work-->>
        Подписыватся на авторов<br />
       <input type='checkbox' name='show_favorites' <!--show_favorites-->>
        Просматривать избранное<br />
       <input type='checkbox' name='show_users_favorites' <!--show_users_favorites-->>
        Просматривать избранное других пользователей<br />        
       <input type='checkbox' name='work_favorites' <!--work_favorites-->>
        Добавлять посты в избранное<br />               
        <br />        
        <input type='checkbox' name='administrate_user' <!--administrate_user-->>
        Администрировать пользователей<br /> 
        <input type='checkbox' name='safe_remove_post' <!--safe_remove_post-->>
        Безопасно удалять посты<br /> 
        <input type='checkbox' name='safe_remove_user' <!--safe_remove_user-->>
        Безопасно удалять пользователей<br /> 
        <input type='checkbox' name='edit_sections' <!--edit_sections-->>
        Редактировать разделы<br /> 
        <input type='checkbox' name='edit_sitesettings' <!--edit_sitesettings-->>
        Редактировать настройки сайта<br /> 
        <input type='checkbox' name='show_statistic' <!--show_statistic-->>
        Просматривать статистику<br />        
        <input type='checkbox' name='show_users' <!--show_users-->>
        Просматривать список пользователей<br />    
        <input type='checkbox' name='edit_widgets' <!--edit_widgets-->>
        Редактировать виджеты<br />    
        <input type='checkbox' name='service_works' <!--service_works-->>
        Просматривать сайт во время технических работ<br />           
        <input type='checkbox' name='edit_groups' <!--edit_groups-->>
        Редактировать группы пользователей<br />         
        <input type='checkbox' name='show_adminpanel' <!--show_adminpanel-->>
        Использовать панель администратора<br /> 
        <br />
        <input type='hidden' name='id' value='<!--id-->'>
        <input type='submit' value='Сохранить'>
    </form>
</div>