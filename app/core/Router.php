<?php 

namespace app\core;
use app\core\View;

class Router
{
	#создадим свойства
	protected $routes = [];
	protected $params = [];


	function __construct()
	{
		#Подключим конфиги роутов
		$arr = require 'app/config/routes.php';
		#Перебираем массиив роутов
		foreach ($arr as $key => $value) {
			#Передадим ключь и значения к функцию эдд
			$this->add($key, $value);
		}
	}

	public function add($route, $params) {
		#Очистим ключи от слешов и спецсимволов
		$route = preg_replace('/{([a-z]+):([^\}]+)}/', '(?P<\1>\2)', $route);
		$route = '#^'.$route.'$#';
		#Присвоим значение (значения от массива из конфига роутов) к свойству $routes
		$this->routes[$route] = $params;
}

    public function match() {
    	#Очишаем url от слешов
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
        	#Перебираем гловальный массив this->routes
          if (preg_match($route, $url, $matches)) {
          	#ищем в роутах соответствие с url
          	foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        if (is_numeric($match)) {
                            $match = (int) $match;
                        }
                        $params[$key] = $match;
                    }
                }

              $this->params = $params;
             return true;
					}
        }
        return false;
			}

public function run() {
		if ($this->match()) {
			$path = 'app\controllers\\'.ucfirst($this->params['controller']).'Controller';
			if (class_exists($path)) {

				$action = $this->params['action'].'Action';
				if (method_exists($path, $action)) {
					
					$controller = new $path($this->params);
					$controller->$action();
				}else View::errorCode('404');
			}else View::errorCode('404');
		}else View::errorCode('404');
	}



}