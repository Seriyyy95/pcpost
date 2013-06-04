<div class='title'>Добавить статтю</div>
<br />
<div class='page'>
<form action='<!--url-->postwork/send' method='post'>
<b><span style='color: red'>*</span> Заголовок:</b> 
<br />
<input type='text' name='name' style='width: 80%' required />
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
</textarea>
<br />
<br />
<b><span style='color: red'>*</span> Текст:</b>
<br />
<textarea name='text' style='width: 90%; height: 400px' required>
</textarea>
<br />
<br />
<b>Теги:</b>
<br />
<input type='text' name='tags' style='width: 90%' required />
<br />
Скрыть:
<input type='checkbox' name='hide'>
<br />
<br />
<center>
<input type='submit' style='font-weight: bolder' value='Добавить'>
<input type='reset' value='Очистить'>
</center>
</form>
</div>
