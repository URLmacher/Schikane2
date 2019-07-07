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
                $this->dbforge->add_key('msg_id', TRUE);
                $this->dbforge->create_table('messages');
        }

        public function down()
        {
                $this->dbforge->drop_table('messages');
        }
}