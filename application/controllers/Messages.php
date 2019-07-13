<?php
    class Messages extends CI_Controller{

        /**
         * Holt alle Nachrichten eines Users
         *
         * @return JSON
         */
        public function index() {
            $msgs = $this->Message_model->get_messages($this->session->userdata('user_id'));
            $data['msgs'] = $msgs;
            echo json_encode($data);
        }

        /**
         * Holt eine einzelne Nachricht
         * Markiert Nachricht als gelesen
         *
         * @param int $msg_id
         * @return JSON
         */
        public function view($msg_id) {
            $msg = $this->Message_model->message_seen($msg_id);
            $msg = $this->Message_model->get_message($msg_id);
            if($msg[0]['recipient_id'] == $this->session->userdata('user_id') ) {
                $data['msg'] = $msg;
                echo json_encode($data);
            }
        }

        /**
         * Verschickt eine Nachricht
         * Überprüft Werte 
         * Gibt Fehler zurück
         *
         * @return JSON
         */
        public function create() {
            $recipient = false;
            $title = false;
            $body = false;
            $data['success'] = false;
            $data['errors'] = [];

            if(!empty($_POST['recipient'])){
                if(!$this->User_model->check_username_exists($_POST['recipient'])) {
                    $recipient = $this->User_model->get_user_id($_POST['recipient']);
                }else{
                    $data['errors']['recipient'] = 'Username existiert nicht';
                }
            }else{
                $data['errors']['recipient'] = 'Bitte Namen eingeben';
            }

            if(!empty($_POST['title'])){
                $title = filter_var(trim($_POST['title']),FILTER_SANITIZE_STRING); 
            }else{
                $data['errors']['title'] = 'Bitte Betreff eingeben';
            }

            if(!empty($_POST['body'])){
                $body = $_POST['body'];
            }else{
                $data['errors']['body'] = 'Bitte Nachricht eingeben';
            }

            if($recipient && $title && $body){
                $this->Message_model->send_message($recipient,$title,$body);
                $data['success'] = true;
            }
            
            echo json_encode($data);
        }

    }