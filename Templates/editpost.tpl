<div class='title'>Редактировать статью</div>
<br />
<div class='page'>
<form action='<!--url-->postwork/save/post/<!--post-->' method='post'>
<b><span style='color: red'>*</span> Заголовок:</b> 
<br />
<input type='text' name='name' value='<!--name-->' style='width: 80%' required />
<br />
<br />
<b><span style='color: red'>*</span> Выберите раздел:</b>
<select name='section' required>
<!--option-->
</select>
<br />
<br />
<b><span style='color: red'>*</span> Аннонс</b> <small>будет отображатся в списке постов, не больше 1024 символа</small>
<br />
<textarea name='description' style='width: 90%; height: 150px' required>
<!--description-->
</textarea>
<br />
<br />
<b><span style='color: red'>*</span> Текст:</b>
<br />
<textarea name='text' style='width: 90%; height: 400px' required>
<!--text-->
</textarea>
<br />
<br />
<b>Теги:</b>
<br />
<input type='text' value='<!--tags-->' name='tags' style='width: 90%' required />
<br />
Скрыть:
<input type='checkbox' name='hide' <!--hide-->>
<br />
<br />
<center>
<input type='submit' style='font-weight: bolder' value='Сохранить'>
<input type='reset' value='Очистить'>
</center>
</form>
</div>