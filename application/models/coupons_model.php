<?php

class coupons_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la informacion de un cupon
	 */
	public function getCoupon($id, $exist=false){
		$res = $this->db
			->select('*')
			->from('coupons')
			->where("id_coupon = '".$id."'")
		->get();
		if($res->num_rows() > 0){
			if($exist){
				$res->free_result();
				return true;
			}

			$response = $res->row();
			$res->free_result();
			
			return $response;
		}else
			return false;
	}

	public function create($params){

		$data = array(
			'code'       => String::RandomString(8),
			'uses_total' => (isset($params['uses_total'])? $params['uses_total']: 1),
			'amount'     => $this->getProduct()
			);
		if (isset($params['name']))
			$data['name'] = $params['name'];
		if (isset($params['date_start']))
			$data['date_start'] = $params['date_start'];
		if (isset($params['date_end']))
			$data['date_end'] = $params['date_end'];

		$this->db->insert('coupons', $data);
		$id = $this->db->insert_id();

		$response =  $this->getCoupon($id); 

		return $response;
	}


	public function getProduct($all=false){
		$resy = $this->db->query("SELECT * FROM products WHERE name = 'yuppics'");
		if ($resy->num_rows() > 0) {
			$data = $resy->row();
			$resy->free_result();

			if($all)
				return $data;
			else
				return $data->price;
		}else
			return 0;
	}


}