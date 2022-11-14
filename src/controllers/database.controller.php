<?php namespace Controllers;

use ArrayAccess;
use Services\DatabaseService;
use Helpers\HttpRequest;


class DatabaseController {

  private string $table;
  private string $pk;
  private ?string $id;
  private array $body;
  private string $action;

  public function __construct( HttpRequest $request) {
    
    $this->table = $request->route[0];
    $this->pk = "Id_" . $this->table;
    $this->id = isset($request-> route[1]) ?$request-> route[1] : null;

    $request_body = file_get_contents('php://input');
    $this->body = json_decode($request_body, true) ?: [];

    $this->action = $request->method;
  }
  
public function execute() : ?array
{
 $result = $this->{$this->action}();
  return $result
 // return $this->($this->action)();
  
;}
  
private function get() :?array
{

  $dbs = new DatabaseService($this->table);
  $data = $dbs->selectWhere("$this->pk = ?", [$this->id]);
  return $data;
}

private function put() : array
{
  $dbs = new DatabaseService($this->table);
  $rows = $dbs->insertOrUpdate($this->body);
  return $rows;
}
public function patch(): ?array {     // le patch fait fait un soft deleted
  $dbs = new DatabaseService($this->table);
  $rows = $dbs->softDelete($this->body);
  
  return $rows;
}

public function delete(): ?array {   //fait un hard delete
  $dbs = new DatabaseService($this->table);
  $rows = $dbs->hardDelete($this->body);
  
  return $rows;
}

}

?>