<?php

class AutoLoader {
  
  public static function register() {
    spl_autoload_register(function ($class) {
      $search = ['Controller', 'Service'];
      $replace = ['.controller', '.service'];
      
      $file = str_replace($search, $replace, $class) . '.php';
      
      if(file_exists($file)){
        return require_once $file;
      }
      
      return false;
    });
  }
  
}

AutoLoader::register();

?>