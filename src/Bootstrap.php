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

$request = new HttpRequest($_GET,$_POST,$_COOKIE,$_FILES,$_SERVER);
$response = new HttpResponse();

$content ='404 - Page not found';
$response->setContent($content);
$response->setStatusCode(404);

foreach($response->getHeaders() as $header):
	header($header,false);
endforeach;

echo $response->getContent();

