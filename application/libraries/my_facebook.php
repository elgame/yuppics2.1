<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * My CodeIgniter Library
 *################################################################
 *
 * @author  Indigo Dev Team
 * @since   Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------
/**
 * @category	Facebook API
 * http://developers.facebook.com/docs/reference/api/
 */
class  my_facebook {

	private $CI; // Instancia de Codeigniter

	// Vars
  public $APP_ID = '512075685472594'; // FACEBOOK APP ID
	private $APP_SECRET = 'c49714bb2f943ff53a2453099910bf25'; // FACEBOOK APP SECRET
  private $graph_url = 'https://graph.facebook.com/';

  private $redirect_uri = '';	// URL A LA QUE FACEBOOK RE-DIRECCIONARA
  private $cancel_url = '';
  private $scope = '';	// PERMISOS QUE LA APP PEDIRA AL USUARIO. MAS PERMISOS EN http://developers.facebook.com/docs/concepts/login/permissions-login-dialog/
  private $display = '';

  /**
   * Constructor
   * @param  array $config=array('redirect_uri'=>'http://www.yuppics.com/', 'scope'=> '')
   */
  public function __construct($config=array())
  {
  	// Obtiene la instancia de condeigniter
    $this->CI =& get_instance();

    if (count($config) > 0)
    	$this->initialize($config);

    log_message('debug', "my_facebook Class Initialized");
  }

  /**
   * Inicializa la configuracion de facebook
   * @param  array $config=array('redirect_uri'=>'http://www.yuppics.com/', 'scope'=> '')
   */
  public function initialize($config=array())
  {
    	foreach ($config as $key => $value) {
    		$this->{$key} = $value;
    	}
    }

    /**
     * Realiza la autenticacion del usuario en facebook
     *
     * http://developers.facebook.com/docs/howtos/login/server-side-login/
     * http://developers.facebook.com/docs/reference/dialogs/oauth/
     * @return string Access Token
     */
    public function oauth()
    {
      $this->validate_error();
      if (!isset($_SESSION))
        session_start();

      $code = isset($_GET['code'])?$_GET['code']:'';
      $state = isset($_GET['state'])?$_GET['state']:'';

      if (empty($code))
      {
        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // PROTECCION CSRF
        $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" . $this->APP_ID .
        "&redirect_uri=" . urlencode($this->redirect_uri.($this->display==='popup'?'?popup=t':'')) .
        "&state=" . $_SESSION['state'] .
        (empty($this->scope)?'':"&scope=" . $this->scope) .
        (empty($this->display)?'':"&display=" . $this->display);

        // echo $dialog_url;exit;
        // if ($this->display === 'popup')
        // {
        // echo("<script>fb=window.open('".$dialog_url."', 'Login con facebook', 'width=50, height=50, left=600, top=150')</script>");
        // exit;
        // }
        // else
        // {
        echo("<script>top.location.href='" . $dialog_url . "'</script>");
        // }
      }

      if ($_SESSION['state'] && ($_SESSION['state'] === $state))
      {
        $token_url = $this->graph_url . "oauth/access_token?" .
        "client_id=" . $this->APP_ID .
        "&redirect_uri=" . urlencode($this->redirect_uri.($this->display==='popup'?'?popup=t':'')) .
        "&client_secret=" . $this->APP_SECRET .
        "&code=" . $code;

        $response = file_get_contents($token_url);
        $params = null;
        parse_str($response, $params);

        // $_SESSION['access_token'] = $params['access_token']; // ALMACENAR EN LA BDD O CREAR SESION CON EL ACCESSO_TOKEN
        return $params['access_token'];
      }
      else
      {
        echo("El parametro state no se encontro. Puedes estar siendo victima de CSRF");
      }

    }

  private function validate_error()
  {
    if (isset($_GET))
    {
      if (isset($_GET['error_reason']) && isset($_GET['error']))
      {
        if (isset($_GET['popup']))
          echo("<script>window.close()</script>");
        else
          header('Location:' . base_url('#login'));
      }
      // else
      // {
      //   if (isset($_GET['popup']))
      //     echo("<script>window.close();window.opener.location.href = '".base_url()."'</script>");
      // }
    }
  }

  /**
   * Obtiene la informacion basica del usuario
   * @param  string Access token
   * @return object
   */
  public function get_user_about_me($access_token)
  {
    $graph_url = $this->graph_url . "me?access_token=" . $access_token;
    return json_decode(file_get_contents($graph_url));
  }

  /**
   * Obtiene todas las fotos del usuario sin importar los albums
   * // http://developers.facebook.com/docs/reference/api/photo/
   * @param  string Access token
   * @return object
   */
  public function get_user_photos($access_token)
  {
    $graph_url = $this->graph_url . "me/photos?limit=0&access_token=" . $access_token;
    return json_decode(file_get_contents($graph_url));
  }

  /**
   * Obtiene todos los albums del usuario sin fotos
   * // http://developers.facebook.com/docs/reference/api/album/
   * @param  string Access token
   * @return object
   */
  public function get_user_albums($access_token)
  {
    $graph_url = $this->graph_url . "me/albums?access_token=" . $access_token;
    return json_decode(file_get_contents($graph_url));
  }

  /**
   * Obtiene todas las fotos de un album especifico
   * // http://developers.facebook.com/docs/reference/api/album/
   * @param  string Access token.
   * @param  string Id del Album que se obtendran las fotos.
   * @return object
   */
  public function get_user_album_photos($access_token, $ida)
  {
    $graph_url = $this->graph_url . $ida . "/photos?limit=0&access_token=" . $access_token;
    return json_decode(file_get_contents($graph_url));
  }

  /**
   * Asigna manualmente el scope (permisos)
   * @param  string Permisos que tendra la APP.
   */
  public function set_scope($scope='')
  {
  	$this->scope = $scope;
  }

  /**
   * Asigna manualmente la url a la que redireccionara facebook
   * @param  string Url que redireccionara.
   */
  public function set_redirect_uri($redirect_uri='')
  {
  	$this->redirect_uri = $redirect_uri;
  }

}
/* End of file my_facebook.php */
/* Location: ./application/libraries/my_facebook.php */