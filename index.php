<?php
require_once "autoload.php";

use Controllers\DataBaseController;

$request = $_SERVER['REQUEST_METHOD'] ."/" .
            filter_var(trim($_SERVER["RESQUEST_URI"], '/'), FILTER_SANITIZE_URL);

$controller = new DataBaseController($request);

echo $request;
?>