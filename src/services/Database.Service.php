<?php

namespace Services;

use Models\ModelList;
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


    public function selectWhere(string $where = "1", array $bind = []): array
    {
        $sql = "SELECT * FROM $this->table WHERE $where;";
        $resp = $this->query($sql, $bind);
        $rows = $resp->statement->fetchAll(PDO::FETCH_CLASS);   //FETCH_CLASS donne un objet
        return $rows;
    }

    public function getSchema()
    {

        $schemas = [];
        $sql = "SHOW FULL COLUMNS FROM $this->table";
        $query_resp_column = $this->query($sql);
        $schemas = $query_resp_column->statement->fetchAll(PDO::FETCH_ASSOC);    //FETCH_ASSOC donne une liste[]

        return $schemas;
    }

    public function insertOrUpdate(array $body): ?array
    {
        $modelList = new ModelList($this->table, $body['items']);       //on créer un nouveau modelListe avec le tableau d'items recupéré dans la table

        $idList = $modelList->idList(); //on récupère tous les id de la liste, 

        $where = "$this->pk IN (";  // on recupère la Primary Key
        foreach ($idList as $id) {
            $where .= "?, ";
        }
        $where = substr($where, 0, -2) . ")";   //

        $resp = $this->selectWhere($where, $idList, PDO::FETCH_ASSOC); //tu recupères une condition avec en parametre le $where, (id doit etre dans idlist)

        foreach ($body['items'] as $data) {      //pour chaque elements dqns body items, on le nomme data(valeur renvoyée)
            $exist = false;
            foreach ($resp as &$arr) { // chaque reponses de [resp], on le nomme "arr", "&" veut dire que si on modifie arr, cela change la valeur dans le resp
                if (!isset($arr[$this->pk]) || !isset($data[$this->pk])) { //si le pk de $arr n'existe pas, ou le pk de data n'existe pas alors on passe à l'autre instance
                    continue;
                }

                if ($arr[$this->pk] === $data[$this->pk]) {  // si la pk est === pk de data existe devient true
                    $exist = true;
                    foreach ($data as $k => $v) {       // pour chaque $k(key) et $v(vallue)  dans data
                        $arr[$k] = $v;     // tu remplace les key de $arr par les $v du body 
                    }
                    break;
                }
            }
            if (!$exist) {
                array_push($resp, $data); // si ça n'existe pas dans le resp, on ajoute au tableau
            }
        }
        $modelList = new ModelList($this->table, $resp); //npuvelle instance modellist et on ajoute la resp à la table de l'instance en cour
        $columns = "";
        $values = "";
        $duplicateUpdate = "";      //on initialise des données
        $valuesToBind = [];

        foreach ($modelList->data() as $data) {       //on recupere les data dans modellist et on retourne en tableau associatif
            $values .= "(";

            if (empty($columns)) {
                $columns .= "(";        //si la valeur colums est vide, on ajoute une "("
                foreach (array_keys($data) as $key) {     //on boucle pour recuperer les nom de toutes les propriétées
                    $columns .= "$key, ";  // on ajoute a colums la $key que l'on récupéré dans $data
                    $duplicateUpdate .= "$key=VALUES($key), ";  //on ajoute a $duplicateUpdate les valeurs de colums
                }
                $columns = substr($columns, 0, -2) . ")"; //on retire la "," et l'espace, et on ajoute une "("
                $duplicateUpdate = substr($duplicateUpdate, 0, -2);
            }

            foreach ($data as $k => $v) { ///pour chaque valeur de key dans data 
                $values .= "?, ";       //on ajoute ",? " a $values
                array_push($valuesToBind, $v);      // tu push dans un tableau la valueToBind et la $v
            }
            $values = substr($values, 0, -2) . "), ";
        }

        $values = substr($values, 0, -2);

        $sql = "INSERT INTO $this->table $columns VALUES $values ON DUPLICATE KEY UPDATE $duplicateUpdate;";

        $this->query($sql, $valuesToBind);
        //la requete en cours va prendre en parametre le $sql plus le [] $valuesToBind
        return $modelList->data(); //renvoi un tableau associatif 
    }

    /**
* permet la suppression (is_deleted = 1) d'une ou plusieurs lignes
* renvoie les lignes deleted sous forme de tableau
* si la mise à jour s'est bien passé (sinon null)
*/
public function softDelete(array $body): ?array {
    $modelList = new ModelList($this->table, $body['items']);

    $idList = $modelList->idList();
    $where = "";

    foreach($idList as $id){     // pour chaque Id dans la idList, on créer une chaine de characte de x "?, " (x = taille de la liste)
      $where .= '?, ';
    }

    $where = substr($where, 0, -2);    //on retire les 2 dernier char (dans ce cas ci : ', ')

    $sql = "UPDATE $this->table SET is_deleted=? WHERE $this->pk IN ($where);";       // on met a jour les lignes ou la condition (where) est remplie. 

    $this->query($sql, [1, ...$idList]);   // on execute notre requete en ajoutant 1 au début car il représente le ? de is_deleted=?

    $sql = "SELECT * FROM $this->table WHERE $this->pk IN ($where);";   // on defini $sql pour qu'il sélectionne la PK dans le $where (on récupére les lignes mise à jour juste avant)

    $resp = $this->query($sql, $idList);  // on definit la variable "$resp" ce que nous retourne le query
    if($resp->result){       //si le resp 
      $rows = $resp->statement->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    }

    return null;
  }
  public function hardDelete(array $body): ?array {
    $modelList = new ModelList($this->table, $body['items']);
    
    $idList = $modelList->idList();
    $where = "";
    
    foreach($idList as $id){
      $where .= '?, ';
    }
    
    $where = substr($where, 0, -2);
    
    $sql = "DELETE FROM $this->table WHERE $this->pk IN ($where);";
    
    $this->query($sql, $idList);
    
    $sql = "SELECT * FROM $this->table WHERE $this->pk IN ($where);";
    
    $resp = $this->query($sql, $idList);
    if($resp->result){
      $rows = $resp->statement->fetchAll(PDO::FETCH_ASSOC);
      return $rows;
    }
    
    return null;
  }
}
