<?php

class Route
{

	
    /**
     * Клас-маршрутизатор для визначення сторінки, яку запитують. 
     * Чіпляє класи контроллерів і моделей.
     * Створює об'єкти контроллерів сторінок і викликає екшени цих контроллерів.
     */
    
    static function start()
	{
                /*
                 * контроллер, екшен і id за замовчуванням
                 */
        	$controller_name = 'guestbook';
		$action_name = 'main';
                $id="";
		
                /*
                 * отримуэмо масив із рядку URL
                 */
                $routes = explode('/', $_SERVER['REQUEST_URI']);

		/*
                 * отримуємо ім'я контроллера
                */ 
		if ( !empty($routes[1]) )
		{	
			$controller_name = $routes[1];
		}
                
                /*
                 * отримуємо ім'я екшена
                 */
		if ( !empty($routes[2]) )
		{	
			$action_name = $routes[2];
		}
		
		/*
                 * отримуємо id
                 */
		if ( !empty($routes[3]) )
		{
			$id = $routes[3];
		}
                
                /*
                 * додаємо префікси
                 */ 
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
				
		/*
                 * чіпляємо файл з класом моделі (файлу може і не бути)
                 */ 
                $model_file = strtolower($model_name).'.php';
		$model_path = "models/".$model_file;
		if(file_exists($model_path))
		{
			include "models/".$model_file;
		}

		/*
                 * чіпляємо файл з класом контроллера
                 */
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "controllers/".$controller_file;
		if(file_exists($controller_path))
		{
			include "controllers/".$controller_file;
		} 
				
		/*
                 * створюэмо контроллер
                 */
                $controller = new $controller_name;
                $action = $action_name;
		
		/*
                 * викликаєм екшен контроллера (якщо такий екшен існує)
                 */
                if(method_exists($controller, $action))
		{
			$controller->$action();
		}
	}

}
