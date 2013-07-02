    <div id="content" class="span10">

      <div class="row-fluid">
        <div class="box span12">
          <div class="box-header" data-original-title>
            <h2><i class="icon-picture"></i><span class="break"></span> Temas</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">

            <div class="row-fluid">
              <div class="span12">
                <form class="form-search" action="<?php echo base_url('panel/temas/') ?>" method="GET">
                  <label for="">Autor|Nombre</label>
                  <input type="text" name="fsearch" value="<?php echo set_value_get('fsearch')?>" id="fsearch" class="" placeholder="Autor, Nombre">

                  <label>Status</label>
                    <select name="fstatus">
                      <option value="" <?php echo set_select('fstatus', '', false, $this->input->get('fstatus')) ?>>TODOS</option>
                      <option value="1" <?php echo set_select('fstatus', '1', false, $this->input->get('fstatus')) ?>>ACTIVADOS</option>
                      <option value="0" <?php echo set_select('fstatus', '0', false, $this->input->get('fstatus')) ?>>DESACTIVADOS</option>
                    </select>

                  <button type="submit" class="btn">Buscar</button>
                </form>
              </div>
            </div>

            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Autor</th>
                  <th>Nombre</th>
                  <th>Imagen</th>
                  <th>Franja Cover</th>
                  <th>Status</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($temas['temas'] as $key => $tema){ ?>
                <tr>
                  <td class="center"><?php echo $tema->autor ?></td>
                  <td class="center"><?php echo $tema->name ?></td>
                  <td style="text-align: center;">
                    <img src="<?php echo base_url($tema->imagen) ?>" class="img-polaroid" style="width: 260px; height: 180px;">
                  </td>
                  <td style="text-align: center;">
                    <?php if ($tema->background_franja !== null) { ?>
                      <img src="<?php echo base_url($tema->background_franja) ?>" class="img-polaroid" style="width: 260px; height: 180px;">
                    <?php } else { ?>
                      <span class="label label-warning">Sin Franja</span>
                    <?php } ?>
                  </td>
                  <td class="center">
                    <?php
                      $lbl = '-success';
                      $lblText = 'Activo';
                      if ($tema->status == 0)
                      {
                        $lbl = '-important';
                        $lblText = 'Inactivo';
                      }
                      $label = ''
                     ?>
                    <span class="label label<?php echo $lbl ?>"><?php echo $lblText ?></span>
                  </td>
                  <td class="center">
                    <a href="<?php echo base_url('panel/temas/editar/?id='.$tema->id_theme) ?>" class="btn btn-info" >
                      <i class="icon-edit "></i>
                    </a>

                    <?php
                      if ($tema->status == 0) {
                     ?>
                        <a href="<?php echo base_url('panel/temas/activar/?id='.$tema->id_theme) ?>" class="btn btn-success"
                          onclick="msb.confirm('Estas seguro activar el tema?', 'Temas', this); return false;">
                          <i class="icon-ok "></i>
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo base_url('panel/temas/desactivar/?id='.$tema->id_theme) ?>" class="btn btn-danger"
                          onclick="msb.confirm('Estas seguro desactivar el tema?', 'Temas', this); return false;">
                          <i class="icon-ban-circle "></i>
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
                'total_rows'    => $temas['total_rows'],
                'per_page'      => $temas['items_per_page'],
                'cur_page'      => $temas['result_page']*$temas['items_per_page'],
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