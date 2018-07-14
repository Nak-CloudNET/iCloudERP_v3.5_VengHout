<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_modal extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	function create_driver($data){
		$this->db->insert('companies',$data);
		
	}
	
}
?>