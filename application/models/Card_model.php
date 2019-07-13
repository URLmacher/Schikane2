<?php
namespace Models;

class Card_Model {

    private $mixedCards = [];

    /**
     * Holt alle Karten aus einer JSON-File
     * Mischt und speichert Karten
     */
    function __construct() {
        $cards = file_get_contents($_SERVER['DOCUMENT_ROOT']."assets/cards.json");
        $cards = json_decode($cards);
        shuffle($cards);
        $this->mixedCards = $cards;
   
    }

    /**
     * Gibt Karten aus
     *
     * @return array
     */
    function getCards() {
        return $this->mixedCards;
    }

}
