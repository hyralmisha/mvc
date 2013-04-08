<?php

class Controller 

{
    
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

    function __construct()
    {
        $this->view = new View();
    }

    function actionIndex()
    {
            
    }
       
}

?>
