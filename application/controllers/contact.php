<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller 
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
		}elseif($method == 'send')
			$this->{$method}();
		else
			$this->{'index'}(); //redirect(base_url());
	}

	public function index()
	{
		$this->load->view('skin/contact/contact');
	}

	public function send()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<small>','</small><br>');
		if ($this->form_validation->run() === FALSE)
		{
			$params['frm_errors'] = array(
										'title' => '', 
										'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()), 
										'ico' => 'error');
		}
		else
		{
			$this->load->model('contact_model');
			$mdl_res = $this->contact_model->send_message();

			$params['frm_errors'] = array(
										'title' => '',
										'msg' => 'El mensaje se envio correctamente.',
										'ico' => 'success');
		}
		echo json_encode($params);
	}

}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */