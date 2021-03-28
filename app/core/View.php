<?php

namespace app\core;

class View
{
	public $route;
	public $path;
	public $layout = 'empty';


	public function __construct($route)
	{

		$this->route = $route;
		$this->path = $route['controller'].'/'.$route['action'];

	}

	public function render($title, $vars = []) {

		ob_start();
		require 'app/views/'.$this->path.'.php';
		require 'app/views/layouts/'.$this->layout.'.php';

	}

	public function redirect($url) {
		header('Location: '.$url);
		exit;
	}

	public static function errorcode($code){
		http_response_code($code);
		require 'app/views/errors/'.$code.'.php';
		exit;
	}
}