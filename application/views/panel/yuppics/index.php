    <div id="content" class="span10">

      <div class="row-fluid">
        <div class="box span12">
          <div class="box-header" data-original-title>
            <h2><i class="icon-book"></i><span class="break"></span> Yuppics</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">

            <div class="row-fluid">
              <div class="span12">
                <form class="form-search" action="<?php echo base_url('panel/yuppics/') ?>" method="GET">
                  <label for="">Cliente</label>
                  <input type="text" name="fsearch" value="<?php echo set_value_get('fsearch')?>" id="fsearch" class="" placeholder="Cliente">

                  <label>Status</label>
                  <select name="fstatus">
                    <option value="" <?php echo set_select('fstatus', '', false, $this->input->get('fstatus')) ?>>TODOS</option>
                    <option value="1" <?php echo set_select('fstatus', '1', false, $this->input->get('fstatus')) ?>>COMPRADOS</option>
                    <option value="0" <?php echo set_select('fstatus', '0', false, $this->input->get('fstatus')) ?>>NO COMPRADOS</option>
                  </select>

                  <button type="submit" class="btn">Buscar</button>
                </form>
              </div>
            </div>

            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Cliente</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Telefono</th>
                  <th>Titulo Yuppic</th>
                  <th>Cantidad</th>
                  <th>Creado el</th>
                  <th>Status</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($yuppics['yuppics'] as $key => $yuppic){ ?>
                <tr>
                  <td class="center"><?php echo $yuppic->first_name.' '.$yuppic->last_name ?></td>
                  <td class="center"><?php echo $yuppic->username ?></td>
                  <td class="center"><?php echo $yuppic->email ?></td>
                  <td class="center"><?php echo $yuppic->phone ?></td>
                  <td class="center"><?php echo $yuppic->title ?></td>
                  <td class="center"><?php echo $yuppic->quantity ?></td>
                  <td class="center"><?php echo $yuppic->created ?></td>
                  <td class="center">
                    <?php
                      $lbl = '-success';
                      $lblText = 'Comprado';
                      if ($yuppic->comprado == 0)
                      {
                        $lbl = '-important';
                        $lblText = 'No comprado';
                      }
                      $label = ''
                     ?>
                    <span class="label label<?php echo $lbl ?>"><?php echo $lblText ?></span>
                  </td>
                  <td class="center">

                    <?php if ($yuppic->comprado == 1) { ?>
                      <a href="<?php echo base_url('panel/yuppics/genera_pdf/?yuppic='.$yuppic->id_yuppic) ?>" class="btn btn-info" target="_BLANK">
                        <i class="icon-print "></i>
                      </a>
                    <?php } ?>

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
                'total_rows'    => $yuppics['total_rows'],
                'per_page'      => $yuppics['items_per_page'],
                'cur_page'      => $yuppics['result_page']*$yuppics['items_per_page'],
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