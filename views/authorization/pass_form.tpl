
<?php echo $param['error']; ?> 

<form action="/mvc/authorization/passChange" method="post">
    Введіть новий пароль:<br />
    <input type="text" name="password" value=""/><br />
    Підтвердження паролю:<br />
    <input type="text" name="password_control" value=""/><br />
    <input type="hidden" name="email" value="<?php echo $param['email']; ?>"/><br />
    <br />
    <input type="submit" value="Змінити пароль" />
</form>
