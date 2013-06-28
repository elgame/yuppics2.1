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
	public function getYuppicsCP($status=1, $search=FALSE){

    $dblike = "";
    if ($search)
    {
      $dblike = " AND (LOWER(y.title) LIKE '%".strtolower($_GET['search'])."%' OR LOWER(y.author) LIKE '%".strtolower($_GET['search'])."%')";
    }

		$res = $this->db->query("SELECT y.id_yuppic, y.title, y.author, y.created, yph.background_img AS url_img, yph.bg_pattern
			FROM yuppics AS y
				INNER JOIN (SELECT id_yuppic, background_img, bg_pattern FROM yuppics_theme GROUP BY id_yuppic) AS yph ON yph.id_yuppic = y.id_yuppic
			WHERE y.id_customer = ".$this->session->userdata('id_usuario')." AND y.comprado = ".$status.$dblike);
		//(SELECT * FROM yuppics_photos GROUP BY id_yuppic) AS yph ON yph.id_yuppic = y.id_yuppic
		if($res->num_rows() > 0){
			$response = $res->result();
			$res->free_result();
			return $response;
		}else
			return false;
	}

	public function delete($id_yuppic){
		$result = $this->db->query("SELECT Count(*) AS cont
		                           FROM yuppics
		                           WHERE id_yuppic = ".$id_yuppic." AND id_customer = ".$this->session->userdata('id_usuario'))->row();
		if($result->cont > 0){
			self::deleteDir(APPPATH.'/yuppics/'.$this->session->userdata('id_usuario').'/'.$id_yuppic.'/');
			$this->db->delete('yuppics', 'id_yuppic = '.$id_yuppic);
			return 1;
		}else
			return 2;
	}

	public static function deleteDir($dirPath) {
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}

}