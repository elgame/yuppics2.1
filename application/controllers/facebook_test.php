<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class facebook_test extends MY_Controller {

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

// 		$w = stream_get_wrappers();
// echo 'openssl: ', extension_loaded ('openssl') ? 'yes':'no', "\n";
// echo 'http wrapper: ', in_array('http', $w) ? 'yes':'no', "\n";
// echo 'https wrapper: ', in_array('https', $w) ? 'yes':'no', "\n";
// echo 'wrappers: ', var_dump($w);

// exit;
		// echo 'Pruebillas con la api de facebook.';

		$this->load->library('my_facebook');

		$config = array(
					'redirect_uri' => base_url('facebook_test/'),
					'scope' => 'user_about_me, email, user_photos, friends_photos',
					'display' => ''
		);

		$this->my_facebook->initialize($config);
		$access_token = $this->my_facebook->oauth();
		// echo $access_token.'<br>';

		// $this->get_user_about_me($access_token);
		// $this->get_user_photos($access_token);
		$this->get_user_albums($access_token);

	}

	public function get_user_about_me($access_token)
	{
		$this->load->library('my_facebook');
		$user = $this->my_facebook->get_user_about_me($access_token);
		var_dump('<pre>',$user,'</pre>');
	}

	public function get_user_photos($access_token)
	{
		$this->load->library('my_facebook');
		$photos = $this->my_facebook->get_user_photos($access_token);
		// var_dump('<pre>',$photos,'</pre>');

		$params['data_photos'] = $photos->data;
		$this->load->view('facebook_photos', $params);
	}

	public function get_user_albums($access_token)
	{
		$this->load->library('my_facebook');
		$albums = $this->my_facebook->get_user_albums($access_token);
		// var_dump('<pre>', $albums, '</pre>');

		$params['access_token'] = $access_token;
		$params['data_albums'] = $albums->data;
		$this->load->view('facebook_photos', $params);
	}

	public function get_user_album_photos()
	{
		$this->load->library('my_facebook');
		$album_photos = $this->my_facebook->get_user_album_photos($_GET['access_token'], $_GET['ida']);
		// var_dump('<pre>', $album_photos->data, '</pre>');
		// $album_photos->data[0]->images[0]->source
		$params['data_photos'] = $album_photos->data;
		$this->load->view('facebook_photos', $params);

	}

	public function dialog_request()
	{
		$this->load->view('dialog_request');
	}

	public function dialog_send()
	{
		$this->load->view('dialog_send');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */