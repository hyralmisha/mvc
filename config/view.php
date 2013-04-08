<?php


class View 

{
    
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

         include_once 'views/layout/'.$main;
    }
    
    
}

?>

