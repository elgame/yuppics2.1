<?php

class Newsletter_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	public function suscribete()
	{
		$this->load->library('mcapi');
		
		if ($this->input->post('newsletter') == 0) 
		{
			if( $this->mcapi->listSubscribe($this->config->item('mcapi_list_id'), 
				$this->input->post('email'), array( 'FNAME' => $this->input->post('first_name'), 'LNAME' => $this->input->post('last_name') )) === true)
			{
				$this->db->update('customers', array('newsletter' => '1'), "id_customer = ".$this->session->userdata('id_usuario'));

				return array(true, 'Te suscribiste correctamente al boletin :)');
			}
			else
			{
				return array(false, 'Ocurrio un error al suscribirse, intenta nuevamente.');
			}
		}
		else
		{
			if( $this->mcapi->listUnsubscribe( $this->config->item('mcapi_list_id'), $this->input->post('email') ) === true)
			{
				$this->db->update('customers', array('newsletter' => '0'), "id_customer = ".$this->session->userdata('id_usuario') );
				return array(true, 'Te desuscribiste correctamente al boletin');
			}
			else
			{
				return array(false, 'Ocurrio un error al desuscribirse, intenta nuevamente.');
			}
		}

	}

}