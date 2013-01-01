<?php

class pages_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene los tipos de paginas que se pueden asignar al yuppic
	 */
	public function getPages(){
		$res = $this->db
			->select('id_page, num_imgs, url_preview')
			->from('accomodation_page')
			->order_by('id_page', 'asc')
		->get();
		if($res->num_rows() > 0){

			$response = $res->result();
			$res->free_result();

			foreach ($response as $key => $value) {
				$response[$key]->images = $this->db->query("SELECT api.id_page_img, api.id_img, 
						ai.width, ai.height, api.coord_x, api.coord_y
					FROM accomodation_page_imgs AS api 
						INNER JOIN accomodation_imgs AS ai ON ai.id_img = api.id_img 
					WHERE api.id_page = ".$response[$key]->id_page)->result();
			}
			
			return $response;
		}else
			return false;
	}

	public function getPage($id_ypage){
		$res = $this->db
			->select('id_ypage, id_yuppic, id_page, num_pag')
			->from('yuppics_pages')
			->where("id_ypage = ".$id_ypage)
		->get();
		if($res->num_rows() > 0){

			$response = $res->row();
			$res->free_result();

			$response->images = $this->db->query("SELECT ypp.id_ypage, yp.id_photo, yp.url_img, api.id_page_img, api.id_img, 
					ai.width, ai.height, api.coord_x, api.coord_y, fi.url_frame
				FROM yuppics_pages_photos AS ypp 
					INNER JOIN yuppics_photos as yp ON yp.id_photo = ypp.id_photo
					INNER JOIN accomodation_page_imgs AS api ON ypp.id_page_img = api.id_page_img
					INNER JOIN accomodation_imgs AS ai ON ai.id_img = api.id_img 
					INNER JOIN frames_imgs AS fi ON (fi.id_frame = ypp.id_frame AND ai.id_img = fi.id_img)
				WHERE ypp.id_ypage = ".$id_ypage)->result();
			
			return $response;
		}else
			return false;
	}

	public function savePage(){
		$data_pag = array(
			'id_yuppic' => $this->session->userdata('id_yuppics'),
			'id_page'   => $this->input->post('id_page')
			);

		if ($this->input->post('id_ypage') == '') {
			$this->db->insert('yuppics_pages', $data_pag);
			$id_ypage = $this->db->insert_id();

			foreach ($this->input->post('photos') as $key => $value) {
				$this->db->insert('yuppics_pages_photos', array(
					'id_ypage'    => $id_ypage,
					'id_photo'    => $value['id_photo'],
					'id_page_img' => $value['id_page_img'],
					'id_frame'    => $value['id_frame']
					));
			}
		}else{
			$this->db->update('yuppics_pages', $data_pag, "id_ypage = ".$this->input->post('id_ypage'));

			$this->db->delete('yuppics_pages_photos', "id_ypage = ".$this->input->post('id_ypage'));
			foreach ($this->input->post('photos') as $key => $value) {
				$this->db->insert('yuppics_pages_photos', array(
					'id_ypage'    => $this->input->post('id_ypage'),
					'id_photo'    => $value['id_photo'],
					'id_page_img' => $value['id_page_img'],
					'id_frame'    => $value['id_frame']
					));
			}
		}
	}


}