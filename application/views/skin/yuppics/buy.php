<div id="content" class="span6 ordercps mtop-content"> <!-- STAR SPAN8 -->

  <div class="row-fluid"> <!-- START ROW-FLUID -->
    <div id="send_alert" class="alert hide">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <span></span>
    </div>
    <div class="hero-unit unitwhite">
      <div class="unit-body unit-foo">
        <h3>Resumen de compra</h3>
        <p>A continuación se muestran de manera detallada los datos de compra a realizar, si tienes alguna duda puedes contactarnos
          <a href="#" class="link_green bold" data-toggle="modal" data-target="#modal_contact">aquí</a></p>
      </div>
    </div>
  </div> <!-- END ROW-FLUID -->

  <div class="row-fluid">  <!-- START DATOS -->
    <div class="box span12">
      <div class="box-header well">
        <h2>Datos</h2>
       
        <div class="box-icon pull-right">
          <div class="btn-group">
            <a href="#" class="btn btn-white btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
          </div>
        </div>
      </div>
      <div class="box-content">
        <div class="row-fluid">
          <div id="deleteaddress_alert" class="alert hide">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <span></span>
          </div>
        </div>
        <div class="row-fluid">
          <address class="ord_datos_adress">
            <?php if (!empty($info_customer->username)) {?> <strong>Username: </strong><?php echo $info_customer->username?><br><?php } ?>
            <strong>Nombre: </strong><?php echo $info_customer->first_name.' '.$info_customer->last_name ?><br>
            <strong>Email: </strong><a href="mailto:#"><?php echo $info_customer->email?></a><br>
            <strong><abbr title="Teléfono">Tel: </abbr></strong><?php echo (!empty($info_customer->phone)) ? $info_customer->phone : 'No especificado'; ?><br>
          </address>
        </div>
      </div>
    </div>
  </div> <!-- END DATOS -->

  <form action="<?php echo base_url('buy/order?y='.$_GET['y']);?>" method="POST">
    <div class="row-fluid">  <!-- START ADDRESSBOOK -->
      <div class="box span12" id="address">
        <div class="box-header well">
          <h2>Libro de direcciones</h2>
          <div class="box-icon pull-right">
            <div class="btn-group">
              <a href="#modal_addaddress" class="btn btn-white" title="Agregar direccion" role="button" data-toggle="modal"><i class="icon-plus"></i></a>
              <a href="#" class="btn btn-white btn-minimize"><i class="icon-chevron-up"></i></a>
            </div>
          </div>
        </div>
        <div class="box-content nopaddin">
          <div class="row-fluid">
            <div id="deleteaddress_alert" class="alert hide">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <span></span>
            </div>
          </div>

          <div class="row-fluis">
            <div class="span12 center"><h4 class="text-warning">NOTA: asegurate de especificar correctamente las direcciones de envío y/o facturación</h4></div>
          </div>

          <div class="row-fluid xxboxx">
            <div class="span6 title-box">
                Dirección predeterminada
            </div>

            <!--[if !IE]><!-->
              <div class="span6 title-box">
                Otras Direcciones
              </div>
             <!--<![endif]-->
            <!--[if lt IE 8]>
              <div class="span5 title-box" style="margin-left:0;">
                Dirección predeterminada
              </div>
            <![endif]-->

          </div>
        <?php
        if ($address_books != false)
        {
        ?>
          <div class="paddin10" style="background-color: #fbfbfc;">
            <div class="row-fluid">
              <div class="span6">


                  <div class="control-group">
                <?php
                if (isset($address_books['default']))
                {
                  foreach ($address_books['default'] as $key => $default) {
                    if($default->default_billing == 1 && $default->default_shipping == 1)
                      echo '<input type="hidden" name="id_address_billing" value="'.$default->id_address.'">
                            <input type="hidden" name="id_address_shipping" value="'.$default->id_address.'">';
                    else if ($default->default_billing == 1)
                      echo '<input type="hidden" name="id_address_billing" value="'.$default->id_address.'">';
                    else if ($default->default_shipping == 1)
                      echo '<input type="hidden" name="id_address_shipping" value="'.$default->id_address.'">';
                ?>
                    <p>
                      <span class="span2">
                        <a href="#modal_updateaddress" class="btn-link update_address center" data-toggle="modal" data-id="<?php echo $default->id_address; ?>">
                          <img src="<?php echo base_url('application/images/edit-dir.png'); ?>" width="20" height="20">
                        </a>
                      </span>
                      <span class="span10">
                        <?php
                        echo '<strong style="color:#616569;">'.$default->contact_first_name.' '.$default->contact_last_name.'</strong><br><br>'.
                          ($default->company!=''? $default->company.'<br>': '').
                          ($default->rfc!=''? $default->rfc.'<br>': '').
                          $default->street.', '.$default->colony.'<br>'.
                          $default->city.', '.$default->state.', '.$default->country;
                        ?>
                      </span>
                    </p>
                <?php
                  }
                } ?>
                  </div>

              </div>

              <div class="span6">
                  <div class="control-group">
                  <?php
                  if (isset($address_books['others']))
                  {
                    foreach ($address_books['others'] as $key => $default) {

                    /*<strong>Direccion <?php echo $key+1; ?></strong>*/
                  ?>
                      <p>
                        <span class="span2">
                          <a href="#modal_updateaddress" class="btn-link update_address center" data-toggle="modal" data-id="<?php echo $default->id_address; ?>">
                            <img src="<?php echo base_url('application/images/edit-dir.png'); ?>" width="20" height="20">
                          </a>
                          <a href="<?php echo base_url('address_book/delete?id='.$default->id_address) ?>" class="btn-link center" 
                            style="margin-top:20px;" 
                            onclick="msb.confirm('Estas seguro de eliminar la direccion?', 'Libro de direcciones', this); return false;">
                            <img src="<?php echo base_url('application/images/delete-dir.png'); ?>" width="20" height="20">
                          </a>
                        </span>
                        <span class="span10">
                          <?php
                          echo '<strong style="color:#616569;">'.$default->contact_first_name.' '.$default->contact_last_name.'</strong><br><br>'.
                            ($default->company!=''? $default->company.'<br>': '').
                            ($default->rfc!=''? $default->rfc.'<br>': '').
                            $default->street.', '.$default->colony.'<br>'.
                            $default->city.', '.$default->state.', '.$default->country;
                          ?>
                        </span>
                      </p>
                  <?php
                    }
                  }else{
                  ?>
                    <span>No hay más direcciones disponibles. Da de alta otra dirección <a href="#modal_addaddress" class="link_green bold" title="Agregar direccion" role="button" data-toggle="modal">aquí</a></span>
                  <?php
                  }?>
                  </div>

              </div>
            </div>

          </div>
        <?php
        }
        else
        {
        ?>
            <div class="alert alert-block">
              <h4>No has registrado direcciones</h4>
              Agrega almenos una direccion :)
            </div>
        <?php
        }
        ?>






        </div>
      </div><!--/span-->

    </div><!-- END ADDRESSBOOK -->


    <div class="row-fluid">  <!-- START DATOS -->
      <div class="box span12">
        <div class="box-header well">
          <h2>Datos de la Compra</h2>

          <div class="box-icon pull-right">
            <div class="btn-group">
              <a href="#" class="btn btn-white btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
            </div>
          </div>
        </div>
        <div class="box-content"> <!-- START box-content -->
          <div class="row-fluid">
              <div class="span12 well wellwithe" style="border-bottom: 1px solid #dfdede;">
                <div id="discount_alert" class="alert hide">
                  <button type="button" class="close">×</button>
                  <span></span>
                </div>
                <div class="input-append">
                  <label class="radio inline">
                    <input type="radio" name="typeDiscount" value="coupon" checked>Código de Descuento
                  </label>
                   <input type="text" value="" name="codeDiscount" id="codeDiscount" style="margin-left: 5px;"><button type="button" class="btn btn-info btn-small" id="btnDiscount" disabled>Cargar</button>
                   <span class="mutted" id="txt-discount">Descuento con valor de <strong>$0.00MXN</strong></span>
                </div><!-- 
                <div class="input-append">
                  <label class="radio inline">
                    <input type="radio" name="typeDiscount" value="voucher">Seleccionar Voucher
                  </label>
                </div>
                <strong>Recuerda que por compra sólo puedes utilizar un voucher o un codigo de descuento.</strong> -->
                <input type="hidden" name="type_discount_id" value="" id="type_discount_id">
                <input type="hidden" name="type_discount" value="" id="type_discount">
              </div>
          </div>
          <div class="row-fluid"> <!-- START INFORMACION DETALLADA -->
            <div class="span12">
              <h3>Información detallada de la Compra</h3>

              <table class="table table-striped table-hover"> <!-- START TABLE LIST YUPPICS -->
                <thead>
                  <tr>
                    <th>Yuppic</th>
                    <th>Precio Unitario</th>
                    <th>Cant.</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $ttotal = $tshipping = $tdiscount = 0;
                      foreach ($yuppics['result'] as $k => $yuppic) {
                        if ($yuppic->resultado_verificacion) {
                          $subtotal = floatval($yuppic->price) * floatval($yuppic->quantity);
                          $ttotal += $subtotal;
                  ?>
                      <tr>
                        <td><?php echo $yuppic->title; ?>
                            <input type="hidden" name="yids[]" value="<?php echo $yuppic->id_yuppic; ?>">
                            <input type="hidden" name="ytitle[]" value="<?php echo $yuppic->title; ?>" data-id="<?php echo $yuppic->id_yuppic?>"></td>
                        <td><?php echo $yuppic->price; ?>
                            <input type="hidden" name="yprice[]" value="<?php echo $yuppic->price; ?>" data-id="<?php echo $yuppic->id_yuppic?>"></td>
                        <td><input type="number" name="yqty[]" value="<?php echo $yuppic->quantity ?>" id="yqty" min="1" data-id="<?php echo $yuppic->id_yuppic?>"></td>
                        <td id="<?php echo $yuppic->id_yuppic?>"><?php echo String::formatoNumero($subtotal) ?>MXN</td>
                      </tr>
                  <?php } 
                      }
                  ?>
                </tbody>
              </table> <!-- END TABLE LIST YUPPICS -->

              <div class="row-fluid"> <!-- START TABLE TOTALES -->
                <div class="span12">
                    <table class="table span5 pull-right">
                      <thead>
                        <tr>
                          <th></th>
                          <th></th>
                          <input type="hidden" name="tsubtotal" id="tsubtotal" value="<?php echo $ttotal ?>">
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><em>Gastos de Envío</em></td>
                          <td id="tship-format"><?php echo String::formatoNumero($tshipping)?>MXN</td>
                          <input type="hidden" name="tship" id="tship" value="<?php echo $tshipping ?>">
                        </tr>
                        <tr>
                          <td>Descuentos</td>
                          <td id="tdiscount-format"><?php echo String::formatoNumero($tdiscount)?>MXN</td>
                          <input type="hidden" name="tdiscount" id="tdiscount" value="<?php echo $tdiscount ?>">
                        </tr>
                        <tr class="em13 bold">
                          <td>Total</td>
                          <td id="ttotal-format"><?php echo String::formatoNumero($ttotal + $tshipping + $tdiscount)?>MXN</td>
                          <input type="hidden" name="ttotal" id="ttotal" value="<?php echo $ttotal + $tshipping + $tdiscount?>">
                        </tr>
                      </tbody>
                    </table>
                </div>
              </div> <!-- END TABLE TOTALES -->

              <div class="row-fluid">
                <div class="span12 well wellwithe" style="border-top: 1px solid #dfdede;">
                  <div class="row-fluid">
                    <div class="span6">
                        <h3>Selecciona el método de pago</h3>
                        <label class="radio">
                          <input type="radio" name="payMethod" value="pp" checked>
                          Vía Paypal
                        </label>
                        <label class="radio">
                          <input type="radio" name="payMethod" value="mp">
                          Vía Mercadopago
                        </label>
                    </div>
                    <div class="span6">
                      <button type="submit" class="btn btn-success btn-large pull-right">Realizar compra</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- END INFORMACION DETALLADA -->
        </div><!-- END box-content -->
      </div>
    </div> <!-- END DATOS -->
  </form>

</div> <!-- END SPAN8 -->

<div id="modal_addaddress" class="modal hide fade"><!-- START MODAL AGREGAR ADDRESS -->
  <form action="<?php echo base_url('address_book/add/')?>" method="POST" class="form-horizontal" data-sendajax="true"
      data-alert="address_alert" data-callback="address_success_shop">

    <div class="modal-header tacenter">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h2>Direccion</h2>
    </div>

    <div class="modal-body">

      <div class="span12">
          <div class="control-group">
            <div id="address_alert" class="alert hide">
              <button type="button" class="close">×</button>
              <span></span>
            </div>
          </div>

          <div class="control-group">
            <label for="contact_first_name" class="control-label">Nombre</label>
            <div class="controls">
              <input type="text" name="contact_first_name" value="<?php echo $info_customer->first_name; ?>" class="" id="contact_first_name" placeholder="Nombre" autofocus required maxlength="30">
            </div>
          </div>
          <div class="control-group">
            <label for="contact_last_name" class="control-label">Apellido Paterno</label>
            <div class="controls">
              <input type="text" name="contact_last_name" value="<?php echo $info_customer->last_name; ?>" class="" id="contact_last_name" placeholder="Apellido Paterno" required maxlength="40">
            </div>
          </div>

          <div class="control-group">
            <label for="company" class="control-label">Compañia</label>
            <div class="controls">
              <input type="text" name="company" class="" id="company" placeholder="yuppics" maxlength="110">
            </div>
          </div>
          <div class="control-group">
            <label for="rfc" class="control-label">RFC</label>
            <div class="controls">
              <input type="text" name="rfc" class="" id="rfc" placeholder="RFC" maxlength="13">
            </div>
          </div>

          <div class="control-group">
            <label for="street" class="control-label">Direccion</label>
            <div class="controls">
              <input type="text" name="street" class="" id="street" placeholder="Direccion" maxlength="100" required>
            </div>
          </div>
          <div class="control-group">
            <label for="colony" class="control-label">Colonia</label>
            <div class="controls">
              <input type="text" name="colony" class="" id="colony" placeholder="Colonia" maxlength="70" required>
            </div>
          </div>
          <div class="control-group">
            <label for="city" class="control-label">Ciudad</label>
            <div class="controls">
              <input type="text" name="city" class="" id="city" placeholder="Ciudad" maxlength="70" required>
            </div>
          </div>
          <div class="control-group">
            <label for="between_streets" class="control-label">Entre calles</label>
            <div class="controls">
              <input type="text" name="between_streets" class="" id="between_streets" placeholder="Entre calles" maxlength="160">
            </div>
          </div>

          <div class="control-group">
            <label for="state" class="control-label">Estado</label>
            <div class="controls">
              <select name="state" id="state" required>
                <option value=""></option>
              <?php
              foreach ($states as $key => $value) {
              ?>
                <option value="<?php echo $value->id_state ?>"><?php echo $value->name ?></option>
              <?php
              }
              ?>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
              <label for="default_billing">
                <input type="checkbox" name="default_billing" id="default_billing" value="si"> Direccion predeterminada de Facturacion
              </label>

              <label for="default_shipping">
                <input type="checkbox" name="default_shipping" id="default_shipping" value="si"> Direccion predeterminada de Envio
              </label>
            </div>
          </div>

      </div>


    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-success">Guardar</button>
    </div>
  </form>
</div><!-- END MODAL AGREGAR ADDRESS -->


<div id="modal_updateaddress" class="modal hide fade"> <!-- START MODAL MODIFICAR ADDRESS -->
  <form id="frm_updateaddress" action="<?php echo base_url('address_book/update/')?>" method="POST" class="form-horizontal" data-sendajax="true"
      data-alert="updateaddress_alert" data-callback="address_success_shop">

    <div class="modal-header tacenter">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h2>Modificar direccion</h2>
    </div>

    <div class="modal-body">

      <div class="span12">
          <div class="control-group">
            <div id="updateaddress_alert" class="alert hide">
              <button type="button" class="close">×</button>
              <span></span>
            </div>
          </div>

          <input type="hidden" name="id_address" id="id_address" value="">
          <div class="control-group">
            <label for="contact_first_name" class="control-label">Nombre</label>
            <div class="controls">
              <input type="text" name="contact_first_name" value="<?php echo $info_customer->first_name; ?>" class="" id="contact_first_name" placeholder="Nombre" autofocus required maxlength="30">
            </div>
          </div>
          <div class="control-group">
            <label for="contact_last_name" class="control-label">Apellido Paterno</label>
            <div class="controls">
              <input type="text" name="contact_last_name" value="<?php echo $info_customer->last_name; ?>" class="" id="contact_last_name" placeholder="Apellido Paterno" required maxlength="40">
            </div>
          </div>

          <div class="control-group">
            <label for="company" class="control-label">Compañia</label>
            <div class="controls">
              <input type="text" name="company" class="" id="company" placeholder="yuppics" maxlength="110">
            </div>
          </div>
          <div class="control-group">
            <label for="rfc" class="control-label">RFC</label>
            <div class="controls">
              <input type="text" name="rfc" class="" id="rfc" placeholder="RFC" maxlength="13">
            </div>
          </div>

          <div class="control-group">
            <label for="street" class="control-label">Direccion</label>
            <div class="controls">
              <input type="text" name="street" class="" id="street" placeholder="Direccion" maxlength="100" required>
            </div>
          </div>
          <div class="control-group">
            <label for="colony" class="control-label">Colonia</label>
            <div class="controls">
              <input type="text" name="colony" class="" id="colony" placeholder="Colonia" maxlength="70" required>
            </div>
          </div>
          <div class="control-group">
            <label for="city" class="control-label">Ciudad</label>
            <div class="controls">
              <input type="text" name="city" class="" id="city" placeholder="Ciudad" maxlength="70" required>
            </div>
          </div>
          <div class="control-group">
            <label for="between_streets" class="control-label">Entre calles</label>
            <div class="controls">
              <input type="text" name="between_streets" class="" id="between_streets" placeholder="Entre calles" maxlength="160">
            </div>
          </div>

          <div class="control-group">
            <label for="id_state" class="control-label">Estado</label>
            <div class="controls">
              <select name="id_state" id="id_state" required>
                <option value=""></option>
              <?php
              foreach ($states as $key => $value) {
              ?>
                <option value="<?php echo $value->id_state ?>"><?php echo $value->name ?></option>
              <?php
              }
              ?>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
              <label for="default_billing1">
                <input type="checkbox" name="default_billing" id="default_billing1" value="1"> Direccion predeterminada de Facturacion
              </label>

              <label for="default_shipping1">
                <input type="checkbox" name="default_shipping" id="default_shipping1" value="1"> Direccion predeterminada de Envio
              </label>
            </div>
          </div>

      </div>


    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div><!-- END MODAL MODIFICAR ADDRESS -->

<div id="messajes_alerts" class="modal hide fade">
  <div class="modal-body">
    <p></p>
    <input type="hidden" value="<?php echo isset($throw_alert[1])?$throw_alert[1]:''; ?>" id="alert-throw" data-throwalert="<?php echo isset($throw_alert[0])?$throw_alert[0]:'false'; ?>">
  </div>
  <div class="modal-footer">
    <a href="<?php echo base_url('history'); ?>" class="btn btn-info">Mi Historial</a>
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true" id="modal-alert-btn">Cerrar</a>
  </div>
</div>



<!-- Bloque de alertas -->
<?php if(isset($frm_errors)){
  if($frm_errors['msg'] != ''){
?>
<script type="text/javascript" charset="UTF-8">
  $(document).ready(function(){
    var valert = $("#<?php echo $frm_errors['objs']; ?>");

    valert.addClass("alert-<?php echo $frm_errors['ico']; ?>").show(300);
    $("span", valert).html("<?php echo $frm_errors['msg']; ?>");
  });
</script>
<?php }
}?>
<!-- Bloque de alertas -->