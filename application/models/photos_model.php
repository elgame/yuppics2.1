<?php

class Photos_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la informacion de un cupon
	 */
	public function getThemes($search=null, $exist=false){
		$this->db
			->select('t.id_theme, t.name, t.background_img, t.background_color, t.text_color')
			->from('themes AS t');
		if ($search != null) {
			$this->db->join('themes_tags AS tt', 't.id_theme = tt.id_theme', 'inner')
				->join('tags AS ta', 'ta.id_tag = tt.id_tag', 'inner')
				->where("Lower(t.name) LIKE Lower('".$search."') OR Lower(ta.name) LIKE Lower('".$search."')");
		}

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

	public function save_photos(){
		// $id_yuppic = '1';

		if ($this->session->userdata('id_yuppics')) {

			$id_yuppic = $this->session->userdata('id_yuppics');
			UploadFiles::validaDir($this->session->userdata('id_usuario'), APPPATH.'yuppics/');
			UploadFiles::validaDir($id_yuppic, APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/');
			UploadFiles::validaDir('PHOTOS', APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/');

			// var_dump($_POST['photos']);exit;
			$data_photos = array();
			foreach ($_POST['photos'] as $k => $url_photo) {
				$a = explode('/', $url_photo);
				$b = explode('.', $a[count($a)-1]);

				$path = APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/PHOTOS/'.$a[count($a)-1];
				$path_thum = APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/PHOTOS/'.$b[0].'_thumb.'.$b[1];

				$data_photos[] = array('id_yuppic'=>$id_yuppic, 'url_img' => $path, 'url_thumb' => $path_thum);

				UploadFiles::copyFile($url_photo, $path);
				UploadFiles::copyFile($_POST['thumbs'][$k], $path_thum);
			}

			$this->db->insert_batch('yuppics_photos', $data_photos);

			return $id_yuppic;
		}
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