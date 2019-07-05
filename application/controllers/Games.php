<?php
    class Games extends CI_Controller{

        public function render(){
               
            $this->load->view('templates/gameheader');
            $this->load->view('pages/game');
            $this->load->view('templates/gamefooter');
        }

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
        
        public function search() {
            $this->User_model->searching($this->session->userdata('user_id'));
            $player2 = $this->User_model->search($this->session->userdata('user_id'));
            $data['player2'] = $player2;
            echo json_encode($data);
        }

        public function ready() {
            $json = $this->input->post('player2');
            $this->User_model->setReady($this->session->userdata('user_id'));
            $playersReady = $this->User_model->getReady($json);
            $data['ready'] = $playersReady;
            echo json_encode($data);
        }

    }