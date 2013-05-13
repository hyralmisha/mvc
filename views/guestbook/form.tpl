
<?php 
echo $param['error']; 
echo "<h1>".$param['header']."</h1>"; 
?> 

<form action="/mvc/guestbook/<?php echo $param['action']; ?>" method="post">
    <input type="hidden" name="edit" value="<?php echo $param['id']; ?>"/><br />
    Назва повідомлення:<br />
    <input type="text" name="name" value="<?php echo $param['name']; ?>"/><br />
    Короткий опис повідомлення:<br />
    <textarea name="msgShort" cols="50" rows="5"><?php echo $param['msg_short']; ?></textarea><br />
    Повідомлення:<br />
    <textarea name="msgFull" cols="50" rows="10"><?php echo $param['msg_full']; ?></textarea><br />
    Теги (відділяти теги потрібно комами):<br />
    <textarea name="tags" cols="50" rows="5"><?php echo $param['tags']; ?></textarea><br />
    <br />
    <input type="submit" value="Додати" />
</form> 
