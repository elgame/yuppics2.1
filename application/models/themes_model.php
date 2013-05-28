<?php

class themes_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la informacion de un cupon
	 */
	public function getThemes($search=null, $exist=false){
		$this->db
			->select('t.id_theme, t.name, tta.name AS autor, t.background_img, t.background_color, t.text_color')
			->from('themes AS t')
			->join('themes_autor AS tta', 'tta.id_autor = t.id_autor', 'inner');
		if ($search != null) {
			$search = mb_strtolower($search, 'utf-8');
			$search = str_replace(';', '', $search);
			$this->db->join('themes_tags AS tt', 't.id_theme = tt.id_theme', 'left')
				->join('tags AS ta', 'ta.id_tag = tt.id_tag', 'left')
				->where("Lower(t.name) LIKE '%".$search."%' OR Lower(tta.name) LIKE '%".$search."%' OR Lower(ta.name) LIKE '%".$search."%'");
		}
		$this->db->group_by('t.id_theme');
		$res = $this->db->order_by('t.name', 'asc')
		->get();
		if($res->num_rows() > 0){
			if($exist){
				$res->free_result();
				return true;
			}

			$response = $res->result();
			$res->free_result();
			
			return $response;
		}else
			return false;
	}

	public function getYuppicTheme($id_yuppic){
		$res = $this->db->query("SELECT 
				y.id_yuppic, y.title, y.author, yt.background_img, yt.background_color, yt.text_color 
			FROM yuppics AS y 
				INNER JOIN yuppics_theme AS yt ON y.id_yuppic = yt.id_yuppic 
			WHERE y.id_yuppic = ".$id_yuppic);

		if($res->num_rows() > 0){
			$response = $res->row();
			$res->free_result();
			
			$a = explode('/', $response->background_img);
			$b = explode('.', $a[count($a)-1]);
			unset($a[count($a)-1]);
			$response->background_img_thum = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];

			return $response;
		}else
			return false;
	}

	public function save_theme(){
		$this->load->model('coupons_model');
		$produc = $this->coupons_model->getProduct(true);
		$data_yuppic = array(
			'id_customer' => $this->session->userdata('id_usuario'), 
			'id_product'  => $produc->id_product,
			'title'       => $this->input->post('title'),
			'author'      => $this->input->post('author'),
			'quantity'    => 1
			);

		$data_theme = array(
			'id_yuppic'        => '', 
			'background_img'   => $this->input->post('background_img'), 
			'background_color' => $this->input->post('background_color'), 
			'text_color'       => $this->input->post('text_color')
			);

		UploadFiles::validaDir($data_yuppic['id_customer'], APPPATH.'yuppics/');

		if ($this->session->userdata('id_yuppics') != '') {
			$id_yuppic = $this->session->userdata('id_yuppics');
			$this->db->update('yuppics', $data_yuppic, "id_yuppic = ".$id_yuppic);
			unset($data_theme['id_yuppic']);

			if (strpos($data_theme['background_img'], 'yuppics/themes') !== false) {
				$data_theme['background_img'] = $this->getPathUrl($data_theme, $data_yuppic, $id_yuppic, true);
			}

			$this->db->update('yuppics_theme', $data_theme, "id_yuppic = ".$id_yuppic);
		}else{
			$this->db->insert('yuppics', $data_yuppic);
			$id_yuppic = $this->db->insert_id();

			$data_theme['background_img'] = $this->getPathUrl($data_theme, $data_yuppic, $id_yuppic);

			$data_theme['id_yuppic']      = $id_yuppic;
			$this->db->insert('yuppics_theme', $data_theme);

			$this->session->set_userdata('id_yuppics', $id_yuppic);
		}

		

		return $id_yuppic;
	}

	private function getPathUrl($data_theme, $data_yuppic, $id_yuppic, $delte=false){
		$a = explode('/', $data_theme['background_img']);
		$b = explode('.', $a[count($a)-1]);

		$path = APPPATH.'yuppics/'.$data_yuppic['id_customer'].'/'.$id_yuppic.'/'.$a[count($a)-1];
		$path_thum = APPPATH.'yuppics/'.$data_yuppic['id_customer'].'/'.$id_yuppic.'/'.$b[0].'_thumb.'.$b[1];

		unset($a[count($a)-1]);
		$source_path_thum = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];

		UploadFiles::validaDir($id_yuppic, APPPATH.'yuppics/'.$data_yuppic['id_customer'].'/');
		UploadFiles::copyFile($data_theme['background_img'], $path);
		UploadFiles::copyFile($source_path_thum, $path_thum);

		if ($delte) {
			$info = $this->getYuppicTheme($id_yuppic);
			UploadFiles::deleteFile($info->background_img);
			
			$a = explode('/', $info->background_img);
			$b = explode('.', $a[count($a)-1]);
			unset($a[count($a)-1]);
			$source_path_thum = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];
			UploadFiles::deleteFile($source_path_thum);
		}

		return $path;
	}

}