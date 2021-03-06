<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class yuppics extends MY_Controller {
	/**
	 * Evita la validacion (enfocado cuando se usa ajax). Ver mas en privilegios_model
	 * @var unknown_type
	 */
	private $excepcion_privilegio = array('');

	public function _remap($method){
		$this->load->model("customer_model");

		if ($method == 'save_aviary') {
			$this->{$method}();
		}else if($this->customer_model->checkSession()){
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
		// $this->session->set_userdata('id_yuppics', 2);
		$this->carabiner->css(array(
			// array('libs/jquery.minicolors.css', 'screen'),
			array('libs/jquery.colorpicker2.css', 'screen'),
			array('libs/jquery.colorpicker2_layout.css', 'screen'),
			array('libs/jquery.jPages.css', 'screen'),
			array('skin/dashboard/style.css', 'screen'),
			array('skin/yuppics/style.css', 'screen')
		));

		$this->carabiner->js(array(
			// array('libs/jquery.minicolors.js'),
			array('libs/jquery.colorpicker2.js'),
			array('libs/jquery.colorpicker2eye.js'),
			array('libs/jquery.colorpicker2layout.js'),
			array('libs/jquery.colorpicker2utils.js'),
			array('libs/jquery.form.js'),
			array('libs/jquery.jPages.min.js'),
			array('general/loader.js'),
			array('skin/yuppics_tema.js')
		));

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['seo'] = array(
			'titulo' => 'Crear yuppic, Seleccionar tema - yuppics'
		);

		// Carrito de compras
		$this->load->model('book_model');
		$params['carrito_compra'] = $this->book_model->getShoppingCart();

		$this->load->model('themes_model');
		$params['themes'] = $this->themes_model->getThemes();

		if($this->input->get('new')=='si')
			$this->session->unset_userdata('id_yuppics');

		if($this->session->userdata('id_yuppics')){
			$params['theme_sel'] = $this->themes_model->getYuppicTheme($this->session->userdata('id_yuppics'));
		}else{ //carga el primer tema
			$params['theme_sel'] = $this->themes_model->getThemeDefault();
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
	 * Carga la imagen de la franja al servidor
	 * @return [type] [description]
	 */
	public function theme_franja_img(){
		$this->load->library('my_upload');

		ini_set('memory_limit', '64M');

		$path_imgs = 'yuppics/themes/temp/';

		$config_upload = array(
			'upload_path'     => APPPATH.$path_imgs,
			'allowed_types'   => 'jpg|png',
			'max_size'        => '2048',
			'encrypt_name'    => TRUE
		 );

		$this->my_upload->initialize($config_upload);
		$this->my_upload->do_resize = false;
		
		$data = $this->my_upload->do_upload('imagen_franja');

		$a = explode('/', $data['full_path']);
		$b = explode('.', $a[count($a)-1]);
		$data['path'] = APPPATH.$path_imgs.$b[0].'.'.$b[1];

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
			array('skin/dashboard/style.css', 'screen'),
			array('skin/yuppics/style.css', 'screen'),
		));

		$this->carabiner->js(array(
			array('libs/jquery.mousewheel.min.js'),
			array('libs/jquery.jscrollpane.min.js'),
			array('libs/jquery.form.js'),
			// array('libs/jquery.jPages.min.js'),
			array('general/loader.js'),
			array('skin/yuppics_photos.js'),
		));

		// Carrito de compras
		$this->load->model('book_model');
		$params['carrito_compra'] = $this->book_model->getShoppingCart();

		$params['status']->progress = '50';
		$params['seo'] = array('titulo' => 'Yuppics - Seleccionar Fotografías');

		$params['albums'] = $this->get_user_albums($access_token);
		$params['access_token'] = $access_token;

    $params['max_fotos'] = $this->db->select('max_fotos')->from('config')->get()->row()->max_fotos;

		$this->load->model('photos_model');
		$res = $this->photos_model->getYuppicPhotos($this->session->userdata('id_yuppics')); //$this->session->userdata('id_yuppics')
		$params['totalp'] = 0;
		if ($res){
			$params['photos'] = $res;
			$params['width'] = count($params['photos']) * 195; // 165
			$params['totalp'] = count($params['photos']);
		}

		$this->load->view('skin/header', $params);
		$this->load->view('skin/yuppics/photos', $params);
		$this->load->view('skin/footer', $params);
	}

	// public function photos_save()
	// {
	// 	$this->load->library('form_validation');
	// 	if ($this->form_validation->run() === FALSE)
	// 	{
	// 		$params['frm_errors'] = array(
	// 								'title' => '',
	// 								'msg'   => 'Selecciona al menos una foto o imagen para continuar.',
	// 								'ico'   => 'error');
	// 	}
	// 	else
	// 	{
	// 		$this->load->model('photos_model');
	// 		$mdl_res = $this->photos_model->save_photos();

	// 		$params['id_yuppic'] = $mdl_res;

	// 		$params['frm_errors'] = array(
	// 				'title' => '',
	// 				'msg'   => 'xxxxx',
	// 				'ico'   => 'success');
	// 	}

	// 	echo json_encode($params);
	// }

  public function photos_upload()
  {
    $this->load->model('photos_model');
    $res = $this->photos_model->save_photos();
    echo json_encode($res);
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
		$access_token = ($access_token) ? $access_token : $_GET['access_token'];
		$photos = $this->my_facebook->get_user_photos($access_token);
		echo $photos;
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
    // var_dump();exit;
		$album_photos = $this->my_facebook->get_user_album_photos($_GET['access_token'], $_GET['ida']);
	  echo $album_photos;
  }

  public function get_next_photos()
  {
    $this->load->library('my_facebook');
    $photos = $this->my_facebook->get_next_photos_page($_GET['url']);
    echo $photos;
  }

  public function get_prev_photos()
  {
    $this->load->library('my_facebook');
    $photos = $this->my_facebook->get_previuos_photos_page($_GET['url']);
    echo $photos;
  }

	/**
	 ******************* Seccion personaliza fotos, libro ******************
	 ***********************************************************************
	 *
	 * @return [type] [description]
	 */
	public function book(){
		if (! $this->session->userdata('id_yuppics'))
			redirect(base_url('yuppics/'));

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
			array('http://feather.aviary.com/js/feather.js'),
			array('general/msgbox.js'),
			array('general/loader.js'),
			array('skin/yuppics_book.js'),
			// array('skin/yuppics_photos.js'),
		));

		$params['info_customer'] = $this->info_empleado['info']; //info empleado
		$params['seo'] = array(
			'titulo' => 'Crear yuppic - yuppics'
		);

		// Carrito de compras
		$this->load->model('book_model');
		$params['carrito_compra'] = $this->book_model->getShoppingCart();

		$this->load->model('frames_model');
		$this->load->model('pages_model');
		$this->load->model('photos_model');
		$this->load->model('themes_model');
		$params['frames'] = $this->frames_model->getFrames();
		$params['pages']  = $this->pages_model->getPages();
		$params['photos'] = $this->photos_model->getYuppicPhotos($this->session->userdata('id_yuppics'));
		$params['page']   = $this->pages_model->getPage($this->session->userdata('id_yuppics'));
		$params['theme_sel'] = $this->themes_model->getYuppicTheme($this->session->userdata('id_yuppics'));

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

	public function save_aviary(){
		$image_data = file_get_contents($_REQUEST['url']);
		file_put_contents($_REQUEST['postdata'], $image_data);
	}




	/***********************************************************
	 *Eliminar un yuppic definitivamente
	 */
	public function eliminar(){
		if(isset($_GET['yuppic']{0})){
			$this->load->model('yuppics_model');
			$res = $this->yuppics_model->delete($_GET['yuppic']);

			if($res == 1)
				redirect(base_url('?msg=4'));
			else
				redirect(base_url('?msg=5'));
		}else
			redirect(base_url('?msg=1'));
	}
	public function desactivar(){
		if(isset($_GET['yuppic']{0})){
			$this->load->model('yuppics_model');
			$res = $this->yuppics_model->desactivar($_GET['yuppic']);

			if($res == 1)
				redirect(base_url('?msg=4'));
			else
				redirect(base_url('?msg=5'));
		}else
			redirect(base_url('?msg=1'));
	}

	/**************************************************************
	 * Crea el pdf de un yuppic
	 */
	public function genera_pdf(){
		$this->load->model('book_model');
		$this->load->library('MYpdfgeneral');
		// Creación del objeto de la clase heredada
		$pdf = new MYpdfgeneral('P', 'mm', array(145, 185));

		$yupic = $this->book_model->getYuppic($this->input->get('yuppic'));

		if ($yupic) {
			//**************************************
			// Portada del book
			$pdf->AddPage();

			$color = String::hex2rgb($yupic->background_color);
			$pdf->SetFillColor($color[0], $color[1], $color[2]); //color de fondo
			$color = String::hex2rgb($yupic->text_color);
			$pdf->SetTextColor($color[0], $color[1], $color[2]); //color de texto
			$pdf->Rect(0, 0, $pdf->CurPageSize[0], $pdf->CurPageSize[1], 'F'); // rectangulo con color de fondo
			$size = $pdf->getSizeImage($yupic->background_img, 0, 0);

			if($yupic->bg_pattern == 1){ //si es un pattern se pone la imagen
				$count_x = ceil($pdf->CurPageSize[0]/$size[0]);
				$count_y = ceil($pdf->CurPageSize[1]/$size[1]);
				for ($rows=0; $rows < $count_y; $rows++) {
					for ($cols=0; $cols < $count_x; $cols++) {
						$pdf->Image($yupic->background_img, ($cols*$size[0]), ($rows*$size[1]), 0);
					}
				}
			}else{ //si no es pattern se redimenciona la imagen

				$info = array(
						'x'     => 0,
						'y'     => $yupic->bg_img_y,
						'w'     => ($pdf->CurPageSize[0]),
						'h'     => ($pdf->CurPageSize[1]),
						'pos_x' => $yupic->bg_img_x,
						'pos_y' => 0,
						);

				$size = $this->redimImgPhoto($size, $info);
				$size['h'] -= $yupic->bg_img_y;

				$name_file = explode('.', $yupic->background_img);
				$name_file = $name_file[0].rand(1, 9999).'.'.$name_file[1];
				$img_cortada = $this->cropImg($name_file, $yupic->background_img, $size, $info);

				$pdf->Image($img_cortada, $info['x'], $info['y'], $info['w'], $info['h']); // imagen de fondo
				unlink($img_cortada);
			}



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
						'x'     => ($photo->coord_x*$pdf->CurPageSize[0]/100),
						'y'     => ($photo->coord_y*$pdf->CurPageSize[1]/100),
						'w'     => ($photo->width*$pdf->CurPageSize[0]/100),
						'h'     => ($photo->height*$pdf->CurPageSize[1]/100),
						'pos_x' => $photo->pos_x,//($photo->pos_x*$pdf->CurPageSize[0]/100),
						'pos_y' => $photo->pos_y //($photo->pos_y*$pdf->CurPageSize[1]/100)
						);
					$pdf->SetFillColor(204, 204, 204);
					$pdf->Rect($info['x'], $info['y'], $info['w'], $info['h'], 'F');

					// $name_file = explode('/', $photo->url_img);
					// $img_cortada = $this->cropImg($name_file[count($name_file)-1], $photo->url_img,
					// 	array('new_w' => $photo->width, 'new_h' => $photo->height, 'x' => abs($photo->pos_x), 'y' => abs($photo->pos_y) ),
					// 	$info);

					$size = $pdf->getSizeImage($photo->url_img, 0, 0);
					$size = $this->redimImgPhoto($size, $info);

					$name_file = str_replace('PHOTOS/', '', $photo->url_img);
					$img_cortada = $this->cropImg($name_file, $photo->url_img, $size, $info);

					$pdf->Image($img_cortada, $info['x'], $info['y'], $info['w'], $info['h']); // foto
					unlink($img_cortada);

					if($photo->url_frame != '')
						$pdf->Image($photo->url_frame, $info['x'], $info['y'], $info['w'], $info['h']); // marco

					// $pdf->SetFillColor(255, 255, 255);
					// $pdf->Rect( ($info['x']+$info['w']), ($info['y']-$info['h']), $pdf->CurPageSize[0], ($info['h']+$info['h']), 'F');
					// $pdf->Rect( 0, ($info['y']-$info['h']), $info['x'], ($info['h']+$info['h']), 'F');
				}
			}
		}

		$pdf->Output('yuppic.pdf', 'I');
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
	private function cropImg($thumb_image_name, $image, $conf, $pag){
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);

		$zoom_xciento = $conf['w']*100/$imagewidth;

		$newImageWidth  = $pag['w']*100/$zoom_xciento;
		$newImageHeight = $pag['h']*100/$zoom_xciento;

		$src_x = abs($pag['pos_x'])*$newImageWidth/100;
		$src_y = abs($pag['pos_y'])*$newImageHeight/100;

		$src_width = $newImageWidth;
		$src_height = $newImageHeight;


		$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image);
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image);
				break;
		    case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image);
				break;
	  	}
		imagecopyresampled($newImage, $source, 0, 0, $src_x, $src_y, $newImageWidth, $newImageHeight,
			$src_width, $src_height);
		switch($imageType) {
			case "image/gif":
		  		imagegif($newImage,$thumb_image_name);
				break;
	      	case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
		  		imagejpeg($newImage,$thumb_image_name,90);
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);
				break;
	    }
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}
	/**
	 * / pdf yuppic
	 ********************************************************/


	/**
	 * Asigna el id del yuppic en la session del usuairo para modificarlo
	 * en el apartado de crear yuppic
	 */
	public function set_yuppic(){
		if (isset($_GET['yuppic']))
			$this->session->set_userdata('id_yuppics', $_GET['yuppic']);

		redirect(base_url('yuppics'));
	}

	public function shop_car(){
		if (is_array($this->input->post('yupics')) && is_array($this->input->post('quantity')) ) {
			$car_items = '';
			foreach ($this->input->post('yupics') as $key => $value) {
				$this->db->update('yuppics', array('quantity' => $_POST['quantity'][$key]), "id_yuppic = ".$value);
				$car_items .= ','.$value;
			}

			$response['items'] = substr($car_items, 1);

			$response['msg']  = $this->showMsgs(5, '');
		}else{
			$response['msg'] = $this->showMsgs(2, 'El carro de compras esta vacio');
		}
		echo json_encode($response);
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

