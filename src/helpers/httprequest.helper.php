<?php namespace Helpers;

class HttpRequestHelper {
  
  private static $instance;
  
  public string $method;
  public array $route;
  
  private function __construct(){
    $this->method = $_SERVER['REQUEST_METHOD'];
    
    $filteredUrl = filter_var(trim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL);
    
    $this->route = explode('/', $filteredUrl);
  }
  
  public static function instance() : HttpRequestHelper {
    if(!isset(self::$instance))
      self::$instance = new HttpRequestHelper();
    
    return self::$instance;
  }
  
}

?>