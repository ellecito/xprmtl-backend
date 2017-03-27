<h1>Noticia</h1>
<ul class="nav nav-tabs">
  <li class="active"><a href="#datos" data-toggle="tab">Datos</a></li>
  <li><a href="#cuerpo" data-toggle="tab">Cuerpo</a></li>
  <li><a href="#imagenes" data-toggle="tab">Imágenes</a></li>
</ul>
<form class="form-horizontal" id="form-agregar" enctype="multipart/form-data">
  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="datos">
      <div class="form-group">
        <label for="titulo" class="col-sm-2 control-label">Título</label>
        <div class="col-sm-10">
          <input type="text" id="titulo" name="titulo" class="form-control validate[required]" />
        </div>
      </div>
	  <div class="form-group">
        <label for="fecha" class="col-sm-2 control-label">Fecha</label>
        <div class="col-sm-10">
          <input type="text" id="fecha" name="fecha" class="form-control validate[required]" value="<?php echo date("d/m/Y"); ?>"/>
        </div>
      </div>
      <div class="form-group">
        <label for="resumen" class="col-sm-2 control-label">Resumen</label>
        <div class="col-sm-10">
          <textarea rows="4" class="form-control validate[required]" id="resumen" name="resumen"></textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Guardar</button>
		  <a href="#" class="tab btn btn-primary" rel="cuerpo">Continuar con cuerpo</a>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="cuerpo">
      <textarea rows="6" class="form-control validate[required]" id="contenido" name="contenido"></textarea>
	  <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
		  <a href="#" class="tab btn btn-primary" rel="imagenes">Continuar con imágenes</a>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="imagenes">
      <center><div class="form-group" style="width:<?php echo $this->medidas->ancho_min; ?>px; height:<?php echo $this->medidas->alto_min; ?>px;">
			<label>Adjuntar imagen tamaño mínimo <?php echo $this->medidas->ancho_min.'px x '.$this->medidas->alto_min.'px'; ?></label>
            <div class="multi-imagen" style="margin-bottom:20px;">
                <div style="display:none;" id="replicar" class="box">
                    <div class="img" style="width:<?php echo $this->medidas->ancho_min; ?>px; height:<?php echo $this->medidas->alto_min; ?>px;" ></div>
                </div>
                <div id="cont-imagenes"></div>
                <div id="rutas-imagenes"></div>
            </div>
		</div></center>

    </div>
  </div>
</form>
<script type="text/javascript">
CKEDITOR.replace('contenido');
</script>