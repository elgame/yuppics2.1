<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class customer extends MY_Controller {
	/**
	 * Evita la validacion (enfocado cuando se usa ajax). Ver mas en privilegios_model
	 * @var unknown_type
	 */
	private $excepcion_privilegio = array('');

	public function _remap($method){
		$this->load->model("customer_model");
		if ($method == 'login' || $method == 'register' || $method == 'login_facebook') {
			$this->{$method}();
		} else if($this->customer_model->checkSession()){
			$this->info_empleado = $this->customer_model->getInfoCustomer($this->session->userdata('id_usuario'), true);

			$this->{$method}();
		}else
			$this->{'intro'}();
	}

	public function index(){
		$this->carabiner->css(array(
      array('libs/jquery.jscrollpane.css', 'screen'),
			array('skin/dashboard/style.css', 'screen')
		));

		$this->carabiner->js(array(
			array('libs/jquery.form.js'),
      array('libs/jquery.mousewheel.min.js'),
      array('libs/jquery.jscrollpane.min.js'),
			array('skin/form_ajax.js'),
			array('skin/contact.js'),
			array('skin/newsletter.js'),
      array('skin/dashboard.js'),
		));

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['product_yuppic'] = $this->info_empleado['yuppic']; //info yuppic
		$params['info_dash'] = $this->info_empleado['yuppic_compr']; //Yuppics comprados contador
		$params['seo'] = array(
			'titulo' => 'Dashboard - yuppics'
		);

		// Carrito de compras
		$this->load->model('book_model');
		$params['carrito_compra'] = $this->book_model->getShoppingCart();


		//Yuppics comprados
		//listado
		$this->load->model('yuppics_model');
    $is_search = FALSE;
    if (isset($_GET['search']))
    {
      if ($_GET['search'] !== '')
      {
        $params['info_dash']->listado1 = $this->yuppics_model->getYuppicsCP(1, true);
        $params['info_dash']->listado2 = $this->yuppics_model->getYuppicsCP(0, true);
        $is_search = TRUE;
      }
    }

    if ($is_search === FALSE)
    {
      $params['info_dash']->listado1 = $this->yuppics_model->getYuppicsCP(1);
      $params['info_dash']->listado2 = $this->yuppics_model->getYuppicsCP(0);
    }

		$this->load->view('skin/header', $params);
		$this->load->view('skin/general/menu', $params);
		$this->load->view('skin/general/home', $params);
		$this->load->view('skin/general/right-bar', $params);
		$this->load->view('skin/footer');
	}


	public function intro(){
		$this->carabiner->css(array(
			array('libs/jquery.pschecker.css', 'screen'),
			array('skin/intro/intro.css', 'screen')
		));

		$this->carabiner->js(array(
			array('libs/jquery.form.js'),
			array('libs/jquery.pschecker.js'),
			array('skin/login.js'),
			array('skin/form_ajax.js'),
		));

		$params['seo'] = array(
			'titulo' => 'Intro - Yuppics'
		);

		$this->load->view('skin/header_intro', $params);
		$this->load->view('skin/general/intro', $params);
		$this->load->view('skin/footer');
	}

	/**
	 * carga el login para entrar al panel
	 */
	public function login(){
		$this->load->library('form_validation');
		$rules = array(
			array('field'	=> 'email',
				'label'		  => 'Email',
				'rules'		  => 'required|valid_email'),
			array('field'	=> 'password',
				'label'		  => 'Password',
				'rules'	  	=> 'required|md5')
		);
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == FALSE){
			$params['frm_errors'] = array(
					'title' => 'Error al Iniciar Sesión!',
					'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
					'ico' => 'error');
		}else{
			$data = "email = '".$this->input->post('email')."' AND password = '".$this->input->post('password')."' AND status = 'on' ";
			$mdl_res = $this->customer_model->setLogin($data);
			if ($mdl_res[0])
			{
				$params['frm_errors'] = array(
					'title' => '',
					'msg' => 'Te logeas correctamente',
					'ico' => 'success');
			}
			else
			{
				$params['frm_errors'] = array(
					'title' => 'Error al Iniciar Sesión!',
					'msg' => 'El usuario y/o contraseña son incorrectos, o no cuenta con los permisos necesarios para loguearse',
					'ico' => 'error');
			}
		}

		echo json_encode($params);
	}

	public function login_facebook()
	{
		// $this->load->model('customer_model');
		$this->load->library('my_facebook');

		$config = array(
					'redirect_uri' => base_url('customer/login_facebook'),
					'scope' => 'user_about_me, email, user_photos, friends_photos',
					'display' => 'popup'
		);

		$this->my_facebook->initialize($config);
		$access_token = $this->my_facebook->oauth();


		$user = $this->my_facebook->get_user_about_me($access_token);
		$params = array(
			'sid'        => $user->id,
			'social'     => 'facebook_id',
			'first_name' => $user->first_name,
			'last_name'  => (isset($user->last_name)? $user->last_name: ''),
			'email'      => $user->email,
      'picture'    => $user->pictures['small'],
			);
		$this->customer_model->customer_social_checkin($params);
	}

	/**
	 * cierra la sesion del usuario
	 */
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}



	/*
 	|	Muestra el Formulario para agregar un customer
 	*/
	public function register()
	{
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
			$mdl_res = $this->customer_model->register_customer();

      $msg = 'Te has registrado correctamente a yuppics, <a href="#modal_ingreso" role="button" class="btn btn-small btn-info" data-toggle="modal">Ingresa con tu cuenta</a>';
      $ico = 'success';
      if (isset($mdl_res['msg']))
      {
        $msg = $mdl_res['msg'];
        $ico = 'error';
      }

      $params['frm_errors'] = array(
            'title' => '',
            'msg'   => $msg,
            'ico'   => $ico);
		}

    var_dump($_FILES);
    echo json_encode($params);
	}

	/*
 	|	Muestra el Formulario para modificar un usuario
 	*/
	public function perfil()
	{
		$this->carabiner->css(array(
			array('libs/jquery.pschecker.css', 'screen'),
			array('skin/dashboard/style.css', 'screen')
		));

		$this->carabiner->js(array(
			array('libs/jquery.form.js'),
			array('libs/jquery.pschecker.js'),
			array('libs/jquery.formautofill.min.js'),
			array('general/msgbox.js'),
			array('skin/perfil.js'),
			// array('skin/contact.js'),
			array('skin/newsletter.js'),
			array('skin/form_ajax.js'),
		));

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['product_yuppic'] = $this->info_empleado['yuppic']; //info yuppic
		$params['info_dash'] = $this->info_empleado['yuppic_compr']; //Yuppics comprados contador
		$params['seo'] = array(
			'titulo' => 'Modificar perfil - yuppics'
		);

		// Carrito de compras
		$this->load->model('book_model');
		$params['carrito_compra'] = $this->book_model->getShoppingCart();

		$this->load->model('address_book_model');
		$this->load->model('states_model');
		$params['address_books'] = $this->address_book_model->getAddressBooks($this->session->userdata('id_usuario'));
		$params['states']        = $this->states_model->getStates(1);


		if (isset($_GET['msg']))
			$params['frm_errors'] = $this->showMsgs($_GET['msg']);

		$this->load->view('skin/header', $params);
		$this->load->view('skin/general/menu', $params);
		$this->load->view('skin/customer/perfil', $params);
		$this->load->view('skin/general/right-bar', $params);
		$this->load->view('skin/footer');
	}

	public function update()
	{
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
			$mdl_res = $this->customer_model->update_customer();

      $msg = 'Se actualizo correctamente la informacion del perfil';
      $ico = 'success';
      if (isset($mdl_res['msg']))
      {
        $msg = $mdl_res['msg'];
        $ico = 'error';
      }

      $params['frm_errors'] = array(
            'title' => '',
            'msg'   => $msg,
            'ico'   => $ico);
		}

		echo json_encode($params);
	}




	public function valida_email($email)
	{

		$result = $this->db->query("SELECT id_customer
		                           FROM customers
		                           WHERE email = '".$email."' AND id_customer <> ".$this->session->userdata('id_usuario'));
		if ($result->num_rows() > 0) {
			$this->form_validation->set_message('valida_email', 'El %s no esta disponible, intenta con otro.');
			return FALSE;
		}
		return true;
	}

	public function valida_username($username)
	{
		if ($username != '')
		{
			$result = $this->db->query("SELECT id_customer
			                           FROM customers
			                           WHERE username = '".$username."' AND id_customer <> ".$this->session->userdata('id_usuario'));
			if ($result->num_rows() > 0) {
				$this->form_validation->set_message('valida_username', 'El %s no esta disponible, intenta con otro.');
				return FALSE;
			}
		}
		return true;
	}




	private function showMsgs($tipo, $msg='', $title='Perfil')
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
				$txt = 'La direccion se eliminó correctamente.';
				$objs = 'deleteaddress_alert';
				$icono = 'success';
				break;
		}

		return array(
				'title' => $title,
				'objs' => $objs,
				'msg' => $txt,
				'ico' => $icono);
	}

}

