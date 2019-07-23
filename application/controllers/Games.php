<?php
    class Games extends CI_Controller{

        /**
         * Erstellt Spiel-Bildschirm
         *
         * @return void
         */
        public function render(){
               
            $this->load->view('templates/gameheader');
            $this->load->view('pages/game');
            $this->load->view('templates/gamefooter');
        }

        /**
         * Erstellt Such-Bildschirm
         * Setzt Such- und Bereit-Werte zurück
         *
         * @return void
         */
        public function render_search() {
            if ( ! $this->session->userdata('logged_in'))
            { 
                redirect('login');
            }else{
                $this->load->view('templates/header');
                $this->load->view('pages/search');
                $this->load->view('templates/footer');
                $this->User_model->resetValues($this->session->userdata('user_id'));
            }
        }
        
        /**
         * Markiert User als 'suchend'
         * Findet andere Spieler, die gerade suchen
         *
         * @return JSON
         */
        public function search() {
            $this->User_model->searching($this->session->userdata('user_id'));
            $player2 = $this->User_model->search($this->session->userdata('user_id'));
            $data['player2'] = $player2;
            echo json_encode($data);
        }

        /**
         * Setzt User auf 'Bereit'
         * Findet heraus, ob der zweite Spieler auch bereit ist
         *
         * @return JSON
         */
        public function ready() {
            $json = $this->input->post('player2');
            $this->User_model->setReady($this->session->userdata('user_id'));
            $playersReady = $this->User_model->getReady($json);
            $data['ready'] = $playersReady;
            echo json_encode($data);
        }

         
        /**
         * Überprüft, ob eingeladener Spieler beitritt
         *
         * @return JSON
         */
        public function invite() {
            $user_name = false;
            $data['success'] = false;
            $data['errors'] = [];

            if(!empty($_POST['username'])){
                if(!$this->User_model->check_username_exists($_POST['username'])) {
                    $user_name = $this->User_model->search_specific($_POST['username']);
                }else{
                    $data['errors']['username'] = 'Username existiert nicht';
                }
            }

            if($user_name) {
                $data['success'] = true;
                $data['player2'] = $user_name;
            }
            
            echo json_encode($data);
        }

        /**
         * Ein eingeladener User wird zur Suchseite weitergeleitet
         * sein Username wird als Parameter mitgeschickt
         *
         * @param [string] $user_name
         * @return void
         */
        public function join($user_name) {
            $data['username'] = $user_name;

            $this->load->view('templates/header');
            $this->load->view('pages/search',$data);
            $this->load->view('templates/footer');
        }

    }