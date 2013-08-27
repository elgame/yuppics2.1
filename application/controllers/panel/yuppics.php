<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class yuppics extends MY_Controller {
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
      redirect(base_url('panel/home/login'));
  }

  public function index(){

    $this->carabiner->js(array(
      array('general/msgbox.js'),
      array('panel/cupones/agregar.js'),
    ));

    $this->load->library('pagination');
    $this->load->model('panel_yuppics_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Yuppics'
    );

    $params['yuppics'] = $this->panel_yuppics_model->getAll();

    // echo "<pre>";
    //   var_dump($params['yuppics']);
    // echo "</pre>";exit;

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/yuppics/index', $params);
    $this->load->view('panel/footer');
  }

  /**************************************************************
   * Crea el pdf de un yuppic
   */
  public function genera_pdf(){
    ini_set('max_execution_time', 720);
    ini_set('memory_limit', '512M');

    $this->load->model('book_model');
    $this->load->library('MYpdfgeneral');
    // Creación del objeto de la clase heredada
    $pdf = new MYpdfgeneral('P', 'mm', array(320.2, 460.5));
    $size_pag = array(150, 195);
    $margin = array('x' => 10, 'y' => 29);


    $yupic = $this->book_model->getYuppic($this->input->get('yuppic'));

    if ($yupic) {
      //**************************************
      // Portada del book
      $pdf->AddPage();

      //fuente
      $fuente = 'helvetica';
      if ($yupic->font_cover != '') {
        $yupic->font_cover = str_replace('’', '', $yupic->font_cover);
        if($yupic->font_cover == 'Opens sans light')
          $fuente = 'OpenSans-Light';
        elseif($yupic->font_cover == 'Rockwell regular')
          $fuente = 'Rock';
        $pdf->AddFont($fuente,'');
      }

      $color = String::hex2rgb($yupic->background_color);
      $pdf->SetFillColor($color[0], $color[1], $color[2]); //color de fondo
      $color = String::hex2rgb($yupic->text_color);
      $pdf->SetTextColor($color[0], $color[1], $color[2]); //color de texto
      $pdf->Rect($margin['x'], $margin['y'], $size_pag[0], $size_pag[1], 'DF'); // rectangulo con color de fondo
      $size = $pdf->getSizeImage($yupic->background_img, 0, 0);

      if($yupic->bg_pattern == 1){ //si es un pattern se pone la imagen
        $count_x = ceil($size_pag[0]/$size[0]);
        $count_y = ceil($size_pag[1]/$size[1]);
        for ($rows=0; $rows < $count_y; $rows++) {
          for ($cols=0; $cols < $count_x; $cols++) {
            $pdf->Image($yupic->background_img, ($cols*$size[0])+$margin['x'], ($rows*$size[1])+$margin['y'], 0);
          }
        }
      }else{ //si no es pattern se redimenciona la imagen

        $info = array(
            'x'     => 0+$margin['x'],
            'y'     => $yupic->bg_img_y+$margin['y'],
            'w'     => ($size_pag[0]),
            'h'     => ($size_pag[1]),
            'pos_x' => $yupic->bg_img_x,
            'pos_y' => 0,
            );

        $size = $this->redimImgPhoto($size, $info);
        $size['h'] -= $yupic->bg_img_y;

        $name_file = explode('.', $yupic->background_img);
        $name_file = $name_file[0].rand(1, 9999).'.'.$name_file[1];
        $img_cortada = $this->cropImg($name_file, $yupic->background_img, $size, $info);

        $pdf->Image($img_cortada, $info['x'], $info['y'], $info['w'], $info['h']); // imagen de fondo
        // unlink($img_cortada);
      }
      //**************************************
      // ContraPortada del book
      $pdf->Rect($size_pag[0]+$margin['x'], $margin['y'], $size_pag[0], $size_pag[1], 'DF'); // rectangulo con color de fondo
      if($yupic->bg_pattern == 1){ //si es un pattern se pone la imagen
        $count_x = ceil($size_pag[0]/$size[0]);
        $count_y = ceil($size_pag[1]/$size[1]);
        for ($rows=0; $rows < $count_y; $rows++) {
          for ($cols=0; $cols < $count_x; $cols++) {
            $pdf->Image($yupic->background_img, ($cols*$size[0])+$size_pag[0]+$margin['x'], ($rows*$size[1])+$margin['y'], 0);
          }
        }
      }else{ //si no es pattern se redimenciona la imagen
        $pdf->Image($img_cortada, $info['x']+$size_pag[0], $info['y'], $info['w'], $info['h']); // imagen de fondo
        unlink($img_cortada);
      }

      //**Titulos de portada y contraportada
      // Se obtiene lo alto de la franja
      $pdf->SetFont($fuente, '', 30);
      $nb = 0;
      $nb=max($nb,$pdf->NbLines($size_pag[0], $yupic->title));
      $hfranja = intval(($pdf->FontSize*$nb+3)*2);
      //Se pone la posicion de la franja
      $titulos_posy = (($size_pag[1]) / 2);
      if($yupic->background_franja_position == 't')
        $titulos_posy = $margin['y'];
      elseif($yupic->background_franja_position == 'b')
        $titulos_posy = $margin['y']+$size_pag[1]-$hfranja;
      //si tiene url de franja se pone
      if($yupic->background_franja != ''){
        $size = $pdf->getSizeImage($yupic->background_franja, 0, 0);
        
        $info = array(
          'x'     => 0,
          'y'     => 0,
          'w'     => ($size[0] > $size_pag[0]? $size_pag[0]: $size[0]),
          'h'     => $hfranja,
          'pos_x' => 0,
          'pos_y' => 0, 
          );
        $size = $this->redimImgPhoto($size, $info);
        $name_file = explode('/', $yupic->background_franja);
        $name_file[count($name_file)-1] = 'franja_'.$name_file[count($name_file)-1];
        $name_file = implode('/', $name_file);
        $img_cortada = $this->cropImg($name_file, $yupic->background_franja, $size, $info);
        $size = $pdf->getSizeImage($img_cortada, 0, 0);

        if($size[0] > $size_pag[0]){ //si es una imagen grande
          $pdf->Image($img_cortada, $margin['x'], $titulos_posy, $info['w'], $info['h'] );
          $pdf->Image($img_cortada, $margin['x']+$size_pag[0], $titulos_posy, $info['w'], $info['h'] );
        }else{ //si es un pattern
          $count_x = ceil($size_pag[0]/$size[0]);
          $count_y = 1;
          for ($rows=0; $rows < $count_y; $rows++) {
            for ($cols=0; $cols < $count_x; $cols++) {
              $pdf->Image($img_cortada, ($cols*$size[0])+$margin['x'], ($rows*$size[1])+$titulos_posy, 0, $hfranja );
              $pdf->Image($img_cortada, ($cols*$size[0])+$margin['x']+$size_pag[0], ($rows*$size[1])+$titulos_posy, 0, $hfranja );
            }
          }
        }
        unlink($img_cortada);
      }else{
        $color = String::hex2rgb($yupic->background_franja_color);
        $pdf->SetFillColor($color[0], $color[1], $color[2]); //color de fondo
        $pdf->Rect($margin['x'], $titulos_posy, $size_pag[0], $hfranja, 'F');
        $pdf->Rect($margin['x']+$size_pag[0], $titulos_posy, $size_pag[0], $hfranja, 'F');
      }
      //Texto de portada
      $pdf->SetXY($margin['x'], $titulos_posy);
      $pdf->SetAligns(array('C'));
      $pdf->SetWidths(array($size_pag[0]));
      $pdf->Row(array($yupic->title), false, false);
      $pdf->SetFontSize(18);
      $pdf->SetX($margin['x']);
      $pdf->Row(array($yupic->author), false, false);
      //Texto de contraportada
      $pdf->SetFont($fuente, '', 30);
      $pdf->SetXY($size_pag[0]+$margin['x'], $titulos_posy);
      $pdf->Row(array($yupic->title), false, false);
      $pdf->SetFontSize(18);
      $pdf->SetX($size_pag[0]+$margin['x']);
      $pdf->Row(array($yupic->author), false, false);

      //limpia los sobrantes de la imagen
      $pdf->SetFillColor(255, 255, 255);
      $pdf->Rect(0, $size_pag[1]+$margin['y'], $size_pag[0]+$size_pag[0]+($margin['x']*2), $size_pag[1], 'F'); 
      $pdf->Rect($size_pag[0]+$size_pag[0]+$margin['x'], $margin['y'], $size_pag[0], $size_pag[1], 'F'); 


      //**************************************
      // paginas del book
      $conta_pag = 2;
      $conta_pos = 1;
      foreach ($yupic->pages as $key => $page) {
        if($conta_pag == 1){
          if($conta_pos == 1){
            $pdf->AddPage();
            $init_x    = $margin['x'];
            $init_y    = $margin['y'];
            $conta_pos = 2;
          }else{
            $init_x    = $margin['x']+$size_pag[0];
            $init_y    = $margin['y'];
            $conta_pag = 2;
            $conta_pos = 1;
          }
        }else{
          if($conta_pos == 1){
            $init_x    = $margin['x'];
            $init_y    = $margin['y']+$size_pag[1]+$margin['x']+5;
            $conta_pos = 2;
          }else{
            $init_x    = $margin['x']+$size_pag[0];
            $init_y    = $margin['y']+$size_pag[1]+$margin['x']+5;
            $conta_pag = 1;
            $conta_pos = 1;
          }
        }

        $pdf->Rect($init_x, $init_y, $size_pag[0], $size_pag[1], 'D');

        foreach ($page->images as $key2 => $photo) {
          $info = array(
            'x'     => ($photo->coord_x*$size_pag[0]/100)+$init_x,
            'y'     => ($photo->coord_y*$size_pag[1]/100)+$init_y,
            'w'     => ($photo->width*$size_pag[0]/100),
            'h'     => ($photo->height*$size_pag[1]/100),
            'pos_x' => $photo->pos_x,//($photo->pos_x*$size_pag[0]/100),
            'pos_y' => $photo->pos_y //($photo->pos_y*$size_pag[1]/100)
            );
          $pdf->SetFillColor(204, 204, 204);
          $pdf->Rect($info['x'], $info['y'], $info['w'], $info['h'], 'F');

          // $name_file = explode('/', $photo->url_img);
          // $img_cortada = $this->cropImg($name_file[count($name_file)-1], $photo->url_img,
          //  array('new_w' => $photo->width, 'new_h' => $photo->height, 'x' => abs($photo->pos_x), 'y' => abs($photo->pos_y) ),
          //  $info);

          $size = $pdf->getSizeImage($photo->url_img, 0, 0);
          $size = $this->redimImgPhoto($size, $info);

          $name_file = str_replace('PHOTOS/', '', $photo->url_img);
          $img_cortada = $this->cropImg($name_file, $photo->url_img, $size, $info);

          $pdf->Image($img_cortada, $info['x'], $info['y'], $info['w'], $info['h']); // foto
          unlink($img_cortada);

          if($photo->url_frame != '')
            $pdf->Image($photo->url_frame, $info['x'], $info['y'], $info['w'], $info['h']); // marco

          // $pdf->SetFillColor(255, 255, 255);
          // $pdf->Rect( ($info['x']+$info['w']), ($info['y']-$info['h']), $size_pag[0], ($info['h']+$info['h']), 'F');
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
        imagecolortransparent($newImage, imagecolorallocate($newImage, 0, 0, 0));
        break;
        case "image/pjpeg":
      case "image/jpeg":
      case "image/jpg":
        $source=imagecreatefromjpeg($image);
        break;
        case "image/png":
      case "image/x-png":
        $source=imagecreatefrompng($image);
        imagecolortransparent($newImage, imagecolorallocate($newImage, 0, 0, 0));
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






  public function desactivar()
  {
    if (isset($_GET['id']))
    {
      $this->load->model('panel_temas_model');
      $this->panel_temas_model->desactivar($_GET['id']);

      redirect(base_url('panel/temas/?msg=4'));
    }
    else
      redirect(base_url('panel/temas/?msg=1'));
  }

  public function activar()
  {
    if (isset($_GET['id']))
    {
      $this->load->model('panel_temas_model');
      $this->panel_temas_model->activar($_GET['id']);

      redirect(base_url('panel/temas/?msg=5'));
    }
    else
      redirect(base_url('panel/temas/?msg=1'));
  }


  private function showMsgs($tipo, $msg='', $title='Temas')
  {
    switch($tipo){
      case 1:
        $txt = 'El campo ID es requerido.';
        $objs = '';
        $icono = 'error';
        break;
      case 2: //Cuendo se valida con form_validation
        $txt = $msg;
        $icono = 'error';
        break;
      case 3:
        $txt = 'El tema se agregó correctamente.';
        $objs = '';
        $icono = 'success';
        break;
      case 4:
        $txt = 'El tema se desactivó correctamente.';
        $objs = '';
        $icono = 'success';
        break;
      case 5:
        $txt = 'El tema se activó correctamente.';
        $objs = '';
        $icono = 'success';
        break;
      case 6:
        $txt = 'El tema se modificó correctamente.';
        $objs = '';
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