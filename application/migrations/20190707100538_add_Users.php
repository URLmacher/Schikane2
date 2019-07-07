<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_Users extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'user_id int(11) NOT NULL',
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
                $this->dbforge->add_key('user_id', TRUE);
                $this->dbforge->create_table('users');
        }

        public function down()
        {
                $this->dbforge->drop_table('users');
        }
}