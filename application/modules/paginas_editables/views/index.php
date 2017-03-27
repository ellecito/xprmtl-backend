<div class="page-header">
  <div class="row">
    <h1 class="col-md-7">Páginas Editables</h1>
  </div>
</div>
<?php if($paginas){ ?>
<div class="thumbnail table-responsive all-responsive" id="multiselectForm">
  <table border="0" cellspacing="0" cellpadding="0" class="table" style="margin-bottom:0;">
    <thead>
      <tr>
        <th scope="col">Título</th>
        <th scope="col" style="width:90px;">Modificar</th>
      </tr>
    </thead>
    <tbody class="table-hover">
      <?php foreach($paginas as $pagina){ ?>
      <tr>
        <td><a href="/paginas-editables/editar/<?php echo $pagina->codigo; ?>"><?php echo $pagina->titulo; ?></a></td>
        <td class="editar"><a title="Editar" href="/paginas-editables/editar/<?php echo $pagina->codigo; ?>" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php } ?>
