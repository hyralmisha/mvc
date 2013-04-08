<hr/>
    
<h1 align ="center"><?php echo $name; ?></h1><br/>
<p align='right'> <?php echo $date; ?>
<p><?php echo $msgShort; ?>
<p><?php echo $msgFull; ?>
<p align='right'><a href="/guestbook/edit/<?php echo $id; ?>">Реданувати</a>&nbsp; &nbsp;  
                   <a href="/guestbook/delete/<?php echo $id; ?>">Видалити</a>
                   <a href="/guestbook/main">На головну</a>