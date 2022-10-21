<?php

$env = 'dev';
$test = file_get_contents('src/configs/' . $env . '.config.json');

$_ENV = json_decode(file_get_contents('src/configs/' . $env . '.config.json'), true);
$_ENV['env'] = $env;

require_once 'autoload.php';

use Controllers\DatabaseController;
use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;

$request = HttpRequest::instance();
$tables = DatabaseService::getTables();

if(empty($request->route) || !in_array($request->route[0], $tables)){
  HttpResponse::exit();
}

$controller = new DatabaseController($request);
// $result = $controller->execute();

// HttpResponse::send(["data"=>$result]);

?>