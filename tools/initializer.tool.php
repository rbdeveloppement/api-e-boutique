<?php namespace Tools;

use Exception;
use ErrorException;
use Helpers\HttpRequestHelper;
use Services\DatabaseService;

class InitializerTool {
  
  public static function start(HttpRequestHelper $request) : bool {
    $isForce = count($request->route) > 1 && $request->route[1] == 'force';
    try {
      self::writeTableFile($isForce);
    } catch(Exception $ex){
      return false;
    }
    
    return true;
  }
  
  private static function writeTableFile(bool $isForce = false) : array {
    $tables = DatabaseService::getTables();
    $tableFile = "src/schemas/Table.schema.php";
    
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
        throw new ErrorException("File couldn't be created!");
      };
    }
    
    return $tables;
  }
  
}
  
?>