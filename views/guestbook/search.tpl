<h1>Записи гостьової книги!</h1>
<hr align = "left" width="95%" />
<br/>
<?php 
    echo $param['error'];
    foreach ( $param['row1'] as $list ) {
?>

<?php echo $list['name']; ?><br/>
<p align='right'> <?php echo $list['date']; ?></p>
<p><?php echo $list['msg_short']; ?></p>
<p><?php echo $list['msg_full']; ?></p>
<p align='right'><a href="/mvc/guestbook/view/<?php echo $list['id']; ?>">Переглянути</a></p>
<hr align = "left" width="95%" />
<br/>

<?php    
    } 
    
?>