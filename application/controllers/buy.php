<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buy extends MY_Controller {
  /**
   * Evita la validacion (enfocado cuando se usa ajax). Ver mas en privilegios_model
   * @var unknown_type
   */
  private $excepcion_privilegio = array('');

  public function _remap($method)
  {
    $this->load->model("customer_model");
    if($this->customer_model->checkSession())
    {
      $this->info_empleado = $this->customer_model->getInfoCustomer($this->session->userdata('id_usuario'), true);
      $this->{$method}();
    }
    else
      redirect(base_url());
  }

  public function index(){
    redirect(base_url()); // Redirecciona al Dashboard
  }

  /**
   *    Muestra la orden de compra con los yuppics especificados.
   *    @return [type] [description]
   */
  public function order()
  {
    $this->load->model('buy_model');

    // Verifica si los yuppics a comprar existen y si estan en alguna orden.
    $verificacion = $this->buy_model->verificaYuppic($this->input->get('y'));

    if (!$verificacion)
      redirect(base_url());

    $this->carabiner->css(array(
      array('skin/buy/buy.css', 'screen'),
    ));

    $this->carabiner->js(array(
      array('general/loader.js'),
      array('libs/jquery.form.js'),
      array('libs/jquery.formautofill.min.js'),
      array('general/msgbox.js'),
      array('skin/perfil.js'),
      array('skin/form_ajax.js'),
      array('skin/buy.js'),
      array('general/util.js'),
    ));

    // Carrito de compras
    $this->load->model('book_model');
    $params['carrito_compra'] = $this->book_model->getShoppingCart();


    $this->load->library('form_validation');
    if ($this->form_validation->run() === FALSE)
    {
      $params['frm_errors'] = array(
                              'objs' => 'send_alert',
                              'title' => '',
                              'msg'   =>  preg_replace("[\n|\r|\n\r]", '', validation_errors()),
                              'ico'   => 'error');
    }
    else
    {
      $this->load->model('buy_model');
      $this->buy_model->pay();
    }

    $params['info_customer'] = $this->info_empleado['info']; //info empleado
    $params['product_yuppic'] = $this->info_empleado['yuppic']; //info yuppic
    $params['info_dash'] = $this->info_empleado['yuppic_compr']; //Yuppics comprados contador
    $params['seo']           = array('titulo'=>'Yuppics - Resumen de compra');

    $this->load->model('address_book_model');
    $this->load->model('states_model');
    $params['address_books'] = $this->address_book_model->getAddressBooks($this->session->userdata('id_usuario'));
    $params['states']        = $this->states_model->getStates(1);

    if (! $verificacion['status_general'])
      $params['throw_alert'] = array('true', 'Uno o más de los Yuppics seleccionados para la compra no existen o ya fueron agregados en otra orden de compra.');

    $params['yuppics'] = $verificacion;

    $this->load->view('skin/header', $params);
    $this->load->view('skin/general/menu', $params);
    $this->load->view('skin/yuppics/buy', $params);
    $this->load->view('skin/general/right-bar', $params);
    $this->load->view('skin/footer', $params);
  }

  public function valid_code()
  {
    $this->load->model('buy_model');
    $res_mdl = $this->buy_model->valid_code();

    if ($res_mdl)
    {
      $params['frm_errors'] = array(
                              'title' => '',
                              'msg'   => 'El código se cargo correctamente',
                              'ico'   => 'success',
                              'data'  => array('id'            => $res_mdl->id,
                                               'type'          => $res_mdl->type,
                                               'amount'        => $res_mdl->amount,
                                               'amount_format' => String::formatoNumero($res_mdl->amount).'MXN'));
    }
    else
    {
      $params['frm_errors'] = array(
                              'title' => '',
                              'msg'   => 'El código no es valido ó ya no es utilizable',
                              'ico'   => 'error');
    }

    echo json_encode($params);
  }

  /**
   *    Muestra un mensaje si el pago se realizo correctamente.
   *    Publica en el muro del usuario.
   */
  public function success()
  {

    $this->post_facebook(); // Publica en el muro de facebook del usuario

    $this->session->unset_userdata('id_yuppics'); // Elimina de la session el parametro
                                                  // id_yuppics para evitar que se vuelva a cargar.

    $params['seo']    = array('titulo'=> 'Yuppics - Pago Exitoso');
    $params['status'] = TRUE;
    $params['order']  = $_GET['order'];

    $this->load->model('buy_model');
    $this->buy_model->success($params['order']);

    $this->load->view('skin/header', $params);
    $this->load->view('skin/general/menu', $params);
    $this->load->view('skin/yuppics/pay', $params);
    $this->load->view('skin/general/right-bar', $params);
    $this->load->view('skin/footer', $params);

  }

  /**
   *    Muestra un avizo si el pago se cancelo o no se pudo concretar.
   */
  public function cancel()
  {

    $this->session->unset_userdata('id_yuppics'); // Elimina de la session el parametro
                                                  // id_yuppics para evitar que se vuelva a cargar.

    $params['seo']    = array('titulo'=> 'Yuppics - Pago Cancelado');
    $params['status'] = FALSE;
    $params['order']  = $_GET['order'];

    $this->load->model('buy_model');
    $this->buy_model->cancelOrder($params['order']);

    $this->load->view('skin/header', $params);
    $this->load->view('skin/general/menu', $params);
    $this->load->view('skin/yuppics/pay', $params);
    $this->load->view('skin/general/right-bar', $params);
    $this->load->view('skin/footer', $params);
  }

  /**
   *    Publica en el muro del usuario un mensaje que realizo la compra
   *    de Yuppics
   */
  private function post_facebook()
  {
    $this->load->library("my_facebook");
    $config = array(
          'redirect_uri' => base_url('buy/success?order='.$_GET['order']),
          'scope'        => 'user_about_me, email, user_photos, friends_photos',
          'display'      => ''
    );

    $this->my_facebook->initialize($config);
    $access_token = $this->my_facebook->oauth();

    $url_page = "https://graph.facebook.com/me/feed";

    $message = "Hola amigos, acabo de comprar un Yuppic y está genial <3 Arma el tuyo no esperes más!!! ";
    $data      = array(
                'access_token' => $access_token,
                'message'      => $message,
                'link'         => base_url(),
                'picture'      => '',
                'name'         => 'Yuppics',
                'caption'      => 'Descripción',
                'description'  => '',
                'properties'   => '',
                'actions'      => json_encode (array('name'=>'Yuppics', 'link'=>base_url())));

    $this->my_facebook->post($url_page, $data);
  }

}

/* End of file Buy.php */
/* Location: ./application/controllers/Buy.php */
