<?php
require_once 'config/headers.php'; //заголовки
require_once 'config/autoload.php'; //масив класів
//Визначаємо функцію  __autoload
function __autoload($class) {
    global $_autoload;
    
    if (isset($_autoload[$class])) {
        include($_autoload[$class]);
    } 
}


Route::start(); // запускаем маршрутизатор
?>