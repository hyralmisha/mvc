
<?php echo $param['error']; ?> 

<form action="/mvc/authorization/passRecovery" method="post">
    Введіт Ваш email:<br />
    <input type="text" name="email" value="<?php echo $param['email']; ?>"/><br />
    <input type="submit" value="Відновити" />
</form>
<br/><br/><br/>
