<?php

class history_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene las Ordenes de compra
	 */
	public function getHistory($id_customer){
		$res = $this->db
			->select('*')
			->from('orders')
			->where("id_customer = '".$id_customer."' AND status = 'p'")
		->get();
		if($res->num_rows() > 0){
			$response = $res->result();
			$res->free_result();

			foreach ($response as $key => $value) {
				$response[$key]->yuppics = $this->db->query("SELECT oy.id_yuppics, oy.quantity, oy.unitary_price, y.title, y.created
					FROM orders_yuppics AS oy INNER JOIN yuppics AS y ON y.id_yuppic = oy.id_yuppics 
					WHERE id_order = ".$value->id_order)->result();
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