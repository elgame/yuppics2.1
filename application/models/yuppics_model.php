<?php

class yuppics_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * [getYuppicsCP description]
	 * @param  integer $status [description]
	 * @return [type]          [description]
	 */
	public function getYuppicsCP($status=1){
		$res = $this->db->query("SELECT y.id_yuppic, y.title, y.author, y.created, yph.url_img
			FROM yuppics AS y 
				INNER JOIN (SELECT * FROM yuppics_photos GROUP BY id_yuppic) AS yph ON yph.id_yuppic = y.id_yuppic
			WHERE y.id_customer = ".$this->session->userdata('id_usuario')." AND y.comprado = ".$status);
		if($res->num_rows() > 0){
			$response = $res->result();
			$res->free_result();
			
			return $response;
		}else
			return false;
	}

}