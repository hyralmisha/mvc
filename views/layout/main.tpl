<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Гостьова книга</title>
    </head>
    <body>
       
        <h1>Гостьова книга</h1>        
        
        <?php
        if (file_exists ('views/guestbook/'.$mitteln)){
            include_once 'views/guestbook/'.$mitteln;
        }else{
        echo $mitteln;

        ob_flush();
        ob_clean(); 
        }
        ?>
        
    </body>
</html>