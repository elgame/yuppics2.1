<?php

class promotion_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la informacion de un customer
	 */
	public function getStates($id, $exist=false){
		$res = $this->db
			->select('*')
			->from('customer_promo')
			->where("id_customer = '".$id."'")
		->get();
		if($res->num_rows() > 0){
			if($exist){
				$res->free_result();
				return true;
			}

			$response = $res->row();
			$res->free_result();

			$conta = 0;
			($response->link_facebook==1? $conta++ : '');
			($response->invit_facebook==1? $conta++ : '');
			($response->tweet==1? $conta++ : '');
			($response->feedback==1? $conta++ : '');

			$response->progress = ($conta*25);

			// crea el cupon
			if ($response->progress == 100 && $response->id_coupon == '') {
				$this->load->model('coupons_model');
				$response->coupon = $this->coupons_model->create(array(
					'uses_total' => '1',
					'name'       => 'Cupon de regalo'
					));
				$this->db->update('customer_promo', array('id_coupon' => $response->coupon->id_coupon), "id_promo = ".$response->id_promo);
			}
			elseif ($response->progress == 100)  // obtiene la info del cupon
			{
				$this->load->model('coupons_model');
				$response->coupon = $this->coupons_model->getCoupon($response->id_coupon);
			}
			
			return $response;
		}else
			return false;
	}

	public function updateState($id_customer, $field, $params){
		$response = $this->getStates($id_customer, true);

		$data = array(
				$field => $params
				);
		if ($field == 'feedback') 
		{
			$data[$field] = $params[0];
			$data[$field.'_text'] = $params[1];
		}

		if ($response !== false) 
		{
			$this->db->update('customer_promo', $data, array('id_customer' => $id_customer));	
		}
		else
		{
			$data['id_customer'] = $id_customer;
			$this->db->insert('customer_promo', $data);
		}

		$response = $this->getStates($id_customer);

		return $response;
	}


}