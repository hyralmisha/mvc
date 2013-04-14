<?php 

if ( !empty ( $param ) ) {
    foreach ( $param as $list ) {
                    $id = $list['id'];
                    $name = $list['name'];
                    $msgShort = $list['msg_short'];
                    $msgFull = $list['msg_full'];
                    $dateCreate = $list['date_create'];
                    $dateEdit = $list['date_edit'];
                    $reportName = $participators['report_name'];


        if( $dateCreate == $dateEdit ) {
            $date = "Дата створення: $dateCreate <br/>";
        }else{
            $date = "Дата створення: $dateCreate <br/>       
                    Дата редагування: $dateEdit";    
        }
      
?>

<hr/>
    
<?php echo $name; ?><br/>
<p align='right'> <?php echo $date; ?></p>
<p><?php echo $msgShort; ?></p>
<p><?php echo $msgFull; ?></p>
<p align='right'><a href="/mvc/guestbook/view/<?php echo $id; ?>">Переглянути</a></p>
                   
<?php    
    }        
}
?>