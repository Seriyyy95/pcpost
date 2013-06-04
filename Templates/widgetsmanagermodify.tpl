<div class='title'>
    Редактировать виджет
</div>
<br />
<div class='page'>
    <form method='get' action='<!--url-->widgetsmanager/save'>
        Заголовок:
        <br />
        <input type='text' name='title' value='<!--title-->' size='70%' required>
        <br />
        Текст
        <br />
        <textarea name='text' style='width: 60%; height: 100px' required><!--text--></textarea>
        <br />
        <input type='hidden' name='id' value='<!--id-->'>
        <input type='submit' value='Сохранить'>
    </form>
</div>