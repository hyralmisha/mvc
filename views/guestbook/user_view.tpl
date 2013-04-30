<h1 align ="center"><?php echo $param['name']; ?></h1><br/>
<p align='right'> <?php echo $param['date']; ?>
<p><?php echo $param['msg_short']; ?>
<p><?php echo $param['msg_full']; ?>
<hr align = "left" width="95%" />
<br/>
<p align='right'><a href="/mvc/guestbook/edit/<?php echo $param['id']; ?>">Реданувати</a>&nbsp; &nbsp;  
                   <a href="/mvc/guestbook/delete/<?php echo $param['id']; ?>">Видалити</a>&nbsp; &nbsp;  
                   <a href="/mvc">На головну</a>