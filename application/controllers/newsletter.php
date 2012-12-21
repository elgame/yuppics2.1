<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter extends MY_Controller 
{

	/**
	 * Evita la validacion (enfocado cuando se usa ajax). Ver mas en privilegios_model
	 * @var unknown_type
	 */
	private $excepcion_privilegio = array('');

	public function _remap($method)
	{
		$this->load->model("customer_model");
		if($this->customer_model->checkSession()){
			$this->info_empleado = $this->customer_model->getInfoCustomer($this->session->userdata('id_usuario'), true);

			$this->{$method}();
		}else
			$this->{'index'}();
	}

	public function index()
	{
		redirect(base_url());
	}

	public function send()
	{
		$this->load->library('form_validation');
		// $this->form_validation->set_error_delimiters('<small>','</small><br>');
		if ($this->form_validation->run() === FALSE)
		{
			$params['frm_errors'] = array(
										'title' => '', 
										'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()), 
										'ico' => 'error');
		}
		else
		{
			$this->load->model('newsletter_model');
			$mdl_res = $this->newsletter_model->suscribete();

			$params['frm_errors'] = array(
										'title' => '',
										'msg' => $mdl_res[1],
										'ico' => ($mdl_res[0]? 'success': 'error') );
		}
		echo json_encode($params);
	}

}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */