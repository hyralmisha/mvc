<h1>Записи гостьової книги!</h1>
<hr align = "left" width="95%" />


<?php 
    echo $param['error'];
    foreach ( $param['row1'] as $list ) {
?>

<br/><?php echo $list['name']; ?><br/>
<p align='right'> <?php echo $list['date']; ?></p>
<p><?php echo $list['msg_short']; ?></p>
<p><?php echo $list['msg_full']; ?></p>
<p align='right'>  <a href="/mvc/guestbook/edit/<?php echo $list['id']; ?>">Реданувати</a> &nbsp;  
                   <a href="/mvc/guestbook/delete/<?php echo $list['id']; ?>">Видалити</a> &nbsp;
                   <a href="/mvc/guestbook/view/<?php echo $list['id']; ?>">Переглянути</a> </p>
<hr align = "left" width="95%" />

<?php    
    }  
    
    foreach ( $param['pag'] as $list ) {
        echo $list."&nbsp;";
    } 
        
?>

