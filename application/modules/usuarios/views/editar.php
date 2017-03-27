<div class="page-header">
  <h1>Editar Usuario</h1>
</div>
<form id="form-editar" name="form-editar" method="post" class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label for="nombre" class="col-sm-2 control-label">Nombres</label>
      <div class="col-sm-10">
        <input type="text" id="nombres" name="nombres" class="form-control validate[required]" value="<?php echo $usuario->nombres; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label for="apellidos" class="col-sm-2 control-label">Apellidos</label>
      <div class="col-sm-10">
        <input type="text" id="apellidos" name="apellidos" class="form-control validate[required]"  value="<?php echo $usuario->apellidos; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
      <div class="col-sm-10">
        <input type="text" id="telefono" name="telefono" class="form-control validate[required]"  value="<?php echo $usuario->telefono; ?>" />
        <input type="hidden" id="codigo" name="codigo" class="form-control validate[required]"  value="<?php echo $usuario->codigo; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label for="direccion" class="col-sm-2 control-label">Dirección</label>
      <div class="col-sm-10">
        <input type="text" id="direccion" name="direccion" class="form-control validate[required]"  value="<?php echo $usuario->direccion; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label for="email" class="col-sm-2 control-label">Email</label>
      <div class="col-sm-10">
        <input type="text" id="email"  name="email" class="form-control validate[required, custom[email]]"  value="<?php echo $usuario->email; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label for="contrasena" class="col-sm-2 control-label">Contraseña nueva</label>
      <div class="col-sm-10">
        <input type="password" id="contrasena" name="contrasena" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label for="comuna" class="col-sm-2 control-label">Comuna</label>
      <div class="col-sm-4">
        <select id="comuna" name="comuna" class="selectpicker validate[required]">
			<option disabled>Seleccione</option>
			<?php foreach($comunas as $comuna){ ?>
			<option value="<?php echo $comuna->codigo; ?>" <?php if($comuna->codigo == $usuario->comuna) echo "selected"; ?>><?php echo $comuna->nombre; ?></option>
			<?php } ?>
        </select>
      </div>
    </div>
    <div class="text-box">
      <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
    </div>
  </fieldset>
</form>
