<?php
    class Friendship_model extends CI_Model{

        /**
         * Holt Freunde eines Users aus der DB
         * Sortiert nach Alter
         *
         * @param int $user_id
         * @return array
         */
        public function get_friends($user_id) {
            $this->db->order_by('friendships.created_at','DESC');
            $this->db->join('users', 'friendships.friend_b_id = users.user_id');
            $query = $this->db->get_where('friendships', array('friend_a_id' => $user_id));
            if(!$query->result_array()){
                $this->db->join('users', 'friendships.friend_a_id = users.user_id');
                $query = $this->db->get_where('friendships', array('friend_b_id' => $user_id));
            }
            return $query->result_array();
        }

        /**
         * Löst Freundschaft auf
         *
         * @param int $friendship_id
         * @return bool
         */
        public function delete_friendship($friendship_id) {
            $this->db->where('friendship_id', $friendship_id);
            $this->db->delete('friendships', array('friendship_id' => $friendship_id));
            return true;
        }

        /**
         * Fügt Freundschaft hinzu
         *
         * @param int $friend_id
         */
        public function add_friendship($friend_id) {
            $data = array(
                'friend_a_id' => $friend_id,
                'friend_b_id' => $this->session->userdata('user_id')
            );
            $this->db->insert('friendships',$data);
        }

        /**
         * Überprüft, ob 2 User Freunde sind
         *
         * @param int $user_a_id
         * @param int $user_b_id
         * @return boolean
         */
        public function is_friend($user_a_id,$user_b_id) {
            $check = array(
                'friend_a_id' => $user_a_id,
                'friend_b_id' => $user_b_id
            );
            $query =$this->db->get_where('friendships', $check);
          
            if(!$query->result_array()){
                $check = array(
                    'friend_a_id' => $user_b_id,
                    'friend_b_id' => $user_a_id
                );
                $query = $this->db->get_where('friendships', $check);
                if(!$query->result_array()){
                    return false;
                }else{
                    return true;
                }
            }else{
                return true;
            }
        }

    }
