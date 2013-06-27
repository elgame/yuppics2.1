  <div id="content" class="span10">
    <div class="row-fluid">
        <div class="box span12">
          <div class="box-header">
            <h2><i class="icon-barcode"></i>Cupones</h2>
            <div class="box-icon">
              <!-- <a href="#" class="btn-setting"><i class="icon-wrench"></i></a> -->
              <a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
              <!-- <a href="#" class="btn-close"><i class="icon-remove"></i></a> -->
            </div>
          </div>
          <div class="box-content">
            <form action="<?php echo base_url('panel/cupones/agregar') ?>" method="POST" class="form-horizontal">
              <fieldset>

                <div class="control-group">
                  <label class="control-label" for="pdatestart">Fecha de inicio</label>
                  <div class="controls">
                  <input type="text" name="pdatestart" class="input-xlarge" id="pdatestart" value="<?php echo set_value('pdatestart') ?>" maxlength="10">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pdateend">Fecha de termino</label>
                  <div class="controls">
                  <input type="text" name="pdateend" class="input-xlarge" id="pdateend" value="<?php echo set_value('pdateend') ?>" maxlength="10">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pnombre">Nombre</label>
                  <div class="controls">
                    <input type="text" name="pnombre" value="<?php echo set_value('pnombre') ?>" class="input-xlarge" id="pnombre" maxlength="30">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pcodigo">Código</label>
                  <div class="controls">
                    <input type="text" name="pcodigo" value="<?php echo set_value('pcodigo') ?>" class="input-xlarge" id="pcodigo" maxlength="35">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pcantidad">Cantidad ($) a descontar del costo real del Yuppic</label>
                  <div class="controls">
                    <input type="text" name="pcantidad" value="<?php echo set_value('pcantidad') ?>" class="input-xlarge" id="pcantidad">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="ptotalusos">Total de usos</label>
                  <div class="controls">
                    <input type="text" name="ptotalusos" value="<?php echo set_value('ptotalusos') ?>" class="input-xlarge" id="ptotalusos">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="pporcentaje">Porcentaje aplicado</label>
                  <div class="controls">
                    <input type="hidden" name="pporcentaje" value="<?php echo set_value('pporcentaje', 0) ?>" class="input-medium focused" id="pporcentaje">
                    <div class="slider sliderGreen input-xlarge" id="sliderPorcentage"></div>
                    <div class="field_notice"><span class="must" id="sliderPorcentageLabel"><?php echo set_value('pporcentaje', 0) ?></span>%</div>
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