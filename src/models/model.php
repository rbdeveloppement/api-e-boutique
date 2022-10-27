<?php namespace Models;

class Model {
  
  public string $table;
  public string $pk;
  public array $schema;
  
  public function __construct(string $table, array $json){
    $this->table = $table;
    $this->pk = "Id_$this->table";
    $this->schema = self::getSchema($table);
    if(!isset($json[$this->pk])){
      $json[$this->pk] = $this->nextGuid();
    }
    
    foreach($this->schema as $k => $v){
      if(isset($json[$k])){
        $this->$k = $json[$k];
      } else if($this->schema[$k]['nullable'] == '1' && $this->schema[$k]['default'] == ''){
        $this->$k = null;
      } else {
        $this->$k = $this->schema[$k]['default'];
      }
    }
  }
  
  public function data(): array {
    $data = (array) clone $this;
    
    foreach(array_keys($data) as $key){
      if(!isset($this->schema[$key])){
        unset($data[$key]);
      }
    }
    
    return $data;
  }
  
  public function nextGuid(int $length = 16): string {
    $guid = "";
    
    while(strlen($guid) < $length){
      $num = preg_replace('/[^0-9]/', '', microtime());
      $guid .= base_convert($num, 10, 32);
    }
    
    return substr($guid, 0, $length);
  }
  
  public static function getSchema(string $table): array {
    $schemaName = "Schemas\\" . ucfirst($table) . "Schema";
    return $schemaName::COLUMNS;
  }
  
}

?>