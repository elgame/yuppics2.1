<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * My CodeIgniter Library
 *################################################################
 *
 * @author  Indigo Dev Team
 * @since   Version 1.0
 * @filesource
 */

/* Load required lib files. */
require_once(APPPATH.'libraries/twitteroauth/twitteroauth.php');
require_once(APPPATH.'libraries/twitteroauth/config.php');


// ------------------------------------------------------------------------
/**
 * @category  Twittter API
 */
class  my_twitter {

  private $CI;

  private $connection;

  /**
  * Constructor
  */
  public function __construct()
  {
    session_start();
    
    // Obtiene la instancia de condeigniter por si se necesita
    $this->CI =& get_instance();
    log_message('debug', "my_twitter Class Initialized");
  }

  public function oauth()
  {
    // Verifica si existe el parametro GET oauth_verifier.
    $this->callback();

    if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
        $this->clear_sessions();
    }
    
    $access_token = $_SESSION['access_token'];
    $this->connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    return $access_token;
  }

  private function callback()
  {
    if (isset($_GET['oauth_verifier'])) 
    {
      $this->connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
      $access_token = $this->connection->getAccessToken($_GET['oauth_verifier']);

      unset($_SESSION['oauth_token']);
      unset($_SESSION['oauth_token_secret']);

      /* Save the access tokens. Normally these would be saved in a database for future use. */
      $_SESSION['access_token'] = $access_token;
    }
  }

  private function clear_sessions()
  {
    $this->redirect();
  }

  private function redirect()
  {
    /* Build TwitterOAuth object with client credentials. */
    $this->connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
     
    /* Get temporary credentials. */
    $request_token = $this->connection->getRequestToken(OAUTH_CALLBACK);

    /* Save temporary credentials to session. */
    $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    
    /* If last connection failed don't display authorization link. */
    switch ($this->connection->http_code) {
      case 200:
        /* Build authorize URL and redirect user to Twitter. */
        $url = $this->connection->getAuthorizeURL($token);
        // echo $url;exit;
        header('Location: ' . $url); 
        break;
      default:
        /* Show notification if something went wrong. */
        echo 'No se pudo conectar a twitter. Actualiza la pagina o intentalo nuevamente.';
    }
  }  

  /******** METODOS PARA INTERACTUAR CON LA API ********/

  /**
   * Actualiza el status del usuario tambien conocido como twitt.
   * @param  String $status [Mensaje del Twitt]
   * @return array
   */
  public function statuses_update($status) 
  {
    $params = array('status'=>$status);
    $result = $this->connection->post('statuses/update', $params);
    return $result;
  }

}
/* End of file my_twitter.php */
/* Location: ./application/libraries/my_twitter.php */