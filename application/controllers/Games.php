<?php
    class Games extends CI_Controller{

        public function render(){
               
            $this->load->view('templates/gameheader');
            $this->load->view('pages/game');
            $this->load->view('templates/gamefooter');

 
        }

    }