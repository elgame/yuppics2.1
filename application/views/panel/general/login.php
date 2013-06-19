      <div class="row-fluid">
        <div class="login-box">
          <h2>Accede a tu cuenta</h2>
          <form class="form-horizontal" action="<?php echo base_url('panel/home/login') ?>" method="post">
            <fieldset>

              <input class="input-large span12" name="usuario" id="username" type="text" placeholder="email"/>

              <input class="input-large span12" name="pass" id="password" type="password" placeholder="password"/>

              <div class="clearfix"></div>

              <!-- <label class="remember" for="remember"><input type="checkbox" id="remember" />Recordarme</label> -->

              <div class="clearfix"></div>

              <button type="submit" class="btn btn-primary span12">Login</button>

          </form>
          <!-- <hr>
          <h3>Olvidaste tu password?</h3>
          <p>
            No hay problema, <a href="#">click aquí</a> para obtener un nuevo password.
          </p> -->
        </div><!--/span-->
      </div><!--/row-->

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