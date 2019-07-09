<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_Users extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'user_id int(11) NOT NULL ',
                        'user_name varchar(255) NOT NULL',
                        'password varchar(255) NOT NULL',
                        'user_age int(11) DEFAULT NULL',
                        'user_sex varchar(50) DEFAULT NULL',
                        'games_won int(11) DEFAULT NULL',
                        'games_lost int(11) DEFAULT NULL',
                        'online tinyint(1) NOT NULL',
                        'searching tinyint(1) NOT NULL',
                        'ready tinyint(1) NOT NULL'
                ));
                $this->dbforge->create_table('users');
                $this->db->query('ALTER TABLE users
                ADD PRIMARY KEY (user_id);');
                $this->db->query("ALTER TABLE users
                MODIFY user_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6");
        }

        public function down()
        {
                $this->dbforge->drop_table('users');
        }
}