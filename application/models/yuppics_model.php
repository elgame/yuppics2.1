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

}