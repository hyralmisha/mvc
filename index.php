<?php
//основний файл
header("Content-type: text/html; charset=utf-8");
// підключаємо файли конфігурації 
require_once 'config/model.php';
require_once 'config/view.php';
require_once 'config/controller.php';

        require_once 'config/route.php';
        Route::start(); // запускаем маршрутизатор
		
?>
        
