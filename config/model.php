<?php

class Model 

{
    
    /**
     * Constructor
     *
     * створює з'єднання з БД і зберігає його у змінну $_db
     *  
     * @param  $_db --- містить з1єднання з БД
     * 
     * Destuctor
     * 
     * видаляє з'єднання з БД
     *
     */
    
    /*
     * передаємо константам атрибути з'єднання з БД
     */
    
    const HOST = 'localhost'; /*хостинг*/
    const NAME = 'root'; /*ім'я користувача БД*/
    const PASSWORD = '';/*пароль користувача БД*/
    const DB = 'gbook';/*ім'я БД*/
    
    /*
     * змінній  string $_query у класах наслідниках будемо присвоювати запит до БД
     */
    
    protected $_db, $_query;
        
    public function __construct()
    {
        $this -> _db = mysqli_connect( self::HOST,  self::NAME,  self::PASSWORD,  self::DB );
    }
    
    public function actionIndex()
    {
    
    }
    
    public function __destruct()
    {
        mysqli_close( $this->_db );
    }
    
}
?>
