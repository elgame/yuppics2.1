<?php

class faqs_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la lista de preguntas
	 */
	public function getFaqs($sql=""){
		$this->db
			->select('*')
			->from('faqs');
		if ($sql!='')
			$this->db->where($sql);
		$res = $this->db->order_by('question')->get();
		if($res->num_rows() > 0){
			$response = $res->result();
			$res->free_result();

			return $response;
		}else
			return false;
	}

}