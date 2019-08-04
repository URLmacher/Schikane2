<?php
use Controllers\GameController;
use Models\Card_model;
require_once('vendor/autoload.php');

$obj= new Card_model;
$cards = $obj->getCards();
$game = new GameController($cards);

  class Connection{

    function send( $message, $who ) {
      global $clientSocketArr;
      //$message = $this->seal( json_encode( array('msg'=>$message) ) );

      $message = $this->seal( $message );
      // echo $message;
      $len = strlen( $message );
      foreach( $clientSocketArr as $socketId => $clientSocket ) {
        if ( $who == 'all' || $who ==  $socketId ) {
          @socket_write( $clientSocket, $message, $len );
        }
      }
      return true;
    }

    function unseal($socketData) {
  		$length = ord($socketData[1]) & 127;
  		if($length == 126) {
  			$masks = substr($socketData, 4, 4);
  			$data = substr($socketData, 8);
  		}
  		elseif($length == 127) {
  			$masks = substr($socketData, 10, 4);
  			$data = substr($socketData, 14);
  		}
  		else {
  			$masks = substr($socketData, 2, 4);
  			$data = substr($socketData, 6);
  		}
  		$socketData = "";
  		for ($i = 0; $i < strlen($data); ++$i) {
  			$socketData .= $data[$i] ^ $masks[$i%4];
  		}
  		return $socketData;
  	}

  	function seal($socketData) {
  		$b1 = 0x80 | (0x1 & 0x0f);
  		$length = strlen($socketData);

  		if($length <= 125)
  			$header = pack('CC', $b1, $length);
  		elseif($length > 125 && $length < 65536)
  			$header = pack('CCn', $b1, 126, $length);
  		elseif($length >= 65536)
  			$header = pack('CCNN', $b1, 127, $length);
  		return $header.$socketData;
  	}

  	function doHandshake($received_header,$client_socket_resource, $host_name, $port) {
  		$headers = array();
  		$lines = preg_split("/\r\n/", $received_header);
  		foreach($lines as $line)
  		{
  			$line = chop($line);
  			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
  			{
  				$headers[$matches[1]] = $matches[2];
  			}
  		}

  		$secKey = $headers['Sec-WebSocket-Key'];
  		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
  		$buffer  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
  		"Upgrade: websocket\r\n" .
  		"Connection: Upgrade\r\n" .
  		"WebSocket-Origin: $host_name\r\n" .
  		"WebSocket-Location: ws://$host_name:$port/demo/shout.php\r\n".
  		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
  		socket_write($client_socket_resource,$buffer,strlen($buffer));
  	}

  }
  $chatHandler = new Connection();

  define( 'HOST', 'localhost' );
  define( 'PORT', '5001' );
  $null = NULL;

  $clientid = 1;

  $socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
  socket_set_option( $socket, SOL_SOCKET, SO_REUSEADDR, 1);
  socket_bind( $socket, 0, PORT );
  socket_listen( $socket );

  $clientSocketArr = array( $socket );

  echo 'SocketServer auf Port 5001 läuft.
';

  

  $socketUser = array();

  while( true ) {
    //sleep(1);
    $newSocketArr = $clientSocketArr;
    socket_select( $newSocketArr, $null, $null, 0, 10);
    if ( in_array( $socket, $newSocketArr ) ) {
      echo "neue Verbindung\n";
      $newSocket = socket_accept( $socket );
      $clientSocketArr[$clientid++] = $newSocket;

      $header = socket_read( $newSocket, 1024 );
      $chatHandler->doHandshake( $header, $newSocket, HOST, PORT);

      $newIndex = array_search( $socket, $newSocketArr );
      unset( $newSocketArr[$newIndex] );

    }
    foreach( $newSocketArr as $socketID => $res ) {
      while( @socket_recv( $res, $socketData, 1024, 0 ) >= 1 ) {

        $message = $chatHandler->unseal( $socketData );
        echo "Client #".$socketID." Nachricht: ".$message."\n";
        $msg = json_decode( $message );
        $socketUser = [];

        if ( !isset( $msg->art  ) ) break; // wenn Browserfenster geschlossen wird
        $socketUser[$socketID] = 'usernamehier';
        // HIER SPIELT´S SICH AB
        $who = 'all';
        $returnMsg = new stdClass();
        if($msg->art == 'spielervergabe'){
           if($socketID % 2 == 0){
            $returnMsg = createMessage([
              'art' => 'spielervergabe'
              ]);
              $who = $socketID;
          }
        }else if( $msg->art == 'anmelden'){
          if($socketID % 2 == 0){
            $returnMsg = createMessage($game->registerPlayers($msg->username,2));
            $who = 'all';
          }else{
            $returnMsg = createMessage($game->registerPlayers($msg->username,1));
            $who = 'all';
          }
        }else if( $msg->art == 'austeilen') {
          foreach($socketUser as $client => $value) {
          
            if($client % 2 == 0){
              $returnMsg = createMessage($game->austeilen(2,$msg->username));
              $socketUser[$socketID] = $msg->username;
              $who = $socketID;
  
            }else{
              $returnMsg = createMessage($game->austeilen(1,$msg->username));
              $who = $socketID;
            }
          }
        }else if($msg->art == 'move'){
          if($socketID % 2 == 0){
            $returnMsg = createMessage($game->moveCards($msg->src,$msg->trgt,(int) $msg->id,2));
            $who = 'all';
          }else{
            $returnMsg = createMessage($game->moveCards($msg->src,$msg->trgt,(int) $msg->id,1));
            $who = 'all';
          }
        }else if($msg->art == 'abheben'){
          if($socketID % 2 == 0){
            $returnMsg = createMessage($game->abheben($msg->trgt,2)); 
            $who = $socketID;
          }else{
            $returnMsg = createMessage($game->abheben($msg->trgt,1));
            $who = $socketID;
          }
        }

        $chatHandler->send( json_encode($returnMsg), $who );
        break 2;
      }

      // quit
      $socketData = @socket_read($res, 1024, PHP_NORMAL_READ);
      if ($socketData === false) {
        // $chatHandler->send(  $socketUser[ $socketID ]. ' hat sich ausgeloggt.', 'all')  ;
        unset($clientSocketArr[$socketID]);
        unset($socketUser[$socketID]);
      }
    }



  }
  socket_close( $socket );

function createMessage($arr) {
    $msg = new stdClass();
    foreach($arr as $key => $value) {
        $msg->$key = $value;
    }
    return $msg;
}

//   run command: php connection.php