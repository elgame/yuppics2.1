<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_autores_model extends CI_Model {


  public function getAll($per_page='40', $orderby='name ASC'){

    $sql = '';
    //paginacion
    $params = array(
        'result_items_per_page' => $per_page,
        'result_page' => (isset($_GET['pag'])? $_GET['pag']: 0)
    );

    if($params['result_page'] % $params['result_items_per_page'] == 0)
      $params['result_page'] = ($params['result_page']/$params['result_items_per_page']);

    if($this->input->get('fsearch') != '')
      $sql .= " WHERE lower(a.name) LIKE '%" . mb_strtolower($this->input->get('fsearch'), 'UTF-8') . "%'";

    $query = BDUtil::pagination("
        SELECT id_autor, name
        FROM themes_autor AS a
        ".$sql."
        ORDER BY ".$orderby."
        ", $params, true);
    $res = $this->db->query($query['query']);

    $response = array(
        'autores'        => array(),
        'total_rows'     => $query['total_rows'],
        'items_per_page' => $params['result_items_per_page'],
        'result_page'    => $params['result_page']
    );
    if($res->num_rows() > 0)
      $response['autores'] = $res->result();

    return $response;
  }

  public function info($id=null)
  {
    $id = $id ? $id : $_GET['id'];

    $res = $this->db->select('*')
      ->from('themes_autor')
      ->where('id_autor', $id)
      ->get();

    $autor = array();
    if ($res->num_rows() > 0)
      $autor['info'] = $res->result();

    return $autor;
  }

  public function add()
  {
    $this->db->insert('themes_autor', array('name' => $_POST['pname']));

    return array('passess' => true);
  }

  public function editar($id=null)
  {
    $id = $id ? $id : $_GET['id'];

    $this->db->update('themes_autor', array('name' => $_POST['pname']), array('id_autor' => $id));

    return array('passess' => true);
  }

  public function delete($id=null)
  {
    $id = $id ? $id : $_GET['id'];
    $this->db->delete('themes_autor', array('id_autor' => $id));

    return array('passess' => true);
  }

}

/* End of file panel_temas_model.php */
/* Location: ./application/models/panel_temas_model.php */