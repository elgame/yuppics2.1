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