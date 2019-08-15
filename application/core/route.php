<?php

class Route
{
	static function start() {

		$controller_name = 'Main';
		$action_name = 'index';
		
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		
		if (!empty($routes[1]) ) {	
			$controller_name = $routes[1];
		}
		
		// получаем имя экшена
		if (!empty($routes[2]) ) {
			$action_name = $routes[2];
		}
        
        if (!empty($routes[3])) {
            $param1 = $routes[3];
        }
        
        if (!empty($routes[4])) {
            $param2 = $routes[4];
        }
        
        if (!empty($routes[5])) {
            $param3 = $routes[5];
        }
        
        if (!empty($routes[6])) {
            $param4 = $routes[6];
        }
     
        
        
        

		// добавляем префиксы
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		// подцепляем файл с классом модели (файла модели может и не быть)

		$model_file = strtolower($model_name).'.php';
		$model_path = "application/models/".$model_file;
		if(file_exists($model_path)) {
			include "application/models/".$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "application/controllers/".$controller_file;
		if(file_exists($controller_path)) {
			include "application/controllers/".$controller_file;
		} else {
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Route::ErrorPage404();

		}
		
		// создаем контроллер
            
        $controller = new $controller_name;
        $action = $action_name;
		
        if(method_exists($controller, $action))
        {
                // вызываем действие контроллера
            $controller->$action($param1,$param2,$param3,$param4);
        } else
        {
                // здесь также разумнее было бы кинуть исключение
            Route::ErrorPage404();

                //header('Location: /');
        }
        
		/*$action = $action_name;
		
		if(method_exists($controller, $action))
		{
			// вызываем действие контроллера
			$controller->$action($param1,$param2,$param3,$param4);
        } else
		{
			// здесь также разумнее было бы кинуть исключение
			Route::ErrorPage404();
            
            //header('Location: /');
		}*/
	
	}
	
	function ErrorPage404()
	{
        /*$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');*/
        //header('Location: /error');
        
    }
}

?>