<div class="page-header">
  <h1>Datos Enlace Solar</h1>
</div>
<form method="post" id="form-agregar" class="form-horizontal">
  <div class="form-group">
    <label for="nombre" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="text" id="nombre" name="nombre" class="form-control validate[required]" value="<?php echo ($empresa)?$empresa->nombre:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="slogan" class="col-sm-2 control-label">Slogan</label>
    <div class="col-sm-10">
      <input type="text" id="slogan" name="slogan" class="form-control" value="<?php echo ($empresa)?$empresa->slogan:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="direccion" class="col-sm-2 control-label">Dirección</label>
    <div class="col-sm-10">
      <input type="text" id="direccion" name="direccion" class="form-control validate[required]" value="<?php echo ($empresa)?$empresa->direccion:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
    <div class="col-sm-4">
      <input type="text" id="telefono" name="telefono" class="form-control validate[required, custom[phone]]" value="<?php echo ($empresa)?$empresa->telefono:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="telefono2" class="col-sm-2 control-label">Teléfono 2</label>
    <div class="col-sm-4">
      <input type="text" id="telefono2" name="telefono2" class="form-control validate[custom[phone]]" value="<?php echo ($empresa)?$empresa->telefono_2:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="text" id="email" name="email" class="form-control validate[required, custom[email]]" value="<?php echo ($empresa)?$empresa->email:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="email2" class="col-sm-2 control-label">Email CC</label>
    <div class="col-sm-10">
      <input type="text" id="email2" name="email2" class="form-control validate[custom[email]]" value="<?php echo ($empresa)?$empresa->email_cc:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="email3" class="col-sm-2 control-label">Email BCC</label>
    <div class="col-sm-10">
      <input type="text" id="email3" name="email3" class="form-control validate[custom[email]]" value="<?php echo ($empresa)?$empresa->email_bcc:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="keywords" class="col-sm-2 control-label">Keywords</label>
    <div class="col-sm-10">
      <input type="text" id="keywords" name="keywords" class="form-control" placeholder="keyword, keyword2" value="<?php echo ($empresa)?$empresa->keywords:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="descripcion" class="col-sm-2 control-label">Descripción</label>
    <div class="col-sm-10">
      <textarea type="text" id="descripcion" name="descripcion" class="form-control"><?php echo ($empresa)?$empresa->descripcion:'' ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="facebook" class="col-sm-2 control-label">Facebook</label>
    <div class="col-sm-10">
      <input type="text" id="facebook" name="facebook" class="form-control validate[custom[url]]" value="<?php echo ($empresa)?$empresa->facebook:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="twitter" class="col-sm-2 control-label">Twitter</label>
    <div class="col-sm-10">
      <input type="text" id="twitter" name="twitter" class="form-control validate[custom[url]]" value="<?php echo ($empresa)?$empresa->twitter:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="instagram" class="col-sm-2 control-label">Instagram</label>
    <div class="col-sm-10">
      <input type="text" id="instagram" name="instagram" class="form-control validate[custom[url]]" value="<?php echo ($empresa)?$empresa->instagram:'' ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="youtube" class="col-sm-2 control-label">Youtube</label>
    <div class="col-sm-10">
      <input type="text" id="youtube" name="youtube" class="form-control validate[custom[url]]" value="<?php echo ($empresa)?$empresa->youtube:'' ?>" />
    </div>
  </div>
  <!--<div class="form-group" id="mapa">
    <div class="col-sm-offset-2 col-sm-10" style="padding-bottom:15px;">
      <div id="showMapa"></div>
    </div>
  </div>
  <input type="hidden" name="ltd" id="ltd" value="<?php //echo ($empresa)?$empresa->mapa[0]:'' ?>" />
  <input type="hidden" name="lng" id="lng" value="<?php //echo ($empresa)?$empresa->mapa[1]:'' ?>" />-->
  <div class="text-box">
    <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
  </div>
</form>
<style media="screen">
    #showMapa {
        float: right;
        margin-right: 10px;
        width: 100%;
        height: 350px;
    }
</style>
