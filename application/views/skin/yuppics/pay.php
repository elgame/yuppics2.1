<div class="span8"> <!-- STAR SPAN8 -->
  <div>
    <ul class="breadcrumb">
     <li>
        <a href="<?php echo base_url(); ?>">Inicio</a> <span class="divider">/</span>
      </li>
      <li>
        Pago Compra
      </li>
    </ul>
  </div>

  <div class="row-fluid"> <!-- START ROW-FLUID -->
    <div id="send_alert" class="alert hide">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <span></span>
    </div>
    <div class="hero-unit">

      <h3>Resumen de Pago</h3>
      <?php if ($status){ ?>
          <p>FELICIDADES! Has realizado el pago satisfactoriamente de tus Yuppics. A continuación se te proporciona
             el numero de folio de la order de compra, recuerda guardarla para futuros reclamos.
          <p>No. Folio de Order: <?php echo $order ?></p>
      <?}else{ ?>
          <p>Oops! Al parecer no se realizo el pago correctamente de la compra del o los Yuppics. La orden de compra fue cancelada
           por lo que requeriras crear una nueva orden de compra con los Yuppics previamente seleccionados.
      <?} ?>
          <p>Si tiene alguna duda o reclamo haznola saber <a href="#" data-toggle="modal" data-target="#modal_contact">aquí</a></p>
    </div>
  </div> <!-- END ROW-FLUID -->
</div> <!-- END SPAN8 -->
