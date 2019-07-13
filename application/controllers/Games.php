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

    }