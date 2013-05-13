<?php

class Model_guestbook extends Model 

{
    
    public function addTags( $tags )
    {
       /**
        * записує до БД нові теги
        * 
        * @param array $tags --- масив тегів, які ввів користувач
        * 
        */
        
        
        //витягаємо id останнього запису
        $this -> _query = "SELECT id FROM gbook_msg ORDER BY id DESC LIMIT 1";
        $postId = mysqli_fetch_array( mysqli_query( $this -> _db, $this -> _query) );
        
        foreach ( $tags as $tag ) {
            
            $tag = trim( $tag );
            
            $this -> _query = "SELECT * FROM tags WHERE name = '$tag'";
            $row = mysqli_fetch_array( mysqli_query( $this -> _db, $this -> _query) );
            
            if ( $row ) {
                $this -> _query = "INSERT INTO post_tags ( post_id, tags_id )
                                          VALUES ('".$postId['id']."','".$row['id']."')";
                mysqli_query( $this -> _db, $this -> _query);
                
                $this -> _query = "UPDATE tags 
                                          SET count = count + 1
                                          WHERE name = '$tag'";
                mysqli_query( $this -> _db, $this -> _query);
                
            } else {
                $this -> _query = "INSERT INTO tags ( name, count )
                                          VALUES ('".$tag."','1')";
                mysqli_query( $this -> _db, $this -> _query);
                $this -> _query = "SELECT id FROM tags ORDER BY id DESC LIMIT 1";
                $tagsId = mysqli_fetch_array( mysqli_query( $this -> _db, $this -> _query) );
                

                $this -> _query = "INSERT INTO post_tags ( post_id, tags_id )
                                          VALUES ('".$postId['id']."','".$tagsId['id']."')";
                mysqli_query( $this -> _db, $this -> _query);
            }
        }
        
    }
    
    
    
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
    
    
    
    public function get( $cpage, $itemsperpage ) 
    {
       /**
        * повертає записи з БД в кількості $itemsperpage, починаючи з $cpage
        */
        
        $this -> _query = "SELECT * FROM gbook_msg 
                                ORDER BY id DESC LIMIT $cpage, $itemsperpage;";
        
        
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
    
    public function get1() 
    {
        /**
        * повертає кількість записів у БД
        */
        
        
        
        $this -> _query = "SELECT * FROM gbook_msg 
                                ORDER BY id DESC;";
        $result = mysqli_query( $this -> _db, $this -> _query);
        //$numRows = mysql_num_rows ($result);
        //$result = mysqli_query( $this -> _db, "SELECT FOUND_ROWS()");
        
        $numRows = mysqli_num_rows($result);
        return $numRows;
        
        
        /*if ( isset( $result ) && !empty( $result ) ){
            $i = 0;
            while ( $row = mysqli_fetch_array( $result ) ) {
            $i++;
            }
            return $i;
        }  else {
            return $result;
        }*/
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
    
    public function search($searchPhrase)
    {
        /**
        * повертає записи, які відповідають умовам пошуку
        * 
        * @param $searchPhrase --- фраза, за якою здійснюється пошук
        * 
        * @return $result --- масив записів, які відповідають пошуку
        */
        
        $this -> _query = "SELECT *
                                FROM gbook_msg
                                WHERE MATCH (
                                name, msg_short, msg_full
                                )
                                AGAINST (
                                '$searchPhrase*' IN BOOLEAN MODE
                                )";
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
    
}

?>