<?php

class themes_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 * Obtiene la informacion de un cupon
	 */
	public function getThemes($search=null, $exist=false, $limit=null){
		$this->db
			->select("t.id_theme, t.name, tta.name AS autor, t.background_img, t.background_color, t.text_color, t.background_franja, 
								t.background_franja_color, t.font_cover")
			->from('themes AS t')
			->join('themes_autor AS tta', 'tta.id_autor = t.id_autor', 'inner');
		if ($search != null) {
			$search = mb_strtolower($search, 'utf-8');
			$search = str_replace(';', '', $search);
			$this->db->join('themes_tags AS tt', 't.id_theme = tt.id_theme', 'left')
				->join('tags AS ta', 'ta.id_tag = tt.id_tag', 'left')
				->where("Lower(t.name) LIKE '%".$search."%' OR Lower(tta.name) LIKE '%".$search."%' OR Lower(ta.name) LIKE '%".$search."%'");
		}
		$this->db->group_by('t.id_theme');
		if($limit != null)
			$this->db->limit($limit);
		$res = $this->db->order_by('t.name', 'asc')
			->get();
		if($res->num_rows() > 0){
			if($exist){
				$res->free_result();
				return true;
			}

			$response = $res->result();
			$res->free_result();
			
			return $response;
		}else
			return false;
	}

	public function getThemeDefault(){
		$themes = $this->getThemes(null, null, 1);
		
		if($themes != false){
			$response = new stdClass();
			$response->id_yuppic        = '';
			$response->title            = '—— TÍTULO ——';
			$response->author           = 'Autor de Yuppic';
			$response->background_img   = $themes[0]->background_img;
			$response->background_color = $themes[0]->background_color;
			$response->text_color       = $themes[0]->text_color;
			$response->bg_pattern       = '0';
			$response->bg_img_x         = '0';
			$response->bg_img_y         = '0';
			$response->background_franja          = $themes[0]->background_franja;
			$response->background_franja_color    = $themes[0]->background_franja_color;
			$response->font_cover                 = $themes[0]->font_cover;
			$response->background_franja_position = 'c';
			
			$a = explode('/', $response->background_img);
			$b = explode('.', $a[count($a)-1]);
			unset($a[count($a)-1]);
			$response->background_img_thum = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];

			return $response;
		}else
			return false;
	}

	public function getYuppicTheme($id_yuppic){
		$res = $this->db->query("SELECT 
				y.id_yuppic, y.title, y.author, yt.background_img, yt.background_color, yt.text_color, yt.bg_pattern, 
				yt.bg_img_x, yt.bg_img_y, yt.background_franja, yt.background_franja_color, yt.font_cover, yt.background_franja_position 
			FROM yuppics AS y 
				INNER JOIN yuppics_theme AS yt ON y.id_yuppic = yt.id_yuppic 
			WHERE y.id_yuppic = ".$id_yuppic);

		if($res->num_rows() > 0){
			$response = $res->row();
			$res->free_result();
			
			$a = explode('/', $response->background_img);
			$b = explode('.', $a[count($a)-1]);
			unset($a[count($a)-1]);
			if(isset($b[1]))
				$response->background_img_thum = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];
			else
				$response->background_img_thum = '';

			return $response;
		}else
			return false;
	}

	public function save_theme(){
		$this->load->model('coupons_model');
		$produc = $this->coupons_model->getProduct(true);
		$data_yuppic = array(
			'id_customer' => $this->session->userdata('id_usuario'), 
			'id_product'  => $produc->id_product,
			'title'       => ($this->input->post('title')=="—— TÍTULO ——"? 'Yuppic sin título': $this->input->post('title')),
			'author'      => $this->input->post('author'),
			'quantity'    => 1,
			);

		$size_yuppic = array(145, 185);
		$data_theme = array(
			'id_yuppic'        => '', 
			'background_img'   => $this->input->post('background_img'), 
			'background_color' => $this->input->post('background_color'), 
			'text_color'       => $this->input->post('text_color'),
			'bg_pattern'       => ($this->input->post('bg_pattern')=='si'? '1': '0'),
			'bg_img_x'         => $this->input->post('bg_img_x'),
			'bg_img_y'         => $this->input->post('bg_img_y'),

			'font_cover'                 => $this->input->post('font_cover'),
			'background_franja'          => $this->input->post('background_franja'),
			'background_franja_color'    => $this->input->post('background_franja_color'),
			'background_franja_position' => $this->input->post('background_franja_position'),
			);

		UploadFiles::validaDir($data_yuppic['id_customer'], APPPATH.'yuppics/');

		if ($this->session->userdata('id_yuppics') != '') {
			$id_yuppic = $this->session->userdata('id_yuppics');
			$this->db->update('yuppics', $data_yuppic, "id_yuppic = ".$id_yuppic);
			unset($data_theme['id_yuppic']);

			// if (strpos($data_theme['background_img'], 'yuppics/themes') !== false) {
				$imgs_data = $this->getPathUrl($data_theme, $data_yuppic, $id_yuppic, true);
				$data_theme['background_img']    = $imgs_data['cover'];
				$data_theme['background_franja'] = $imgs_data['franja'];
			// }

			$this->db->update('yuppics_theme', $data_theme, "id_yuppic = ".$id_yuppic);
		}else{
			$this->db->insert('yuppics', $data_yuppic);
			$id_yuppic = $this->db->insert_id();

			$imgs_data = $this->getPathUrl($data_theme, $data_yuppic, $id_yuppic);
			$data_theme['background_img']    = $imgs_data['cover'];
			$data_theme['background_franja'] = $imgs_data['franja'];

			$data_theme['id_yuppic']      = $id_yuppic;
			$this->db->insert('yuppics_theme', $data_theme);

			$this->session->set_userdata('id_yuppics', $id_yuppic);
		}

		

		return $id_yuppic;
	}

	private function getPathUrl($data_theme, $data_yuppic, $id_yuppic, $delte=false){
		$a = explode('/', $data_theme['background_img']);
		$path = '';
		$franja_path = '';
		$path_base = APPPATH.'yuppics/'.$data_yuppic['id_customer'].'/'.$id_yuppic.'/';
		
		UploadFiles::validaDir($id_yuppic, APPPATH.'yuppics/'.$data_yuppic['id_customer'].'/');

		if(isset($a[count($a)-1])){
			$b = explode('.', $a[count($a)-1]);

			if(isset($b[1])){
				$path = $path_base.$a[count($a)-1];
				$path_thum = $path_base.$b[0].'_thumb.'.$b[1];

				unset($a[count($a)-1]);
				$source_path_thum = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];

				UploadFiles::copyFile($data_theme['background_img'], $path);
				UploadFiles::copyFile($source_path_thum, $path_thum);
			}
		}

		//copiar la imgen de la franja
		$a = explode('/', $data_theme['background_franja']);
		if(isset($a[count($a)-1])){
			if($a[count($a)-1] != ''){
				$franja_path = $path_base.$a[count($a)-1];
				UploadFiles::copyFile($data_theme['background_franja'], $franja_path);
			}
		}

		if ($delte) {
			$info = $this->getYuppicTheme($id_yuppic);
			//borrar imagen del cover
			$aa = explode('/', $data_theme['background_img']);
			$aa = isset($aa[count($aa)-1])? $aa[count($aa)-1]: '';
			$a = explode('/', $info->background_img);
			if(isset($a[count($a)-1])){
				if($a[count($a)-1] != $aa){
					UploadFiles::deleteFile($info->background_img);

					$b = explode('.', $a[count($a)-1]);
					if(isset($b[1])){
						unset($a[count($a)-1]);
						$source_path_thum = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];
						UploadFiles::deleteFile($source_path_thum);
					}
				}
			}
			//borrar imagen de la franja
			$aa = explode('/', $data_theme['background_franja']);
			$aa = isset($aa[count($aa)-1])? $aa[count($aa)-1]: '';
			$a = explode('/', $info->background_franja);
			if(isset($a[count($a)-1])){
				if($a[count($a)-1] != $aa){
					UploadFiles::deleteFile($info->background_franja);
				}
			}
		}

		return array('cover' => $path, 'franja' => $franja_path);
	}

}