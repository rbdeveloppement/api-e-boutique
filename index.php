<?php

use Tools\InitializerTool;

$env = 'dev';
$_ENV = json_decode(file_get_contents('src/configs/' . $env . '.config.json'), true);
$_ENV['env'] = $env;

require_once 'autoload.php';

use Controllers\DatabaseController;
use Helpers\HttpRequestHelper;
use Helpers\HttpResponseHelper;
use Services\DatabaseService;

use Models\Model;
use Models\ModelList;

$request = HttpRequestHelper::instance();
$tables = DatabaseService::getTables();

if($_ENV['env'] == 'dev' && !empty($request->route) && $request->route[0] == 'init'){
  if(InitializerTool::start($request)){
    HttpResponseHelper::send(['message'=>'Api Initialized']);
  }
  HttpResponseHelper::send(['message'=>'Api Not Initialized, try again...']);
}

if(!empty($request->route)){
  $const = strtoupper($request->route[0]);
  $key = "Schemas\TableSchema::$const";
  
  if(!defined($key)){
    HttpResponseHelper::exit();
  }
} else {
  HttpResponseHelper::exit();
}

$controller = new DatabaseController($request);
$result = $controller->execute();

HttpResponseHelper::send(['data'=>$result]);

?>