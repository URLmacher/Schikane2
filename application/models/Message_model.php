<?php
    class Message_model extends CI_Model{

        public function __construct(){
            $this->load->database();
        }

        public function get_messages($user_id) {
            $this->db->order_by('messages.created_at','DESC');
            $this->db->join('users', 'messages.sender_id = users.user_id');
            $query = $this->db->get_where('messages', array('recipient_id' => $user_id));
       
            return $query->result_array();
        }

        public function message_seen($msg_id) {
            $data = array(
                'msg_seen' => 1
            );

            $this->db->where('msg_id', $msg_id);
            $this->db->update('messages', $data);
        }

        public function get_message($msg_id) {
          
            $this->db->order_by('messages.created_at','DESC');
            $this->db->join('users', 'messages.sender_id = users.user_id');
            $query = $this->db->get_where('messages', array('msg_id' => $msg_id));
       
            return $query->result_array();
        }

        public function send_message($user_id,$title,$body) {
            $data = array(
                'msg_title' => $title,
                'msg_body' => $body,
                'sender_id' => $this->session->userdata('user_id'),
                'recipient_id' => $user_id
            );
            return $this->db->insert('messages',$data);
        }

    }
