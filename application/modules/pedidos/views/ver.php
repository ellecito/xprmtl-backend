<div class="page-header">
	<h1>Pedido N° <?php echo $pedido->codigo; ?> <a href="/pedidos/pdf/<?php echo $pedido->codigo; ?>/"><button title="Editar" type="button" class="btn btn-success btn-sm">PDF</button></a></h1>
	
</div>
<form class="form-horizontal">
  <div class="form-group">
    <label for="monto" class="col-sm-2 control-label">Monto</label>
    <div class="col-sm-10">
      <input type="text" id="monto" readonly class="form-control" value="$<?php echo number_format($pedido->monto,0,",","."); ?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="estado" class="col-sm-2 control-label">Estado</label>
    <div class="col-sm-4">
      <input type="text" id="estado" readonly class="form-control" value="<?php echo $pedido->estado->nombre; ?>"  />
    </div>
    <label for="fecha" class="col-sm-2 control-label">Fecha</label>
    <div class="col-sm-4">
      <div class="input-group date">
        <input id="datepicker" type="text" readonly class="form-control" value="<?php echo formatearFecha(substr($pedido->fecha,0,10)); ?> - <?php echo substr($pedido->hora,0,5); ?>"  />
        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
    </div>
  </div>
  <?php if($pedido->estado->codigo == 4){ ?>
  <div class="form-group">
    <label for="tracking" class="col-sm-2 control-label">Tracking</label>
    <div class="col-sm-4">
      <input type="text" id="tracking" readonly class="form-control" value="<?php echo $pedido->tracking; ?>"  />
    </div>
  </div>
  <?php } ?>
  <div class="form-group">
    <label for="detalle" class="col-sm-2 control-label">Detalle</label>
    <div class="col-sm-10">
      <?php echo $pedido->detalle; ?>
    </div>
  </div>
</form>
<div class="page-header">
  <h1>Usuario</h1>
</div>
<form class="form-horizontal">
  <div class="form-group">
    <label for="nombre" class="col-sm-2 control-label">Nombre Completo</label>
    <div class="col-sm-10">
      <input type="text" id="nombre" readonly class="form-control" value="<?php echo $pedido->usuario->nombres . " " . $pedido->usuario->apellidos; ?>"/>
    </div>
  </div>
  <div class="form-group">
    <label for="telefono" class="col-sm-2 control-label">Telefono</label>
    <div class="col-sm-4">
      <input type="text" id="telefono" readonly class="form-control" value="<?php echo $pedido->usuario->telefono; ?>"  />
    </div>
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
      <input type="text" id="email" readonly class="form-control" value="<?php echo $pedido->usuario->email; ?>"  />
    </div>
  </div>
  <div class="form-group">
    <label for="direccion" class="col-sm-2 control-label">Dirección</label>
    <div class="col-sm-10">
      <input type="text" id="direccion" readonly class="form-control" value="<?php echo $pedido->usuario->direccion . ", " . $pedido->usuario->comuna->nombre . ", " . $pedido->usuario->comuna->region->nombre . ", " . $pedido->usuario->comuna->region->pais->nombre; ?>"/>
    </div>
  </div>
</form>
<div class="page-header">
  <h1>Bitacora</h1>
</div>
<form class="form-horizontal">
  <div class="form-group">
    <label for="respuesta" class="col-sm-2 control-label">Respuesta</label>
    <div class="col-sm-4">
      <input type="text" id="respuesta" readonly class="form-control" value="<?php if($pedido->bitacora->respuesta == 0)echo "Aprobada"; else echo "Rechazada"; ?>"  />
    </div>
    <label for="final_numero_tarjeta" class="col-sm-2 control-label">Numero Tarjeta</label>
    <div class="col-sm-4">
      <input type="text" id="final_numero_tarjeta" readonly class="form-control" value="************<?php echo $pedido->bitacora->final_numero_tarjeta; ?>"  />
    </div>
  </div>
  <div class="form-group">
    <label for="tipo_pago" class="col-sm-2 control-label">Tipo Pago</label>
    <div class="col-sm-4">
      <input type="text" id="tipo_pago" readonly class="form-control" value="<?php 
		if($pedido->bitacora->tipo_pago == 'VD') echo "Red Compra";
		else echo "Crédito";
		echo " - ";
		switch($pedido->bitacora->tipo_pago){
        	case 'VN':
        		echo "Sin cuotas";
        		break;
        	case 'VC':
        		echo "Cuotas normales";
				break;
        	case 'SI':
        		echo "Sin interés";
				break;
        	case 'CI':
        		echo "Cuotas Comercio";
				break;
        	case 'VD':
        		echo "Débito";
				break;
        }
	  ?>"  />
    </div>
    <label for="numero_cuotas" class="col-sm-2 control-label">Numero Cuotas</label>
    <div class="col-sm-4">
      <input type="text" id="numero_cuotas" readonly class="form-control" value="<?php echo $pedido->bitacora->numero_cuotas; ?>"  />
    </div>
  </div>
  <div class="form-group">
    <label for="ip" class="col-sm-2 control-label">IP</label>
    <div class="col-sm-4">
      <input type="text" id="ip" readonly class="form-control" value="<?php echo $pedido->bitacora->ip; ?>"  />
    </div>
    <label for="fecha_transaccion" class="col-sm-2 control-label">Fecha Transaccion</label>
    <div class="col-sm-4">
      <input type="text" id="fecha_transaccion" readonly class="form-control" value="<?php echo $pedido->bitacora->fecha_transaccion . " " . $pedido->bitacora->hora_transaccion; ?>"  />
    </div>
  </div>
</form>