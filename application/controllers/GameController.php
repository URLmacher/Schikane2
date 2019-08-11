<?php
namespace Controllers;

use \PDO;

/**
 * Kümmerts sich um alle Spielerelevanten Angelegenheiten
 * Speichern von Karten,
 * Austeilungen,
 * Überprüfungen von Zügen
 * Informierungen der Spieler
 * Punktestände uvm.
 */
class GameController {

    private $playArea = [
        'playarea|0' => [],
        'playarea|1' => [],
        'playarea|2' => [],
        'playarea|3' => [],
        'playarea|4' => [],
        'playarea|5' => [],
        'playarea|6' => [],
        'playarea|7' => []];
    private $playerHands = [
        'p1Hand|0' => [],
        'p2Hand|0' => []
    ];
    private $mainStack = [];
    private $drawStacks = [
        'p1Drawstack|0' => [],
        'p2Drawstack|0' => []
    ];
    private $jokerStacks = [
        'p1Jokerablage|0' => [],
        'p2Jokerablage|0' => [],
    ];
    private $trayStacks =[
        'p1Ablage' => [ 
            'p1Ablage|0' => [],
            'p1Ablage|1' => [],
            'p1Ablage|2' => [],
            'p1Ablage|3' => []],
        'p2Ablage' => [ 
            'p2Ablage|0' => [],
            'p2Ablage|1' => [],
            'p2Ablage|2' => [],
            'p2Ablage|3' => []],
    ];
    private $currentPlayer; //Zum testen
    private $playerOne = false;
    private $playerTwo = false;

    /**
     * Hier werden die ganzen fürs Spiel benötigten Arrays befüllt
     * @param array $cards
     */
    public function __construct($cards) {
    
        $this->playerHands['p1Hand|0'] = array_splice($cards, 0, 6);
        $this->playerHands['p2Hand|0'] = array_splice($cards, 0, 6);
        // $this->drawStacks['p1Drawstack|0'] = array_splice($cards, 0, 14);
        // $this->drawStacks['p2Drawstack|0'] = array_splice($cards, 0, 14);
        // Zur Demonstration nur eine Karte im Playstack
        $this->drawStacks['p1Drawstack|0'] = array_splice($cards, 0, 1);
        $this->drawStacks['p2Drawstack|0'] = array_splice($cards, 0, 1);
        $this->mainStack = array_splice($cards,0,(count($cards)-1));
          
    }


    /**
     * Meldet die Spieler an
     * Gibt die Usernamen an alle zurück
     *
     * @param string $playerName
     * @param int $client
     * @return void
     */
    public function registerPlayers($playerName,$client){
         if($client == 1) {
            $this->currentPlayer = 1; 
            $this->playerOne = $playerName;
            $msg = [
                'art' => 'anmelden',
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                'player2Points' => count($this->drawStacks['p2Drawstack|0'])
            ];
            return $msg;
        }else{
            $this->playerTwo = $playerName;
            $msg = [
                'art' => 'anmelden',
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                'player2Points' => count($this->drawStacks['p2Drawstack|0'])
            ];
            return $msg;
        }
    }

    /**
     * Gibt die Render-Anweisung der auszuteilenden Karten aus
     *
     * @param int $player
     * @return array $msg
     */
    public function austeilen($client,$playerName) {
        if($client == 1) {
            $this->currentPlayer = 1; 
            $this->playerOne = $playerName;
            $msg = [
                'art' => 'austeilen',
                'trgt' => 'p1Hand|0',
                'hand' => $this->playerHands['p1Hand|0'],
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'msgP1' => 'dran',
                'msgP2' => 'nicht dran',
                'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                'p1Drawstack' => end($this->drawStacks['p1Drawstack|0']),
                'p2Drawstack' => end($this->drawStacks['p2Drawstack|0'])
            ];
            return $msg;
        }else{
            $this->playerTwo = $playerName;
            $msg = [
                'art' => 'austeilen',
                'trgt' => 'p2Hand|0',
                'hand' => $this->playerHands['p2Hand|0'],
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'msgP1' => 'dran',
                'msgP2' => 'nicht dran',
                'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                'p1Drawstack' => end($this->drawStacks['p1Drawstack|0']),
                'p2Drawstack' => end($this->drawStacks['p2Drawstack|0'])
            ];
            return $msg;
        }
    }

    /**
     * Gibt die Render-Anweisung der abgehobenen Karten aus
     *
     * @param int $player
     * @return array $msg
     */
    public function abheben($in,$player) {
        // Nur abheben, wenn Karten im Haupstapel sind und in der Hand weniger als 6 Karten sind
        if($player == 1) {
            if(count($this->mainStack) > 0 && count($this->playerHands['p1Hand|0']) < 6) {
                $cardCount = 6 - count($this->playerHands['p1Hand|0']);

                if($cardCount > count($this->mainStack)) {
                     $msg = [
                        'art' => 'unentschieden'
                    ];
                    return $msg;
                }else{
                    $tempCards = array_splice($this->mainStack, 0, $cardCount);
                    foreach( $tempCards as $tempCard) {
                        array_push( $this->playerHands[$in] ,$tempCard);
                    }
                    
                    $msg = [
                        'art' => 'abheben',
                        'trgt' => 'p1Hand|0',
                        'cards' => $tempCards,
                        'count' => count($this->playerHands[$in]),
                        'array' => $this->playerHands[$in],
                        'cardCount' => $cardCount
                    ];
                    return $msg;
                }
            }
        }else if ($player == 2){
            if(count($this->mainStack) > 0 && count($this->playerHands['p2Hand|0']) < 6) {
                $cardCount = 6 - count($this->playerHands['p2Hand|0']);

                if($cardCount > count($this->mainStack)) {
                    $msg = [
                        'art' => 'unentschieden'
                    ];
                    return $msg;
                }else{
                    $tempCards = array_splice($this->mainStack, 0, $cardCount);
                    foreach( $tempCards as $tempCard) {
                        array_push( $this->playerHands[$in] ,$tempCard);
                    }
                    
                    $msg = [
                        'art' => 'abheben',
                        'trgt' => 'p2Hand|0',
                        'cards' => $tempCards,
                        'count' => count($this->playerHands[$in]),
                        'array' => $this->playerHands[$in],
                        'cardCount' => $cardCount
                    ];
                    return $msg;
                }
            }
        }
    }

    /**
     * Überprüft Züge auf Gültigkeit,
     * verschiebt Karten in die jeweiligen Array-Stapel,
     * gibt Render-Anweisungen zurück
     *
     * @param string $out
     * @param string $in
     * @param int $id
     * @return array $msg
     */
    public function moveCards($out,$in,$id,$player) {
        $srcArr  = $this->preSelect($out);
        $trgtArr = $this->preSelect($in);;
        $card = $this->findCard($srcArr,$id);
       
        // Ob der Spieler überhaupst dran ist
        if ($player != $this->currentPlayer) {
         
            $msg = [
                'art' => 'dran',
                'debug' => 'Hand - Ass - Playarea - wenn leer',
                'src' => $out,
                'trgt' => $in,
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'msgP1' => 'nicht dran',
                'msgP2' => 'nicht dran',
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
                'card' => $card
            ];
            
            return $msg;
        }
        // Hand - Ass - Playarea - wenn leer
        if( $srcArr == @$this->playerHands[$out]
            && array_key_exists($in, $this->playArea)
            && $card->value == 1
            && count($trgtArr) == 0) 
        {
            
            $tempCard = array_splice($this->playerHands[$out], array_search($card,$this->playerHands[$out]),1);
            array_push( $this->playArea[$in], $tempCard[0]);
        
            $msg = [
                'art' => 'move',
                'debug' => 'Hand - Ass - Playarea - wenn leer',
                'src' => $out,
                'trgt' => $in,
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1),
                'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2),
                'card' => $card,
            ];
            return $msg;
            
        }
        
        // Hand - Karte - Playarea - wenn Karte um 1 höher
        else if( $srcArr == @$this->playerHands[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0 
            && $card->value != 'any'
            && $trgtArr[count($trgtArr)-1]->value == $card->value -1 )
        {
            
            $tempCard = array_splice($this->playerHands[$out], array_search($card,$this->playerHands[$out]),1);
            array_push( $this->playArea[$in], $tempCard[0]);

            if (count($trgtArr) == 12) {
                #trgtstackvoll
                $this->backToMainStack($this->playArea[$in]);

                $msg = [
                    'art' => 'stackfull', 
                    'debug' => 'Hand - Karte - Playarea - wenn Karte um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1),
                    'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2),
                    'abheben' => false
                ];
            
                return $msg;
            }else{

                $msg = [
                    'art' => 'move',
                    'debug' => 'Hand - Karte - Playarea - wenn Karte um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1),
                    'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2),
                    'card' => $card
                ];
                
                return $msg;
            }
           
        }

        // Hand - Joker - Playarea - wenn nicht leer
        else if( $srcArr == @$this->playerHands[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0 
            && count($trgtArr) != 12
            && $card->value == 'any' )
        {
            $prevCard = end($this->playArea[$in]);
            $card->value = $prevCard->value + 1;
            $tempCard = array_splice($this->playerHands[$out], array_search($card,$this->playerHands[$out]),1);
            array_push( $this->playArea[$in], $tempCard[0]);

            if (count($trgtArr) == 12) {
                #trgtstackvoll
                $this->backToMainStack($this->playArea[$in]);

                $msg = [
                    'art' => 'stackfull',
                    'debug' => 'Hand - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1),
                    'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2),
                    'abheben' => false
                ];
            
                return $msg;
            }else{

                $msg = [
                    'art' => 'move',
                    'debug' => 'Hand - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1),
                    'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2),
                    'card' => $card
                ];
                
                return $msg;
            }
            
        }

        // Hand - Karte - Spieler Eins Ablage - wenn nicht Joker
        else if( $srcArr == @$this->playerHands[$out]
            && array_key_exists($in, $this->trayStacks['p1Ablage'])
            && $card->value != 'any' )
        {
            #nächsteRunde
            $tempCard = array_splice($this->playerHands[$out], array_search($card,$this->playerHands[$out]),1);
            array_push( $this->trayStacks['p1Ablage'][$in], $tempCard[0]);
            $this->changePlayer();

            $msg = [
                'art' => 'move',
                'debug' => 'Hand - Karte - Ablage - wenn nicht Joker',
                'src' => $out,
                'trgt' => $in,
                'card' => $card,
                'abheben' => true,
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1,'roundend'),
                'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2,'roundend'),
                'msgP1' => 'nicht dran',
                'msgP2' => 'dran',
            ];
            
            return $msg;
            
        
        }
        // Hand - Karte - Spieler Zwei Ablage - wenn nicht Joker
        else if( $srcArr == @$this->playerHands[$out]
            && array_key_exists($in, $this->trayStacks['p2Ablage'])
            && $card->value != 'any' )
        {
            #nächsteRunde
            $tempCard = array_splice($this->playerHands[$out], array_search($card,$this->playerHands[$out]),1);
            array_push( $this->trayStacks['p2Ablage'][$in], $tempCard[0]);
            $this->changePlayer();
            
            $msg = [
                'art' => 'move',
                'debug' => 'Hand - Karte - Ablage - wenn nicht Joker',
                'src' => $out,
                'trgt' => $in,
                'card' => $card,
                'abheben' => true,
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1,'roundend'),
                'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2,'roundend'),
                'msgP1' => 'dran',
                'msgP2' => 'nicht dran',
            ];
            
            return $msg;
            
        
        }

        // Hand - Joker - Joker-Ablage - wenn Joker
        else if( $srcArr == @$this->playerHands[$out]
            && array_key_exists($in, $this->jokerStacks)
            && $card->value == 'any' )
        {
            
            $tempCard = array_splice($this->playerHands[$out], array_search($card,$this->playerHands[$out]),1);
            array_push( $this->jokerStacks[$in], $tempCard[0]);
            
            $msg = [
                'art' => 'move',
                'debug' => 'Hand - Joker - Joker-Ablage - wenn Joker',
                'src' => $out,
                'trgt' => $in,
                'card' => $card,
                'abheben' => false,
                'trgtfull' => false,
                'player1Username' => $this->playerOne,
                'player2Username' => $this->playerTwo,
                'abhebenP1' => $this->abhebenAllowed($this->playerHands[$out],$out,1),
                'abhebenP2' => $this->abhebenAllowed($this->playerHands[$out],$out,2),
                'dran' => true,
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
            ];
            
            return $msg;
       
        }

        // Spieler Eins Ablage - Ass - Playarea - wenn leer
        else if( array_key_exists($out, $this->trayStacks['p1Ablage'])
            && array_key_exists($in, $this->playArea)
            && $card->value == 1
            && count($trgtArr) == 0) 
        {
            $tempCard = array_splice($this->trayStacks['p1Ablage'][$out], array_search($card,$this->trayStacks['p1Ablage'][$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
         
            $msg = [
                'art' => 'move',
                'debug' => 'Ablage - Ass - Playarea - wenn leer',
                'src' => $out,
                'trgt' => $in,
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
                'card' => $card,
                'abheben' => false
            ];
            
            return $msg;
            
        }

        // Spieler Zwei Ablage - Ass - Playarea - wenn leer
        else if( array_key_exists($out, $this->trayStacks['p2Ablage'])
            && array_key_exists($in, $this->playArea)
            && $card->value == 1
            && count($trgtArr) == 0) 
        {
            $tempCard = array_splice($this->trayStacks['p2Ablage'][$out], array_search($card,$this->trayStacks['p2Ablage'][$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
         
            $msg = [
                'art' => 'move',
                'debug' => 'Ablage - Ass - Playarea - wenn leer',
                'src' => $out,
                'trgt' => $in,
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
                'card' => $card,
                'dran' => true,
                'abheben' => false
            ];
            
            return $msg;
            
        }

        // Spieler Eins Ablage - Karte - Playarea - wenn Karte um 1 höher
        else if( array_key_exists($out, $this->trayStacks['p1Ablage'])
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0
            && $trgtArr[count($trgtArr)-1]->value == $card->value - 1 ) 
        {
         
            $tempCard = array_splice($this->trayStacks['p1Ablage'][$out], array_search($card,$this->trayStacks['p1Ablage'][$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);

            if (count($trgtArr) == 12) {
                #trgtstackvoll
                $this->backToMainStack($this->playArea[$in]);

                $msg = [
                    'art' => 'stackfull',
                    'debug' => 'Ablage - Karte - Playarea - wenn Karte um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
            
                return $msg;
            }else{
                $msg = [
                    'art' => 'move',
                    'debug' => 'Ablage - Karte - Playarea - wenn Karte um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'trgtfull' => false
                ];
                
                return $msg;
            
            }
        }

        // Spieler Zwei Ablage - Karte - Playarea - wenn Karte um 1 höher
        else if( array_key_exists($out, $this->trayStacks['p2Ablage'])
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0
            && $trgtArr[count($trgtArr)-1]->value == $card->value - 1 ) 
        {
         
            $tempCard = array_splice($this->trayStacks['p2Ablage'][$out], array_search($card,$this->trayStacks['p2Ablage'][$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);

            if (count($trgtArr) == 12) {
                #trgtstackvoll
                $this->backToMainStack($this->playArea[$in]);

                $msg = [
                    'art' => 'stackfull',
                    'debug' => 'Ablage - Karte - Playarea - wenn Karte um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
            
                return $msg;
            }else{
                $msg = [
                    'art' => 'move',
                    'debug' => 'Ablage - Karte - Playarea - wenn Karte um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'trgtfull' => false
                ];
                
                return $msg;
            
            }
        }

        // Joker-Ablage - Joker - Playarea - wenn nicht leer
        else if( $srcArr == @$this->jokerStacks[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0
            && count($trgtArr) != 12
            && $card->value == 'any' ) 
        {
           
            $prevCard = end($this->playArea[$in]);
            $card->value = $prevCard->value + 1;
            $tempCard = array_splice($this->jokerStacks[$out], array_search($card,$this->jokerStacks[$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
            
            if (count($trgtArr) == 12) {
                #trgtstackvoll
                $this->backToMainStack($this->playArea[$in]);

                $msg = [
                    'art' => 'stackfull',
                    'debug' => 'Joker-Ablage - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
            
                return $msg;
            }else{
                $msg = [
                    'art' => 'move',
                    'debug' => 'Joker-Ablage - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'trgtfull' => false
                ];
                
                return $msg;
            }
        
        }

        // Draw-Stack - Ass - Playarea - wenn leer
        else if( $srcArr == @$this->drawStacks[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) == 0
            && $card->value == 1 ) 
        {
            
            $tempCard = array_splice($this->drawStacks[$out], array_search($card,$this->drawStacks[$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
        
            if(count($this->drawStacks[$out]) == 0) {
                #gameover  
                $this->whoWon($this->currentPlayer, $this->playerOne, $this->playerTwo);
                
                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Ass - Playarea - wenn leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'winner' => $this->currentPlayer,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
                
                $this->changePlayer('gameover');

                return $msg;
            }else{
 
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Ass - Playarea - wenn leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'newcard' => end($this->drawStacks[$out]),
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
            
                return $msg;
            }
         
        }

        // Draw-Stack - Karte - Playarea - wenn um 1 höher
        else if( $srcArr == @$this->drawStacks[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0
            && $card->value != 'any'
            && $trgtArr[count($trgtArr)-1]->value == $card->value - 1 ) 
        {
            
            $tempCard = array_splice($this->drawStacks[$out], array_search($card,$this->drawStacks[$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
            
            if(count($this->drawStacks[$out]) == 0) {
                #gameover  
                $this->whoWon($this->currentPlayer, $this->playerOne, $this->playerTwo);

                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Karte - Playarea - wenn um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'winner' => $this->currentPlayer,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
                
                $this->changePlayer('gameover');

                return $msg;
            }else if (count($trgtArr) == 12) {
                #trgtstackvoll
                $this->backToMainStack($this->playArea[$in]);

                $msg = [
                    'art' => 'stackfull',
                    'debug' => 'Draw-Stack - Karte - Playarea - wenn um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false
                ];
            
                return $msg;
            }else{
                
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Karte - Playarea - wenn um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false
                ];
                
                return $msg;
            }
    
        }

        // Draw-Stack - Joker - Playarea - wenn nicht leer
        else if( $srcArr == @$this->drawStacks[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0
            && count($trgtArr) != 12
            && $card->value == 'any' ) 
        {
            
            $prevCard = end($this->drawStacks[$out]);
            $card->value = $prevCard->value + 1;
            $tempCard = array_splice($this->drawStacks[$out], array_search($card,$this->drawStacks[$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
         
            if(count($this->drawStacks[$out]) == 0) {
                #gameover  
                $this->whoWon($this->currentPlayer, $this->playerOne, $this->playerTwo);

                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'winner' => $this->currentPlayer,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
                
                $this->changePlayer('gameover');

                return $msg;
            }else if (count($trgtArr) == 12) {
                #trgtstackvoll
                $this->backToMainStack($this->playArea[$in]);

                $msg = [
                    'art' => 'stackfull',
                    'debug' => 'Draw-Stack - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];
            
                return $msg;
            }else{
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),                    
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false,
                    'trgtfull' => false
                ];
                
                return $msg;
            }
       
        }

        // Draw-Stack - Joker - JokerAblage- wenn Joker
        else if( $srcArr == @$this->drawStacks[$out] 
            && array_key_exists($in, $this->jokerStacks)
            && $card->value == 'any'  ) 
        {
           
            $tempCard = array_splice($this->drawStacks[$out], array_search($card,$this->drawStacks[$out]),1);
            array_push($this->jokerStacks[$in], $tempCard[0]);
            
            if(count($this->drawStacks[$out]) == 0) {
                #gameover  
                $this->whoWon($this->currentPlayer, $this->playerOne, $this->playerTwo);

                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Joker - JokerAblage- wenn Joker',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),
                    'winner' => $this->currentPlayer,
                    'player1Username' => $this->playerOne,
                    'player2Username' => $this->playerTwo,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false
                ];

                $this->changePlayer('gameover');
                
                return $msg;
            }else{
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Joker - JokerAblage- wenn Joker',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'player1Points' => count($this->drawStacks['p1Drawstack|0']),
                    'player2Points' => count($this->drawStacks['p2Drawstack|0']),                    
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false,
                    'trgtfull' => false
                 ];
            
                return $msg;
            }
         
        }

        // FEHLER / UNGÜLTIGER ZUG / DEBUG
        else{
        
            $msg = [
                'art' => 'debug',
                'debug' => 'FEHLER, ungültiger zug',
                'srci' => $out,
                'trgti' => $in,
                'trgtArri' => $trgtArr,
                'srcArri' => $srcArr,
                'cardi' => $card,
                'idi' => $id,
                'ablage' => $this->trayStacks['p1Ablage']
            ];
            
            return $msg;
            
        }

        // Benötigte Info:
        // 'art': zum einordnen
        // 'src': zum entfernen der Karte im DOM
        // 'trgt': zum rendern der Karte im DOM
        // 'card': zum rendern der Karte im DOM (nur ID wird benötigt)
        // 'newcard': zum rendern der Karte im Spiel-Stack, nach dem eine abgehoben wurde 
        // 'p1Drawstack'. beim austeilen
        // 'p2Drawstack'. beim austeilen
        // 'player1Username': Name Spieler/Client 1
        // 'player2Username': Name Spieler/Client 2
        // 'abhebenP1': bool, ob er abheben darf
        // 'abhebenP2': bool, ob er abheben darf
        // 'msgP1': 'dran',
        // 'msgP2': 'nicht dran',
        // 'winner': wer gewonnen hat/ clientnr
    }

    /**
     * Zerlegt eine String/Nummer-Kombination in ihre Einzelteile
     * und gibt die passenden Array-Stapel aus
     * mögliche Eingabe: "p1Hand|0"
     *
     * @param string $stringNum
     * @return array
     */
    private function preSelect($stringNum) {
        $arr = explode('|', $stringNum);
        $string = $arr[0];
        $num = (int)$arr[1];

        switch ($string) {
            case 'p1Hand':
                return $this->playerHands[$stringNum];
                break;
            case 'p2Hand':
                return $this->playerHands[$stringNum];
                break;
            case 'playarea':
            return $this->playArea[$stringNum];
                break;
            case 'p1Jokerablage':
                return $this->jokerStacks[$stringNum];
                break;
            case 'p2Jokerablage':
                return $this->jokerStacks[$stringNum];
                break;
            case 'p1Drawstack':
                return $this->drawStacks[$stringNum];
                break;
            case 'p2Drawstack':
                return $this->drawStacks[$stringNum];
                break;
            case 'p1Ablage':
                return $this->trayStacks[$string][$stringNum];
                break;
            case 'p2Ablage':
                return $this->trayStacks[$string][$stringNum];
                break;
            default:
                return 'not found: '.$stringNum;
                break;
        }
    }

    /**
     * Sucht die Karte aus einem Array und gibt sie aus
     *
     * @param array $arr
     * @param int $id
     * @return stdObj $el
     */
    private function findCard($arr, $id) {
        foreach($arr as $el => $val) {
            if($val->id == $id) {    
                return $val;
            }
        }
        return false;
    }

    /**
     * Sortiert Karten aus der Playarea wieder in den Hauptstapel ein
     * Entwertet Joker-Karten
     * mischt die Karten
     *
     * @param array $arr
     * @return void
     */
    private function backToMainStack(&$arr)
    {
        foreach($arr as $card) {
            if($card->joker == true) {
                $card->value = 'any';
                array_push($this->mainStack, $card);
            }else{
                array_push($this->mainStack, $card);
            }
        }
        shuffle($this->mainStack);
        $arr = [];
    }

    /**
     * Wechselt Spieler ab bei Rundenende
     * Keiner ist dran, wenn das Spiel vorbei ist
     *
     * @param boolean $gameover
     * @return void
     */
    private function changePlayer($gameover = false) {
        if($this->currentPlayer == 1) {
            $this->currentPlayer = 2;
        }else if($gameover) {
            $this->currentPlayer = false;
        }else{
            $this->currentPlayer = 1;
        }
    }
   
    /** 
     * Überprüft welchem der Spieler das Abheben erlaubt wird
     * anhand dem betroffenen Array
     *
     * @param string $srcArr
     * @param string $key
     * @param int $playerNum
     * @param bool $roundend
     * @return bool
     */
    public function abhebenAllowed($srcArr,$key,$playerNum,$roundend = false) {
        
        if( $roundend ) {
            $otherPlayerKey = 'p'.$playerNum.'Hand|0';
            if(strpos( $key, $playerNum ) == false 
            && count($this->playerHands[$otherPlayerKey]) < 6 
            && $playerNum == $this->currentPlayer) {
                return true;
            }
            return false;
        }
        if( strpos( $key, strval($playerNum) ) != false && count($srcArr) == 1 ) {
           return true;
        }
        return false;
    }

    /**
     * Findet raus, wergewonnen hat
     * ruft Punkteupdatung auf
     *
     * @param int $currentPlayer
     * @param string $player1
     * @param string $player2
     * @return void
     */
    private function whoWon($currentPlayer, $player1, $player2) {
        if($currentPlayer == 1) {
            $this->updatePoints($player1,$player2);
        }else if($currentPlayer == 2) {
            $this->updatePoints($player2,$player1);
        }
    }

    /**
     * Aktualisiert die Siege und Verlierungen der Spieler nach Spielende
     *
     * @param string $winner
     * @param string $loser
     * @return void
     */
    private function updatePoints($winner,$loser) {
        $host =  'localhost';
        $user = 'root';
        $password = '';
        $dbname = 'Schikane';
        $dsn = 'mysql:host='. $host .';dbname='. $dbname;

        $pdo = new \PDO($dsn, $user, $password);
       
        $sql = 'SELECT games_won FROM users WHERE user_name = :winner';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['winner' => $winner]);
        $winnerWins = $stmt->fetchAll();
        $winnerWins = $winnerWins[0]['games_won'];
        $winnerWins ++;

        $sql = 'SELECT games_lost FROM users WHERE user_name = :loser';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['loser' => $loser]);
        $loserLoss = $stmt->fetchAll();
        $loserLoss  = $loserLoss[0]['games_lost'];
        $loserLoss ++;

        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = 'UPDATE users SET games_won = :points WHERE user_name = :winner';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['points' => $winnerWins, 'winner' => $winner]);

        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = 'UPDATE users SET games_lost = :points WHERE user_name = :loser';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['points' => $loserLoss, 'loser' => $loser]);
    }

}
