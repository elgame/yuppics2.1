<?php

class Contact_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	public function send_message()
	{
		$data = array('name' => $this->input->post('name'),
					  'email' => $this->input->post('email'),
					  'message' => nl2br($this->input->post('message')));

		$this->db->insert('contact', $data);
		return array('mid' => $this->db->insert_id());
	}

}