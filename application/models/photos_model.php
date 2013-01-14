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

	public function save_photos()
	{
    $this->session->set_userdata('id_yuppics', '1');
		if ($this->session->userdata('id_yuppics'))
    {
			$id_yuppic = $this->session->userdata('id_yuppics');
			UploadFiles::validaDir($this->session->userdata('id_usuario'), APPPATH.'yuppics/');
			UploadFiles::validaDir($id_yuppic, APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/');
			UploadFiles::validaDir('PHOTOS', APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/');

			// $data_photos = array();

   //    $make_insert = FALSE;
			// foreach ($_POST['photos'] as $k => $url_photo)
   //    {
   //      if ($url_photo !== 'false')
   //      {
      $a = explode('/', $_POST['photo']);
      $b = explode('.', $a[count($a)-1]);

      $path = APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/PHOTOS/'.$a[count($a)-1];
      $path_thum = APPPATH.'yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/PHOTOS/'.$b[0].'_thumb.'.$b[1];

      $data_photos = array('id_yuppic'=>$id_yuppic, 'url_img' => $path, 'url_thumb' => $path_thum);

      UploadFiles::copyFile($_POST['photo'], $path);
      UploadFiles::copyFile($_POST['thumb'], $path_thum);

   //        $make_insert = TRUE;
   //      }
			// }

      // if ($make_insert)
			   // $this->db->insert_batch('yuppics_photos', $data_photos);
      $this->db->insert('yuppics_photos', $data_photos);

			return $_POST;
    }
	}

	public function photo_delete($id_photo = false)
	{
		$idp = ($id_photo) ? $id_photo : $_POST['idp'];
		$res = $this->db->select('url_img, url_thumb')->from('yuppics_photos')->where('id_photo', $idp)->get();
		$data_photo = $res->row();

		UploadFiles::deleteFile($data_photo->url_img);
		UploadFiles::deleteFile($data_photo->url_thumb);

		$this->db->delete('yuppics_photos', array('id_photo'=>$idp));

		return TRUE;
	}

}