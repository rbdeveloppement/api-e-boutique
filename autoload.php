<?php

class AutoLoader {
  
  public static function register() {
    spl_autoload_register(function ($class) {
      $pattern = ['/Controller\b/', '/Service\b/', '/Config\b/', '/Helper\b/', '/Tool\b/', '/Schema\b/'];
      $replace = ['.controller', '.service', '.config', '.helper', '.tool', '.schema'];
      
      $replacedClassName = preg_replace($pattern, $replace, $class);
      
      $file = "$replacedClassName.php";
      $srcFile = "src/$file";
      
      // ? La plupart des fichiés sont dans 'src/' donc on le vérifie avant, ça permet d'éviter de faire trop de condition.
      if(file_exists($srcFile)){
        return require_once $srcFile;
      } else if(file_exists($file)){
        return require_once $file;
      }
      
      return false;
    });
  }
  
}

AutoLoader::register();

?>