  <div id="content" class="span10">
    <div class="row-fluid">
        <div class="box span12">
          <div class="box-header">
            <h2><i class="icon-picture"></i>Temas</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">
            <form action="<?php echo base_url('panel/temas/editar/?id='.$tema['info'][0]->id_theme) ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
              <fieldset>

                <div class="control-group">
                  <label class="control-label" for="pautor">Autor</label>
                  <div class="controls">
                    <select name="pautor" class="input-xlarge">
                      <option value=""></option>
                    <?php foreach ($autores as $a) { ?>
                      <option value="<?php echo $a->id_autor ?>" <?php echo set_select('pautor', $a->id_autor, false, isset($_POST['pautor']) ? $_POST['pautor'] : $tema['info'][0]->id_autor) ?>><?php echo $a->name?></option>
                    <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pname">Nombre del Tema</label>
                  <div class="controls">
                  <input type="text" name="pname" class="input-xlarge" id="pname" value="<?php echo set_value('pname', $tema['info'][0]->name) ?>" maxlength="30">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pimg">Imagen</label>
                  <div class="controls">
                    <input type="file" name="pimg" value="" class="input-xlarge">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pbgcolor">Background Color</label>
                  <div class="controls">
                    <input type="text" name="pbgcolor" value="<?php echo set_value('pbgcolor', $tema['info'][0]->background_color) ?>" class="input-xlarge" id="pbgcolor" maxlength="10">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="ptxtcolor">Text Color</label>
                  <div class="controls">
                    <input type="text" name="ptxtcolor" value="<?php echo set_value('ptxtcolor', $tema['info'][0]->text_color) ?>" class="input-xlarge" id="ptxtcolor" maxlength="10">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pimg_franja">Imagen Franja</label>
                  <div class="controls">
                    <input type="file" name="pimg_franja" value="" class="input-xlarge">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pbgcolor_franja">Background Color Franja</label>
                  <div class="controls">
                    <input type="text" name="pbgcolor_franja" value="<?php echo set_value('pbgcolor_franja', $tema['info'][0]->background_franja_color) ?>" class="input-xlarge" id="pbgcolor_franja" maxlength="10">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pfuente_cover">Tipo de fuente para el cover</label>
                  <div class="controls">
                    <select name="pfuente_cover" class="input-xlarge" id="pfuente_cover">
                      <option value="Opens sans light" <?php echo set_select('pfuente_cover', 'Opens sans light', false, $tema['info'][0]->font_cover) ?>>Opens sans light</option>
                      <option value="Times New Roman" <?php echo set_select('pfuente_cover', 'Times New Roman', false, $tema['info'][0]->font_cover) ?>>Times New Roman</option>
                      <option value="Rockwell regular" <?php echo set_select('pfuente_cover', 'Rockwell regular', false, $tema['info'][0]->font_cover) ?>>Rockwell regular</option>
                    </select>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="ptags">Tags</label>
                  <div class="controls">
                    <select name="ptags[]" class="input-xlarge" id="ptags" multiple data-rel="chosen">
                      <?php foreach ($tags as $tag) { ?>

                        <?php foreach($tema['tags'] as $ttag) {
                                $selected = 0;
                                if ($tag->id_tag == $ttag)
                                {
                                  $selected = $ttag;
                                  break;
                                }
                              }
                        ?>
                              <option value="<?php echo $tag->id_tag ?>" <?php echo set_select('ptags[]', $tag->id_tag, false, $selected) ?>>
                                <?php echo $tag->name ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pmastags">Agregar más tags</label>
                  <div class="controls">
                    <div class="input-append">
                      <input type="text" name="pmastags" value="<?php echo set_value('pmastags') ?>" class="input-xlarge" id="pmastags">
                    </div>
                    <span class="help-inline">Separados por coma Ejemplo: Perro, Gato, Lapiz</span>
                  </div>
                </div>

                <div class="form-actions">
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <a href="<?php echo base_url('panel/cupones/') ?>" class="btn">Cancel</a>
                </div>

              </fieldset>
              </form>

          </div>
        </div><!--/span-->

      </div><!--/row-->
  </div>

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