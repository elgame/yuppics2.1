<?php

class pages_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene los tipos de paginas que se pueden asignar al yuppic,
	 * si se le espesifica el id, solo regresara la info de la pag
	 * espesificada
	 */
	public function getPages($id_page=null){
		$this->db
			->select('id_page, num_imgs, url_preview')
			->from('accomodation_page');
		if ($id_page != null) {
			$this->db->where('id_page = '.$id_page);
		}
		$res = $this->db->order_by('id_page', 'asc')->get();
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

	/**
	 * Obtiene informacion de la pagina espesificada de un yuppic, si la pagina no tiene todas las
	 * imagenes del diseño espesifico de pagina se le agregan las que falten sin info.
	 * @param  integer $num_pag [description]
	 * @return [type]           [description]
	 */
	public function getPage($id_yuppic, $num_pag=1){
		$res = $this->db
			->select('id_ypage, id_yuppic, id_page, num_pag')
			->from('yuppics_pages')
			->where("id_yuppic = ".$id_yuppic." AND num_pag = ".$num_pag)
		->get();
		if($res->num_rows() > 0){

			$response = $res->row();
			$res->free_result();

			$response->images = $this->db->query("SELECT ypp.id_ypage, yp.id_photo, yp.url_img, api.id_page_img, api.id_img, 
					ai.width, ai.height, api.coord_x, api.coord_y, fi.url_frame, fi.id_frame
				FROM yuppics_pages_photos AS ypp 
					INNER JOIN yuppics_photos as yp ON yp.id_photo = ypp.id_photo
					INNER JOIN accomodation_page_imgs AS api ON ypp.id_page_img = api.id_page_img
					INNER JOIN accomodation_imgs AS ai ON ai.id_img = api.id_img 
					INNER JOIN frames_imgs AS fi ON (fi.id_frame = ypp.id_frame AND ai.id_img = fi.id_img)
				WHERE ypp.id_ypage = ".$response->id_ypage)->result();

			$pag_inf = $this->getPages($response->id_page);
			foreach ($pag_inf[0]->images as $key => $value) {
				if( is_array(Arrays::buscarArray($response->images, 'id_page_img', $value->id_page_img)) ){
					$response->images[] = $value;
				}
			}
			
			return $response;
		}else
			return false;
	}

	public function savePage(){
		$data_pag = array(
			'id_yuppic' => $this->session->userdata('id_yuppics'),
			'id_page'   => $this->input->post('id_page'),
			'num_pag'   => ($this->input->post('num_pag')!=''? $this->input->post('num_pag'): 1)
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
		return $this->getPage($data_pag['id_yuppic'], ($this->input->post('direction')=='prev'? $data_pag['num_pag']-1: $data_pag['num_pag']+1) );
	}

	public function deletePage($id_pag)
	{
		$this->db->delete('yuppics_pages', array('id_ypage'=>$id_pag));
		$res = $this->db->query("SELECT id_ypage FROM yuppics_pages 
			WHERE id_yuppic = ".$this->session->userdata('id_yuppics')." 
			ORDER BY num_pag ASC");
		foreach ($res->result() as $key => $value) {
			$this->db->update('yuppics_pages', array('num_pag' => ($key+1)), array('id_ypage' => $value->id_ypage));
		}
		return TRUE;
	}

	public function magicBook(){
		$data_photos = $this->db->query("SELECT id_photo FROM yuppics_photos 
			WHERE id_yuppic = ".$this->session->userdata('id_yuppics')." ORDER BY id_photo ASC")->result();

		$data_pag_photos = $this->db->query("SELECT ap.id_page, api.id_page_img, ap.num_imgs
			FROM accomodation_page AS ap INNER JOIN accomodation_page_imgs AS api ON ap.id_page = api.id_page
			WHERE ap.id_page = ".$this->input->get('id_page'))->result();

		$frames = $this->input->get('id_frame');

		$total_photos = count($data_photos);
		$cont_photo   = 0;
		$num_pages    = ceil($total_photos/$data_pag_photos[0]->num_imgs);

		$this->db->delete('yuppics_pages', array('id_yuppic' => $this->session->userdata('id_yuppics')));
		for ($i=1; $i <= $num_pages; $i++) { 
			$data_pag = array(
				'id_yuppic' => $this->session->userdata('id_yuppics'),
				'id_page'   => $this->input->get('id_page'),
				'num_pag'   => $i
			);
			$this->db->insert('yuppics_pages', $data_pag);
			$id_ypage = $this->db->insert_id();

			for ($c=0; $c < $data_pag_photos[0]->num_imgs; $c++) {
				if ($cont_photo < $total_photos) {
					$this->db->insert('yuppics_pages_photos', array(
						'id_ypage'    => $id_ypage,
						'id_photo'    => $data_photos[$cont_photo]->id_photo,
						'id_page_img' => $data_pag_photos[$c]->id_page_img,
						'id_frame'    => $frames[$c]
					));
				}
				$cont_photo++;
			}
		}
	}

}