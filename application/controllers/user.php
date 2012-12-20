<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends MY_Controller {

	public function login()
	{
		$this->carabiner->css(array(
			array('bootstrap/bootstrap.css'),
			array('bootstrap/bootstrap-responsive.css'),
		));

		$this->carabiner->js(array(
			array('bootstrap/jquery-1.8.3.min.js'),
			array('bootstrap/bootstrap.min.js'),
			array('skin/login.js'),
		));

		$params['seo'] = array('titulo' => 'Login - Yuppics2.0');
		$this->load->view('header', $params);
		$this->load->view('login', $params);
		$this->load->view('footer', $params);
	}

	public function register()
	{
		$this->carabiner->css(array(
			array('bootstrap/bootstrap.css'),
			array('bootstrap/bootstrap-responsive.css')
		));
		
		$this->carabiner->js(array(
			array('bootstrap/jquery-1.8.3.min.js'),
			array('bootstrap/bootstrap.min.js'),
		));

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');

		if ($this->form_validation->run() === FALSE) 
		{
			// echo validation_errors();
		}
		else
		{
			// echo 'Regitro Exitoso!!! :D';
			// var_dump($_POST);
			redirect(base_url('user/register'));
		}

		$params['seo'] = array('titulo' => 'Registro Yuppics2.0');
		$this->load->view('header', $params);
		$this->load->view('register', $params);
		$this->load->view('footer', $params);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */