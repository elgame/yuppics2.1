<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_temas_model extends CI_Model {


  public function getTemas($per_page='40', $orderby='name ASC'){

    $sql = '';
    //paginacion
    $params = array(
        'result_items_per_page' => $per_page,
        'result_page' => (isset($_GET['pag'])? $_GET['pag']: 0)
    );

    if($params['result_page'] % $params['result_items_per_page'] == 0)
      $params['result_page'] = ($params['result_page']/$params['result_items_per_page']);

    if ($this->input->get('fstatus') != '')
      $sql = "WHERE t.status = " . $this->input->get('fstatus');

    if($this->input->get('fsearch') != '')
      $sql .= " AND lower(t.name) LIKE '%" . mb_strtolower($this->input->get('fsearch'), 'UTF-8') . "%' ||
                lower(ta.name) LIKE '%" . mb_strtolower($this->input->get('fsearch'), 'UTF-8') . "%'";

    $query = BDUtil::pagination("
        SELECT t.id_theme, ta.name as autor, t.name, t.background_img as imagen, t.background_color, t.text_color, t.status,
               t.background_franja
        FROM themes as t
        INNER JOIN themes_autor as ta ON ta.id_autor = t.id_autor
        ".$sql."
        ORDER BY ".$orderby."
        ", $params, true);
    $res = $this->db->query($query['query']);

    $response = array(
        'temas'        => array(),
        'total_rows'     => $query['total_rows'],
        'items_per_page' => $params['result_items_per_page'],
        'result_page'    => $params['result_page']
    );
    if($res->num_rows() > 0)
      $response['temas'] = $res->result();

    return $response;
  }

  public function info($id=null)
  {
    $id = $id ? $id : $_GET['id'];

    $res = $this->db->select('id_theme, id_autor, name, background_img, background_color,
                              text_color, status, background_franja, background_franja_color, font_cover')
      ->from('themes')
      ->where('id_theme', $id)
      ->get();

    $tema = array();
    if ($res->num_rows() > 0)
    {
      $tema['info'] = $res->result();
      $tema['tags'] = array();

      $res2 = $this->db->select("id_tag")->from("themes_tags")->where('id_theme', $id)->get();

      if ($res2->num_rows() > 0)
        foreach ($res2->result() as $tag)
          $tema['tags'][] = $tag->id_tag;
    }

    return $tema;
  }

  public function add()
  {
    $this->load->library("my_upload");

    /****************** BACKGROUND THEME***************************/

    $path_themes       = 'yuppics/themes/';

    $config_upload = array(
     'upload_path'     => APPPATH.$path_themes,
     'allowed_types'   => 'jpg|png',
     'max_size'        => '2048',
     'encrypt_name'    => true
    );

    // $this->my_upload->do_resize = FALSE;
    // $this->my_upload->config_resize = $config_resize;

    $this->my_upload->initialize($config_upload);
    $theme = $this->my_upload->do_upload('pimg');

    $theme_path = explode('application/', $theme['full_path']);
    $theme_path = 'application/' . $theme_path[1];

    $name_file = explode('.', $theme_path);
    $name_file = $name_file[0].rand(1, 9999).'.'.$name_file[1];

    $info = getimagesize($theme_path);

    $size = array($info[0], $info[1]);

    $info = array(
      'x'     => 0,
      'y'     => 0,
      'w'     => 1024,
      'h'     => 768,
      'pos_x' => 0,
      'pos_y' => 0,
    );

    $size = $this->redimImgPhoto($size, $info);
    $crop = $this->cropImg($name_file, $theme_path, $size, $info);

    UploadFiles::deleteFile(base_url($theme_path));

    $config_thumb = array(
      'image_library'  => 'gd2',
      'source_image'   => '',
      'width'          => '400',
      'height'         => '300',
      'maintain_ratio' => FALSE
    );

    $this->my_upload->new_image_path = APPPATH.$path_themes;
    $this->my_upload->config_resize  = $config_thumb;
    $this->my_upload->resize_image($crop);

    /****************** BACKGROUND THEME***************************/

    /****************** BACKGROUND FRANJA***************************/

    $franja_path = null;

    if ( ! empty($_FILES['pimg_franja']['name']))
    {
      $path_franjas       = 'yuppics/themes/franjas/';

      $config_upload = array(
       'upload_path'     => APPPATH.$path_franjas,
       'allowed_types'   => 'jpg|png',
       'max_size'        => '2048',
       'encrypt_name'    => true
      );

      // $config_resize = array(
      //   'image_library' => 'gd2',
      //   'source_image'  => '',
      //   'width'         => '1024',
      //   'height'        => '768',
      //   'master_dim'    => 'height',
      //   'maintain_ratio' => TRUE
      // );

      $this->my_upload->do_resize     = FALSE;
      // $this->my_upload->config_resize = $config_resize;

      $this->my_upload->initialize($config_upload);
      $franja = $this->my_upload->do_upload('pimg_franja');

      $franja_path = explode('application/', $franja['full_path']);
      $franja_path = 'application/' . $franja_path[1];
    }

    /****************** BACKGROUND FRANJA***************************/

    $data = array(
      'id_autor'         => $this->input->post('pautor'),
      'name'             => $this->input->post('pname'),
      'background_img'   => $crop,
      'background_color' => $this->input->post('pbgcolor'),
      'text_color'       => $this->input->post('ptxtcolor'),
      'background_franja' => $franja_path,
      'background_franja_color' => $this->input->post('pbgcolor_franja'),
      'font_cover'      => $this->input->post('pfuente_cover'),
    );

    $this->db->insert('themes', $data);
    $theme_id = $this->db->insert_id();

    if (isset($_POST['ptags']))
    {
      $dataTags = array();
      foreach ($_POST['ptags'] as $tag)
        $dataTags[] = array('id_tag'=> $tag, 'id_theme'=>$theme_id);

      $this->db->insert_batch('themes_tags', $dataTags);
    }

    if ($_POST['pmastags'] !== '')
    {
      $mastags = explode(',', $_POST['pmastags']);

      if (count($mastags) === 0)
        $mastags = array($_POST['pmastags']);

      $dataNewTags = array();
      foreach ($mastags as $key => $tag)
      {
        $this->db->insert('tags', array('name' => ltrim($tag)));
        $dataNewTags[] = array(
          'id_tag'  => $this->db->insert_id(),
          'id_theme' => $theme_id,
        );
      }

      $this->db->insert_batch('themes_tags', $dataNewTags);
    }

    return array('passess' => true);
  }

  public function editar($id=null)
  {
    $id = $id ? $id : $_GET['id'];

    $data = array(
      'id_autor'         => $this->input->post('pautor'),
      'name'             => $this->input->post('pname'),
      'background_color' => $this->input->post('pbgcolor'),
      'text_color'       => $this->input->post('ptxtcolor'),
      'font_cover'      => $this->input->post('pfuente_cover'),
    );

    $this->load->library("my_upload");

    if ( ! empty($_FILES['pimg']['name']))
    {
      $path_themes       = 'yuppics/themes/';

      $config_upload = array(
       'upload_path'     => APPPATH.$path_themes,
       'allowed_types'   => 'jpg|png',
       'max_size'        => '2048',
       'encrypt_name'    => true
      );

      // $this->my_upload->do_resize = FALSE;
      // $this->my_upload->config_resize = $config_resize;

      $this->my_upload->initialize($config_upload);
      $theme = $this->my_upload->do_upload('pimg');

      $theme_path = explode('application/', $theme['full_path']);
      $theme_path = 'application/' . $theme_path[1];

      $name_file = explode('.', $theme_path);
      $name_file = $name_file[0].rand(1, 9999).'.'.$name_file[1];

      $info = getimagesize($theme_path);

      $size = array($info[0], $info[1]);

      $info = array(
        'x'     => 0,
        'y'     => 0,
        'w'     => 1024,
        'h'     => 768,
        'pos_x' => 0,
        'pos_y' => 0,
      );

      $size = $this->redimImgPhoto($size, $info);
      $crop = $this->cropImg($name_file, $theme_path, $size, $info);

      UploadFiles::deleteFile(base_url($theme_path));

      $config_thumb = array(
        'image_library'  => 'gd2',
        'source_image'   => '',
        'width'          => '400',
        'height'         => '300',
        'maintain_ratio' => FALSE
      );

      $this->my_upload->new_image_path = APPPATH.$path_themes;
      $this->my_upload->config_resize  = $config_thumb;
      $this->my_upload->resize_image($crop);

      $data['background_img'] = $crop;

      $old_image = $this->db->select("background_img")
        ->from("themes")
        ->where("id_theme", $id)
        ->get()
        ->row()
        ->background_img;

      UploadFiles::deleteFile(base_url($old_image));

      $thumb = explode('.', $old_image);
      UploadFiles::deleteFile(base_url($thumb[0].'_thumb.'.$thumb[1]));
    }

    /****************** BACKGROUND FRANJA***************************/

    if ( ! empty($_FILES['pimg_franja']['name']))
    {
      $path_franjas       = 'yuppics/themes/franjas/';

      $config_upload = array(
       'upload_path'     => APPPATH.$path_franjas,
       'allowed_types'   => 'jpg|png',
       'max_size'        => '2048',
       'encrypt_name'    => true
      );

      // $config_resize = array(
      //   'image_library' => 'gd2',
      //   'source_image'  => '',
      //   'width'         => '1024',
      //   'height'        => '768',
      //   'master_dim'    => 'height',
      //   'maintain_ratio' => TRUE
      // );

      $this->my_upload->do_resize     = FALSE;
      // $this->my_upload->config_resize = $config_resize;

      $this->my_upload->initialize($config_upload);
      $franja = $this->my_upload->do_upload('pimg_franja');

      $franja_path = explode('application/', $franja['full_path']);
      $franja_path = 'application/' . $franja_path[1];

      $data['background_franja'] = $franja_path;

      $old_franja = $this->db->select("background_franja")
        ->from("themes")
        ->where("id_theme", $id)
        ->get()
        ->row()
        ->background_franja;

      UploadFiles::deleteFile(base_url($old_franja));
    }

    /****************** BACKGROUND FRANJA***************************/

    $this->db->update('themes', $data, array('id_theme' => $id));

    $this->db->delete('themes_tags', array('id_theme' => $id));

    if (isset($_POST['ptags']))
    {
      $dataTags = array();
      foreach ($_POST['ptags'] as $tag)
        $dataTags[] = array('id_tag'=> $tag, 'id_theme'=>$id);

      $this->db->insert_batch('themes_tags', $dataTags);
    }

    if ($_POST['pmastags'] !== '')
    {
      $mastags = explode(',', $_POST['pmastags']);

      if (count($mastags) === 0)
        $mastags = array($_POST['pmastags']);

      $dataNewTags = array();
      foreach ($mastags as $key => $tag)
      {
        $this->db->insert('tags', array('name' => ltrim($tag)));
        $dataNewTags[] = array(
          'id_tag'  => $this->db->insert_id(),
          'id_theme' => $id,
        );
      }

      $this->db->insert_batch('themes_tags', $dataNewTags);
    }

    return array('passess' => true);
  }

  public function desactivar($id=null)
  {
    $id = $id ? $id : $_GET['id'];
    $this->db->update('themes', array('status' => 0), array('id_theme' => $id));

    return array('passess' => true);
  }

  public function activar($id=null)
  {
    $id = $id ? $id : $_GET['id'];
    $this->db->update('themes', array('status' => 1), array('id_theme' => $id));

    return array('passess' => true);
  }

  public function redimImgPhoto($size, $info){
    $diff_pix = 0;
    $resize   = array('w'=>0, 'h'=>0);

    if ($info['w'] > $info['h']) {
      $diff_pix = $info['w'] / $size[0];

      $resize['w'] = $info['w'];
      $resize['h'] = ($diff_pix * $size[1]);
    } else {
      $diff_pix = $info['h'] / $size[1];

      $resize['w'] = ($diff_pix * $size[0]);
      $resize['h'] = $info['h'];
    }
    return $resize;
  }

  public function cropImg($thumb_image_name, $image, $conf, $pag){
    ini_set('memory_limit', '64M');
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $imageType = image_type_to_mime_type($imageType);

    $newImageWidth  = $pag['w'];
    $newImageHeight = $pag['h'];

    $src_x = 0;
    $src_y = 0;

    $src_width = $imagewidth;
    $src_height = $imageheight;

    $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
    switch($imageType) {
      case "image/gif":
        $source=imagecreatefromgif($image);
        break;
        case "image/pjpeg":
      case "image/jpeg":
      case "image/jpg":
        $source=imagecreatefromjpeg($image);
        break;
        case "image/png":
      case "image/x-png":
        $source=imagecreatefrompng($image);
        break;
      }
    imagecopyresampled($newImage, $source, 0, 0, $src_x, $src_y, $newImageWidth, $newImageHeight,
      $src_width, $src_height);
    switch($imageType) {
      case "image/gif":
          imagegif($newImage,$thumb_image_name);
        break;
          case "image/pjpeg":
      case "image/jpeg":
      case "image/jpg":
          imagejpeg($newImage,$thumb_image_name,90);
        break;
      case "image/png":
      case "image/x-png":
        imagepng($newImage,$thumb_image_name);
        break;
      }
    chmod($thumb_image_name, 0777);
    return $thumb_image_name;
  }


}

/* End of file panel_temas_model.php */
/* Location: ./application/models/panel_temas_model.php */