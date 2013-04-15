<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class order_model extends CI_Model {


	function __construct()
	{
		parent::__construct();
	}


	/*
	 |	Obtiene la informacion de un usuario
	 */
	public function get_order_info($id_order=FALSE, $basic_info=FALSE)
	{
		$id_order = (isset($_GET['id']))? $_GET['id']: $id_order;

		$sql_res = $this->db->select("id_order, id_customer, id_address_billing, id_address_shipping, total_shipping, total_discount,
			total, created, updated, status, comment, guide_num" )
												->from("orders")
												->where("id_order", $id_order)
												->get();
		$data['info'] = array();

		if ($sql_res->num_rows() > 0)
			$data['info']	= $sql_res->row();

		if ($basic_info == False) {
			//Productos o yuppics comprados
			$data['products'] = array();
			$res = $this->db
				->select('oy.id_order, oy.id_yuppics, y.title, oy.quantity, oy.unitary_price')
				->from('orders_yuppics AS oy')
					->join('yuppics AS y', 'y.id_yuppic = oy.id_yuppics', 'inner')
				->where("oy.id_order = '".$id_order."'")
			->get();
			if($res->num_rows() > 0){
				$data['products']	= $res->result();
			}
			$res->free_result();
		}

		return $data;
	}

}
/* End of file usuarios_model.php */
/* Location: ./application/controllers/usuarios_model.php */