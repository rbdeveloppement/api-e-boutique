<?php namespace Services;

use Helpers\HttpResponseHelper;
use Models\ModelList;
use PDO;
use PDOException;

class DatabaseService {
  
  private static ?PDO $connection = null;
  
  public ?string $table;
  public string $pk;
  
  public function __construct(?string $table = null){
    $this->table = $table;
    $this->pk = "Id_$this->table";
  }
  
  private function connect() : PDO {
    if(self::$connection == null){
      $dbConfig = $_ENV['db'];
      
      $host = $dbConfig['host'];
      $port = $dbConfig['port'];
      $dbName = $dbConfig['dbName'];
      
      $dsn = "mysql:host=$host;port=$port;dbname=$dbName";
      
      $user = $dbConfig['user'];
      $pass = $dbConfig['pass'];
      
      try {
        $dbConnection = new PDO($dsn, $user, $pass, array(
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));
      } catch (PDOException $ex) {
        die("Erreur de connexion à la base de données : $ex->getMessage()");
      }
      
      self::$connection = $dbConnection;
    }
    
    return self::$connection;
  }
  
  public function query(string $sql, array $params = []): object {
    $statement = $this->connect()->prepare($sql);
    $result = $statement->execute($params);
    
    return (object)['result'=>$result, "statement"=>$statement];
  }
  
  public function selectWhere(string $where = "1", array $bind = [], int $fetch_type = PDO::FETCH_CLASS): array {
    $sql = "SELECT * FROM $this->table WHERE $where;";
    $resp = $this->query($sql, $bind);
    $rows = $resp->statement->fetchAll($fetch_type);
    
    return $rows;
  }
  
  public function insertOrUpdate(array $body): ?array {
    $modelList = new ModelList($this->table, $body['items']);
    
    $existingRowsListId = $modelList->idList();
    
    $where = "$this->pk IN (";
    foreach($existingRowsListId as $id){
        $where .= "?, ";
    }
    $where = substr($where, 0, -2) . ")";
    
    $rows = $this->selectWhere($where, $existingRowsListId, PDO::FETCH_ASSOC);
    
    $existingModelList = new Modellist($this->table, $rows);
    
    $columns = "";
    $values = "";
    $valuesToBind = [];
    
    foreach($modelList->data() as $data){
        $values .= "(";
        
        if(empty($columns)){
            $columns .= "(";
            foreach(array_keys($data) as $key){
                $columns .= "$key, ";
            }
            $columns = substr($columns, 0, -2) . "), ";
        }
        
        foreach($data as $k => $v){
            $values .= "?, ";
            array_push($valuesToBind, $v);
        }
        $values = substr($values, 0, -2) . "), ";
    }
    
    $columns = substr($columns, 0, -2);
    $values = substr($values, 0, -2);
    
    $sql = "INSERT INTO $this->table $columns VALUES $values ON DUPLICATE KEY UPDATE $this->pk=$this->pk;";
    
    $resp = $this->query($sql, $valuesToBind);
    if($resp->result){
        return $resp->statement->fetchAll(PDO::FETCH_CLASS);
    }
    
    return null;
  }
  
  public static function getTables() : array {
    $dbs = new DatabaseService();
    
    $response = $dbs->query('SELECT table_name FROM information_schema.tables WHERE table_schema=?', [$_ENV['db']['dbName']]);
    $rows = $response->statement->fetchAll(PDO::FETCH_COLUMN);
    
    return $rows;
  }
  
  public function getSchema() : array {
    $schema = [];
    $sql = "SHOW FULL COLUMNS FROM $this->table";
    
    $response = $this->query($sql);
    $schema = $response->statement->fetchAll(PDO::FETCH_CLASS);
    
    return $schema;
  }
  
}

?>