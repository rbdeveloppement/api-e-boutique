<?php

namespace Tools;

use Services\DatabaseService;
use Helpers\HttpRequest;
use Exception;
use ErrorException;

class Initializer
{


    public static function start(HttpRequest $request): bool
    {

        $isForce = count($request->route) > 1 && $request->route[1] == 'force';
        try {

            $tables = self::writeTableFile($isForce);           // self::writeTableFile($isForce);  appel la fonction et mettre "$tables=" permet de stocker la fonction dans la valeur $tables
            self::writeSchemasFiles($tables, $isForce);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
    public static function writeTableFile(bool $isForce = false): array
    {
        $tables = DatabaseService::getTables();
        $tableFile = "src/Schemas/Tables.php";

        if (file_exists($tableFile) && $isForce) {

            if (!unlink($tableFile)) {
                throw new Exception("le fichier n'est pas supprimé");
            }
        }
        if (!file_exists($tableFile)) {
            $fileContent = "<?php namespace Schemas ;\r\n\r\n";      // \r\n   fait un retour à la ligne
            $fileContent .= "class Table{\r\n\r\n";               // le ".=" rajoute a la ligne precedente sinon ecrase
            foreach ($tables as $table) {                    // boucle sur les parametres ()
                $const = strtoupper($table);            // mets en majuscule le parametre entre ()
                $fileContent .= "\tconst $const = '$table';\r\n";
            }
            $fileContent .= "\r\n\r\n }";
            if (!file_put_contents($tableFile, $fileContent)) {
                throw new Exception("le fichier n'est pas créé");
            }
        }
        return $tables;
    }

    private static function writeSchemasFiles(array $tables, bool $isForce): void
    {
        foreach ($tables as $table) {
            $className = ucfirst($table);
            $schemaFile = "src/Schemas/$className.php";
            if (file_exists($schemaFile) && $isForce) {
                if (!unlink($schemaFile)) {
                    throw new Exception("le fichier n'est pas supprimé");
                }
            }
            if (!file_exists($schemaFile)) {
                $fileContent = "<?php namespace Schemas ;\r\n\r\n";      // \r\n   fait un retour à la ligne
                $fileContent .= "class $className{\r\n\r\n";               // le ".=" rajoute a la ligne precedente sinon ecrase
                $fileContent.= "\tconst COLUMNS =[\r\n";
               $dbs = new DatabaseService($table);
               $colonnes = $dbs->getSchema();
                
               foreach($colonnes as $colonne){
                $Null = ($colonne['Null']== "NO") ? ('') : ("1");     //ternaire: declaration d'une variable = on recupère les donnees == "condition"  ?  (return1) ou (return2)
                
                
                    $fileContent.="\t\t'".$colonne['Field']."'=> ['type' =>'".$colonne['Type']."' ,'nullable' =>'".$Null."' ,'default' => '".$colonne['Default']."'],\r\n";
               }

                
             
                $fileContent .= "\t];\r\n";
                $fileContent .= "}";
                if(!file_put_contents($schemaFile, $fileContent)){
                    throw new Exception("le fichier n'est pas créé");
                }
            }
        }
    }
}
