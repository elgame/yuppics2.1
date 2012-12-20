<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class paypal_test extends MY_Controller {

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
	 * map to /index.php/welcome/<!-- <method_name> -->
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->library('my_paypal');

		if (isset($_GET['PayerID'])) {
			echo 'El pago se realizo correctamente :D  !!!! ';
			exit;
		}

		$this->my_paypal->config_sale(array(
			'maxamt' => '30000.00',
			'returnurl' => base_url('paypal_test/'),
			'cancelurl' => base_url('paypal_test/cancel/'),
			'brandname' => 'Yuppics2.0',
			'customerservicenumber' => '(312) 12345'
			));

		$this->my_paypal->config_payment(array(
			'currencycode' => 'MXN',
			'desc' => 'yuppics descripcion',
			'shippingamt' => 0
			));

		$products = array(
				array(
					'name' => 'photobook1',
					'price' => '100',
					'qty' => '1'
					),
				array(
					'name' => 'photobook2',
					'price' => '80',
					'qty' => '2'
					)
			);
		$this->my_paypal->add_products($products);

		$this->my_paypal->send_checkout();
	}

	public function cancel(){
		echo 'Cancelaste el pago de los photobooks D: ';
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */