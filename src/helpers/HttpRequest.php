<?php namespace Helpers;

class HttpRequest {
  
  private static $instance;
  
  public string $method;
  public array $route;
  
  private function __construct(){
    $this->method = $_SERVER['REQUEST_METHOD'];
    
    $filteredUrl = filter_var(trim($_SERVER['REQUEST_URI'], '/'), FILTER_SANITIZE_URL);
    
    $this->route = explode('/', $filteredUrl);
  }
  
  public static function instance() : HttpRequest {
    echo self::$instance;
    
    if(!isset(self::$instance))
      self::$instance = new HttpRequest();
    
    return self::$instance;
  }
  
}

?>