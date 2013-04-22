<?php

class Model_authorization extends Model 

{
    
    public function registration( $lastName, $firstName, $email, $password )
    {  
        /**
        * записує до БД новий інформацію про користувача 
        * 
        * @param sting $lastName --- прізвище
        * @param sting $firstName --- ім'я
        * @param sting $email --- email
        * @param sting $password --- пароль
        *
        * @param $dateCreate, $dateEnter --- час і дата реєстрації і останнього 
        * входу у систему (поточні час і дата)
        */
        
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
        $result = mysqli_query( $this -> _db, $this -> _query);
        
        return $result;
            
    }
    
    public function confirmation($email)
    {
       /**
        * повертає інфу про користувача за його email
        * 
        * @return str $email --- email користувача
        */
        
        $this -> _query = "SELECT * FROM users 
                                WHERE email = '$email'";
        $result_1 = mysqli_query( $this -> _db, $this -> _query);
        if ( isset( $result_1 ) && !empty( $result_1 ) ){
            $result = mysqli_fetch_array( $result_1 );
            return $result;
        }  else {
            return $result_1;
        }
        
    }
    
    public function dateEnter( $email )
    {
       /**
        * оновлює дату входу користувача у систему
        * 
        * @return str $email --- email користувача
        *
        * @param $dateEnter  --- час і дата входу користувача у систему 
        */
        
        $dateEnter = date( "Y-m-d H:i:s" ); 
        $this -> _query = "UPDATE users 
                                SET
                                    date_enter = '$dateEnter'
                                WHERE email = $email;";
        $result = mysqli_query( $this -> _db, $this -> _query);

        return $result;
    }
    
    public function dateChange($email)
    {
        /**
        * записуэ у БД час ы дату выдновлення пароля користувача з email = $email
        * 
        * @return str $email --- email користувача
        */
        
        $dateChange = time(); 
        $this -> _query = "UPDATE users 
                                    SET
                                        date_change = '$dateChange'
                                    WHERE email = $email;";
     $result = mysqli_query( $this -> _db, $this -> _query);

        return $result;
    }
    
    
    public function check ($id)
    {
        /**
        * повертає інфу про користувача, який выдновлюэ пароль  за його hash_key
        * 
        * @param str $id --- hash_key відправлений користувачу для 
        * відновлення паролю 
        */
        $this -> _query = "SELECT * FROM users 
                                WHERE hash_key = '$id'";
        $result_1 = mysqli_query( $this -> _db, $this -> _query);
        if ( isset( $result_1 ) && !empty( $result_1 ) ){
            $result = mysqli_fetch_array( $result_1 );
            return $result;
        }  else {
            return $result_1;
        }
    }
    
    
    public function passChange( $email, $password )
    {
        /**
        * оновлює пароль користувача у БД за його email
        * 
        * @param str $email --- email користувачу 
        * @param str $password --- пароль користувачу  
        */
        
        $this -> _query = "UPDATE users 
                                    SET
                                        password = '$password'
                                    WHERE email = '$email';";
        $result = mysqli_query( $this -> _db, $this -> _query);
        
        return $result;
    }
    
    public function hashKeySave($email, $hashKey)
    {
        /**
        * записує у БД hash_key користувача, відправлений для відновлення паролю
        * 
        * @param str $email --- email користувачу 
        * @param str $hashKey --- hashKey користувачу  
        */
        $this -> _query = "UPDATE users 
                                    SET
                                        hash_key = '$hashKey'
                                    WHERE email = '$email';";
            $result = mysqli_query( $this -> _db, $this -> _query);
            
            return $result;
    }
}
?>
