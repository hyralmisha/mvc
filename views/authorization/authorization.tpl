<h1>Введіть Ваш email і пароль!</h1>

<?php echo $param['error']; ?> 

<form action="/mvc/authorization/authorization" method="post">
    Email:<br />
    <input type="text" name="email" value="<?php echo $param['email']; ?>"/><br /><br />
    Пароль:<br />
    <input type="text" name="password" value=""/><br/><br/>
    <input type="submit" value="Увійти" />
</form>
<br/><br/><br/>
<hr align = "left" width="95%" />
<a href="/mvc/authorization/passRecovery"> Забули пароль?</a><br/><br/>
<a href="/mvc/authorization/registration"> Зареєструватся на сайті!</a><br/><br/>
