<?php namespace Controllers;

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
 $result = self::get();
  return $result
 // return $this->($this->action)();
  
;}
  
private function get() :?array
{

  $dbs = new DatabaseService($this->table);
  $data = $dbs->selectWhere("$this->pk = ?", [$this->id]);
  return $data;
}
}

?>