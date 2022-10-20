<?php

class AutoLoader {
  
  public static function register() {
    spl_autoload_register(function ($class) {
      echo "class: $class ";
      $pattern = ['/Controller\b/', '/Service\b/', '/Config\b/'];
      $replace = ['.controller', '.service', '.config'];
      
      $file = strtolower(preg_replace($pattern, $replace, $class) . '.php');
      
      $test = preg_replace($pattern, $replace, 'Controller');
      
      if(file_exists($file)){
        return require_once $file;
      }
      
      return false;
    });
  }
  
}

AutoLoader::register();

?>