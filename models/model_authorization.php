<?php

class Model_authorization extends Model 

{
    
    public function registration( $lastName, $firstName, $email, $password )
    {   
        $dateCreate = date( "Y-m-d H:i:s" );
        $dateEnter = date( "Y-m-d H:i:s" );
        
        $this -> _query = "INSERT INTO users(
                                last_name,
                                first_name,
                                email,
                                password,
                                date_create, 
                                date_enter)
                            VALUES(
                                '$lastName',
                                '$firstName',
                                '$email',
                                '$password', 
                                '$dateCreate',
                                '$dateEnter')";
        mysqli_query( $this -> _db, $this -> _query)
                or die ('Помилка: запит до бази даних не може бути виконаний!');
            
    }
    
    public function confirmation($email)
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
        
        $this -> _query = "SELECT * FROM users 
                                WHERE email = '$email'";
        $result1 = mysqli_query( $this -> _db, $this -> _query)
                or die ('Помилка: запит до бази даних не може бути виконаний!');
        $result = mysqli_fetch_array( $result1 );
        return $result;
    }
    
}
?>
