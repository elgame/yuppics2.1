<?php

class states_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene las direcciones de envio y facturacion de un cliente
	 */
	public function getStates($id_country){
		$res = $this->db
			->select('id_state, name')
			->from('states')
			->where('id_country = '.$id_country)
			->order_by('name', 'asc')
		->get();
		if($res->num_rows() > 0){
			$response = $res->result();
			return $response;
		}else
			return false;
	}


}