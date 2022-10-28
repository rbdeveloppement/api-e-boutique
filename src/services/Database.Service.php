<?php

namespace Services;

use PDO;
use PDOException;

class DatabaseService
{
    public ?string $table;
    public string $pk;
    
    public function __construct(?string $table = null)
    {
        $this->table = $table;
        $this->pk = "Id_" . $this->table;
    }
    private static ?PDO $connection = null;
    private function connect(): PDO
    {
        if (self::$connection == null) {
            $dbConfig = $_ENV['db'];
            $host = $dbConfig["host"];
            $port = $dbConfig["port"];
            $dbName = $dbConfig["dbName"];
            $dsn = "mysql:host=$host;port=$port;dbname=$dbName";
            $user = $dbConfig["user"];
            $pass = $dbConfig["pass"];
            try {
                $dbConnection = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    )
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données :
                $e -> getMessage ()");
            }
            self::$connection = $dbConnection;
        }
        return self::$connection;
    }
    public function query(string $sql, array $params = []): object
    {
        $statement = $this->connect()->prepare($sql);
        $result = $statement->execute($params);
        return (object)['result' => $result, 'statement' => $statement];
    }
    /**
     * Retourne la liste des tables en base de données sous forme de tableau
     */
    public static function getTables(): array
    {
        $dbs = new DatabaseService(null);
        $query_resp = $dbs->query("SELECT table_name FROM information_schema.tables
                                     WHERE table_schema = ?", ['e-boutique']);
        $rows = $query_resp->statement->fetchAll(PDO::FETCH_COLUMN);
       
        return $rows;
    }


    public function selectWhere(string $where = "1", array $bind = []) : array
    {
        $sql = "SELECT * FROM $this->table WHERE $where;";
        $resp = $this->query($sql, $bind);
        $rows = $resp->statement->fetchAll(PDO::FETCH_CLASS);   //FETCH_CLASS donne un objet
        return $rows;
    }

    public function getSchema(){
        
        $schemas = [];
        $sql = "SHOW FULL COLUMNS FROM $this->table";
        $query_resp_column = $this->query($sql);
        $schemas = $query_resp_column->statement->fetchAll(PDO::FETCH_ASSOC);    //FETCH_ASSOC donne une liste[]

        return $schemas;
    }
}
