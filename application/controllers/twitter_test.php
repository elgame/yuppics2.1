<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class twitter_test extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->library('my_twitter');
		$res = $this->my_twitter->oauth();
		var_dump($res);
	}

	public function callback()
	{
		$this->load->library('my_twitter');
		$res = $this->my_twitter->oauth();
		$result = $this->my_twitter->statuses_update('Este mensaje es de prueba ajuaaa');
		var_dump($result);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */