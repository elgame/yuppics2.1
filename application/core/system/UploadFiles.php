<?php

class UploadFiles{

	public static function mesToString($mes){
		switch(floatval($mes)){
			case 1: return 'ENERO'; break;
			case 2: return 'FEBRERO'; break;
			case 3: return 'MARZO'; break;
			case 4: return 'ABRIL'; break;
			case 5: return 'MAYO'; break;
			case 6: return 'JUNIO'; break;
			case 7: return 'JULIO'; break;
			case 8: return 'AGOSTO'; break;
			case 9: return 'SEPTIEMBRE'; break;
			case 10: return 'OCTUBRE'; break;
			case 11: return 'NOVIEMBRE'; break;
			case 12: return 'DICIEMBRE'; break;
		}
	}

	/**
	 * Valida si el directorio espesificado existe o si no lo crea.
	 */
	public static function validaDir($dir, $path)
	{
		// $path = APPPATH.$dir.'articulos/'.$path;
		// if($tipo=='anio'){
		// 	$directorio = date("Y");
		// }elseif($tipo == 'mes'){
		// 	$directorio = self::mesToString(date("n"));
		// }

		if(!file_exists($path.$dir."/")){
			self::crearFolder($path, $dir."/");
		}
		return $dir;
	}

/**
	 * Crea un folder en el servidor.
	 * @param $path_directorio: string. ruta donde se creara el directorio.
	 * @param $nombre_directorio: string. nombre del folder a crear.
	 */
	public static function crearFolder($path_directorio, $nombre_directorio)
	{
		if($nombre_directorio != "" && file_exists($path_directorio)){
			if(!file_exists($path_directorio.$nombre_directorio))
				return mkdir($path_directorio.$nombre_directorio, 0777);
			else
				return true;
		}else
			return false;
	}

	public static function copyFile($source, $dest){
		if(copy($source, $dest))
			return true;
		else
			return false;
	}


  /*
  |	Sube un archivo al servidor
  */
  public static function upload_file($file_field, $dir, $path)
  {
		$config = array(
			'upload_path' 		=> APPPATH.$dir.'articulos/'.$path,
			'allowed_types' 	=> '*',
			'max_size' 				=> '2048',
			'encrypt_name'		=> TRUE
		);

		$this->upload->initialize($config);
		if($this->upload->do_upload($file_field) == FALSE)
		{
			// echo $this->upload->display_errors();
			return array(FALSE, 'msg'=>'Error al intentar subir archivo, intentelo de nuevo.');
		}
		$file_data = $this->upload->data();

		if ($file_data['is_image'])
		{
			$resize_res = $this->resize_image($config['upload_path'].$file_data['file_name']);
			if (!$resize_res)
				return array(FALSE, 'msg'=>'Error al intentar redimensionar imagen.');
		}
		return $file_data['file_name'];
	}

	/*
	 |	Redimenciona una imagen
	 */
  public static function resize_image($img_path)
  {
  	$config = array('image_library' => 'gd2',
										'source_image'  => $img_path,
										'width'         => '348',
										'height'        => '280',
										'maintain_ratio' => FALSE);

  	$this->image_lib->initialize($config);
  	if (!$this->image_lib->resize())
  	{
  		$this->image_lib->clear();
  		// echo $this->image_lib->display_errors();
			return FALSE;
		}

		$this->image_lib->clear();
		return TRUE;
  }






	/**
	 * Guarda la imagen de un empleado
	 */
	public static function uploadImgEmpleado(){
		$ci =& get_instance();
		if(isset($_FILES['durl_img'])){
			if($_FILES['durl_img']['name']!=''){
				$config['upload_path'] = APPPATH.'images/empleados/';
				$config['allowed_types'] = 'jpg|jpeg|gif|png';
				$config['max_size']	= '200';
				$config['max_width'] = '1024';
				$config['max_height'] = '768';
				$config['encrypt_name'] = true;
				$ci->load->library('upload', $config);
				if(!$ci->upload->do_upload('durl_img')){
					$data = array(false, $ci->upload->display_errors());
				}else{
					$data = array(true, $ci->upload->data());
					$config = array();
					$config['image_library'] = 'gd2';
					$config['source_image']	= $data[1]['full_path'];
					$config['create_thumb'] = false;
					$config['master_dim'] = 'auto';
					$config['width']	 = 150;
					$config['height']	= 150;

					$ci->load->library('image_lib', $config);
					$ci->image_lib->resize();
				}
				return $data;
			}
			return false;
		}

		return 'ok';
	}


	public static function deleteFile($path){
		$path = str_replace(base_url(), '', $path);
		try {
			if(file_exists($path) && strpos($path, '.') !== false)
				unlink($path);
			return true;
		}catch (Exception $e){}
		return false;
	}



	/**
	 * Guarda la imagen de una serie y folio
	 */
	public static function uploadImgSerieFolio(){
		$ci =& get_instance();
		if(isset($_FILES['durl_img'])){
			if($_FILES['durl_img']['name']!=''){
				$config['upload_path'] = APPPATH.'images/series_folios/';
				$config['allowed_types'] = 'jpg|jpeg|gif|png';
				$config['max_size']	= '200';
				$config['max_width'] = '1024';
				$config['max_height'] = '768';
				$config['encrypt_name'] = true;
				$ci->load->library('upload', $config);
				if(!$ci->upload->do_upload('durl_img')){
					$data = array(false, $ci->upload->display_errors());
				}else{
					$data = array(true, $ci->upload->data());
					$config = array();
					$config['image_library'] = 'gd2';
					$config['source_image']	= $data[1]['full_path'];
					$config['create_thumb'] = false;
					$config['master_dim'] = 'auto';
					$config['width']	 = 150;
					$config['height']	= 150;

					$ci->load->library('image_lib', $config);
					$ci->image_lib->resize();
				}
				return $data;
			}
			return false;
		}

		return 'ok';
	}

/**
 *    Obtiene el contenido de un url que apunta a un archivo (imagen, txt etc etc)
 *    y lo guarda en el directorio y nombre expecificado ($dir)
 *    @param  [String] $url [URL donde se encuentra el archivo]
 *    @param  [String] $dir [Path o Directorio junto con el nombre Ej. application/images/foto.jpg]
 */
  public static function copy_file_url($url, $dir)
  {
    $file = file_get_contents($url);
    if ($file !== FALSE)
      file_put_contents($dir, $file);
    return $file;
  }

}
?>