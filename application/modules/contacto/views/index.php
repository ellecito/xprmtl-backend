<div class="page-header">
    <div class="row">
        <h1 class="col-md-8">Respaldo Contactos</h1>
        <div class="col-md-3" style="margin-top:24px;">
            <form class="form-inline" method="get" action="/contacto/">
                <div class="input-group">
                    <input type="text" value="<?php echo $q_f; ?>" name="q"  class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="icon-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
		<div class="col-md-1" style="margin-top:26px;">
			<a href="/contacto/exportar<?php echo $url;?>"><button title="Editar" type="button" class="btn btn-success btn-sm">Exportar</button></a>
		</div>
    </div>
</div>
<div class="thumbnail table-responsive all-responsive" id="multiselectForm">
    <table border="0" cellspacing="0" cellpadding="0" class="table" style="margin-bottom:0;">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Fecha/hora</th>
                <th scope="col">Email</th>
                <th scope="col" style="width:50px;">Ver</th>
            </tr>
        </thead>
        <tbody class="table-hover">
            <?php if($contactos){ ?>
            <?php foreach($contactos as $contacto): ?>
            <tr>
                <td><?php echo $contacto->nombres; ?></td>
                <td><?php echo $contacto->apellidos;?></td>
                <td><?php echo formatearFecha(substr($contacto->fecha,0,10));?> - <?php echo substr($contacto->fecha,11,5); ?></td>
                <td><?php echo $contacto->email;?></td>
				<td>
					<a href="/contacto/ver/<?php echo $contacto->codigo; ?>/">
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
