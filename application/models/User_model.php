<?php
class User_model extends CI_Model{
    
    /**
     * Speichert die Daten eines Users nach Registrierung
     * Setzt Werte auf Null
     *
     * @param string $enc_password
     * @return void
     */
    public function register($enc_password){
        $data = array(
            'user_name' => $this->input->post('user_name'),
            'games_won' => 0,
            'games_lost' => 0,
            'password' => $enc_password
        );

        $this->db->insert('users',$data);
    }

    /**
     * Meldet User an
     * Setzt User auf 'online'
     *
     * @param string $username
     * @param string $password
     * @return array
     * @return bool
     */
    public function login($username, $password){
        $this->db->where('user_name', $username); 
        $this->db->where('password', $password);
        
        $result = $this->db->get('users');
        
        if($result->num_rows() == 1) {
            $data = array(
                'online' => 1
            );

            $this->db->where('user_name', $username);
            $this->db->update('users', $data); 
            $result_array = $result->result_array();
            return $result_array[0];
        }else{
            return false;
        }
    }

    /**
     * Überprüft, ob ein Username schon vergeben ist
     *
     * @param string $username
     * @return bool
     */
    public function check_username_exists($username){
        $query = $this->db->get_where('users', array('user_name' => $username));
        if(empty($query->row_array())) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Holt die ID eines Users
     *
     * @param string $username
     * @return array
     */
    public function get_user_id($username){
        $query = $this->db->get_where('users', array('user_name' => $username));
        $row = $query->row_array(0);
        return $row['user_id'];
    }

    /**
     * Meldet User ab
     * Setzt Werte zurück
     *
     * @param int $user_id
     * @return void
     */
    public function logout($user_id){
        $data = array(
            'online' => 0,
            'searching' => 0
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    /**
     * Markiert User als 'suchend'
     *
     * @param int $user_id
     * @return void
     */
    public function searching($user_id) {
        $data = array(
            'searching' => 1
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    /**
     * Findet User, die gerade Mitspieler suchen
     *
     * @param int $user_id
     * @return array
     * @return bool
     */
    public function search($user_id) {
        $query = $this->db->get_where('users', array('searching' => 1));
        foreach($query->result_array() as $row) {
            if($row['user_id'] != $user_id) {
                return $row['user_name'];
            }
        }
        return false;
    }

    /**
     * Markiert einen User als 'bereit'
     *
     * @param [type] $user_id
     * @return void
     */
    public function setReady($user_id) {
        $data = array(
            'ready' => 1
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    /**
     * Überprüft, ob der Mitspieler bereit ist
     *
     * @param string $user_name
     * @return array
     * @return bool
     */
    public function getReady($user_name) {
        $query = $this->db->get_where('users', array('ready' => 1));
        foreach($query->result_array() as $row) {
            if($row['user_name'] == $user_name) {
                return $row['user_name'];
            }
        }
        return false;
    }

    /**
     * Setzt Werte zurück, die nur in einer Phase benötigt werden
     *
     * @param int $user_id
     * @return void
     */
    public function resetValues($user_id) {
        $data = array(
            'ready' => 0,
            'searching' => 0
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    /**
     * Speichert die neuen Werte des Nutzer-Profils
     *
     * @param string $sex
     * @param int $age
     * @param string $city
     * @return void
     */
    public function editProfile($sex,$age,$city) {
        $data = array(
            'user_age' => $age,
            'user_sex' => $sex,
            'user_city' => $city
        );

        $this->db->where('user_id',  $this->session->userdata('user_id'));
        $this->db->update('users', $data);
    }

    /**
     * Setzt Username und Passwort eines Users auf 'gelöscht'
     *
     * @param string $password
     * @param string $user_name
     * @return bool
     */
    public function deleteProfile($password,$user_name) {
        $this->db->where('user_name', $user_name); 
        $this->db->where('password', $password);
        
        $result = $this->db->get('users');
        
        if($result->num_rows() == 1) {
            $data = array(
                'user_name' => 'deleted',
                'password' => '0',
                'online' => '0'
            );

            $this->db->where('user_name', $user_name);
            $this->db->update('users', $data); 
            return true;
        }else{
            return false;
        } 
   
    }
 
}