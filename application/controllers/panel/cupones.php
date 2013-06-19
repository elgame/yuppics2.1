<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cupones extends MY_Controller {
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
    $this->load->model('panel_cupones_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Cupones'
    );

    $params['cupones'] = $this->panel_cupones_model->getCupones();

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/cupones/index', $params);
    $this->load->view('panel/footer');
  }

  public function agregar()
  {
    $this->carabiner->js(array(
      array('panel/cupones/agregar.js'),
    ));

    $this->load->model('panel_cupones_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración - Cupones'
    );

    $this->configValidator();
    if($this->form_validation->run() == FALSE){
      $params['frm_errors'] = array(
          'title' => 'Error!',
          'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
          'ico' => 'error');
    } else  {
      $mdl_res = $this->panel_cupones_model->add();
      if ($mdl_res['passess']) {
        redirect(base_url('panel/cupones/agregar'));
      }
    }

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/cupones/agregar', $params);
    $this->load->view('panel/footer');
  }

  public function editar()
  {
    if (isset($_GET['id']))
    {
      $this->carabiner->js(array(
        array('panel/cupones/agregar.js'),
      ));

      $this->load->model('panel_cupones_model');

      $params['info_empleado'] = $this->info_empleado['info']; //info empleado
      $params['seo'] = array(
        'titulo' => 'Panel de Administración - Cupones'
      );

      $this->configValidator();
      if($this->form_validation->run() == FALSE){
        $params['frm_errors'] = array(
            'title' => 'Error!',
            'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
            'ico' => 'error');
      } else  {
        $mdl_res = $this->panel_cupones_model->update();
        if ($mdl_res['passess']) {
          redirect(base_url('panel/cupones/editar/?id='.$_GET['id'].'&msg=5'));
        }
      }

      $params['cupon'] = $this->panel_cupones_model->info($_GET['id']);

      if (isset($_GET['msg']))
        $params['frm_errors'] = $this->showMsgs($_GET['msg']);

      $this->load->view('panel/header', $params);
      $this->load->view('panel/general/menu', $params);
      $this->load->view('panel/cupones/editar', $params);
      $this->load->view('panel/footer');
    }
    else
      redirect(base_url('panel/cupones/?msg=1'));
  }

  public function eliminar()
  {
    if (isset($_GET['id']))
    {
      $this->load->model('panel_cupones_model');
      $this->panel_cupones_model->del($_GET['id']);

      redirect(base_url('panel/cupones/?msg=4'));
    }
    else
      redirect(base_url('panel/cupones/?msg=1'));
  }

  public function configValidator()
  {
    $this->load->library('form_validation');

    $rules = array(

      array('field' => 'pdatestart',
            'label' => 'Fecha de inicio',
            'rules' => ''),
      array('field' => 'pdateend',
            'label' => 'Fecha de termino',
            'rules' => ''),
      array('field' => 'pnombre',
            'label' => 'Nombre',
            'rules' => 'required|max_length[30]'),
      array('field' => 'pcodigo',
            'label' => 'Código',
            'rules' => 'required|max_length[30]'),
      array('field' => 'pcantidad',
            'label' => 'Cantidad',
            'rules' => 'required|is_natural'),
      array('field' => 'ptotalusos',
            'label' => 'Total de usos',
            'rules' => 'required|is_natural'),
      array('field' => 'pporcentaje',
            'label' => 'Porcentaje',
            'rules' => 'required|is_natural'),
    );

    $this->form_validation->set_rules($rules);
  }

  private function showMsgs($tipo, $msg='', $title='Cupones')
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
        $txt = 'El cupón se agregó correctamente.';
        $objs = '';
        $icono = 'success';
        break;
      case 4:
        $txt = 'El cupón se eliminó correctamente.';
        $objs = '';
        $icono = 'success';
        break;
      case 5:
        $txt = 'El cupón se modificó correctamente.';
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