
<?php echo $param['error']; ?> 

<form action="/mvc/authorization/passRecovery" method="post">
    <h1>Щоб відновити пароль, введіт Ваш email:</h1>
    <input type="text" name="email" value="<?php echo $param['email']; ?>"/><br/><br/>
    <input type="submit" value="Відновити пароль" />
</form>
<br/><br/><br/>
