<?php

class countries_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * 
	 */
	public function getCountries(){
		$res = $this->db
			->select('id_country, name')
			->from('countries')
			->order_by('name', 'asc')
		->get();
		if($res->num_rows() > 0){
			$response = $res->result();
			return $response;
		}else
			return false;
	}


}