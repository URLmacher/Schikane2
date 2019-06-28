<?php
namespace Controllers;

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

    /**
     * Hier werden die ganzen fürs Spiel benötigten Arrays befüllt
     * @param array $cards
     */
    function __construct($cards) {
    
        $this->playerHands['p1Hand|0'] = array_splice($cards, 0, 6);
        $this->playerHands['p2Hand|0'] = array_splice($cards, 0, 6);
        $this->drawStacks['p1Drawstack|0'] = array_splice($cards, 0, 14);
        $this->drawStacks['p2Drawstack|0'] = array_splice($cards, 0, 14);
        $this->mainStack = array_splice($cards,0,(count($cards)-1));
        
        
    }

    /**
     * Nur zum testen
     * @return array $playerHands
     */
    function getHands() {
        return $this->playerHands;
    }

    /**
     * Nur zum testen
     * @return array $mainStack
     */
    function getMainStack() {
        return $this->mainStack;
    }

    /**
     * Nur zum testen
     * @return int $currentPlayer
     */
    function getCurrentPlayer() {
        return $this->currentPlayer;
    }


    /**
     * Gibt die Render-Anweisung der auszuteilenden Karten aus
     *
     * @param int $player
     * @return array $msg
     */
    function austeilen($player) {
        if($player == 1) {
            $this->currentPlayer = 1; 
            $msg = [
                'art' => 'austeilen',
                'trgt' => 'p1Hand|0',
                'hand' => $this->playerHands['p1Hand|0'],
                'p1Drawstack' => end($this->drawStacks['p1Drawstack|0']),
                'p2Drawstack' => end($this->drawStacks['p2Drawstack|0'])
            ];
            return $msg;
        }else{
            $msg = [
                'art' => 'austeilen',
                'trgt' => 'p2Hand|0',
                'hand' => $this->playerHands['p2Hand|0'],
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
    function abheben($in,$player) {
        // Nur abheben, wenn Karten im Haupstapel sind und in der Hand weniger als 6 Karten sind
        if($player == 1) {
            if(count($this->mainStack) > 0 && count($this->playerHands['p1Hand|0']) < 6) {
                $tempCard = array_splice($this->mainStack, 0, 1);
                array_push( $this->playerHands[$in] ,$tempCard[0]);
                
                $msg = [
                    'art' => 'abheben',
                    'trgt' => 'p1Hand|0',
                    'card' => $tempCard[0],
                    'count' => count($this->playerHands[$in]),
                    'array' => $this->playerHands[$in]
                ];
                return $msg;
            }else{
                 $msg = [
                    'art' => 'debug',
                    'trgt' => 'p1Hand|0',
                    'card' => '$tempCard[0]'
                ];
                return $msg;
            }
        }else{
            if(count($this->mainStack) > 0 && count($this->playerHands['p2Hand|0']) < 6) {
                $tempCard = array_splice($this->mainStack, 0, 1);
                array_push( $this->playerHands[$in] ,$tempCard[0]);
                
                $msg = [
                    'art' => 'abheben',
                    'trgt' => 'p2Hand|0',
                    'card' => $tempCard[0],
                    'count' => count($this->playerHands[$in]),
                    'array' => $this->playerHands[$in]
                ];
                return $msg;
            }else{
                 $msg = [
                    'art' => 'debug',
                    'trgt' => 'p2Hand|0',
                    'card' => '$tempCard[0]'
                ];
                return $msg;
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
    function moveCards($out,$in,$id,$player) {
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
                'dran' => 'Du bist nicht dran!',
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
                'card' => $card
            ];
            
            return $msg;
        }
        // Hand - Ass - Playarea - wenn leer
        if( $srcArr == $this->playerHands[$out]
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
                'dran' => true,
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
                'card' => $card
            ];
            return $msg;

            if(count($srcArr) == 0) {
                #handleer 
            }
            
        }
        
        // Hand - Karte - Playarea - wenn Karte um 1 höher
        else if( $srcArr == $this->playerHands[$out]
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
                    'abheben' => false,
                    'dran' => true
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
                    'card' => $card,
                    'dran' => true
                ];
                
                return $msg;
            }
            
            
            if(count($srcArr) == 0) {
                #handleer 
            }
        }

        // Hand - Joker - Playarea - wenn nicht leer
        else if( $srcArr == $this->playerHands[$out]
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
                    'abheben' => false,
                    'dran' => true
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
                    'card' => $card,
                    'dran' => true
                ];
                
                return $msg;
            }
            

            if(count($srcArr) == 0) {
                #handleer 
            }
            
        }

        // Hand - Karte - Spieler Eins Ablage - wenn nicht Joker
        else if( $srcArr == $this->playerHands[$out]
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
                'dran' => false,
                'karteGefunden' => array_search($card,$srcArr),
                'wasIstDas' => gettype($card),
                'karti'=>$srcArr,
                'karti2'=>$this->playerHands['p1Hand|0']
            ];
            
            return $msg;
            
        
        }
        // Hand - Karte - Spieler Zwei Ablage - wenn nicht Joker
        else if( $srcArr == $this->playerHands[$out]
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
                'dran' => false,
                'karteGefunden' => array_search($card,$srcArr),
                'wasIstDas' => gettype($card),
                'karti'=>$srcArr,
                'karti2'=>$this->playerHands['p1Hand|0']
            ];
            
            return $msg;
            
        
        }

        // Hand - Joker - Joker-Ablage - wenn Joker
        else if( $srcArr == $this->playerHands[$out]
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
                'dran' => true,
                'trgtArr' => $trgtArr,
                'srcArr' => $srcArr,
            ];
            
            return $msg;
            

            if(count($srcArr) == 0) {
                #handleer 
            }
       
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
                'dran' => true,
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
                    'abheben' => false,
                    'dran' => true
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
                    'trgtfull' => false,
                    'dran' => true
                ];
                
                return $msg;
            
            }
        }

        // Spieler Zwie Ablage - Karte - Playarea - wenn Karte um 1 höher
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
                    'abheben' => false,
                    'dran' => true,
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
                    'trgtfull' => false,
                    'dran' => true
                ];
                
                return $msg;
            
            }
        }

        // Joker-Ablage - Joker - Playarea - wenn nicht leer
        else if( $srcArr == $this->jokerStacks[$out]
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
                    'abheben' => false,
                    'dran' => true
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
                    'trgtfull' => false,
                    'dran' => true
                ];
                
                return $msg;
            }
        
        }

        // Draw-Stack - Ass - Playarea - wenn leer
        else if( $srcArr == $this->drawStacks[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) == 0
            && $card->value == 1 ) 
        {
            
            $tempCard = array_splice($this->drawStacks[$out], array_search($card,$this->drawStacks[$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
        
            if(count($this->drawStacks[$out]) == 0) {
                #gameover  
                $this->changePlayer('gameover');

                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Ass - Playarea - wenn leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'dran' => false
                ];
            
                return $msg;
            }else{
 
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Ass - Playarea - wenn leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'newcard' => end($this->drawStacks[$out]),
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'dran' => true
                ];
            
                return $msg;
            }
         
        }

        // Draw-Stack - Karte - Playarea - wenn um 1 höher
        else if( $srcArr == $this->drawStacks[$out]
            && array_key_exists($in, $this->playArea)
            && count($trgtArr) != 0
            && $card->value != 'any'
            && $trgtArr[count($trgtArr)-1]->value == $card->value - 1 ) 
        {
            
            $tempCard = array_splice($this->drawStacks[$out], array_search($card,$this->drawStacks[$out]),1);
            array_push($this->playArea[$in] , $tempCard[0]);
            
            if(count($this->drawStacks[$out]) == 0) {
                #gameover  
                $this->changePlayer('gameover');

                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Karte - Playarea - wenn um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'dran' => false
                ];
            
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
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false,
                    'dran' => true
                ];
            
                return $msg;
            }else{
                
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Karte - Playarea - wenn um 1 höher',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false,
                    'dran' => true
                ];
                
                return $msg;
            }
    
        }

        // Draw-Stack - Joker - Playarea - wenn nicht leer
        else if( $srcArr == $this->drawStacks[$out]
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
                $this->changePlayer('gameover');

                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'dran' => true
                ];
            
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
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'dran' => true
                ];
            
                return $msg;
            }else{
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Joker - Playarea - wenn nicht leer',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false,
                    'trgtfull' => false,
                    'dran' => true
                ];
                
                return $msg;
            }
       
        }

        // Draw-Stack - Joker - JokerAblage- wenn Joker
        else if( $srcArr == $this->drawStacks[$out] 
            && array_key_exists($in, $this->jokerStacks)
            && $card->value == 'any'  ) 
        {
           
            $tempCard = array_splice($this->drawStacks[$out], array_search($card,$this->drawStacks[$out]),1);
            array_push($this->jokerStacks[$in], $tempCard[0]);
            
            if(count($this->drawStacks[$out]) == 0) {
                #gameover  
                $this->changePlayer('gameover');

                $msg = [
                    'art' => 'gameover',
                    'debug' => 'Draw-Stack - Joker - JokerAblage- wenn Joker',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'abheben' => false,
                    'dran' => false
                ];
            
                return $msg;
            }else{
                $msg = [
                    'art' => 'draw',
                    'debug' => 'Draw-Stack - Joker - JokerAblage- wenn Joker',
                    'src' => $out,
                    'trgt' => $in,
                    'card' => $card,
                    'trgtArr' => $trgtArr,
                    'srcArr' => $srcArr,
                    'newcard' => end($this->drawStacks[$out]),
                    'abheben' => false,
                    'trgtfull' => false,
                    'dran' => true
                 ];
            
                return $msg;
            }
         
        }

        // FEHLER / UNGÜLTIGER ZUG / DEBUG
        else{
            // $tempIndex = array_search($card,$srcArr);
            // $tempCard = array_splice($srcArr, array_search($card,$srcArr),1);
           
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
        // 'dran': schaltet den EventListener ab, je nach dem wer dran is_string
        // 'newcard': zum rendern der Karte im Spiel-Stack, nach dem eine abgehoben wurde 
        // 'p1Drawstack'. beim austeilen
        // 'p2Drawstack'. beim austeilen
    }

    /**
     * Zerlegt eine String/Nummer-Kombination in ihre Einzelteile
     * und gibt die passenden Array-Stapel aus
     * mögliche Eingabe: "p1Hand|0"
     *
     * @param string $stringNum
     * @return array
     */
    function preSelect($stringNum) {
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
    function findCard($arr, $id) {
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
    function backToMainStack(&$arr)
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

    function changePlayer($gameover = false) {
        if($this->currentPlayer == 1) {
            $this->currentPlayer = 2;
        }else if($gameover) {
            $this->currentPlayer = false;
        }else{
            $this->currentPlayer = 1;
        }
    }
   

}
