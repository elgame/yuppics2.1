<?php

class frames_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la lista de bordes que se pueden usar en el yuppic
	 */
	public function getFrames(){
		$res = $this->db
			->select('id_frame, name, url_preview')
			->from('frames')
			->order_by('name', 'asc')
		->get();
		if($res->num_rows() > 0){

			$response = $res->result();
			$res->free_result();
			
			return $response;
		}else
			return false;
	}

	public function getFrame($id_frame, $id_img){
		$res = $this->db
			->select('id_frame, id_img, url_frame')
			->from('frames_imgs')
			->where('id_frame = '.$id_frame.' AND id_img = '.$id_img)
		->get();
		if($res->num_rows() > 0){

			$response = $res->row();
			$res->free_result();
			
			return $response;
		}else
			return false;
	}

}