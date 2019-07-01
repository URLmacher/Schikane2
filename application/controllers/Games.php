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
                $this->User_model->searching($this->session->userdata('user_id'));
                $this->load->view('templates/header');
                $this->load->view('pages/search');
                $this->load->view('templates/footer');
            }
        }

        public function search() {
            $player2 = $this->User_model->search($this->session->userdata('user_id'));
            $data['player2'] = $player2['user_name'];
            $data['player2_id'] = $player2['user_id'];
            echo json_encode($data);
        }

        public function ready() {
            $json = json_decode(file_get_contents('php://input'));
            $this->User_model->setReady($this->session->userdata('user_id'));
            $playersReady = $this->User_model->getReady($json->player2id);
            // $data['ready'] = $playersReady;
            // echo json_encode($data);
            $data['debug'] = '$playersReady';
            echo json_encode($data);
        }

    }