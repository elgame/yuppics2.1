<?php

class customer_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la informacion de un customer
	 */
	public function getInfoCustomer($id, $info_basic=false){
		$res = $this->db
			->select('*')
			->from('customers')
			->where("id_customer = '".$id."'")
		->get();
		if($res->num_rows() > 0){
			$response['info'] = $res->row();
			$res->free_result();
			$response['info']->url_avatar = ($response['info']->url_avatar!=''? $response['info']->url_avatar: 'application/images/anonimous44.jpg');
			//Precio yuppic
			$res = $this->db->select('*')->from('products')->where("id_product = '1'")->get();
			$response['yuppic'] = $res->row();
			$res->free_result();
			//Yuppics comprados contador
			$response['yuppic_compr'] = $this->db->query("SELECT (SELECT Count(*) FROM orders WHERE id_customer = ".$this->session->userdata('id_usuario')." AND status IN ('a','e')) AS purchases")->row();

			if($info_basic)
				return $response;

			//Informacion extra

		}else
			return false;
	}


	/**
	 * Registra a un customer utilizando el formulario de registro
	 * @return [type] [description]
	 */
	public function register_customer()
	{
		$data = array('username' => $this->input->post('username'),
			'password'   => $this->input->post('password'),
			'first_name' => $this->input->post('firstname'),
			'last_name'  => $this->input->post('lastname'),
			'email'      => $this->input->post('email'),
			'created'    => date('Y-m-d H:i:s'),
			'status'     => 'on',
		);

    if (isset($_FILES['avatar']))
    {
      $this->load->library('my_upload');

      $config_upload = array(
        'upload_path'     => APPPATH.'images/avatars/',
        'allowed_types'   => 'jpg|png',
        'max_size'        => '2048',
        'encrypt_name'    => TRUE
      );

      $this->my_upload->initialize($config_upload);

      $config_resize = array(
        'image_library' => 'gd2',
        'source_image'  => '',
        'width'         => '50',
        'height'        => '280',
        'maintain_ratio' => TRUE
      );

      $this->my_upload->do_resize     = TRUE;
      $this->my_upload->config_resize = $config_resize;
      $data_image = $this->my_upload->do_upload('avatar');

      if (isset($data_image['file_name']))
      {
        $data['url_avatar'] = APPPATH.'images/avatars/'.$data_image['file_name'];
      }
      else
      {
        return array(FALSE, 'msg' => $data_image['msg']);
      }
    }

		$this->db->insert('customers', $data);
		return array('cid' => $this->db->insert_id());
	}

	/**
	 * Registra y logea a los customer utilizando facebook
	 * @param  array   $sdata       [description]
	 * @param  boolean $return_inst [description]
	 * @return [type]               [description]
	 */
	public function customer_social_checkin($sdata = array(), $return_inst=false)
	{

		$data                   = array();
		$data['email']          = isset($sdata['email']) ? $sdata['email'] : '';
		$data['first_name']     = isset($sdata['first_name']) ? $sdata['first_name'] : '';
		$data['last_name']      = isset($sdata['last_name']) ? $sdata['last_name'] : '';
		$data['status']         = 'on';
		// posibles valores en $data['social'] (facebook_id o twitter_id)
		$data[$sdata['social']] = $sdata['sid'];

		$valid = $this->db->select('id_customer, facebook_id, email, url_avatar')
                			->from('customers')
                			->where("facebook_id = '".$sdata['sid']."' OR email = '".$sdata['email']."'")
                		  ->get();

    $path_picture = APPPATH.'images/avatars/'.$sdata['sid'].'.jpg';
		if ($valid->num_rows() > 0) // ya existe el usuario
		{
			$info = $valid->row();
			if ($info->facebook_id != $sdata['sid'] || $info->email != $sdata['email']) //actualiza si cambio la info
			{
				$this->db->update('customers', array(
						$sdata['social'] => $sdata['sid'],
						'email'          => $sdata['email']
					), "id_customer = ".$info->id_customer );
			}

      // Si el usuario no tiene un avatar actualiza la imagen
      if ($info->url_avatar === '')
      {
        if (UploadFiles::copy_file_url($sdata['picture'], $path_picture) !== FALSE) // Guarda la imagen de Fb en el server
          $this->db->update('customers', array('url_avatar' => $path_picture), "id_customer = ".$info->id_customer);
      }
		}
		else  // Registra al usuario
		{
			$data['created'] = date('Y-m-d H:i:s');

      // Guarda la imagen de Fb en el server y asigna el PATH de la picture al campo de la BDD
      if (UploadFiles::copy_file_url($sdata['picture'], $path_picture) !== FALSE)
        $data['url_avatar'] = $path_picture;

			$this->db->insert('customers', $data);

			if ($return_inst)
				return array('cid' => $this->db->insert_id());
		}

		$sql = "email = '".$sdata['email']."' AND ".$sdata['social']." = '".$sdata['sid']."' AND status = 'on' ";
		$mdl_res = $this->setLogin($sql);
		if ($mdl_res[0])
		{
			echo("<script>window.close();window.opener.location.href = '".base_url()."'</script>");
		}
	}

	public function update_customer()
	{
		$data = array(
			'first_name' => $this->input->post('firstname'),
			'last_name'  => $this->input->post('lastname'),
			'email'      => $this->input->post('email')
		);
		if ($this->input->post('username') != '')
			$data['username'] = $this->input->post('username');
		if ($this->input->post('password') != '')
			$data['password'] = $this->input->post('password');
		if ($this->input->post('status') != '')
			$data['status'] = $this->input->post('status');

    if (isset($_FILES['avatar']))
    {
      $this->load->library('my_upload');

      $result = $this->db->query("SELECT url_avatar
                                  FROM customers
                                  WHERE id_customer = " . $this->session->userdata('id_usuario'));

      if ($result->num_rows() > 0)
      {
        $avatar = $result->result();
        $path = base_url($avatar[0]->url_avatar);
        UploadFiles::deleteFile($path);
      }

      $config_upload = array(
        'upload_path'     => APPPATH.'images/avatars/',
        'allowed_types'   => 'jpg|png',
        'max_size'        => '2048',
        'encrypt_name'    => TRUE
      );

      $this->my_upload->initialize($config_upload);

      $config_resize = array(
        'image_library' => 'gd2',
        'source_image'  => '',
        'width'         => '1',
        'height'        => '50',
        'master_dim'    => 'height',
        'maintain_ratio' => TRUE
      );

      $this->my_upload->do_resize     = TRUE;
      $this->my_upload->config_resize = $config_resize;
      $data_image = $this->my_upload->do_upload('avatar');

      if (isset($data_image['file_name']))
      {
        $data['url_avatar'] = APPPATH.'images/avatars/'.$data_image['file_name'];
      }
      else
      {
        return array(FALSE, 'msg' => $data_image['msg']);
      }
    }

		$this->db->update('customers', $data, "id_customer = ".$this->input->post('customer'));
		return true;
	}

	public function getPromos(){
		$result = $this->db->query("SELECT Count(*) AS c
		                           FROM customer_promo AS cp INNER JOIN coupons AS c ON cp.id_coupon = c.id_coupon
		                           WHERE cp.id_customer = ".$this->session->userdata('id_usuario')." AND c.status = 0 ")->row();
		return $result;
	}






	/**
	 * Revisa si la sesion del usuario esta activa
	 */
	public function checkSession(){
		if($this->session->userdata('id_usuario') && $this->session->userdata('nombre') && $this->session->userdata('type') === 'customer') {
			if($this->session->userdata('id_usuario')!='' && $this->session->userdata('nombre')!=''){
				return true;
			}
		}
		return false;
	}

	/*
		|	Logea al usuario y crea la session con los parametros
		| id_usuario, username, email y si marco el campo "no cerra sesion" agrega el parametro "remember" a
		|	la session con 1 año para que expire
		*/
	public function setLogin($user_data)
	{
		$fun_res = $this->exec_GetWhere('customers', $user_data, TRUE);
		if ($fun_res != FALSE)
		{
			$user_data = array(	'id_usuario'=> $fun_res[0]->id_customer,
													'nombre'  => $fun_res[0]->first_name,
													'email'   => $fun_res[0]->email,
													'type'    => 'customer',
													'idunico' => uniqid('l', true));
				$this->crea_session($user_data);
		}

			return array($fun_res, 'msg'=>'El correo electrónico y/o contraseña son incorrectos');
		// return array($fun_res);
	}

	/*
		|	Ejecuta un db->get_where() || Select * From <tabla> Where <condicion>
		|	$tabla : tabla de la bdd
		|	$where : condicion
		| $return_data : Indica si regresara el resulta de la consulta, si es False y la consulta obtuvo al menos
		|		un registro entonces regresara TRUE si no FALSE
		*/
	public function exec_GetWhere($tabla, $where, $return_data=FALSE)
	{
		// SELECT * FROM $tabla WHERE id=''
		$sql_res = $this->db->get_where($tabla, $where);

		if ($sql_res->num_rows() > 0)
		{
			if ($return_data)
				return $sql_res->result();
			return TRUE;
		}
		return FALSE;
	}

	/*
		|	Crea la session del usuario que se logueara
		|	$user_data : informacion del usuario que se agregara al array session
		*/
	private function crea_session($user_data)
	{
		if (isset($_POST['remember']))
		{
			$this->session->set_userdata('remember',TRUE);
			$this->session->sess_expiration	= 60*60*24*365;
		}
		else
			$this->session->sess_expiration	= 7200;

		$this->session->set_userdata($user_data);
	}


}