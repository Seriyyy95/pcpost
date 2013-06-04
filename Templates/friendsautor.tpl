<div class='title'>Друзья</div>
<br />
<!--addfriend-->
<br />
<b> Предложения дружбы:</b>
<br />
<form method='get' action='friends.php'>
<!--notifytext-->
<br />
<input type='hidden' name='id' value=<!--id--> />
<input type='submit' name='accept' value='принять' />
<input type='submit' name='deletenotify' value='отклонить' />
<br />
</form>
<br />
<b>Список друзей:</b>
<br />
<form method='get' action='friends.php'>
<!--friendstext-->
<br />
<input type='hidden' name='id' value=<!--id--> />
<input type='submit' name='delete' value='Удалить' />
<br />
Рассылка сообщений
<br />
<textarea cols=50 rows=10 name='text'></textarea>
<br />
<input type='submit' name='send' value='Розослать' />
</form>
<br />
