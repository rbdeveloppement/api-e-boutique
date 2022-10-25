<?php namespace Tools;

use Services\DatabaseService;

class InitializerTool {
  
  private static function writeTableFile(bool $isForce = false) : array {
    $tables = DatabaseService::getTables();
    $tableFile = "src/schemas/Table.php";
    
  }
  
}
  
?>