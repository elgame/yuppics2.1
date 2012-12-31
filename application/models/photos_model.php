<?php

class Photos_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	public function getYuppicPhotos($id_yuppic)
	{
		$res = $this->db->query("SELECT 
				yp.id_photo, yp.id_yuppic, yp.url_img, yp.url_thumb
			FROM yuppics_photos AS yp
			WHERE yp.id_yuppic = ". $id_yuppic);

		if($res->num_rows() > 0){
			return $res->result();
		}else
			return false;
	}

	public function save_photos(){
		$id_yuppic = '1';

		// if ($this->session->userdata('id_yuppics')) {

			// $id_yuppic = $this->session->userdata('id_yuppics');
			UploadFiles::validaDir($this->session->userdata('id_usuario'), APPPATH.'yuppics/');
			UploadFiles::validaDir($id_yuppic, APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/');
			UploadFiles::validaDir('PHOTOS', APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/');

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
		// }
	}

}