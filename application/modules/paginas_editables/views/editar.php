<h1><?php echo $pagina->titulo; ?></h1>
<ul class="nav nav-tabs">
  <li class="active"><a href="#cuerpo" data-toggle="tab">Cuerpo</a></li>
</ul>
<form class="form-horizontal" id="form-editar">
  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="cuerpo">
      <textarea rows="6" class="form-control validate[required]" id="contenido" name="contenido"><?php echo $pagina->contenido; ?></textarea>
	  <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
			<input type="hidden" name="codigo" value="<?php echo $pagina->codigo; ?>">
		  <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
CKEDITOR.replace('contenido');
</script>