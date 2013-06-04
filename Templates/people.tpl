<div class='title'>Люди</div>
<span class='litlepanel'>
    <b>Сортировать по:</b>
    <!--karma--> |
    <!--date--> |
    <!--login-->
</span>
<br />
<br />
<br />
<div class='page'>
    <form action='<!--search_url-->'>
        <input type='text' name='search' value='<!--word-->' style='width: 50%'>
        <input type='submit' value='Искать!'>
    </form>
    <hr>
    <br />
    <div class='filters'>
        <form method='get' action='<!--filters_url-->'>
        <div class='bolder'>Фильтры</div>
        <b>Дата регистрации:</b>
        <br />
        От:
        <select name='lower_reg_day'>
            <!--lower_reg_day-->
        </select>
        <select name='lower_reg_month'>
            <!--lower_reg_month-->
        </select>
        <select name='lower_reg_year'>
            <!--lower_reg_year-->
        </select>
        <br />
        До:
        <select name='upper_reg_day'>
            <!--upper_reg_day-->
        </select>
        <select name='upper_reg_month'>
            <!--upper_reg_month-->
        </select>
        <select name='upper_reg_year'>
            <!--upper_reg_year-->
        </select>
        <br />
        <b>Дата рождения:</b>
        <br />
        От:
        <select name='lower_birth_day'>
            <!--lower_birth_day-->
        </select>
        <select name='lower_birth_month'>
            <!--lower_birth_month-->
        </select>
        <select name='lower_birth_year'>
            <!--lower_birth_year-->
        </select>
        <br />
        До:
        <select name='upper_birth_day'>
            <!--upper_birth_day-->
        </select>
        <select name='upper_birth_month'>
            <!--upper_birth_month-->
        </select>
        <select name='upper_birth_year'>
            <!--upper_birth_year-->
        </select>
        <br />
        <b>Странна:</b>
        <br />
        <input type='text' name='country' value='<!--country-->' />
        <br />
        <b>Город:</b>
        <br />
        <input type='text' name='city' value='<!--city-->' />
        <br />
        <b>Пол:</b> 
        <br />
        <span style='line-height: 4px'>
            <input type='radio' name='pol' value='M'  <!--M-->/> Мужской
            <br />
            <input type='radio' name='pol' value='F' <!--F--> /> Женский
            <br />
            <input type='radio' name='pol' value='A'  <!--A--> /> Любой
        </span>
        <br />
        <input type='submit' value='Фильтровать'>
        </form>
    </div>
    <!--user-->
    <br />
    <br />
    <div style='word-spacing: 10px; font-size: 25px'>
        <!--prev-->   <!--next-->
    </div>
    <!--pages-->
</div>