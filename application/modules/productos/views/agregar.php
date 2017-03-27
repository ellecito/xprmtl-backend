<h1>Producto</h1>
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
        <label for="nombre" class="col-sm-2 control-label">Nombre</label>
        <div class="col-sm-10">
          <input type="text" id="nombre" name="nombre" class="form-control validate[required]" />
        </div>
      </div>
	  <div class="form-group">
        <label for="precio" class="col-sm-2 control-label">Precio</label>
        <div class="col-sm-10">
          <input type="text" id="precio" name="precio" class="form-control validate[required, custom[integer]]" />
        </div>
      </div>
	  <div class="form-group">
      <label for="categoria" class="col-sm-2 control-label">Categoría</label>
      <div class="col-sm-4">
        <select id="categoria" name="categoria" class="selectpicker validate[required]">
			<option disabled selected>Seleccione</option>
			<?php foreach($categorias as $categoria){ ?>
			<option value="<?php echo $categoria->codigo; ?>"><?php echo $categoria->nombre; ?></option>
			<?php } ?>
        </select>
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
		<h4>Descripción</h4>
      <textarea rows="6" class="form-control validate[required]" id="descripcion" name="descripcion"></textarea>
	  <h4>Especificaciones Técnicas</h4>
      <textarea rows="6" class="form-control validate[required]" id="esp_tecnicas" name="esp_tecnicas"></textarea>
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
<script>
	var codigo = $("#codigo").val();
	var id = 1;
	var urlDelete = '/productos/eliminar_imagen/';
	cargar_imagenes();
	
function cargar_imagenes(){
		
	var replica = $("#replicar").clone();
	replica.attr('id','');
	replica.children().attr('id',"img"+id);
	replica.css('display','inline-block');
	$("#cont-imagenes").prepend(replica);
	
	var rutas = '<input type="hidden" class="imagenes" name="ruta_grande[]" id="img-grande-'+id+'" >'+
		'<input type="hidden" name="ruta_interna[]" id="img-interna-'+id+'" >';
		
	$("#rutas-imagenes").append(rutas);

	var croppicContainerModalOptions = {
		uploadUrl:'/productos/cargar_imagen/',
		cropUrl:'/productos/cortar_imagen/',
		modal:true,
		outputUrlId:'img-interna-'+id,
		outputUrlIdGr:'img-grande-'+id,
		urlDelete:urlDelete,
		uploadData:{
			"codigo":codigo
		},
		cropData:{
			"codigo":codigo
		},
		imgEyecandyOpacity:0.4,
		loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>',
		onAfterImgUpload: function(){ },
		onAfterImgCrop: function(){
			cargar_imagenes();
		},
	}
	var cropContainerModal = new Croppic('img'+id, croppicContainerModalOptions);
	
	id += 1;
		
}
	
</script>