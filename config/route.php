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
                 * отримуэмо масив із рядка URL
                 */
                $routes = explode('/', $_SERVER['REQUEST_URI']);

		/*
                 * отримуємо ім'я контроллера
                */ 
		if ( !empty( $routes[2] ) ) {	
                    $controller_name = $routes[2];
		}
                
                /*
                 * отримуємо ім'я екшена
                 */
		if ( !empty( $routes[3] ) ) {	
                    $action_name = $routes[3];
		}
		
		/*
                 * отримуємо id
                 */
		if ( !empty( $routes[4] ) ) {
                    $_GET['id']= $routes[4];
                    $id=$_GET['id'];
		}
                
                //отримуємо номер сторінки, яку треба вивести 
                if ( !empty( $routes[5] ) ) {
                    
                    $_GET["$routes[4]"]= $routes[5];
                }
                
                
                /*
                 * додаємо префікси
                 */ 
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
				
		
				
		/*
                 * створюэмо контроллер
                 */
                $controller = new $controller_name;
                $action = $action_name;
		
		/*
                 * викликаєм екшен контроллера (якщо такий екшен існує)
                 */
                if( method_exists( $controller, $action ) ) {
                    $controller->$action();
		}
	}

}
