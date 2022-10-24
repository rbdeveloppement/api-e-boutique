<?php namespace Helpers;
class HttpResponse{
/**
* Cette méthode fixe le status code de la réponse HTTP,
* si le status est >= 300 elle appelle la méthode exit
* Cette méthode écrit dans le flux de sortie les data au format json,
* puis elle arrête l'exécution du script
*/
public static function send(array $data, int $status = 200) : void
{
    if($status >= 300){
         self::exit($status); // methode static (self) quand c'est pas static c'est ($this)
        
    }
    echo  json_encode($data); // retourne une chaine de caractère en format JSON
    die;
}
/**
* Cette méthode fixe le status code de la réponse HTTP (>=300)
* puis elle arrête l'exécution du script
*/
public static function exit(int $status = 404) : void
{
    header("HTTP/1.0 ". $status);
    die;

}
}


