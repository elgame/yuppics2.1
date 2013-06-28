    <div id="content" class="span10">

      <div class="row-fluid">
        <div class="box span12">
          <div class="box-header" data-original-title>
            <h2><i class="icon-group"></i><span class="break"></span> Autores</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">

            <div class="row-fluid">
              <div class="span12">
                <form class="form-search" action="<?php echo base_url('panel/autores/') ?>" method="GET">
                  <label for="">Autor|Nombre</label>
                  <input type="text" name="fsearch" value="<?php echo set_value_get('fsearch')?>" id="fsearch" class="" placeholder="Autor, Nombre">
                  <button type="submit" class="btn">Buscar</button>
                </form>
              </div>
            </div>

            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Autor</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($autores['autores'] as $key => $autor){ ?>
                <tr>
                  <td class="center"><?php echo $autor->name ?></td>
                  <td class="center">
                    <a href="<?php echo base_url('panel/autores/editar/?id='.$autor->id_autor) ?>" class="btn btn-info" >
                      <i class="icon-edit "></i>
                    </a>

                    <a href="<?php echo base_url('panel/autores/eliminar/?id='.$autor->id_autor) ?>" class="btn btn-danger"
                      onclick="msb.confirm('Estas seguro eliminar al autor?', 'Autores', this); return false;">
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
                'total_rows'    => $autores['total_rows'],
                'per_page'      => $autores['items_per_page'],
                'cur_page'      => $autores['result_page']*$autores['items_per_page'],
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