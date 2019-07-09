<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_Friendships extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                    'friendship_id int(11) NOT NULL',
                    'friend_a_id int(11) NOT NULL',
                    'friend_b_id int(11) NOT NULL',
                    'created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP'
                ));

            
                $this->dbforge->create_table('friendships');
               
     
                $this->db->query('ALTER TABLE friendships
                                ADD PRIMARY KEY (friendship_id),
                                ADD KEY fk_friend_a_id (friend_a_id),
                                ADD KEY fk_friend_b_id (friend_b_id)');
     
                $this->db->query('ALTER TABLE friendships
                                MODIFY friendship_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4');

                $this->db->query('ALTER TABLE friendships
                                ADD CONSTRAINT fk_friend_a_id FOREIGN KEY (friend_a_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
                                ADD CONSTRAINT fk_friend_b_id FOREIGN KEY (friend_b_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE');
        }

        public function down()
        {
                $this->dbforge->drop_table('friendships');
        }
}