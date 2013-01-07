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



	/**
	 ******************** Seccion guardar fotos ************************
	 *******************************************************************
	 *
	 * @return [type] [description]
	 */
	public function photos()
	{
		if (! $this->session->userdata('id_yuppics'))
			redirect(base_url('yuppics/'));

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
		$res = $this->photos_model->getYuppicPhotos($this->session->userdata('id_yuppics'));
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

			$params['id_yuppic'] = $mdl_res;

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



	/**
	 ******************* Seccion personaliza fotos, libro ******************
	 ***********************************************************************
	 *
	 * @return [type] [description]
	 */
	public function book(){
		$this->carabiner->css(array(
			array('libs/jquery.jscrollpane.css', 'screen'),
			array('libs/jquery.jPages.css', 'screen'),
			array('skin/dashboard/style.css', 'screen'),
			array('skin/yuppics/style.css', 'screen')
		));

		$this->carabiner->js(array(
			array('libs/jquery.mousewheel.min.js'),
			array('libs/jquery.jscrollpane.min.js'),
			array('libs/jquery.form.js'),
			array('libs/jquery.jPages.min.js'),
			array('general/msgbox.js'),
			array('general/loader.js'),
			array('skin/yuppics_book.js')
		));

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['seo'] = array(
			'titulo' => 'Crear yuppic - yuppics'
		);

		$this->load->model('frames_model');
		$this->load->model('pages_model');
		$this->load->model('photos_model');
		$params['frames'] = $this->frames_model->getFrames();
		$params['pages']  = $this->pages_model->getPages();
		$params['photos'] = $this->photos_model->getYuppicPhotos($this->session->userdata('id_yuppics'));
		$params['page']   = $this->pages_model->getPage($this->session->userdata('id_yuppics'));

		if($this->session->userdata('id_yuppics')){
			// $params['theme_sel'] = $this->themes_model->getYuppicTheme($this->session->userdata('id_yuppics'));
		}


		// $params['themes'] = $this->load->view('skin/yuppics/themes_items', $params, true);
		$this->load->view('skin/header', $params);
		$this->load->view('skin/yuppics/book', $params);
		$this->load->view('skin/footer');
	}

	public function getFrame(){
		if ($this->input->get('id_frame')!==false && $this->input->get('id_img')!==false) {
			$this->load->model('frames_model');
			$response['marco'] = $this->frames_model->getFrame($this->input->get('id_frame'), $this->input->get('id_img'));
			$response['msg']   = $this->showMsgs(5);
		}else{
			$response['msg'] = $this->showMsgs(4);
		}

		echo json_encode($response);
	}

	public function save_page(){
		if ($this->input->post('id_page')!==false) {
			$this->load->model('pages_model');
			$response['page'] = $this->pages_model->savePage();
			$response['msg']  = $this->showMsgs(5, 'Se guardo la pagina correctamente.');
		}else{
			$response['msg'] = $this->showMsgs(2, 'Parametros faltantes');
		}

		echo json_encode($response);
	}

	public function load_page(){
		if ($this->input->get('num_pag')!==false) {
			$this->load->model('pages_model');
			$response['page'] = $this->pages_model->getPage($this->session->userdata('id_yuppics'), $this->input->get('num_pag'));
			$response['msg']  = $this->showMsgs(5, '');
		}else{
			$response['msg'] = $this->showMsgs(2, 'Especifica la pagina');
		}

		echo json_encode($response);
	}

	public function delete_page(){
		if ($this->input->post('id_ypage')!==false) {
			$this->load->model('pages_model');
			$res = $this->pages_model->deletePage($this->input->post('id_ypage'));
			$response['msg']  = $this->showMsgs(5, 'La página se elimino correctamente');
		}else{
			$response['msg'] = $this->showMsgs(2, 'Especifica la pagina');
		}
		echo json_encode($response);
	}

	public function magic_book(){
		if ($this->input->get('id_page')!==false && $this->input->get('id_frame')!==false) {
			$this->load->model('pages_model');
			$res = $this->pages_model->magicBook();
			$response['msg']  = $this->showMsgs(5, 'Se crearon las paginas correctamente');
		}else{
			$response['msg'] = $this->showMsgs(2, 'Especifica la página y el marco');
		}
		echo json_encode($response);
	}

	/**
	 * Descarga el listado de cuentas por pagar en formato pdf
	 */
	public function genera_pdf(){
		$this->load->model('book_model');
		$this->load->library('MYpdfgeneral');
		// Creación del objeto de la clase heredada
		$pdf = new MYpdfgeneral('P', 'mm', 'Letter');

		$yupic = $this->book_model->getYuppic($this->session->userdata('id_yuppics'));

		//**************************************
		// Portada del book
		$pdf->AddPage();

		$color = String::hex2rgb($yupic->background_color);
		$pdf->SetFillColor($color[0], $color[1], $color[2]); //color de fondo
		$color = String::hex2rgb($yupic->text_color);
		$pdf->SetTextColor($color[0], $color[1], $color[2]); //color de texto
		$pdf->Rect(0, 0, $pdf->CurPageSize[0], $pdf->CurPageSize[1], 'F'); // rectangulo con color de fondo
		$size = $pdf->getSizeImage($yupic->background_img, 0, 0);
		$y = 0;
		if ($size[1] < $pdf->CurPageSize[1]) // se centra la imagen a lo alto
			$y = (($pdf->CurPageSize[1]-$size[1]) / 2);
		$pdf->Image($yupic->background_img, 0, $y, 0); // se establece la imagen de fondo

		$pdf->SetFont('Arial', 'B', 30);
		$pdf->SetXY(0, (($pdf->CurPageSize[1]-20) / 2));

		$pdf->SetAligns(array('C'));
		$pdf->SetWidths(array(0));
		$pdf->Row(array($yupic->title), false, false);
		$pdf->SetFontSize(18);
		$pdf->Row(array($yupic->author), false, false);


		//**************************************
		// paginas del book
		foreach ($yupic->pages as $key => $page) {
			$pdf->AddPage();

			foreach ($page->images as $key2 => $photo) {
				$info = array(
					'x' => ($photo->coord_x*$pdf->CurPageSize[0]/100),
					'y' => ($photo->coord_y*$pdf->CurPageSize[1]/100),
					'w' => ($photo->width*$pdf->CurPageSize[0]/100),
					'h' => ($photo->height*$pdf->CurPageSize[1]/100)
					);
				$pdf->SetFillColor(204, 204, 204);
				$pdf->Rect($info['x'], $info['y'], $info['w'], $info['h'], 'F');

				$size = $pdf->getSizeImage($photo->url_img, 0, 0);
				$size = $this->redimImgPhoto($size, $info);
				$pdf->Image($photo->url_img, $info['x'], $info['y'], $size['w'], $size['h']); // foto

				$pdf->Image($photo->url_frame, $info['x'], $info['y'], $info['w'], $info['h']); // marco

				$pdf->SetFillColor(255, 255, 255);
				$pdf->Rect( ($info['x']+$info['w']), ($info['y']-.5), $pdf->CurPageSize[0], ($info['h']+1), 'F');
			}
		}

		$pdf->Output('cuentas_x_pagar.pdf', 'I');
	}

	function redimImgPhoto($size, $info){
		$diff_pix = 0; 
		$resize   = array('w'=>0, 'h'=>0);

		if ($info['w'] > $info['h']) {
			$diff_pix = $info['w'] / $size[0];

			$resize['w'] = $info['w'];
			$resize['h'] = ($diff_pix * $size[1]);
		} else {
			$diff_pix = $info['h'] / $size[1];

			$resize['w'] = ($diff_pix * $size[0]);
			$resize['h'] = $info['h'];
		}
		return $resize;
	}



	private function showMsgs($tipo, $msg='', $title='Yuppics')
	{
		$objs = '';
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

			case 4:
				$txt = 'Selecciona la imagen y el marco a utilizar.';
				$icono = 'error';
				break;
			case 5:
				$txt = $msg;
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

