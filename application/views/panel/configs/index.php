  <div id="content" class="span10">
    <div class="row-fluid">
        <div class="box span12">
          <div class="box-header">
            <h2><i class="icon-edit"></i>Configuraciones</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">
            <form action="<?php echo base_url('panel/configs/') ?>" method="POST" class="form-horizontal">
              <fieldset>

                <div class="control-group">
                  <label class="control-label" for="pFotosMax">Fotos Máximas</label>
                  <div class="controls">
                    <input type="text" name="pFotosMax" value="<?php echo $configs[0]->max_fotos ?>" class="input-xlarge" id="pFotosMax">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pporcentaje">Porcentaje Cupones</label>
                  <div class="controls">
                    <input type="hidden" name="pporcentaje" value="<?php echo $configs[0]->percentage ?>" class="input-xlarge focused" id="pporcentaje">
                    <div class="slider sliderGreen input-xlarge" id="sliderPorcentage"></div>
                    <div class="field_notice"><span class="must" id="sliderPorcentageLabel"><?php echo $configs[0]->percentage ?></span>%</div>
                  </div>
                </div>

                <div class="form-actions">
                  <button type="submit" class="btn btn-primary">Guardar</button>
                  <a href="<?php echo base_url('panel/home') ?>" class="btn">Cancel</a>
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