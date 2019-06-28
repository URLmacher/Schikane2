<?php
class User_model extends CI_Model{

    public function register($enc_password){
        $data = array(
            'user_name' => $this->input->post('user_name'),
            // 0 => $this->input->post('games_won'),
            // 0 => $this->input->post('games_lost'),
            'password' => $enc_password
        );

        return $this->db->insert('users',$data);
    }

    public function login($username, $password){
        
        $this->db->where('user_name', $username);
        $this->db->where('password', $password);

        $result = $this->db->get('users');

        if($result->num_rows() == 1) {
            return $result->row(0)->user_id;
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

 
}