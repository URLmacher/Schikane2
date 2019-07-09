<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_Messages extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                    'msg_id int(11) NOT NULL',
                    'msg_title varchar(255) NOT NULL',
                    'msg_body text NOT NULL',
                    'created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',
                    'msg_seen tinyint(1) NOT NULL',
                    'sender_id int(11) NOT NULL',
                    'recipient_id int(11) NOT NULL'
                ));

            
                $this->dbforge->create_table('messages');
               
     
                $this->db->query("ALTER TABLE messages ADD PRIMARY KEY (msg_id), ADD KEY fk_sender_id (sender_id), ADD KEY fk_recipient_id (recipient_id)");
     
                $this->db->query("ALTER TABLE messages MODIFY msg_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4");
                $this->db->query('ALTER TABLE messages
                ADD CONSTRAINT fk_recipient_id FOREIGN KEY (recipient_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT fk_sender_id FOREIGN KEY (sender_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE;');
        }

        public function down()
        {
                $this->dbforge->drop_table('messages');
        }
}