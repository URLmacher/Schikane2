<?php
namespace Models;

class Cards {

    private $mixedCards = [];

    function __construct() {
        $cards = file_get_contents($_SERVER['DOCUMENT_ROOT']."/src/cards.json");
        $cards = json_decode($cards);
        shuffle($cards);
        $this->mixedCards = $cards;
   
    }

    function getCards() {
        return $this->mixedCards;
    }

}
// class Singleton{
//     /**
//      * Die einzig Instanz der Klasse wir hier gespeichert
//      */

//     private static $instance = false;

//     /**
//      * Hier wird die Instanz übergeben
//      */

//     public static function getInstance() {
//         // wenn es keine Instanz gibt, wird eine erzeugt
//         if(self::$instance == false) {
//             return self::$instance = new static();
//         }
//         //Late static binding
//         return self::$instance;
//     }
 
//     /**
//      * Zugriff nicth erlaubt
//      */
//     protected function __construct(){}

//     /**
//      * Keine Klone erlaubt
//      */
//     private function __clone(){}

//     /**
//      * Keine serialization
//      */
//     private function __wakeup(){}
// }
