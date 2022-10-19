<?php

require_once 'autoload.php';

use controllers\DatabaseController;

$request = $_SERVER['REQUEST_METHOD'] . '/'. filter_var(trim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL);

$controller = new DatabaseController($request);

echo $request;

?>