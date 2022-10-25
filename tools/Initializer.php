<?php namespace Tools;

use Services\DatabaseService;
use Helpers\HttpRequest;
use Exception;
use ErrorException;

class Initializer{

    
public static function start (HttpRequest $request) : bool
{
    
    $isForce = count($request->route) > 1 && $request->routes[1] == 'force';
    try{
        self:writeTableFile($isForce);
    }
    catch(Exception $e){
        return false;
    }
    return true;
}
    public static function writeTableFile(bool $isForce = false) : array
    {
        $tables = DatabaseService::getTables();
        $tableFile = "src/Schemas/Tables.php";
        
        if(file_exists($tableFile) && $isForce){

           if(!unlink($tableFile)){
            throw new Exception("le fichier n'est pas supprimé");
           }
           
        }
        if(!file_exists($tableFile)){
            $fileContent="<?php namespace Schemas ;\r\n\r\n";      // \r\n   fait un retour à la ligne
            $fileContent.="class Table{\r\n\r\n";               // le ".=" rajoute a la ligne precedente sinon ecrase
                foreach ($tables as $table){                    // boucle sur les parametres ()
                    $const = strtoupper($table);            // mets en majuscule le parametre entre ()
                    $fileContent.="\tconst $const = '$table';\r\n";
                }
            $fileContent.="\r\n\r\n }";
        file_put_contents($tableFile, $fileContent);
        }
        return $tables;
    }
}