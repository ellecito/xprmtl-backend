<div class="page-header">
  <div class="row">
    <h1 class="col-md-7">Categorías</h1>
    <div class="col-md-3" style="margin-top:24px;">
      <form method="get" class="input-group">
        <input type="text" class="form-control" name="buscar" value="<?php echo $buscar_get; ?>">
        <span class="input-group-btn">
        <button type="button" class="btn btn-default"><i class="icon-search"></i></button>
        </span>
      </form>
    </div>
    <div class="col-md-2" style=" margin:24px 0 10px;">
      <div class="text-center new">
        <button onclick="javascript:location.href='/categorias/agregar/'" type="button" class="btn btn-primary col-md-12">Agregar</button>
      </div>
    </div>
  </div>
</div>
<?php if($categorias){ ?>
<div class="thumbnail table-responsive all-responsive" id="multiselectForm">
  <table border="0" cellspacing="0" cellpadding="0" class="table" style="margin-bottom:0;">
    <thead>
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Orden</th>
		<th scope="col">Estado</th>
        <th scope="col" style="width:90px;">Modificar</th>
      </tr>
    </thead>
    <tbody class="table-hover">
      <?php foreach($categorias as $categoria){ ?>
      <tr>
        <td><a href="/categorias/editar/<?php echo $categoria->codigo; ?>"><?php echo $categoria->nombre; ?></a></td>
        <td><?php echo $categoria->orden; ?></td>
		<td>
			<?php if($categoria->estado){ ?>
				<button type="button" class="btn btn-primary btn-xs estado" rel="<?php echo $categoria->codigo.'-0'; ?>">Publicado</button>
			<?php } else{ ?>
				<button type="button" class="btn btn-warning btn-xs estado" rel="<?php echo $categoria->codigo.'-1'; ?>">Borrador</button>
			<?php } ?>
        </td>
        <td class="editar"><a title="Editar" href="/categorias/editar/<?php echo $categoria->codigo; ?>" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> <a title="Eliminar" type="button" class="btn btn-danger btn-sm eliminar" rel="<?php echo $categoria->codigo; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php } ?>
<?php echo $this->pagination->create_links(); ?>