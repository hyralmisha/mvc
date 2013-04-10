<?php


class View 

{
    const GUESTBOOK='views/guestbook/';//шлях до view-файлів
    const LAYOUT='views/layout/';//шлях до layout-файлів

    public function __construct()
    {
    
    }
    
    public function actionIndex($main,$mitteln)
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
        include_once self :: GUESTBOOK.$mitteln; 
        $mitteln=ob_get_contents();
        ob_end_clean();
        
        //підключаємо layout-файл 
        include_once self :: LAYOUT.$main;
        ob_end_flush();
        ob_end_clean();   
        
    }
    
    
}

?>

