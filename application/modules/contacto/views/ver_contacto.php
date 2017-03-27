<div class="page-header">
  <h1>Respaldo Contacto</h1>
</div>
<form class="form-horizontal">
  <div class="form-group">
    <label for="nombre" class="col-sm-2 control-label">Nombre</label>
    <div class="col-sm-10">
      <input type="text" id="nombre" readonly class="form-control" value="<?php echo $contacto->nombres; ?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="apellidos" class="col-sm-2 control-label">Apellidos</label>
    <div class="col-sm-10">
      <input type="text" id="apellidos" readonly class="form-control" value="<?php echo $contacto->apellidos; ?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="text" id="email" readonly class="form-control" value="<?php echo $contacto->email; ?>"  />
    </div>
  </div>
  <div class="form-group">
    <label for="telefono" class="col-sm-2 control-label">Teléfono</label>
    <div class="col-sm-4">
      <input type="text" id="telefono" readonly class="form-control" value="<?php echo $contacto->telefono; ?>"  />
    </div>
    <label for="fecha" class="col-sm-2 control-label">Fecha</label>
    <div class="col-sm-4">
      <div class="input-group date">
        <input id="datepicker" type="text" readonly class="form-control" value="<?php echo formatearFecha(substr($contacto->fecha,0,10)); ?> - <?php echo substr($contacto->fecha,11,5); ?>"  />
        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
    </div>
  </div>
  <div class="form-group">
    <label for="estado" class="col-sm-2 control-label">Resumen</label>
    <div class="col-sm-10">
      <textarea readonly rows="4" class="form-control" id="estado"><?php echo $contacto->mensaje; ?></textarea>
    </div>
  </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#datepicker").datepicker();
});
</script>
