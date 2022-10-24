<?php

$env = 'dev';
$_ENV = json_decode(file_get_contents("src/Configs/" . $env . ".config.json"), true);
$_ENV['env'] = $env;

require_once 'autoload.php';

// use Helpers\HttpResponse;
// $data="OK";
// // HttpResponse::send(["data"=>$data]);

use Controllers\DatabaseController;
use Helpers\HttpRequest;
use Helpers\HttpResponse;
use Services\DatabaseService;


$request = HttpRequest::instance();
$tables = DatabaseService::getTables();
if(empty($request->route) || !in_array($request->route[0], $tables)){
    HttpResponse::exit();

}
// $controller = new DatabaseController($request);
// $result = $controller->execute();
// HttpResponse::send(["method"=>$request->method, "route"=>$request->route]);

// $request = $_SERVER['REQUEST_METHOD'] . '/'. filter_var(trim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL);

$controller = new DatabaseController($request);
$result = $controller -> execute ();
HttpResponse :: send ([ "data" => $result ]);
//  HttpRequest::instance();
// echo $request;
