<?php namespace Models;

use Models\Model;

class ModelList {
  
  public string $string;
  public string $pk;
  public array $items;
  
  public function __construct(string $table, array $list){
    $this->table = $table;
    $this->pk = "Id_$this->table";
    $this->items = [];
    
    foreach($list as $json){
      array_push($this->items, new Model($table, $json));
    }
  }
  
  public function data(): array {
    $data = [];
    
    foreach($this->items as $dt){
      array_push($data, $dt->data());
    }
    
    return $data;
  }
  
  public function idList($key = null): array {
    if(!isset($key)){
      $key = $this->pk;
    }
    
    $list = [];
    foreach($this->items as $itm){
      if(isset($itm->$key)){
        array_push($list, $itm->$key);
      }
    }
    
    return $list;
  }
  
  public function findById($id): ?Model {
    foreach($this->items as $model){
      if(isset($model[$model->pk]) && $model[$model->pk] == $id){
        return $model;
      }
    }
    
    return null;
  }
  
  public static function getSchema($table): array {
    $schemaName = "Schemas\\" . ucfirst($table) . "Schema";
    return $schemaName::COLUMNS;
  }
  
}

?>