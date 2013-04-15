<?php

class Model_guestbook extends Model 

{
    
    public function add( $name, $msgShort, $msgFull )
    {
       /**
        * записує до БД новий запис
        * 
        * @param sting $name назва запису
        * @param sting $msgShort опис запису
        * @param sting $msgFull текст запису
        *
        * @param $dateCreate, $dateEdit --- час і дата створення і редагування 
        * запису (поточні час і дата)
        */
        
        $dateCreate = date( "Y-m-d H:i:s" );
        $dateEdit = date( "Y-m-d H:i:s" );
        
        $this -> _query = "INSERT INTO gbook_msg(
                                name,
                                msg_short,
                                msg_full,
                                date_create,
                                date_edit)
                            VALUES(
                                '$name',
                                '$msgShort',
                                '$msgFull',
                                '$dateCreate',
                                '$dateEdit')";
        $result = mysqli_query( $this -> _db, $this -> _query);
        
        return $result;
    }
    
    public function delete( $id )
    {
        /**
        * видаляє з БД запис 
        * 
        * @param int $id --- id повідомлення, яке потрібно видалити з БД
        */
        
        $this -> _query = "DELETE FROM gbook_msg WHERE id = $id";
        $result = mysqli_query( $this -> _db, $this -> _query);
        
        return $result;
    }
    
    public function edit( $id )
    {
       /**
        * повертає елементи повідомлення, яке потрібно відредагувати
        * 
        * @param int $id --- id повідомлення, яке потрібно відредагувати
        * 
        * @return $result --- інформація про повідомлення, 
        * яке потрібно відредагувати
        */
        
        $this -> _query = "SELECT * FROM gbook_msg 
                                WHERE id = $id";
        $result = mysqli_query( $this -> _db, $this -> _query);
    
        return $result;
    }
    
    public function editor( $name, $msgShort, $msgFull, $id )
    {
       /**
        * редагує записи у БД
        * 
        * @param int $id id запису, який редагується  
        * @param sting $name нова назва запису
        * @param sting $msgShort новий опис запису
        * @param sting $msgFull новий текст запису
        *
        * @param $dateCreate, $dateEdit --- час і дата створення і редагування 
        * запису (поточні час і дата)
        */
        
        $dateEdit = date( "Y-m-d H:i:s" ); 
            $this -> _query = "UPDATE gbook_msg 
                                    SET
                                        name = '$name',
                                        msg_short = '$msgShort',
                                        msg_full = '$msgFull',
                                        date_edit = '$dateEdit'
                                    WHERE id = $id;";
            $result = mysqli_query( $this -> _db, $this -> _query);
            
            return $result;
    }
    
    public function get()
    {
       /**
        * повертає інформацію про всі записи у БД
        * 
        * @return $result --- інформація про всі записи у БД
        */
        
        $this -> _query = "SELECT * FROM gbook_msg 
                                ORDER BY id DESC";
        $result = mysqli_query( $this -> _db, $this -> _query);
        
        if ( isset( $result ) && !empty( $result ) ){
        
            while ( $row = mysqli_fetch_array( $result ) ) {
            $list['id'] = $row['id'];
            $list['date_create'] = $row['date_create'];
            $list['date_edit'] = $row['date_edit'];
            $list['name'] = $row['name'];
            $list['msg_short'] = $row['msg_short'];
            $list['msg_full'] = $row['msg_full'];
            
            if( $row['date_create'] == $row['date_edit'] ) {
                $list['date'] = "Дата створення: ".$row['date_create']."<br/>";
            }else{
                $list['date'] = "Дата створення: ".$row['date_create']."<br/>
                                 Дата редагування: ".$row['date_edit'];    
        }
            
            $listAll[] = $list;
        }
        
            return $listAll;
        }  else {
            return $result;
        }
    }
    
    public function view( $view )
    {
       /**
        * повертає елементи повідомлення, яке потрібно вивести в окремову вікні
        * 
        * @param int $edit --- id повідомлення, яке потрібно вивести 
        * в окремову вікні
        * 
        * @return $result --- інформація про повідомлення, 
        * яке потрібно вивести в окремову вікні
        */
        
        $this -> _query = "SELECT * FROM gbook_msg 
                                WHERE id = $view";
        $result = mysqli_query( $this -> _db, $this -> _query);
    
        return $result;
    }
}

?>