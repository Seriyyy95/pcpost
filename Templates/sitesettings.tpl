<div class='title'>
    Настройки сайта
</div>
<div class='page'>
    <form method='post' action='<!--url-->sitesettings/save'>
        <br />
        <b>Общие настройки:</b>
        <br />
        Логотип сайта:
        <br />
        <input type='text' style='width: 60%' name='logo' value='<!--logo-->' />
        <br />
        Иконка сайта <smal>отображается около заголовка</smal>:
        <br />
        <input type='text' style='width: 60%' name='favicon' value='<!--favicon-->' />
        <br />
        Главный файл стилей:
        <br />
        <input type='text' style='width: 60%' name='style' value='<!--style-->' />
        <br />
        Файл стилей блоков:
        <br />
        <input type='text'style='width: 60%'  name='bloks' value='<!--bloks-->' />
        <br />
        Адрес скрипта RSS ленты:
        <br />
        <input type='text' style='width: 60%' name='rss' value='<!--rss-->' />
        <br />
        Иконка RSS:
        <br />
        <input type='text' style='width: 60%' name='rss_icon' value='<!--rss_icon-->' />
        <br />
        <br />
        <b>Другие настройки:</b>
        <br />
        Колличество элементов на страннице:
        <br />
        <input type='text' name='page_count' value='<!--page_count-->' />
        <br />
        Количество отображаемых страниц:
        <br />
        <input type='text' name='limit_pages' value='<!--limit_pages-->' />
        <br />
        Режим технических работ:
        <select name='serviceworks'>
            <option value='1' <!--service_1-->>Включить</option>
            <option value='0' <!--service_0-->>Отключить</option>
        </select>
        <br />
        <br />
        <input type='submit' value='Сохранить' style='font-weight: bolder'>
        <input type='reset' value='Сбросить'>
    </form>
</div>