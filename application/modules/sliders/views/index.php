<div class="page-header">
  <div class="row">
    <h1 class="col-md-7">Sliders</h1>
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
        <button onclick="javascript:location.href='/sliders/agregar/'" type="button" class="btn btn-primary col-md-12">Agregar</button>
      </div>
    </div>
  </div>
</div>
<?php if($sliders){ ?>
<div class="thumbnail table-responsive all-responsive" id="multiselectForm">
  <table border="0" cellspacing="0" cellpadding="0" class="table" style="margin-bottom:0;">
    <thead>
      <tr>
        <th scope="col">Título</th>
        <th scope="col">Orden</th>
		<th scope="col">Estado</th>
        <th scope="col" style="width:90px;">Modificar</th>
      </tr>
    </thead>
    <tbody class="table-hover">
      <?php foreach($sliders as $slider){ ?>
      <tr>
        <td><a href="/sliders/editar/<?php echo $slider->codigo; ?>"><?php echo $slider->titulo; ?></a></td>
        <td><?php echo $slider->orden;?></td>
		<td>
			<?php if($slider->estado){ ?>
				<button type="button" class="btn btn-primary btn-xs estado" rel="<?php echo $slider->codigo.'-0'; ?>">Publicado</button>
			<?php } else{ ?>
				<button type="button" class="btn btn-warning btn-xs estado" rel="<?php echo $slider->codigo.'-1'; ?>">Borrador</button>
			<?php } ?>
        </td>
        <td class="editar"><a title="Editar" href="/sliders/editar/<?php echo $slider->codigo; ?>" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> <a title="Eliminar" type="button" class="btn btn-danger btn-sm eliminar" rel="<?php echo $slider->codigo; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php } ?>
