<?php
class User_model extends CI_Model{

    public function register($enc_password){
        $data = array(
            'user_name' => $this->input->post('user_name'),
            'games_won' => 0,
            'games_lost' => 0,
            'password' => $enc_password
        );

        return $this->db->insert('users',$data);
    }

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

    public function check_username_exists($username){
        $query = $this->db->get_where('users', array('user_name' => $username));
        if(empty($query->row_array())) {
            return true;
        }else{
            return false;
        }
    }

    public function get_user_id($username){
        $query = $this->db->get_where('users', array('user_name' => $username));
        $row = $query->row_array(0);
        return $row['user_id'];
    }

    public function logout($user_id){
        $data = array(
            'online' => 0,
            'searching' => 0
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    public function searching($user_id) {
        $data = array(
            'searching' => 1
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    public function search($user_id) {
        $query = $this->db->get_where('users', array('searching' => 1));
        foreach($query->result_array() as $row) {
            if($row['user_id'] != $user_id) {
                return $row['user_name'];
            }
        }
        return false;
    }

    public function setReady($user_id) {
        $data = array(
            'ready' => 1
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    public function getReady($user_name) {
        $query = $this->db->get_where('users', array('ready' => 1));
        foreach($query->result_array() as $row) {
            if($row['user_name'] == $user_name) {
                return $row['user_name'];
            }
        }
        return false;
    }

    public function resetValues($user_id) {
        $data = array(
            'ready' => 0,
            'searching' => 0
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }

    public function editProfile($sex,$age,$city) {
        $data = array(
            'user_age' => $age,
            'user_sex' => $sex,
            'user_city' => $city
        );

        $this->db->where('user_id',  $this->session->userdata('user_id'));
        $this->db->update('users', $data);
    }
 
}