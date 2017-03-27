<div class="page-header">
  <div class="row">
    <h1 class="col-md-7">Usuarios</h1>
    <div class="col-md-3" style="margin-top:24px;">
        <form class="form-inline" method="get" action="/usuarios/">
      <div class="input-group">
        <input type="text"  value="<?php echo $q_f; ?>" name="q" class="form-control">
        <span class="input-group-btn">
        <button type="submit" class="btn btn-default"><i class="icon-search"></i></button>
        </span></div>
    </form>
    </div>
    <div class="col-md-2" style=" margin:24px 0 10px;">
      <div class="text-center new">
        <button onclick="javascript:location.href='/usuarios/agregar/'" type="button" class="btn btn-primary col-md-12">Agregar</button>
      </div>
    </div>
  </div>
</div>
<div class="thumbnail table-responsive all-responsive" id="multiselectForm">
  <table border="0" cellspacing="0" cellpadding="0" class="table" style="margin-bottom:0;">
    <thead>
      <tr>
        <th scope="col">Nombres/Apellidos</th>
        <th scope="col" style="width:400px;">Email</th>
        <th scope="col" style="width:100px;">Estado</th>
        <th scope="col" style="width:90px;">&nbsp;</th>
      </tr>
    </thead>
    <tbody class="table-hover">
		<?php if($datos){ ?>
			<?php foreach($datos as $usuario): ?>
				<tr>
					<td><?php echo $usuario->nombres . " " . $usuario->apellidos; ?></td>
					<td><?php echo $usuario->email;?></td>
					<td>
						<?php if($usuario->estado){ ?>
							<button type="button" class="btn btn-primary btn-xs estado" rel="<?php echo $usuario->codigo.'-0'; ?>" >Publicado</button>
						<?php } else{ ?>
							<button type="button" class="btn btn-warning btn-xs estado" rel="<?php echo $usuario->codigo.'-1'; ?>">Borrador</button>
						<?php } ?>
					</td>
					<td class="editar">
						<a href="/usuarios/editar/<?php echo $usuario->codigo; ?>">
							<button title="Editar" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
						</a>

						<a class="eliminar" rel="<?php echo $usuario->codigo; ?>" href="#">
							<button title="Eliminar" type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
						</a>
					</td>
				</tr>
			<?php endforeach;?>
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
