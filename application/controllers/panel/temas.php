<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class temas extends MY_Controller {
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
    $this->load->model('panel_temas_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Temas'
    );

    $params['temas'] = $this->panel_temas_model->getTemas();

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/temas/index', $params);
    $this->load->view('panel/footer');
  }

  public function agregar()
  {
    $this->carabiner->css(array(
      array('libs/jquery.colorpicker2.css', 'screen'),
      // array('libs/jquery.colorpicker2_layout.css', 'screen'),
    ));

    $this->carabiner->js(array(
      array('libs/jquery.colorpicker2.js'),
      array('libs/jquery.colorpicker2eye.js'),
      array('libs/jquery.colorpicker2layout.js'),
      array('libs/jquery.colorpicker2utils.js'),
      array('panel/temas/agregar.js'),
    ));

    $this->load->model('panel_temas_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Temas'
    );

    $this->configValidator();
    if($this->form_validation->run() == FALSE){
      $params['frm_errors'] = array(
          'title' => 'Error!',
          'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
          'ico' => 'error');
    } else  {
      $mdl_res = $this->panel_temas_model->add();
      if ($mdl_res['passess']) {
        redirect(base_url('panel/temas/agregar/?msg=3'));
      }
    }

    $params['autores'] = $this->db->select("id_autor, name")
                           ->from('themes_autor')->get()->result();

    $params['tags'] = $this->db->select("id_tag, name")
                           ->from('tags')->get()->result();

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/temas/agregar', $params);
    $this->load->view('panel/footer');
  }

  public function editar()
  {
    if (isset($_GET['id']))
    {
      $this->carabiner->css(array(
      array('libs/jquery.colorpicker2.css', 'screen'),
      // array('libs/jquery.colorpicker2_layout.css', 'screen'),
    ));

    $this->carabiner->js(array(
      array('libs/jquery.colorpicker2.js'),
      array('libs/jquery.colorpicker2eye.js'),
      array('libs/jquery.colorpicker2layout.js'),
      array('libs/jquery.colorpicker2utils.js'),
      array('panel/temas/agregar.js'),
    ));

    $this->load->model('panel_temas_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Temas'
    );

    $this->configValidator('editar');
    if($this->form_validation->run() == FALSE){
      $params['frm_errors'] = array(
          'title' => 'Error!',
          'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
          'ico' => 'error');
    } else  {
      $mdl_res = $this->panel_temas_model->editar();
      if ($mdl_res['passess']) {
        redirect(base_url('panel/temas/editar/?id='.$_GET['id'].'&msg=6'));
      }
    }

    $params['tema'] = $this->panel_temas_model->info($_GET['id']);

    $params['autores'] = $this->db->select("id_autor, name")
                           ->from('themes_autor')->get()->result();

    $params['tags'] = $this->db->select("id_tag, name")
                           ->from('tags')->get()->result();

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/temas/editar', $params);
    $this->load->view('panel/footer');
    }
    else
      redirect(base_url('panel/temas/?msg=1'));
  }

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

  public function configValidator($accion = 'agregar')
  {
    $this->load->library('form_validation');

    $rules = array(

      array('field' => 'pautor',
            'label' => 'Autor',
            'rules' => 'required'),
      array('field' => 'pname',
            'label' => 'Nombre del Tema',
            'rules' => 'required'),
      array('field' => 'pbgcolor',
            'label' => 'Background Color',
            'rules' => 'required|max_length[10]'),
      array('field' => 'ptxtcolor',
            'label' => 'Text Color',
            'rules' => 'required|max_length[10]'),
      array('field' => 'ptags[]',
            'label' => 'Tags',
            'rules' => ''),
      array('field' => 'pmastags',
            'label' => 'Agregar mas tags',
            'rules' => ''),
      array('field' => 'pbgcolor_franja',
            'label' => 'Background Color Franja',
            'rules' => 'required|max_length[10]'),
      array('field' => 'pfuente_cover',
            'label' => 'Tipo de fuente',
            'rules' => 'required'),
    );

    if (isset($_POST))
    {
      if ( empty($_POST['pmastags']))
      {
        $rules[] = array('field' => 'ptags[]',
                         'label' => 'Tags',
                         'rules' => 'required');
      }
    }

    if ($accion === 'agregar')
    {
      if (isset($_POST))
      {
        if ( empty($_FILES['pimg']['name']))
        {
          $rules[] = array('field' => 'pimg',
                           'label' => 'Imagen',
                           'rules' => 'required');
        }
      }
    }

    $this->form_validation->set_rules($rules);
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