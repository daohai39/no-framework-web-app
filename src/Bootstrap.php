<?php
declare(strict_types = 1);
namespace Example;
require __DIR__ . '/../vendor/autoload.php';

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
