<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_yuppics_model extends CI_Model {


  public function getAll($per_page='40', $orderby='created DESC'){

    $sql = '';
    //paginacion
    $params = array(
        'result_items_per_page' => $per_page,
        'result_page' => (isset($_GET['pag'])? $_GET['pag']: 0)
    );

    if($params['result_page'] % $params['result_items_per_page'] == 0)
      $params['result_page'] = ($params['result_page']/$params['result_items_per_page']);

    if ($this->input->get('fstatus') != '')
      $sql = "WHERE y.comprado = " . $this->input->get('fstatus');

    if($this->input->get('fsearch') != '')
      $sql .= " AND lower(c.first_name) LIKE '%" . mb_strtolower($this->input->get('fsearch'), 'UTF-8') . "%' ||
                lower(c.last_name) LIKE '%" . mb_strtolower($this->input->get('fsearch'), 'UTF-8') . "%'";

    $query = BDUtil::pagination("
        SELECT y.id_yuppic,
               y.id_customer,
               c.first_name,
               c.last_name,
               c.email,
               c.username,
               c.phone,
               y.id_product,
               y.title,
               y.author,
               y.quantity,
               y.created,
               y.comprado
        FROM yuppics AS y
        INNER JOIN customers AS c ON c.id_customer = y.id_customer
        ".$sql."
        ORDER BY ".$orderby."
        ", $params, true);
    $res = $this->db->query($query['query']);

    $response = array(
        'yuppics'        => array(),
        'total_rows'     => $query['total_rows'],
        'items_per_page' => $params['result_items_per_page'],
        'result_page'    => $params['result_page']
    );
    if($res->num_rows() > 0)
      $response['yuppics'] = $res->result();

    return $response;
  }

  public function info($id=null)
  {
    $id = $id ? $id : $_GET['id'];

    $res = $this->db->select('id_theme, id_autor, name, background_img, background_color,
                              text_color, status')
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

    $path_themes       = 'yuppics/themes/';

    $config_upload = array(
     'upload_path'     => APPPATH.$path_themes,
     'allowed_types'   => 'jpg|png',
     'max_size'        => '2048',
     'encrypt_name'    => true
    );

    $config_resize = array(
      'image_library' => 'gd2',
      'source_image'  => '',
      'create_thumb' => TRUE,
      'width'         => '350',
      'height'        => '280',
      'maintain_ratio' => TRUE
    );

    $this->my_upload->do_resize     = TRUE;
    $this->my_upload->config_resize = $config_resize;

    $this->my_upload->initialize($config_upload);
    $theme = $this->my_upload->do_upload('pimg');

    $theme_path = explode('application/', $theme['full_path']);
    $theme_path = 'application/' . $theme_path[1];

    $data = array(
      'id_autor'         => $this->input->post('pautor'),
      'name'             => $this->input->post('pname'),
      'background_img'   => $theme_path,
      'background_color' => $this->input->post('pbgcolor'),
      'text_color'       => $this->input->post('ptxtcolor'),
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
    );

    if ( ! empty($_FILES['pimg']['name']))
    {
      $this->load->library("my_upload");

      $path_themes       = 'yuppics/themes/';

      $config_upload = array(
       'upload_path'     => APPPATH.$path_themes,
       'allowed_types'   => 'jpg|png',
       'max_size'        => '2048',
       'encrypt_name'    => true
      );

      $config_resize = array(
        'image_library' => 'gd2',
        'source_image'  => '',
        'create_thumb' => TRUE,
        'width'         => '350',
        'height'        => '280',
        'maintain_ratio' => TRUE
      );

      $this->my_upload->do_resize     = TRUE;
      $this->my_upload->config_resize = $config_resize;

      $this->my_upload->initialize($config_upload);
      $theme = $this->my_upload->do_upload('pimg');

      $theme_path = explode('application/', $theme['full_path']);
      $theme_path = 'application/' . $theme_path[1];

      $data['background_img'] = $theme_path;

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

}

/* End of file panel_temas_model.php */
/* Location: ./application/models/panel_temas_model.php */