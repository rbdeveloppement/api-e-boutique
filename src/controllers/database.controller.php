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
    $this->id = 
    $this->body =
    $this->action =

  }
  
}

?>