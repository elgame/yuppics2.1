<?php

class address_book_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene las direcciones de envio y facturacion de un cliente
	 */
	public function getAddressBooks($id_customer){
		$res = $this->db
			->select('ab.id_address, ab.id_customer, ab.contact_first_name, ab.contact_last_name, 
				ab.phone, ab.company, ab.rfc, ab.street, ab.between_streets, ab.colony, ab.city, 
				ab.default_billing, ab.default_shipping, c.name AS country, s.name AS state')
			->from('address_book AS ab')
				->join('countries AS c', 'c.id_country = ab.id_country', 'inner')
				->join('states AS s', 's.id_state = ab.id_state', 'inner')
			->where("id_customer = '".$id_customer."'")
		->get();
		if($res->num_rows() > 0){
			foreach ($res->result() as $value) 
			{
				if ($value->default_billing == 1 || $value->default_shipping == 1) {
					$response['default'][] = $value;
				}
				else
				{
					$response['others'][] = $value;
				}
			}
			
			return $response;
		}else
			return false;
	}

	public function getAddress($id_address){
		$res = $this->db
			->select('*')
			->from('address_book')
			->where("id_address = '".$id_address."'")
		->get();
		if($res->num_rows() > 0){
			$response = $res->row();
			return $response;
		}else
			return false;
	}


	/**
	 * Registra a un customer utilizando el formulario de registro
	 * @return [type] [description]
	 */
	public function add_address()
	{
		$conta = $this->db->query("SELECT Count(*) AS num FROM address_book WHERE id_customer = ".$this->session->userdata('id_usuario'))->row();

		$data = array('id_customer' => $this->session->userdata('id_usuario'),
			'id_country'         => '1',
			'id_state'           => $this->input->post('state'),
			'contact_first_name' => $this->input->post('contact_first_name'),
			'contact_last_name'  => $this->input->post('contact_last_name'),
			'phone'              => $this->input->post('phone'),
			'company'            => $this->input->post('company'),
			'rfc'                => $this->input->post('rfc'),
			'street'             => $this->input->post('street'),
			'between_streets'    => $this->input->post('between_streets'),
			'colony'             => $this->input->post('colony'),
			'city'               => $this->input->post('city'),
			'default_billing'    => (($this->input->post('default_billing')===false && $conta->num > 0)? '0': '1'),
			'default_shipping'   => (($this->input->post('default_shipping')===false && $conta->num > 0)? '0': '1'),
		);

		if ($this->input->post('default_billing') !== false) {
			$this->db->update('address_book', array('default_billing' => '0'), "id_customer = ".$data['id_customer']);
		}
		if ($this->input->post('default_shipping') !== false) {
			$this->db->update('address_book', array('default_shipping' => '0'), "id_customer = ".$data['id_customer']);
		}

		$this->db->insert('address_book', $data);
		return array('cid' => $this->db->insert_id());
	}

	public function update_address()
	{
		$data = array(
			'id_country'         => '1',
			'id_state'           => $this->input->post('id_state'),
			'contact_first_name' => $this->input->post('contact_first_name'),
			'contact_last_name'  => $this->input->post('contact_last_name'),
			'phone'              => $this->input->post('phone'),
			'company'            => $this->input->post('company'),
			'rfc'                => $this->input->post('rfc'),
			'street'             => $this->input->post('street'),
			'between_streets'    => $this->input->post('between_streets'),
			'colony'             => $this->input->post('colony'),
			'city'               => $this->input->post('city'),
			'default_billing'    => ($this->input->post('default_billing')===false? '0': '1'),
			'default_shipping'   => ($this->input->post('default_shipping')===false? '0': '1'),
		);

		if ($this->input->post('default_billing') !== false) {
			$this->db->update('address_book', array('default_billing' => '0'), "id_customer = ".$this->session->userdata('id_usuario'));
		}
		if ($this->input->post('default_shipping') !== false) {
			$this->db->update('address_book', array('default_shipping' => '0'), "id_customer = ".$this->session->userdata('id_usuario'));
		}

		$this->db->update('address_book', $data, "id_address = ".$this->input->post('id_address'));
		return true;
	}

	public function delete_address($id_address){
		$this->db->delete('address_book', "id_address = ".$id_address);
		return true;
	}



}