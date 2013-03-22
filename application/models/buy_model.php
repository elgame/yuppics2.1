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
          $result = $this->db->query("SELECT y.id_yuppic, y.title, y.quantity, o.status, p.price, y.comprado
                                     FROM yuppics AS y
                                     LEFT JOIN orders_yuppics AS oy ON oy.id_yuppics = y.id_yuppic
                                     LEFT JOIN orders AS o ON o.id_order = oy.id_order
                                     LEFT JOIN products as p ON p.id_product = y.id_product
                                     WHERE y.id_yuppic = ".$v." AND y.comprado = 0");

          if ($result->num_rows() > 0)
          {
            $obj_result = $result->result();
            if (is_null($obj_result[0]->status) || $obj_result[0]->status === 'c' || $obj_result[0]->comprado === '0')
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
    $id_field = ($_POST['type'] === 'coupon') ? 'id_coupon' : 'id_voucher';

    $query = $this->db->select($id_field.' as id, date_start, date_end')->from($table)->where('code', $_POST['code'])->get();

    if ($query->num_rows() > 0)
    {
      $couvau = $query->row();

      $query->free_result();
      $query = $this->db->select('count(id_history) as total')->from('coupons_history')->where('id_coupon', $couvau->id)->get();
      $uses_total = $query->row()->total;

      $date_start = ($couvau->date_start === NULL) ? '' : ' AND date_start < NOW()';
      $date_end   = ($couvau->date_end === NULL) ? '' : ' AND date_end > NOW()';

      $query->free_result();
      $query = $this->db->select($id_field . ' as id, amount')->
                                from($table)->
                                where('code = "'.$_POST['code'].'" AND uses_total >='.(intval($uses_total) + 1).$date_start.$date_end)->
                                get();

      if ($query->num_rows() > 0)
      {
        $query_res = $query->row();
        $query_res->type = $table;
        return $query_res;
      }
      else return FALSE;
    }
    else return FALSE;

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

    $data_ids_yuppics = array();
    foreach($_POST['yids'] as $k => $v)
    {
      $data_ids_yuppics[] = array('id_order' => $id_order, 'id_yuppics' => $v);
      $this->db->update('yuppics', array('quantity' => $_POST['yqty'][$k]), array('id_yuppic' => $v));
    }
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
          $this->db->update('coupons', array('status' => 1), array('id_coupon' => $type_discount_id));
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
          $this->db->update('vouchers', array('status' => 1), array('id_voucher' => $type_discount_id));
      }
    }

    // Valida el tipo de metodo de pago para hacerlo por Paypal o MercadoPago
    if ($this->input->post('payMethod') === 'pp')
      $this->payByPaypal($id_order);
    else
      $this->payByMercadoPago($id_order);

  }

  /**
   * Paypal
   * @param  [type] $id_order [description]
   * @return [type]           [description]
   */
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
  public function confirmByPaypal($id_order)
  {
    $this->load->model('order_model');
    $data = $this->order_model->get_order_info($id_order);

    $this->load->library('my_paypal');

    $this->my_paypal->config_do_payment(array(
      'token'   => $this->input->get('token'),
      'payerid' => $this->input->get('PayerID')
    ));

    $this->my_paypal->config_payment(array(
      'currencycode'         => 'MXN',
      'desc'                 => 'Pago Yuppics',
      'shippingamt'          => 0,
      'discount_amount_cart' => $data['info']->total_discount
    ));

    $products = array();
    if (isset($data['products'])) {
      foreach ($data['products'] as $k => $v)
      {
        $products[] = array(
                        'name'  => $v->title,
                        'price' => $v->unitary_price,
                        'qty'   => $v->quantity
                      );
      }
    }

    if (floatval($data['info']->total_discount) > 0)
    {
      $products[] = array(
                      'name'  => 'Descuento | Discount',
                      'price' => floatval($data['info']->total_discount) * -1,
                      'qty'   => 1
                    );
    }

    $this->my_paypal->add_products($products);
    return $this->my_paypal->do_payment();
  }

  /**
   * Mercadopago
   * @param  [type] $id_order [description]
   * @return [type]           [description]
   */
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

    $discount = floatval($_POST['tdiscount']);
    $exis_discount = ($discount > 0 ) ? TRUE : FALSE;
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

  public function success($order)
  {
    var_dump($this->confirmByPaypal($order));
    exit;

    $query = $this->db->query("SELECT id_yuppics
                               FROM orders_yuppics
                               WHERE id_order = ".$order);
    $yuppics = $query->result();

    foreach ($yuppics as $v)
      $this->db->update('yuppics', array('comprado' => '1'), array('id_yuppic' => $v->id_yuppics));

    return $order;
  }

  public function cancelOrder($order)
  {
    // $this->db->update('orders', array('status' => 'c'), array('id_order' => $order));
    $this->db->delete('orders', array('id_order' => $order));
    return $order;
  }
}

/* End of file compras_model.php */
/* Location: ./application/models/compras_model.php */
