<?php

class Controller 

{
    
    protected $_guestbok = 'views/guestbook/';//шлях до view-файлів
    protected $_layout = 'views/layout/';//шлях до layout-файлів
    protected  $_authorization = 'views/authorization/';

    
    /**
     * Constructor
     *
     * Створює об'єкт класу View() --- (клас View() описаний у файлі view.php)  
     * 
     * @param  object	 $view --- об'єкт класу View()
     * 
     */

    public $model;
    public $view;

    public function __construct()
    {
        $this->view = new View();
        
    }

    function actionIndex()
    {
            
    }
       
}

?>