<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile extends CI_Controller {

    public function __construct(){
            parent::__construct();
            $this->load->model('mobile_api/Db_connection', 'dbconnection');
		    $this->dbconnection->connect_maindb();
    }
	
    public function call_api($folder,$mdl,$func){
    	$this->load->model($folder.'/'.$mdl);
    	$this->$mdl->$func();
    }
}