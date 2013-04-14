<?php


class View 

{
    
    public function __construct()
    {
    
    }
    
    public function actionIndex($main,$mitteln, $param="")
    {
        /**
         * підключає основний файл шаблону сторінки
         * 
         * @param file $main --- ім'я і шлях файла основного шаблону
         * @param file $mitteln --- ім'я і шлях файла сторінки
         */
        
        ob_start(); 
        ob_start();
        
        //підключаємо view-файл 
        include_once $mitteln; 
        $mitteln=ob_get_contents();
        ob_end_clean();
        
        //підключаємо layout-файл 
        include_once $main;
        ob_end_flush();
        ob_end_clean();   
        
    }
    
    
}

?>