<!--
Форма для додавання нових записів
-->
<?php echo $param['error']; ?> 

<form action="/mvc/guestbook/<?php echo $param['action']; ?>" method="post">
    <input type="hidden" name="edit" value="<?php echo $param['id']; ?>"/><br />
    Назва повідомлення:<br />
    <input type="text" name="name" value="<?php echo $param['name']; ?>"/><br />
    Короткий опис повідомлення:<br />
    <textarea name="msgShort" cols="50" rows="5"><?php echo $param['msg_short']; ?></textarea><br />
    Повідомлення:<br />
    <textarea name="msgFull" cols="50" rows="10"><?php echo $param['msg_full']; ?></textarea><br />
    <br />
    <input type="submit" value="Додати" />
</form> 
