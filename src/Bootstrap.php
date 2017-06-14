<?php
declare(strict_types = 1);
namespace Example;
require __DIR__ . '/../vendor/autoload.php';
use Http\HttpRequest;
use Http\HttpResponse;

error_reporting(E_ALL);

$enviroment = 'development';

$whoops = new \Whoops\Run;
if ($enviroment !== 'production'):
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
else:
	$whoops->pushHandler(function($e){
		echo 'Todo: Friendly error page and send email to the developer';
	});
endif;

$whoops->register();

/////////////////////////////////
$request = new \Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
$response = new \Http\HttpResponse;
/////////////////////////////////

$routeDefinitionCallback = function (\FastRoute\RouteCollector $r){
	$routes = include('Routes.php');
	foreach($routes as $route):
		$r->addRoute($route[0],$route[1],$route[2]);
	endforeach;
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(),$request->getPath());

switch($routeInfo[0]):
	case \FastRoute\Dispatcher::NOT_FOUND:
		$response->setContent('404 - Page not found');
		$response->setStatusCode(404);
		break;
	case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$response->setContent('405 - Method not allowed');
		$response->setStatusCode(405);
		break;
	case \FastRoute\Dispatcher::FOUND:
		$className = $routeInfo[1][0];
		$method = $routeInfo[1][1];

		$vars = $routeInfo[2];

		$class = new $className;
		$class->$method($vars);
		break;
endswitch;

///////////////////////////////////
foreach ($response->getHeaders() as $header) {
    header($header, false);
}

echo $response->getContent();