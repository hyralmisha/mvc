<?php

class Controller 

{
    
    const GUESTBOOK = 'views/guestbook/';//шлях до view-файлів
    const LAYOUT = 'views/layout/';//шлях до layout-файлів
    const AUTHORIZATION = 'views/authorization/';

    
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