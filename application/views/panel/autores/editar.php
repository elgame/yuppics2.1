  <div id="content" class="span10">
    <div class="row-fluid">
        <div class="box span12">
          <div class="box-header">
            <h2><i class="icon-group"></i>Autores</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">
            <form action="<?php echo base_url('panel/autores/editar/?id='.$_GET['id']) ?>" method="POST" class="form-horizontal">
              <fieldset>

                <div class="control-group">
                  <label class="control-label" for="pname">Nombre del Autor</label>
                  <div class="controls">
                  <input type="text" name="pname" class="input-xlarge" id="pname" value="<?php echo set_value('pname', $autor['info'][0]->name) ?>" maxlength="30">
                  </div>
                </div>

                <div class="form-actions">
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <a href="<?php echo base_url('panel/autores/') ?>" class="btn">Cancel</a>
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