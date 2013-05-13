<?php

class Model_tags extends Model 

{
    
    public function getTags( $tagCount )
    {
       /**
        * виводить з БД масив тегів для "хмари тегів"
        * 
        * 
        */
        
        //витягаємо id останнього запису
        $this -> _query = "SELECT * FROM tags ORDER BY count DESC LIMIT $tagCount";
        $result = mysqli_query( $this -> _db, $this -> _query);
        while ( $row = mysqli_fetch_array( $result ) ) {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    
    public function get( $cpage, $itemsperpage, $idTag ) 
    {
       /**
        * повертає записи з БД в кількості $itemsperpage, починаючи з $cpage
        */
        
        $this -> _query = "SELECT * FROM gbook_msg 
                                WHERE id IN ( SELECT post_id FROM post_tags
                                WHERE tags_id = $idTag)
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
    
    public function get1( $idTag ) 
    {
        /**
        * повертає кількість записів у БД
        */
        
        
        
        $this -> _query = "SELECT * FROM post_tags
                                WHERE tags_id = $idTag
                                ORDER BY id DESC;";
        $result = mysqli_query( $this -> _db, $this -> _query);
        
        $numRows = mysqli_num_rows($result);
        return $numRows;
        
        
    }
    
        
}


?>
