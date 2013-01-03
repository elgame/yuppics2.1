<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class yuppics extends MY_Controller {
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
			redirect(base_url());
	}

	/**
	 * Seleccionar tema del yuppic
	 * @return [type] [description]
	 */
	public function index(){
		$this->carabiner->css(array(
			array('libs/jquery.colorpicker.css', 'screen'),
			array('libs/jquery.jPages.css', 'screen'),
			array('skin/dashboard/style.css', 'screen'),
			array('skin/yuppics/style.css', 'screen')
		));

		$this->carabiner->js(array(
			array('libs/jquery.colorpicker.js'),
			array('libs/jquery.form.js'),
			array('libs/jquery.jPages.min.js'),
			array('general/loader.js'),
			array('skin/yuppics_tema.js')
		));

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['seo'] = array(
			'titulo' => 'Crear yuppic, Seleccionar tema - yuppics'
		);

		$this->load->model('themes_model');
		$params['themes'] = $this->themes_model->getThemes();

		if($this->session->userdata('id_yuppics')){
			$params['theme_sel'] = $this->themes_model->getYuppicTheme($this->session->userdata('id_yuppics'));
		}


		$params['themes'] = $this->load->view('skin/yuppics/themes_items', $params, true);
		$this->load->view('skin/header', $params);
		$this->load->view('skin/yuppics/themes', $params);
		$this->load->view('skin/footer');
	}

	/**
	 * Carga la imagen del tema al servidor
	 * @return [type] [description]
	 */
	public function theme_image(){
		$this->load->library('my_upload');

		ini_set('memory_limit', '64M');

		$path_imgs = 'yuppics/themes/temp/';

		$config_upload = array(
			'upload_path'     => APPPATH.$path_imgs,
			'allowed_types'   => '*',
			'max_size'        => '2048',
			'encrypt_name'    => TRUE
		 );

		$config_resize = array(
			'image_library' => 'gd2',
			'source_image'  => '',
			'new_image'     => '',
			'width'         => '400',
			'height'        => '400',
			'maintain_ratio' => TRUE
		);

		$this->my_upload->initialize($config_upload);

		$this->my_upload->do_resize = TRUE;
		$this->my_upload->new_image_path = APPPATH.$path_imgs;

		$this->my_upload->config_resize = $config_resize;
		$data = $this->my_upload->do_upload('imagen_fondo');
		
		$a = explode('/', $data['full_path']);
		$b = explode('.', $a[count($a)-1]);
		$data['path'] = APPPATH.$path_imgs.$b[0].'.'.$b[1];
		$data['thumb'] = APPPATH.$path_imgs.$b[0].'_thumb.'.$b[1];

		echo json_encode(array('status'=>1, 'resp'=>$data));
	}

	/**
	 * Busca temas en la bd y los regresa como html
	 * @return [type] [description]
	 */
	public function theme_search(){
		$this->load->model('themes_model');

		$params['themes'] = $this->themes_model->getThemes($this->input->get('qs'));

		echo $this->load->view('skin/yuppics/themes_items', $params, true);
	}

	/**
	 * Guarda la info del tema
	 * @return [type] [description]
	 */
	public function theme_save(){
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
			$this->load->model('themes_model');
			$mdl_res = $this->themes_model->save_theme();

			$params['id_yuppic'] = $mdl_res;

			$params['frm_errors'] = array(
					'title' => '',
					'msg'   => 'ssds',
					'ico'   => 'success');
		}

		echo json_encode($params);
	}

	public function theme_extra_valida(){
		if ($this->input->post('background_img')=='' && $this->input->post('background_color')=='' ) {
			$this->form_validation->set_message('theme_extra_valida', 'Es requerido la "Imagen de fondo" y/o el "Color de fondo".');
			return FALSE;
		}

		return true;
	}

	public function photos()
	{
		// if (! $this->session->userdata('id_yuppics'))
		// 	redirect(base_url('yuppics/'));

		$access_token = $this->input->get('token');
		if (!$access_token) {
			$access_token = $this->fb_get_access_token();
		}

		$this->carabiner->css(array(
			array('libs/jquery.jscrollpane.css', 'screen'),
			array('libs/jquery.jPages.css', 'screen'),
			array('skin/yuppics/style.css', 'screen'),
		));

		$this->carabiner->js(array(
			array('libs/jquery.mousewheel.min.js'),
			array('libs/jquery.jscrollpane.min.js'),
			array('libs/jquery.form.js'),
			array('libs/jquery.jPages.min.js'),
			array('general/loader.js'),
			array('skin/yuppics_photos.js'),
		));

		$params['status']->progress = '50';
		$params['seo'] = array('titulo' => 'Yuppics - Seleccionar Fotografías');

		$params['albums'] = $this->get_user_albums($access_token);
		$params['access_token'] = $access_token;

		$this->load->model('photos_model');
		$res = $this->photos_model->getYuppicPhotos('1'); // $this->session->userdata('id_yuppics')
		$params['totalp'] = 0;
		if ($res){
			$params['photos'] = $res;
			$params['width'] = count($params['photos']) * 165;
			$params['totalp'] = count($params['photos']);
		}

		$this->load->view('skin/header', $params);
		$this->load->view('skin/yuppics/photos', $params);
		$this->load->view('skin/footer', $params);
	}

	public function photos_save()
	{
		$this->load->library('form_validation');
		if ($this->form_validation->run() === FALSE)
		{
			$params['frm_errors'] = array(
									'title' => '',
									'msg'   => 'Selecciona al menos una foto o imagen para continuar.',
									'ico'   => 'error');
		}
		else
		{
			$this->load->model('photos_model');
			$mdl_res = $this->photos_model->save_photos();

			// $params['id_yuppic'] = $mdl_res;

			$params['frm_errors'] = array(
					'title' => '',
					'msg'   => 'xxxxx',
					'ico'   => 'success');
		}

		echo json_encode($params);
	}

	public function photo_delete()
	{
		$this->load->model('photos_model');
		$res = $this->photos_model->photo_delete();

		echo $res;
	}

	public function fb_get_access_token()
	{
		$this->load->library('my_facebook');
		$config = array(
					'redirect_uri' => base_url('yuppics/fb_get_access_token'),
					'scope' => 'user_about_me, email, user_photos, friends_photos',
					'display' => ''
		);

		$this->my_facebook->initialize($config);
		$access_token = $this->my_facebook->oauth();
		redirect(base_url('yuppics/photos?token='.$access_token));
	}

	public function get_user_photos($access_token = FALSE)
	{
		$this->load->library('my_facebook');
		$access_token = ($access_token) ? $access_token : $_POST['access_token'];
		$photos = $this->my_facebook->get_user_photos($access_token);
		echo json_encode($photos->data);
	}

	public function get_user_albums($access_token)
	{
		$this->load->library('my_facebook');
		$albums = $this->my_facebook->get_user_albums($access_token);
		return $albums->data;
	}

	public function get_user_album_photos()
	{
		$this->load->library('my_facebook');
		$album_photos = $this->my_facebook->get_user_album_photos($_POST['access_token'], $_POST['ida']);
		echo  json_encode($album_photos->data);
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

