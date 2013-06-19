    <div id="content" class="span10">

      <div class="row-fluid">
        <div class="box span12">
          <div class="box-header" data-original-title>
            <h2><i class="icon-barcode"></i><span class="break"></span> Cupones</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Creado</th>
                  <th>Nombre</th>
                  <th>Código</th>
                  <th>Cantidad</th>
                  <th>Porcentaje</th>
                  <th>F. Inicio</th>
                  <th>F. Fin</th>
                  <th>Status</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($cupones['cupones'] as $key => $cupon){ ?>
                <tr>
                  <td class="center"><?php echo $cupon->created ?></td>
                  <td class="center"><?php echo $cupon->name ?></td>
                  <td class="center"><?php echo $cupon->code ?></td>
                  <td class="center"><?php echo $cupon->amount ?></td>
                  <td class="center"><?php echo $cupon->percentage ?></td>
                  <td class="center"><?php echo $cupon->date_start ?></td>
                  <td class="center"><?php echo $cupon->date_end ?></td>
                  <td class="center">
                    <?php
                      $lbl = '-success';
                      $lblText = 'Utilizable';
                      if ($cupon->status != 0)
                      {
                        $lbl = '-important';
                        $lblText = 'Canjeado';
                      }
                      $label = ''
                     ?>
                    <span class="label label<?php echo $lbl ?>"><?php echo $lblText ?></span>
                  </td>
                  <td class="center">
                    <a href="<?php echo base_url('panel/cupones/editar/?id='.$cupon->id_coupon) ?>" class="btn btn-info" >
                      <i class="icon-edit "></i>
                    </a>
                    <a href="<?php echo base_url('panel/cupones/eliminar/?id='.$cupon->id_coupon) ?>" class="btn btn-danger"
                      onclick="msb.confirm('Estas seguro eliminar el cupón?', 'Cupones', this); return false;">
                      <i class="icon-trash "></i>
                    </a>


                    <!-- 'onclick' => "msb.confirm('Estas seguro de activar la bascula?', 'bascula', this); return false;") -->
                  </td>
                </tr>
              <?php } ?>

            </tbody>
          </table>

          <?php
            //Paginacion
            $this->pagination->initialize(array(
                'base_url'      => base_url($this->uri->uri_string()).'?'.String::getVarsLink(array('pag')).'&',
                'total_rows'    => $cupones['total_rows'],
                'per_page'      => $cupones['items_per_page'],
                'cur_page'      => $cupones['result_page']*$cupones['items_per_page'],
                'page_query_string' => TRUE,
                'num_links'     => 1,
                'anchor_class'  => 'pags corner-all',
                'num_tag_open'  => '<li>',
                'num_tag_close' => '</li>',
                'cur_tag_open'  => '<li class="active"><a href="#">',
                'cur_tag_close' => '</a></li>',
                'first_link'     => false,
                'last_link'      => false,
                'next_link'      => 'Siguiente',
                'next_tag_open'  => '<li>',
                'next_tag_close' => '</li>',
                'prev_link'      => 'Atrás',
                'prev_tag_open'  => '<li>',
                'prev_tag_close' => '</li>',
            ));
            $pagination = $this->pagination->create_links();
            echo '<div class="pagination pagination-centered"><ul>'.$pagination.'</ul></div>';
            ?>
        </div>
      </div><!--/span-->

    </div><!--/row-->
  </div><!--/content-->


<!-- Bloque de alertas -->
<?php if(isset($frm_errors)){
  if($frm_errors['msg'] != ''){
?>
<script type="text/javascript" charset="UTF-8">
  $(document).ready(function(){
    $.gritter.add({
      title: "<?php echo $frm_errors['title']; ?>",
      text: "<?php echo $frm_errors['msg']; ?>",
      // image: 'img/avatar.jpg',
    });
  });
</script>
<?php }
}?>
<!-- Bloque de alertas -->