<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mercadopago_test extends CI_Controller {

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
		
		$this->load->library('mp', array(
			'client_id'     => '7172967533334556', 
			'client_secret' => 'omCwGHkRTK9PL1b2pskoCUaBIDtnhgy1') );

		$access = $this->mp->get_access_token();

		//var_dump($this->mp->create_test_user());
		
		$preference = array(
			'items' => array(
				array(
					'id'          => 'DDssa',
					'title'       => 'Producto de prueba',
					'description' => ' asd asd asdas ds d',
					'quantity'    => 2,
					'currency_id' => 'MXN',
					'unit_price'  => 5.5,
					'picture_url' => 'https://www.mercadopago.com/org-img/MP3/home/logomp3.gif'
					// Optional
					// 'item_id'            => '',
					// 'item_description'   => '',
					// 'item_picture_url'   => '',
					// 'external_reference' => '',
					// 'payer_name'         => '',
					// 'payer_surname'      => '',
					// 'payer_email'        => '',
					// 'back_url_success'   => '',
					// 'back_url_pending'   => ''
				)
			),
			'back_urls' => array(
				'success' => 'http://localhost/yuppics2.0/mercadopago_test?msg=success',
				'failure' => 'http://localhost/yuppics2.0/mercadopago_test?msg=failure',
				'pending' => 'http://localhost/yuppics2.0/mercadopago_test?msg=pending'
			)
		);

		$result = $this->mp->create_preference($preference);
		header("Location: ".$result['response']['init_point']);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */