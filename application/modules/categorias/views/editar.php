<h1>Categoría</h1>
<ul class="nav nav-tabs">
  <li class="active"><a href="#datos" data-toggle="tab">Datos</a></li>
</ul>
<form class="form-horizontal" id="form-editar" enctype="multipart/form-data">
  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="datos">
      <div class="form-group">
        <label for="nombre" class="col-sm-2 control-label">Nombre</label>
        <div class="col-sm-10">
          <input type="text" id="nombre" name="nombre" class="form-control validate[required]" value="<?php echo $categoria->nombre; ?>" />
        </div>
      </div>
	  <div class="form-group">
        <label for="orden" class="col-sm-2 control-label">Orden</label>
        <div class="col-sm-10">
          <input type="text" id="orden" name="orden" class="form-control validate[required, custom[integer]]" value="<?php echo $categoria->orden; ?>" />
          <input type="hidden" id="codigo" name="codigo" class="validate[required, custom[integer]]" value="<?php echo $categoria->codigo; ?>" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</form>