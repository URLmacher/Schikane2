<?php
    class Users extends CI_Controller{

        /**
         * Speichert Benutzerdaten
         * Überprüft Eingaben
         * Gibt Fehler zurück
         * Leitet zur Startseite weiter
         *
         * @return void
         */
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

        /**
         * Meldet Benutzer nach Überprüfung der Anmeldedaten an
         * Gibt Fehler aus
         * Setzt Session-Daten
         * Leitet zur Startseite weiter
         *
         * @return void
         */
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
                $user_data = $this->User_model->login($username, $password);
                if($user_data) {
                    
                    $session_data = array(
                        'user_id' => $user_data['user_id'],
                        'user_name' => $username, 
                        'user_age' => $user_data['user_age'],
                        'user_sex' => $user_data['user_sex'],
                        'user_city' => $user_data['user_city'],
                        'games_won' => $user_data['games_won'],
                        'games_lost' => $user_data['games_lost'],
                        'logged_in' => true
                    );
                     
                        
                    $this->session->set_userdata($session_data);
                    $this->session->set_flashdata('user_loggedin', 'Sie sind nun angemeldet.');
                    redirect('home'); 
                }else{
                    $this->session->set_flashdata('login_failed', 'Falsche Anmeldedaten');
                    redirect('users/login'); 
                }
  
            }
        }

        /**
         * Meldet User ab
         * zerstört Session-daten
         * Leitet zum Login-Bildschirm weiter
         *
         * @return void
         */
        public function logout(){
            $this->User_model->logout($this->session->user_id);
            $this->session->sess_destroy();
           
            redirect('users/login'); 
        }
        
        /**
         * Überprüft, ob ein Username schon vergeben ist
         * Gibt Fehler aus
         *
         * @param string $username
         * @return bool
         */
        public function check_username_exists($username){
            $this->form_validation->set_message('check_username_exists', 'Username bereits belegt');

            if($this->User_model->check_username_exists($username)) {
                return true;
            }else{
                return false;
            }
        }

        /**
         * Ändert zusätzliche Profildaten
         * Gibt Fehler zurück
         *
         * @return JSON
         */
        public function edit() {
            $age = false;
            $sex = false;
            $city = false;
            $data['success'] = false;
            $data['errors'] = [];

            if(!empty($_POST['age']) ){
                if(is_numeric($_POST['age']) && $_POST['age'] > 0  && $_POST['age'] < 120) {
                    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
                }else{
                    $data['errors']['age'] = 'Ungültige Eingabe';
                }
            }
                
            if(!empty($_POST['sex'])){
                if($_POST['sex'] == 'männlich' || $_POST['sex'] == 'weiblich' || $_POST['sex'] == 'sonstig') {
                    $sex = filter_var($_POST['sex'], FILTER_SANITIZE_STRING);
                }else{
                    $data['errors']['sex'] = 'Ungültige Eingabe';
                    $data['debug'] = $_POST['sex'];
                }
            }

            if(!empty($_POST['city'])){
                if(is_string($_POST['city']) && strlen($_POST['city']) > 2 && strlen($_POST['city']) < 50 ) {
                    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
                }else{
                    $data['errors']['city'] = 'Ungültige Eingabe';
                }
            }

            if($age && $city && $sex){
                $this->User_model->editProfile($sex,$age,$city);
                $data['success'] = true;
                $session_data = array(
                    'user_age' => $age,
                    'user_sex' => $sex,
                    'user_city' => $city
                );
                    
                        
                $this->session->set_userdata($session_data);
            }
            
            echo json_encode($data);
        }
        
        /**
         * Löscht Profil nach Passwort Überprüfung
         * Gibt Fehler zurück
         *
         * @return JSON
         */
        public function delete() {
            $password = false;
            $data['success'] = false;
            $data['errors'] = [];
            if(!empty($_POST['password']) ){
                $password =  md5($_POST['password']);
            }else{
                $data['errors']['password'] = 'Bitte Passwort eingeben';
            }
          

            if($password){
                if($this->User_model->deleteProfile($password,$this->session->userdata('user_name'))) {
                    $this->session->sess_destroy();
                    $data['success'] = true;
                }else{
                   $data['errors']['password'] = 'Falsches Passwort';
                }
            }

            echo json_encode($data);
        }
        
        /**
         *  Zeigt Profil für andere Leute
         *
         * @return JSON
         */
        public function show() {
            $profile = false;
            $data['success'] = false;
            $data['errors'] = [];

            if(!empty($_POST['username']) ){
                if(!$this->User_model->check_username_exists($_POST['username'])) {
                    $profile = $this->User_model->get_profile($_POST['username']);
                }else{
                    $data['errors']['username'] = 'Username existiert nicht';
                }
            }else{
                $data['errors']['username'] = 'Kein Username angegeben';
            }
          

            if($profile){
                $data['success'] = true;
                $data['profile'] = $profile;
            }

            echo json_encode($data);
        }

    }