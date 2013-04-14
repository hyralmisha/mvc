<hr/>
    
<h1 align ="center"><?php echo $param['name']; ?></h1><br/>
<p align='right'> <?php echo $param['date']; ?>
<p><?php echo $param['msg_short']; ?>
<p><?php echo $param['msg_full']; ?>
<p align='right'><a href="/mvc/guestbook/edit/<?php echo $param['id']; ?>">Реданувати</a>&nbsp; &nbsp;  
                   <a href="/mvc/guestbook/delete/<?php echo $param['id']; ?>">Видалити</a>
                   <a href="/mvc/guestbook/main">На головну</a>