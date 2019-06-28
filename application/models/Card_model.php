<?php
namespace Models;

class Card_Model {

    private $mixedCards = [];

    function __construct() {
        $cards = file_get_contents($_SERVER['DOCUMENT_ROOT']."assets/cards.json");
        $cards = json_decode($cards);
        shuffle($cards);
        $this->mixedCards = $cards;
   
    }

    function getCards() {
        return $this->mixedCards;
    }

}
