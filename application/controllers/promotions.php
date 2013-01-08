<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotions extends MY_Controller {

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

	public function index()
	{
		$this->carabiner->css(array(
			array('skin/promo/promo.css'),
		));

		$this->carabiner->js(array(
			array('http://connect.facebook.net/en_US/all.js'),
			array('libs/jquery.form.js'),
			array('skin/form_ajax.js'),
			array('skin/contact.js'),
			array('skin/newsletter.js'),
			array('skin/promotions.js')
		));

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['seo'] = array(
			'titulo' => 'Promociones - yuppics'
		);

		// Carrito de compras
		$this->load->model('book_model');
		$params['carrito_compra'] = $this->book_model->getShoppingCart();

		$this->load->library('my_facebook');
		$params['fb_app_id'] = $this->my_facebook->APP_ID;

		$this->load->model('promotion_model');
		$params['status'] = $this->promotion_model->getStates($this->session->userdata('id_usuario'));

		if (isset($_GET['msg']) && isset($params['status']->progress) )
			if($params['status']->progress < 100)
				$params['frm_errors'] = $this->showMsgs($_GET['msg']);

		$this->load->view('skin/header', $params);
		$this->load->view('skin/general/menu', $params);
		$this->load->view('skin/promotions/yuppic', $params);
		$this->load->view('skin/general/right-bar', $params);
		$this->load->view('skin/footer');
	}

	public function update_state(){
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
			$this->load->model('promotion_model');

			$data = $this->input->post('value');
			if ($this->input->post('field') == 'feedback') {
				$data = array($this->input->post('value'), $this->input->post('text') );
			}
			$params['status1'] = $this->promotion_model->updateState($this->session->userdata('id_usuario'), $this->input->post('field'), $data);

			$params['frm_errors'] = array(
							'title' => '',
							'msg'   => 'Se actualizo correctamente.',
							'ico'   => 'success');
		}
		echo json_encode($params);
	}

	public function twitter(){
		$data_res = $this->db->query("SELECT Count(tweet) AS tweet FROM customer_promo WHERE id_customer = ".$this->session->userdata('id_usuario'));
		if ($data_res->num_rows() > 0) {
			$data = $data_res->row();
			$data_res->free_result();

			if ($data->tweet == 0) {
				$this->load->library('my_twitter');
				$res = $this->my_twitter->oauth();
				$result = $this->my_twitter->statuses_update('Amigos entren a http://localhost/yuppics2.1/ y crea tus photobooks, rapido y facil :)');

				if (isset($result->text) && isset($result->id_str)) {
					$this->load->model('promotion_model');
					$this->promotion_model->updateState($this->session->userdata('id_usuario'), 'tweet', '1');

					redirect(base_url('promotions?msg=3'));
				}
			}
		}
		
		redirect(base_url('promotions?msg=4'));
	}



	public function extra_valida(){
		if (!preg_match("/[link_facebook|invit_facebook|tweet|feedback]/", $this->input->post('field'))) {
			$this->form_validation->set_message('extra_valida', 'El campo especificado no es valido.');
			return FALSE;
		}

		if (!preg_match("/[1|0]/", $this->input->post('value'))) {
			$this->form_validation->set_message('extra_valida', 'El valor no es valido.');
			return FALSE;
		}

		return true;
	}


	private function showMsgs($tipo, $msg='', $title='promociones')
	{
		switch($tipo){
			case 1:
				$txt = 'El campo ID es requerido.';
				$icono = 'error';
				break;
			case 2: //Cuendo se valida con form_validation
				$txt = $msg;
				$icono = 'error';
				break;
			case 3:
				$txt = 'Se publico el tweet correctamente.';
				$objs = 'promo_alert';
				$icono = 'success';
				break;
			case 4:
				$txt = 'No se pudo realizar la publicacion en twitter, intentalo de nuevo.';
				$objs = 'promo_alert';
				$icono = 'error';
				break;
		}
	
		return array(
				'title' => $title,
				'objs' => $objs,
				'msg' => $txt,
				'ico' => $icono);
	}

}

/* End of file faq.php */
/* Location: ./application/controllers/faq.php */
