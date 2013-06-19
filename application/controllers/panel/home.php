<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class home extends MY_Controller {
	/**
	 * Evita la validacion (enfocado cuando se usa ajax). Ver mas en privilegios_model
	 * @var unknown_type
	 */
	private $excepcion_privilegio = array('');

	public function _remap($method){

		$this->load->model("panel_empleados_model");
		if($this->panel_empleados_model->checkSession()){
			$this->panel_empleados_model->excepcion_privilegio = $this->excepcion_privilegio;
			$this->info_empleado                         = $this->panel_empleados_model->getInfoEmpleado($this->session->userdata('id_usuario_panel'), true);

			$this->{$method}();
		}else
			$this->{'login'}();
	}

	public function index(){

		$params['info_empleado'] = $this->info_empleado['info']; //info empleado
		$params['seo'] = array(
			'titulo' => 'Panel de Administración'
		);

		$this->load->view('panel/header', $params);
		$this->load->view('panel/general/menu', $params);
		$this->load->view('panel/general/home', $params);
		$this->load->view('panel/footer');
	}




	/**
	 * carga el login para entrar al panel
	 */
	public function login(){

		$params['seo'] = array(
			'titulo' => 'Login'
		);

		$this->load->library('form_validation');
		$rules = array(
			array('field'	=> 'usuario',
				'label'		=> 'Email',
				'rules'		=> 'required|valid_email'),
			array('field'	=> 'pass',
				'label'		=> 'Password',
				'rules'		=> 'required|md5')
		);
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == FALSE){
			$params['frm_errors'] = array(
					'title' => 'Error al Iniciar Sesión!',
					'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
					'ico' => 'error');
		}else{
			$data = "email = '".$this->input->post('usuario')."' AND pass = '".$this->input->post('pass')."' AND status = '1' ";
			$mdl_res = $this->panel_empleados_model->setLogin($data);
			if ($mdl_res[0] && $this->panel_empleados_model->checkSession()) {
				redirect(base_url('panel/home'));
			}
			else{
				$params['frm_errors'] = array(
					'title' => 'Error al Iniciar Sesión!',
					'msg' => 'El usuario y/o contraseña son incorrectos, o no cuenta con los permisos necesarios para loguearse',
					'ico' => 'error');
			}
		}

		$this->load->view('panel/header', $params);
		$this->load->view('panel/general/login', $params);
		$this->load->view('panel/footer');
	}

	/**
	 * cierra la sesion del usuario
	 */
	public function logout(){
		// $this->session->sess_destroy();
    $user_data = array( 'id_usuario_panel'=> '',
                        'nombre_panel'    => '',
                        'email_panel'     => '',
                        'acceso_panel'    => '',
                        'idunico_panel'   => '');





    $this->session->unset_userdata($user_data);
		redirect(base_url('panel/home'));
	}

}

?>