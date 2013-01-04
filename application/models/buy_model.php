<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buy_model extends CI_Model {

  /**
   * Verifica que el o los yuppics existan y no esten en otra orden.
   * @param  Array $ids [Array de ids de yuppics]
   * @return booelean
   */
  public function verificaYuppic($ids)
  {
    if ($ids && !empty($ids))
    {
      $array_ids = explode(',', $ids);
      $data_yuppics = array();
      $final_result = TRUE;

      foreach ($array_ids as $k => $v)
      {
        if ($v !== '')
          {
          $exist = FALSE;
          $result = $this->db->query("SELECT y.id_yuppic, y.title, y.quantity, o.status, p.price
                                     FROM yuppics AS y
                                     LEFT JOIN orders_yuppics AS oy ON oy.id_yuppics = y.id_yuppic
                                     LEFT JOIN orders AS o ON o.id_order = oy.id_order
                                     LEFT JOIN products as p ON p.id_product = y.id_product
                                     WHERE y.id_yuppic = ".$v);

          if ($result->num_rows() > 0)
          {
            $obj_result = $result->result();
            if (is_null($obj_result[0]->status) || $obj_result[0]->status === 'c' )
            {
              $obj_result[0]->resultado_verificacion = TRUE;
              $data_yuppics['result'][] =  $obj_result[0];
            }
            else
            {
              $exist = TRUE;
              $final_result = FALSE;
            }
          }
          else
          {
            $exist = TRUE;
            $final_result = FALSE;
          }

          if ($exist)
          {
            $obj = new stdClass();
            $obj->id_yuppic = $v;
            $obj->title = isset($obj_result[0]->title) ? $obj_result[0]->title : '';
            $obj->status = isset($obj_result[0]->status) ? $obj_result[0]->status : '';
            $obj->resultado_verificacion = FALSE;

             $data_yuppics['result'][] = $obj;
          }

          $result->free_result();
          unset($obj_result);
        }
      }

      $data_yuppics['status_general'] = $final_result;
      return $data_yuppics;
    }
    else
      return FALSE;
  }

  public function valid_code()
  {
    $table = ($_POST['type'] === 'coupon') ? 'coupons' : 'vouchers';
    $id_field = ($_POST['type'] === 'coupon') ? 'id_coupon as id' : 'id_voucher as id';

    $query = $this->db->select('count(id_history) as total')->from('coupons_history')->where('id_coupon',$_POST['code'])->get();
    $uses_total = $query->row()->total;

    $query->free_result();
    $query = $this->db->select($id_field . ', amount')->
                              from($table)->
                              where('code = "'.$_POST['code'].'" AND uses_total >='.(intval($uses_total) + 1).' AND date_start < NOW() AND date_end > NOW()')->
                              get();

    $query_res = $query->row();
    if (count($query_res) > 0)
    {
      $query_res->type = $table;
      return $query_res;
    }
    else
      return FALSE;
  }

  public function pay()
  {
    $id_customer = $this->session->userdata('id_usuario');
    $data_order = array(
          'id_customer'         => $id_customer,
          'id_address_billing'  => $this->input->post('id_address_billing'),
          'id_address_shipping' => $this->input->post('id_address_shipping'),
          'total_shipping'      => $this->input->post('tship'),
          'total_discount'      => $this->input->post('tdiscount'),
          'total'               => $this->input->post('ttotal'),
          'created'             => date('Y-m-d H:m:i'),
          'status'              => 'p'
    );

    $this->db->insert('orders', $data_order);
    $id_order = $this->db->insert_id();

    $yuppics = explode(',', $_GET['y']);
    $data_ids_yuppics = array();
    foreach ($yuppics as $id)
      $data_ids_yuppics[] = array('id_order'=>$id_order, 'id_yuppics'=>$id);

    $this->db->insert_batch('orders_yuppics', $data_ids_yuppics);

    $type_discount = $this->input->post('type_discount');
    $type_discount_id = $this->input->post('type_discount_id');

    if (!empty($type_discount) && !empty($type_discount_id))
    {

      $data_discount = array(
            'id_customer' => $id_customer,
            'id_order'    => $id_order,
            'amount'      => $this->input->post('tdiscount'));

      if ($type_discount === 'coupons')
      {
        $data_discount['id_coupon'] = $type_discount_id;
        $this->db->insert('coupons_history', $data_discount);

        $cres = $this->db->select('COUNT(id_history) as total')->
                                  from('coupons_history')->
                                  where('id_coupon', $type_discount_id)->
                                  get();

        $total_used = $cres->row()->total;

        $cres2 = $this->db->select('uses_total')->
                                  from('coupons')->
                                  where('id_coupon', $type_discount_id)->
                                  get();

        $max_uses = $cres2->row()->uses_total;

        if (floatval($max_uses) == floatval($total_used))
        {
          $this->db->update('coupons', array('status' => 1), array('id_coupon' => $type_discount_id));
        }
      }
      else
      {
        $data_discount['id_voucher'] = $type_discount_id;
        $this->db->insert('vouchers_history', $data_discount);

        $vres = $this->db->select('COUNT(id_history) as total')->
                          from('vouchers_history')->
                          where('id_voucher', $type_discount_id)->
                          get();
        $total_used = $vres->row()->total;

        $vres2 = $this->db->select('uses_total')->
                                  from('vouchers')->
                                  where('id_voucher', $type_discount_id)->
                                  get();

        $max_uses = $vres2->row()->uses_total;

        if (floatval($max_uses) === floatval($total_used))
        {
          $this->db->update('vouchers', array('status' => 1), array('id_voucher' => $type_discount_id));
        }
      }
    }

    // Valida el tipo de metodo de pago para hacerlo por Paypal o MercadoPago
    if ($this->input->post('payMethod') === 'pp')
    {
      $this->payByPaypal($id_order);
    }
    else
    {
      $this->payByMercadoPago($id_order);
    }

  }

  public function payByPaypal($id_order)
  {
    $this->load->library('my_paypal');

    $this->my_paypal->config_sale(array(
      'maxamt'                => '30000.00',
      'returnurl'             => base_url('buy/success?order='.$id_order),
      'cancelurl'             => base_url('buy/cancel?order='.$id_order),
      'brandname'             => 'Yuppics',
      'customerservicenumber' => '(312) 12345'
    ));

    $this->my_paypal->config_payment(array(
      'currencycode' => 'MXN',
      'desc'         => 'Pago Yuppics',
      'shippingamt'  => 0,
      // 'discount'     => 0,
      // 'taxamt'       => $_POST['tdiscount'],
      'discount_amount_cart' => $_POST['tdiscount']
    ));

    $products = array();
    foreach ($_POST['ytitle'] as $k => $v)
    {
      $products[] = array(
                      'name'  => $v,
                      'price' => $_POST['yprice'][$k],
                      'qty'   => $_POST['yqty'][$k]
                    );
    }

    if (floatval($_POST['tdiscount']) > 0)
    {
      $products[] = array(
                      'name'  => 'Descuento | Discount',
                      'price' => floatval($_POST['tdiscount']) * -1,
                      'qty'   => 1
                    );
    }

    $this->my_paypal->add_products($products);
    $this->my_paypal->send_checkout();

  }

  public function payByMercadoPago($id_order)
  {
    $this->load->library('mp', array(
      'client_id'     => '7172967533334556',
      'client_secret' => 'omCwGHkRTK9PL1b2pskoCUaBIDtnhgy1') );

    $access = $this->mp->get_access_token();

    $preference = array(
      'items' => array(),
      'back_urls' => array(
        'success' => base_url('buy/success?order='.$id_order),
        'failure' => base_url('buy/cancel?order='.$id_order),
        'pending' => base_url('buy/cancel?order='.$id_order),
      )
    );

    $products = array();

    $discount = floatval($_POST['tdiscount']);
    $exis_discount = ($discount > 0 ) ? TRUE : FALSE;
    $applied_discount = FALSE;
    $total = 0;

    foreach ($_POST['ytitle'] as $k => $v)
    {
      $total += floatval(floatval($_POST['yprice'][$k]) * floatval($_POST['yqty'][$k]));
    }

    if ($exis_discount)
    {
      $total = $total - $discount;
    }

    $preference['items'][] = array(
        'id'          => '',
        'title'       => 'Yuppics',
        'description' => 'Compra Yuppics',
        'quantity'    => 1,
        'currency_id' => 'MXN',
        'unit_price'  => $total,
        'picture_url' => ''
      );

    $result = $this->mp->create_preference($preference);
    header("Location: ".$result['response']['init_point']);
  }

  public function cancelOrder($order)
  {
    $this->db->update('orders', array('status' => 'c'), array('id_order' => $order));
    return $order;
  }

}

/* End of file compras_model.php */
/* Location: ./application/models/compras_model.php */
