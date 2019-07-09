<?php
    class Friendships extends CI_Controller{

        public function index() {
            $friends = $this->Friendship_model->get_friends($this->session->userdata('user_id'));
            $data['friends'] = $friends;
            echo json_encode($data);
        }

        public function delete($friendship_id) {
            $data['success'] = false;
            if($this->Friendship_model->delete_friendship($friendship_id)) {
                $data['success'] = true;
            }
            echo json_encode($data);
        }

        public function add() {
            $friend_id = false;
            $data['success'] = false;
            $data['errors'] = [];

            if(!empty($_POST['friend_name'])){
                if(!$this->User_model->check_username_exists($_POST['friend_name'])) {
                    $friend_id  = $this->User_model->get_user_id($_POST['friend_name']);
                }else{
                    $data['errors']['friend_name'] = 'Username existiert nicht';
                }
            }else{
                $data['errors']['friend_name'] = 'Bitte Namen eingeben';
            }

            if($friend_id ){
                $this->Friendship_model->add_friendship($friend_id);
                $data['success'] = true;
            }
            
            echo json_encode($data);
        } 
    }