<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class history extends MY_Controller {

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
		}
		else
      redirect(base_url());
	}

	public function index()
	{
		$this->carabiner->css(array(
			array('skin/dashboard/style.css', 'screen'),
			array('skin/faq/faq.css'),
		));

		$this->carabiner->js(array(
			array('libs/jquery.form.js'),
			array('skin/form_ajax.js'),
			array('skin/contact.js'),
			array('skin/newsletter.js'),
		));

		// Carrito de compras
		$this->load->model('book_model');
		$params['carrito_compra'] = $this->book_model->getShoppingCart();

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['product_yuppic'] = $this->info_empleado['yuppic']; //info yuppic
		$params['info_dash'] = $this->info_empleado['yuppic_compr']; //Yuppics comprados contador
		$params['seo'] = array(
			'titulo' => 'Historial de compras - yuppics'
		);

		$this->load->model('history_model');
		$params['orders'] = $this->history_model->getHistory($this->session->userdata('id_usuario'));

		$this->load->view('skin/header', $params);
		$this->load->view('skin/general/menu', $params);
		$this->load->view('skin/history/history', $params);
		$this->load->view('skin/general/right-bar', $params);
		$this->load->view('skin/footer');
	}

}

/* End of file faq.php */
/* Location: ./application/controllers/faq.php */
