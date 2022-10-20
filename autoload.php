<?php

class AutoLoader {
  
  public static function register() {
    spl_autoload_register(function ($class) {
      $pattern = ['/Controller\b/', '/Service\b/', '/Config\b/'];
      $replace = ['.controller', '.service', '.config'];
      
      $file = preg_replace($pattern, $replace, $class) . '.php';
      
      if(file_exists($file)){
        return require_once $file;
      }
      
      return false;
    });
  }
  
}

AutoLoader::register();

?>