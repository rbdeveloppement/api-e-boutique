<?php

namespace Helpers;

class HttpRequest
{
    public string $method;
    public array $route;
    /**
     * Récupère la méthode (ex : GET, POST, etc ...)
     * et les différentes partie de la route sous forme de tableau
     * (ex : ["product", 1])
     */
    private function __construct()
    {
        //voir $_SERVER['REQUEST_METHOD'] et $_SERVER["REQUEST_URI"]
        $this->method = $_SERVER['REQUEST_METHOD'];                         // récupère la methode "method" et lui assigne la valeur stockée dans $_SERVER['REQUEST_METHOD']
        $this->route = explode("/", trim($_SERVER['REQUEST_URI'], "/"));   // recupère la route "route" avec le $_SERVER['REQUEST_URI'] et trim supprime les "/" qui entourents la chaine de caractere
        
    }
    private static $instance;
    
    
    /**
     * Crée une instance de HttpRequest si $instance est null
     * puis retourne cette instance
     */
    public static function instance(): HttpRequest
    {if(!isset(self::$instance)){  // 1) si la donnée existe alors 
        self::$instance = new HttpRequest();        // 2) je créer une nouvelle instance 
    }
        //...
        return self::$instance;
    }
}

