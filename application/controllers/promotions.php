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
			$this->{'index'}();
	}

	public function index()
	{
		$this->carabiner->css(array(
			array('skin/faq/faq.css'),
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

		$this->load->library('my_facebook');
		$params['fb_app_id'] = $this->my_facebook->APP_ID;

		$this->load->view('skin/header', $params);
		$this->load->view('skin/general/menu', $params);
		$this->load->view('skin/promotions/yuppic', $params);
		$this->load->view('skin/general/right-bar', $params);
		$this->load->view('skin/footer');
	}

}

/* End of file faq.php */
/* Location: ./application/controllers/faq.php */
