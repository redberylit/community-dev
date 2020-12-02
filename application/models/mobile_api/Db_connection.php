<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Db_connection extends CI_Model {

	public function connect_maindb(){
		$host = '192.168.52.5';
		$user = 'mubashir';
		$pass = 'Subaru123';
		$dbname = 'spur_hidhaya';
 
		$config = array();
		$config['hostname'] = $host;
		$config['username'] = $user;
		$config['password'] = $pass;
		$config['database'] = $dbname;
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';
		$this->load->database($config, FALSE, TRUE);
	}

}