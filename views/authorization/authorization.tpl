
<?php echo $param['error']; ?> 

<form action="/mvc/authorization/authorization" method="post">
    Email:<br />
    <input type="text" name="email" value="<?php echo $param['email']; ?>"/><br />
    Пароль:<br />
    <input type="text" name="password" value=""/><br />
    <input type="submit" value="Увійти" />
</form>
<br/><br/><br/>
<a href="#"> Забули пароль?</a><br/><br/>
<a href="/mvc/authorization/registration"> Зареєструватся на сайті!</a><br/><br/>
