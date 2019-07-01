<?php
    class Users extends CI_Controller{
        public function register(){
            $data['title'] = 'Registrieren';   

            $this->form_validation->set_rules('user_name','Username','required|callback_check_username_exists');  
            $this->form_validation->set_rules('password','Password','required');  
            $this->form_validation->set_rules('password2','Confirm Password','matches[password]');    

            if($this->form_validation->run() === FALSE){

                $this->load->view('templates/header');
                $this->load->view('users/register',$data);
                $this->load->view('templates/footer');

            }else{
                $enc_password = md5($this->input->post('password'));
                $this->User_model->register($enc_password);

                $this->session->set_flashdata('user_registered', 'Sie sind nun registriert.');
                redirect('home');
            }
        }

        public function login(){
            $data['title'] = 'Anmelden';

            $this->form_validation->set_rules('user_name','Username','required');  
            $this->form_validation->set_rules('password','Password','required');  
       
            if($this->form_validation->run() === FALSE){

                $this->load->view('templates/header');
                $this->load->view('users/login',$data);
                $this->load->view('templates/footer');

            }else{
              
                $username = $this->input->post('user_name');
                $password = md5($this->input->post('password'));
                $user_id = $this->User_model->login($username, $password);

                if($user_id) {
                    
                    $user_data = array(
                        'user_id' => $user_id,
                        'user_name' => $username,
                        'logged_in' => true
                    );

                    $this->session->set_userdata($user_data);
                    $this->session->set_flashdata('user_loggedin', 'Sie sind nun angemeldet.');
                    redirect('home'); 
                }else{
                    $this->session->set_flashdata('login_failed', 'Falsche Anmeldedaten');
                    redirect('users/login'); 
                }
  
            }
        }

        public function logout(){
            $this->User_model->logout($this->session->user_id);
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('user_name');

            $this->session->set_flashdata('user_loggedout', 'Sie sind nun abgemeldet');
            redirect('users/login'); 
        }
        
        public function check_username_exists($username){
            $this->form_validation->set_message('check_username_exists', 'Username bereits belegt');

            if($this->User_model->check_username_exists($username)) {
                return true;
            }else{
                return false;
            }
        }
   
    }