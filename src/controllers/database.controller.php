<?php namespace Controllers; // databasecontroller


use Services\DatabaseService;
use Helpers\HttpRequest;

class DatabaseController {

  private string $table;
  private string $pk;
  private ?string $id;
  private array $body;
  private string $action;
  
  public function __construct(HttpRequest $request) {

    /* initialise les 5 variables de la classe */   

    $this->table = $request->route[0]; // récupère la route avec un tableau qui contient la table  
    $this->pk = "Id_$this->table"; // chaine de caractère id_ auquel on rajoute la table 
    $this->id = isset($request->route[1])?$request->route[1] : null;// condition ternaire

    $request_body = file_get_contents('php://input');
     $this-> body = json_decode($request_body, true) ?: [];

     $this->action = $request ->method;
    
  }

  public function execute() : ?array 
  {
      return $this->{$this->action}();
      
  }

  private function get() :?array
  {
  
    $dbs = new DatabaseService($this->table);
    $data = $dbs->selectWhere(is_null($this->id) ?: "$this->pk = ?", [$this->id]);
    return $data;
  }
  

  
}

//la table n'existe pas il faut aller la chercher dans request

?>