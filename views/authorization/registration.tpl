
<?php echo $param['error']; ?> 

<form action="/mvc/authorization/<?php echo $param['action']; ?>" method="post">
    Прізвище:<br />
    <input type="text" name="last_name" value="<?php echo $param['last_name']; ?>"/><br />
    Ім'я:<br />
    <input type="text" name="first_name" value="<?php echo $param['first_name']; ?>"/><br />
    Email:<br />
    <input type="text" name="email" value="<?php echo $param['email']; ?>"/><br />
    Пароль:<br />
    <input type="text" name="password" value=""/><br />
    Підтвердження паролю:<br />
    <input type="text" name="password_control" value=""/><br />
    <br />
    <input type="submit" value="Зареєструватися" />
</form>
