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
 * @category	Twittter API
 */
class  my_twitter2 {

	private $CI; // Instancia de Codeigniter
  private $status = TRUE;
  private $http_status = '';
  private $last_api_call = '';

	// Vars
  private $key = 'MPqCGUskTYllpU4iOM0VQ'; // TWITTER CONSUMER KEY
	private $secret = '41nH00dTH8KoynjkXbQsR2EaZDpopx4AWNySoZDWpA'; // TWITTER CONSUMER SECRET
  private $oauth_token = '171242286-5vBLYdJBl5Vc5nLQBXXHhXvNlwO8XKHikPv7lErZ';  // TWITTER ACCESS TOKEN
  private $oauth_secret_token = 'qr2GTE0RgbmfqBerLzAhtzERte7wWc52l2JPvHFRM'; // TWITTER ACCESS TOKEN SECRET
 
  private $request_token = 'https://api.twitter.com/oauth/request_token';
  private $access_token = 'https://api.twitter.com/oauth/access_token';
  
  private $callback_url = '';	// URL A LA QUE TWITER RE-DIRECCIONARA

  /**
   * Constructor
   */
  public function __construct()
  {
  	// Obtiene la instancia de condeigniter
    $this->CI =& get_instance();
    log_message('debug', "my_twitter Class Initialized");
  }

  /*
  | Paso 1 Obteniendo el Request Token.
  | Obtiene los parametros oauth_token. oauth_token_secret, oauth_callback_confirmed.
  |
  | Paso 2 Redireccionando al Usuario.
  | Redirecciona al usuario para que de permisos a la APP.
  | El usuario sera redireccionado para que de o no permisos a la APP.
  |
  | https://dev.twitter.com/docs/auth/implementing-sign-twitter
  */
  public function oauth()
  {
    // Parametros necesarios para la Autorizacion de la API
    $params = array(
                    // 'oauth_callback'         => urlencode(base_url('twitter_test/signin')),
                    'oauth_consumer_key'     => $this->key,
                    'oauth_nonce'            => time(),
                    'oauth_signature_method' => 'HMAC-SHA1',
                    'oauth_timestamp'        => time(),
                    'oauth_version'          => '1.0');

    $url = $this->buildUrl($params, TRUE);
    
    $result_request_token = $this->_http($url);
    parse_str($result_request_token); //convierte el request token el query string
    // header('Location:https://api.twitter.com/oauth/authenticate?oauth_token='.$oauth_token);  // Paso 2
    $oauth = 'https://api.twitter.com/oauth/authenticate?oauth_token='.$oauth_token;
    echo("<script>window.location.href='".$oauth."'</script>");
  }

  /*
  | Paso 3 Convirtiendo el request token en acces token.
  | Si el usuario acepto o cancelo la autorizacion a la APP es redireccionado a este metodo
  | Si da permisos llegara los parametros $_GET: oauth_token y oauth_verifier
  |   entonces se realiza otro POST enviando el oauth_verifier, una respuesta exitosa
  |   regresara los parametros oauth_token, oauth_token_secret, user_id y screen_name
  |
  | Si no da permisos llegara el parametro $_GET: denied
  |
  | https://dev.twitter.com/docs/api/1/post/oauth/access_token
  | https://dev.twitter.com/docs/auth/implementing-sign-twitter
  */
  public function signin()
  {
    // Si el usuario da permisos a la APP entonces se obtiene un access token legitimo
    if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']))
    {
      // $params = array('oauth_nonce'            => time(),
      //                 'oauth_signature_method' => 'HMAC-SHA1',
      //                 'oauth_timestamp'        => time(),
      //                 'oauth_consumer_key'     => $this->key,
      //                 'oauth_token'            => $_GET['oauth_token'],
      //                 'oauth_version'          => '1.0');

      // $url = $this->buildUrl($params, FALSE);
      // $post_data = array('oauth_verifier' => $_GET['oauth_verifier']);
      // $result_request_token =  $this->_http($url, $post_data);

      // var_dump($result_request_token);

      // var_dump($this->buildUrl($params, FALSE, TRUE));exit;

      // return $result_request_token;

      // parse_str($result_request_token);

      // if ( isset($oauth_token) && isset($user_id) ) {
      //   // Obtiene Informacion extra del usuario
      //   $extra_info = $this->_http("https://api.twitter.com/1/users/show.json?user_id=$user_id");
      //   $extra_info = json_decode($extra_info, TRUE);
      // }

      // $params = array('oauth_nonce'            => time(),
      //                 'oauth_signature_method' => 'HMAC-SHA1',
      //                 'oauth_timestamp'        => time(),
      //                 'oauth_consumer_key'     => $this->key,
      //                 'oauth_token'            => '171242286-5vBLYdJBl5Vc5nLQBXXHhXvNlwO8XKHikPv7lErZ',
      //                 // 'oauth_token'            => $_GET['oauth_token'],
      //                 // 'oauth_token_secret'     => 'qr2GTE0RgbmfqBerLzAhtzERte7wWc52l2JPvHFRM',
      //                 'oauth_version'          => '1.0',
      //                 'status'                => rawurlencode("waka waka"));


      // var_dump($params);
      // 
      // echo $this->buildUrl($params, FALSE, TRUE);

      // $this->statuses_update('status=Maybe%20he%27ll%20finally%20find%20his%20keys.%20%23peterfalk', $this->buildUrl($params, FALSE, TRUE));
      // echo  $this->buildUrl($params, FALSE, TRUE, "POST");exit;
      $this->statuses_update("status=".rawurlencode("waka waka"), $this->buildUrl($params, FALSE, TRUE, "POST", 'https://api.twitter.com/1.1/statuses/update.json'));

    }
    elseif (isset($_GET['denied'])) // Si el usuario rechazo o cancelo la autorizacion de la app
    {
      header('Location:'.base_url());
    }
  }


  /**
   * Actualiza el estado de un usuario, tambien conocido como un twitt.
   */
  public function statuses_update($params, $headers)
  {
    $result = $this->_http('https://api.twitter.com/1.1/statuses/update.json', $params, $headers);
    var_dump($result);exit;
  }

  public function directo() 
  {
     $params = array('oauth_nonce'            => time(),
                      'oauth_signature_method' => 'HMAC-SHA1',
                      'oauth_timestamp'        => time(),
                      'oauth_consumer_key'     => $this->key,
                      'oauth_token'            => $this->oauth_token,
                      // 'oauth_token'            => $_GET['oauth_token'],
                      // 'oauth_token_secret'     => 'qr2GTE0RgbmfqBerLzAhtzERte7wWc52l2JPvHFRM',
                      'oauth_version'          => '1.0',
                      'status'                => "waka waka");

    $header = $this->buildUrl($params, FALSE, TRUE, "POST", 'https://api.twitter.com/1.1/statuses/update.json');
    // var_dump($header);exit;

    $res = $this->statuses_update('status='.rawurlencode("waka waka"), $header);
    var_dump($res);exit;
  }

  private function buildUrl($params=array(), $request_token = TRUE, $header_authorization = FALSE, $method='GET', $url=NULL)
  {
    $keys   = $this->_urlencode_rfc3986(array_keys($params));
    $values = $this->_urlencode_rfc3986(array_values($params));
    $params = array_combine($keys, $values);
    uksort($params, 'strcmp');

    // $url_token = ($request_token)?$this->request_token:$this->access_token;
    $url_token = ($url)?$url:(($request_token)?$this->request_token:$this->access_token);
    
    foreach ($params as $k => $v) {
        $pairs[] = $this->_urlencode_rfc3986($k).'='.(($k !== 'status')?$this->_urlencode_rfc3986($v):$v);
    }
      $concatenatedParams = implode('&', $pairs);
      // echo $concatenatedParams;exit;

    // form base string (first key)
    $baseString= $method."&".$this->_urlencode_rfc3986($url_token)."&".$this->_urlencode_rfc3986($concatenatedParams);

    if ($method === 'POST') {
      // echo $baseString;exit;
    }
    // form secret (second key)
    $secret = $this->_urlencode_rfc3986($this->secret)."&";

    // Genera el parametro oauth_signature y lo añade al array params
    $params['oauth_signature'] = $this->_urlencode_rfc3986(base64_encode(hash_hmac('sha1', $baseString, $secret, TRUE)));

    uksort($params, 'strcmp');
    
    if ($header_authorization){
      foreach ($params as $k => $v) {
        if ($k !== 'status') {
          $urlPairs[] = $k.'="'.$v.'"';
        } 
      }
      return "Authorization: OAuth " . implode(',  ', $urlPairs);
    }   

    foreach ($params as $k => $v) {$urlPairs[] = $k."=".$v;}
    $concatenatedUrlParams = implode('&', $urlPairs);
    // form url
    return $url_token."?".$concatenatedUrlParams;
  }

  private function _http($url, $post_data = NULL, $headers = NULL)
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    if(isset($post_data))
    {
      // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth oauth_consumer_key="MPqCGUskTYllpU4iOM0VQ", oauth_nonce="a3e6c2f6faa7c19da74d203ce6c7a19b", oauth_signature="25lmeVE%2FhUUvJSOHskbuyLlu%2BSA%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1354735989", oauth_token="171242286-5vBLYdJBl5Vc5nLQBXXHhXvNlwO8XKHikPv7lErZ", oauth_version="1.0"'));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array($headers));
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }

    $response = curl_exec($ch);
    $this->http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $this->last_api_call = $url;
    curl_close($ch);

    return $response;
  }

  private function _urlencode_rfc3986($input)
  {
    if (is_array($input)) 
      return array_map(array('MY_twitter', '_urlencode_rfc3986'), $input);
    else if (is_scalar($input)) 
      return str_replace('+',' ',str_replace('%7E', '~', rawurlencode($input)));
    else
      return '';
  }

}
/* End of file my_twitter.php */
/* Location: ./application/libraries/my_twitter.php */