<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_configs_model extends CI_Model {


  public function getConfigs()
  {
    $res = $this->db->select("*, (SELECT price FROM products WHERE id_product = 1) AS price")->from("config")->get();

    return $res->result();
  }

  public function update()
  {
    $data = array(
      'max_fotos' => $this->input->post('pFotosMax'),
      'percentage' => $this->input->post('pporcentaje'),
    );

    $this->db->update('config', $data);

    $this->db->update('products', array('price' => $_POST['pprecio']), array('id_product'=>'1'));


    return array('passess' => true);
  }


}

/* End of file panel_configs_model.php */
/* Location: ./application/models/panel_configs_model.php */