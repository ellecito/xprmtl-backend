<div class="page-header">
    <div class="row">
        <h1 class="col-md-8">Pedidos</h1>
        <div class="col-md-3" style="margin-top:24px;">
            <form class="form-inline" method="get" action="/pedidos/">
                <div class="input-group">
                    <input type="text" value="<?php echo $q_f; ?>" name="q"  class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="icon-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
		<div class="col-md-1" style="margin-top:26px;">
			<a href="/pedidos/exportar<?php echo $url;?>"><button title="Editar" type="button" class="btn btn-success btn-sm">Exportar</button></a>
		</div>
    </div>
</div>
<div class="thumbnail table-responsive all-responsive" id="multiselectForm">
    <table border="0" cellspacing="0" cellpadding="0" class="table" style="margin-bottom:0;">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Fecha/Hora</th>
                <th scope="col">Monto</th>
                <th scope="col">Usuario</th>
                <th scope="col">Estado</th>
                <th scope="col" style="width:50px;">Tracking</th>
                <th scope="col" style="width:50px;">Ver</th>
            </tr>
        </thead>
        <tbody class="table-hover">
            <?php if($pedidos){ ?>
            <?php foreach($pedidos as $pedido): ?>
            <tr>
                <td><?php echo $pedido->codigo; ?></td>
                <td><?php echo formatearFecha(substr($pedido->fecha,0,10));?> - <?php echo substr($pedido->hora,0,5); ?></td>
                <td>$<?php echo number_format($pedido->monto,0,",",".");?></td>
                <td><?php echo $pedido->usuario->nombres . " " . $pedido->usuario->apellidos; ?></td>
                <td><?php echo $pedido->estado->nombre;?></td>
				<td>
				<?php if($pedido->estado->codigo == 2 or $pedido->estado->codigo == 4){ ?>
				<button id="aprobar-pedido" type="button" value="<?php echo $pedido->codigo; ?>" class="btn btn-link aprobar" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-road" aria-hidden="true"></span></button>
				<?php } ?>
				</td>
				<td>
					<a href="/pedidos/ver/<?php echo $pedido->codigo; ?>/">
						<button title="Editar" type="button" class="btn btn-success btn-sm"><i class="icon-search"></i></button>
					</a>
				</td>
            </tr>
        <?php endforeach; ?>
		<?php } else{ ?>
			<tr>
				<td colspan="4" style="text-align:center;"><i>No hay registros</i></td>
			</tr>
		<?php } ?>
        </tbody>
    </table>
</div>

<!-- [PAGINACION] -->
<?php echo $pagination; ?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title" id="myModalLabel">Aprobar pedido</h3>
      </div>
      <div class="modal-body">
        <form role="form" id="form-aceptar">
          <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th scope="col">Nº Pedido</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Número de tracking</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="orden_e">XXX</td>
            <td>Chilexpress</td>
            <td>
			<input type="text" id="tracking" name="tracking" class="form-control validate[required, custom[integer], min[1]]" /><input type="hidden" id="codigo_e" name="codigo" class="form-control validate[required, custom[integer]]" /></td>
          </tr>
        </tbody>
      </table>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Enviar</button>
		</form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>