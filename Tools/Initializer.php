<?php namespace Tools;



use Services\DatabaseService;
use Helpers\HttpRequest;
use Exception;


class Initializer {

    public static function start (HttpRequest $request) : bool 
    {
        $isForce = count($request->route) > 1 && $request->routes[1] == 'force';
        try{
            self::writeTableFile($isForce);
        }
        catch(Exception $e){
            return false;
        }
        return true;
    }


public static function writeTableFile(bool $isForce = false) : array
{
$tables = DatabaseService::getTables();
$tableFile = "src/Schemas/Table.php";
if(file_exists($tableFile) && $isForce){
 
   if(!unlink($tableFile)){ // On supprime le fichier .

    throw new Exception("Le fichier n'est pas supprimer");// on créer une exception 
  
   }

}
if(!file_exists($tableFile)){
 $fileContent="<?php namespace Schemas ;\r\n\r\n"; // \r\n fait un saut de ligne 
 $fileContent.="class Table{\r\n\r\n"; // Le ".=" rajoute à la ligne précédente sinon écrase
    foreach ($tables as $table) { // boucle sur chaque tables de la BDD
        $const = strtoupper($table); // met en majuscule le paramétre 
        $fileContent.="\tconst $const = '$table';\r\n"; //t veut dire tabulation
        
    }
    $fileContent.="\r\n\r\n }"; // } fermeture de l'accolade 
    file_put_contents($tableFile, $fileContent);

    throw new Exception("Le fichier n'a pas été créer");


}
return $tables;
}

}