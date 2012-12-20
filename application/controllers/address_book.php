<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class address_book extends MY_Controller {
	/**
	 * Evita la validacion (enfocado cuando se usa ajax). Ver mas en privilegios_model
	 * @var unknown_type
	 */
	private $excepcion_privilegio = array('');

	public function _remap($method){
		$this->load->model("customer_model");
		if($this->customer_model->checkSession()){
			$this->info_empleado = $this->customer_model->getInfoCustomer($this->session->userdata('id_usuario'), true);

			$this->{$method}();
		}else
			redirect(base_url());
	}

	public function index(){
		// $this->carabiner->css(array(
		// 	array('skin/dashboard/style.css', 'screen')
		// ));

		// $params['info_customer'] = $this->info_empleado['info']; //info empleado
		// $params['seo'] = array(
		// 	'titulo' => 'Dashboard - yuppics'
		// );


		// $this->load->view('skin/header', $params);
		// $this->load->view('skin/general/menu', $params);
		// // $this->load->view('skin/general/home', $params);
		// $this->load->view('skin/footer');
	}


	/*
 	|	Muestra el Formulario para agregar un customer
 	*/
	public function add()
	{
		$this->load->library('form_validation');
		if ($this->form_validation->run() === FALSE) 
		{
			$params['frm_errors'] = array(
					'title' => '',
					'msg'   => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
					'ico'   => 'error');
		}
		else
		{
			$this->load->model('address_book_model');
			$mdl_res = $this->address_book_model->add_address();

			$params['frm_errors'] = array(
					'title' => '',
					'msg'   => 'Se registro correctamente la direccion.',
					'ico'   => 'success');
		}

		echo json_encode($params);
	}
	
	public function update()
	{
		$this->load->library('form_validation');
		if ($this->form_validation->run() === FALSE) 
		{
			$params['frm_errors'] = array(
					'title' => '',
					'msg'   => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
					'ico'   => 'error');
		}
		else
		{
			$this->load->model('address_book_model');
			$mdl_res = $this->address_book_model->update_address();

			$params['frm_errors'] = array(
					'title' => '',
					'msg'   => 'Se actualizo correctamente la direccion',
					'ico'   => 'success');
		}

		echo json_encode($params);
	}

	public function delete(){
		if ($this->input->get('id') !== false) 
		{
			$this->load->model('address_book_model');
			$mdl_res = $this->address_book_model->delete_address($this->input->get('id'));

			redirect(base_url('customer/perfil?msg=3'));
		}
		else
		{
			redirect(base_url('customer/perfil?msg=1'));
		}

		
	}

	public function info_address()
	{
		if ($this->input->get('address') !== false) 
		{
			$this->load->model('address_book_model');
			$mdl_res = $this->address_book_model->getAddress($this->input->get('address'));

			if ($mdl_res) {
				$params['address'] = $mdl_res;
				$params['frm_errors'] = array(
					'title' => '',
					'msg'   => 'Se cargo la informacion',
					'ico'   => 'success');
			}else
				$params['frm_errors'] = array(
					'title' => '',
					'msg'   => 'No se cargo la informacion de la direccion',
					'ico'   => 'error');
		}
		else
		{
			$params['frm_errors'] = array(
					'title' => '',
					'msg'   => 'No se cargo la informacion de la direccion',
					'ico'   => 'error');
		}

		echo json_encode($params);
	}




}
