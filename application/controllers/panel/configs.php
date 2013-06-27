<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class configs extends MY_Controller {
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

      array('panel/configs/index.js'),

    ));

    $this->load->model('panel_configs_model');

    $params['info_empleado'] = $this->info_empleado['info']; //info empleado
    $params['seo'] = array(
      'titulo' => 'Panel de Administración'
    );

    $this->configValidator();
    if($this->form_validation->run() == FALSE){
      $params['frm_errors'] = array(
          'title' => 'Error!',
          'msg' => preg_replace("[\n|\r|\n\r]", '', validation_errors()),
          'ico' => 'error');
    }else{
      $mdl_res = $this->panel_configs_model->update();
      if ($mdl_res['passess']) {
        redirect(base_url('panel/configs/?msg=3'));
      }
    }

    $params['configs'] = $this->panel_configs_model->getConfigs();

    if (isset($_GET['msg']))
      $params['frm_errors'] = $this->showMsgs($_GET['msg']);

    $this->load->view('panel/header', $params);
    $this->load->view('panel/general/menu', $params);
    $this->load->view('panel/configs/index', $params);
    $this->load->view('panel/footer');
  }

  public function configValidator()
  {
    $this->load->library('form_validation');

    $rules = array(

      array('field' => 'pprecio',
            'label' => 'Precio',
            'rules' => 'required|numeric'),
      array('field' => 'pFotosMax',
            'label' => 'Fotos Máximas',
            'rules' => 'required|is_natural'),
      array('field' => 'pporcentaje',
            'label' => 'Porcentaje',
            'rules' => 'required|is_natural'),
    );

    $this->form_validation->set_rules($rules);
  }

  private function showMsgs($tipo, $msg='', $title='Configuraciones')
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
        $txt = 'La configuración se actualizo correctamente.';
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