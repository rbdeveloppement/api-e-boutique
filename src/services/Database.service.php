<?php namespace Services;

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
  
  public function query(string $sql, array $params = []) : object {
    $statement = $this->connect()->prepare($sql);
    $result = $statement->execute($params);
    
    return (object)['result'=>$result, "statement"=>$statement];
  }
  
  public function selectWhere(string $where = "1", array $bind = []) : array {
    $sql = "SELECT * FROM $this->table WHERE $where;";
    $resp = $this->query($sql, $bind);
    $rows = $resp->statement->fetchAll(PDO::FETCH_CLASS);
    
    return $rows;
  }
  
  public static function getTables() : array {
    $dbs = new DatabaseService();
    
    $response = $dbs->query('SELECT table_name FROM information_schema.tables WHERE table_schema=?', [$_ENV['db']['dbName']]);
    $rows = $response->statement->fetchAll(PDO::FETCH_COLUMN);
    
    return $rows;
  }
  
  public function getSchema(){
    $schema = [];
    $sql = "SHOW FULL COLUMNS FROM $this->table";
    
    $response = $this->query($sql);
    $schema = $response->statement->fetchAll(PDO::FETCH_CLASS);
    
    return $schema;
  }
  
}

?>