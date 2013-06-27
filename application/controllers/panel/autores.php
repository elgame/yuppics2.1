<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class autores extends MY_Controller {
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
    $this->load->model('panel_autores_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Temas'
    );

    $params['autores'] = $this->panel_autores_model->getAll();

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/autores/index', $params);
    $this->load->view('panel/footer');
  }

  public function agregar()
  {
    $this->carabiner->css(array(
      array('libs/jquery.colorpicker2.css', 'screen'),
      // array('libs/jquery.colorpicker2_layout.css', 'screen'),
    ));

    $this->load->model('panel_autores_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Autores de Yuppics'
    );

    $this->configValidator();
    if($this->form_validation->run() == FALSE){
      $params['frm_errors'] = array(
          'title' => 'Error!',
          'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
          'ico' => 'error');
    } else  {
      $mdl_res = $this->panel_autores_model->add();
      if ($mdl_res['passess']) {
        redirect(base_url('panel/autores/agregar/?msg=3'));
      }
    }

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/autores/agregar', $params);
    $this->load->view('panel/footer');
  }

  public function editar()
  {
    if (isset($_GET['id']))
    {

    $this->load->model('panel_autores_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Editar Autor'
    );

    $this->configValidator();
    if($this->form_validation->run() == FALSE){
      $params['frm_errors'] = array(
          'title' => 'Error!',
          'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
          'ico' => 'error');
    } else  {
      $mdl_res = $this->panel_autores_model->editar();
      if ($mdl_res['passess']) {
        redirect(base_url('panel/autores/editar/?id='.$_GET['id'].'&msg=5'));
      }
    }

    $params['autor'] = $this->panel_autores_model->info($_GET['id']);

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/autores/editar', $params);
    $this->load->view('panel/footer');
    }
    else
      redirect(base_url('panel/temas/?msg=1'));
  }

  public function eliminar()
  {
    if (isset($_GET['id']))
    {
      $this->load->model('panel_autores_model');
      $this->panel_autores_model->delete($_GET['id']);

      redirect(base_url('panel/autores/?msg=4'));
    }
    else
      redirect(base_url('panel/autores/?msg=1'));
  }

  public function configValidator()
  {
    $this->load->library('form_validation');

    $rules = array(

      array('field' => 'pname',
            'label' => 'Nombre del Autor',
            'rules' => 'required|max_length[30]'),
    );

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
        $objs = '';
        $icono = 'error';
        break;
      case 3:
        $txt = 'El autor se agregó correctamente.';
        $objs = '';
        $icono = 'success';
        break;
      case 4:
        $txt = 'El autor se eliminó correctamente.';
        $objs = '';
        $icono = 'success';
        break;
      case 5:
        $txt = 'El autor se modificó correctamente.';
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