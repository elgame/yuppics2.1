<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_cupones_model extends CI_Model {


  public function getCupones($per_page='40', $orderby='created DESC'){

    $sql = '';
    //paginacion
    $params = array(
        'result_items_per_page' => $per_page,
        'result_page' => (isset($_GET['pag'])? $_GET['pag']: 0)
    );

    if($params['result_page'] % $params['result_items_per_page'] == 0)
      $params['result_page'] = ($params['result_page']/$params['result_items_per_page']);

    // if(isset($_GET['id']{0}) && $compara_id)
    //   $sql .= " AND id_familia = '".$_GET['id']."'";

    // if($this->input->get('fnombre') != '')
    //   $sql .= " AND lower(nombre) LIKE '%".mb_strtolower($this->input->get('fnombre'), 'UTF-8')."%'";

    $query = BDUtil::pagination("
        SELECT id_coupon, name, code, amount, percentage, uses_total,
               DATE(date_start) date_start, DATE(date_end) date_end, created, status
        FROM coupons
        ".$sql."
        ORDER BY ".$orderby."
        ", $params, true);
    $res = $this->db->query($query['query']);

    $response = array(
        'cupones'        => array(),
        'total_rows'     => $query['total_rows'],
        'items_per_page' => $params['result_items_per_page'],
        'result_page'    => $params['result_page']
    );
    if($res->num_rows() > 0)
      $response['cupones'] = $res->result();

    return $response;
  }

  public function info($id=null)
  {
    $id = $id ? $id : $_GET['id'];

    $res = $this->db->select('id_coupon, name, code, amount, percentage,
                              uses_total, DATE(date_start) date_start, DATE(date_end) date_end')
      ->from('coupons')
      ->where('id_coupon', $id)
      ->get();

    $cupon = array();
    if ($res->num_rows() > 0)
      $cupon['info'] = $res->result();

    return $cupon;
  }

  public function add()
  {
    $data = array(
      'name'       => $this->input->post('pnombre'),
      'code'       => $this->input->post('pcodigo'),
      'amount'     => $this->input->post('pcantidad'),
      'percentage' => $this->input->post('pporcentaje'),
      'uses_total' => $this->input->post('ptotalusos'),
      'date_start' => empty($_POST['pdatestart']) ? null : $_POST['pdatestart'],
      'date_end'   => empty($_POST['pdateend']) ? null : $_POST['pdateend'],
    );

    $this->db->insert('coupons', $data);

    return array('passess' => true);
  }

  public function update($id=null)
  {
    $id = $id ? $id : $_GET['id'];

    $data = array(
      'name'       => $this->input->post('pnombre'),
      'code'       => $this->input->post('pcodigo'),
      'amount'     => $this->input->post('pcantidad'),
      'percentage' => $this->input->post('pporcentaje'),
      'uses_total' => $this->input->post('ptotalusos'),
      'date_start' => empty($_POST['pdatestart']) ? null : $_POST['pdatestart'],
      'date_end'   => empty($_POST['pdateend']) ? null : $_POST['pdateend'],
    );

    $this->db->update('coupons', $data, array('id_coupon' => $id));

    return array('passess' => true);
  }

  public function del($id=null)
  {
    $id = $id ? $id : $_GET['id'];
    $this->db->delete('coupons', array('id_coupon' => $id));

    return array('passess' => true);
  }

}

/* End of file panel_configs_model.php */
/* Location: ./application/models/panel_configs_model.php */