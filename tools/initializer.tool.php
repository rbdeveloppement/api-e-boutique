<?php namespace Tools;

use Exception;
use ErrorException;
use Helpers\HttpRequestHelper;
use Services\DatabaseService;

class InitializerTool {
  
  public static function start(HttpRequestHelper $request) : bool {
    $isForce = count($request->route) > 1 && $request->route[1] == 'force';
    try {
      $tables = self::writeTableFile($isForce);
      
      self::writeSchemasFiles($tables, $isForce);
    } catch(Exception $ex){
      return false;
    }
    
    return true;
  }
  
  private static function writeTableFile(bool $isForce = false) : array {
    $tables = DatabaseService::getTables();
    $tableFile = "src/schemas/table.schema.php";
    
    if(file_exists($tableFile) && $isForce){
      if (!unlink($tableFile)) {
        throw new ErrorException("Wasn't able to delete file '$tableFile'.");
      }
    }
    
    if(!file_exists($tableFile)){
      $file_content = "<?php namespace Schemas;\n\nclass TableSchema {\n\n";
      
      foreach($tables as $table){
        $file_content .= "\tconst " . strtoupper($table) . " = '$table';\n";
      }
      
      $file_content .= "\n}\n\n?>";
      
      if(!file_put_contents($tableFile, $file_content)){
        throw new ErrorException("File '$tableFile'couldn't be created!");
      };
    }
    
    return $tables;
  }
  
  private static function writeSchemasFiles(array $tables, bool $isForce) : void {
    foreach($tables as $table){
      $schemaFile = "src/schemas/$table.schema.php";
      
      if(file_exists($schemaFile) && $isForce){
        if(!unlink($schemaFile)){
          throw new ErrorException("Wasn't able to delete file '$schemaFile'.");
        }
      }
      
      if(!file_exists($schemaFile)){
        $dbs = new DatabaseService($table);
        $schemas = $dbs->getSchema();
        
        $data = "<?php namespace Schemas;\n\nclass " . $table . "Schema {\n\n\tconst COLUMNS = [\n";
          
        foreach($schemas as $schema){
          $nullableBool = $schema->Null != 'NO';
          $data .= "\t\t'$schema->Field' => ['type'=>'$schema->Type', 'nullable'=>'$nullableBool', 'default'=>'$schema->Default'],\n";
        }
          
        // => [
        //   'type'=>'$schema->Type',
        //   'nullable'=>'" . $schema->Null == 'NO' ? 0 : 1 . "',
        //   'default'=>'$schema->Default'
        // ],\n
        
        $data .= "\t];\n\n}\n\n?>";
        
        if(!file_put_contents($schemaFile, $data)){
          throw new ErrorException("File '$schemaFile' couldn't be created!");
        }
      }
    }
  }
  
}
  
?>