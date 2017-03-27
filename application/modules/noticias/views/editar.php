<h1>Noticia</h1>
<ul class="nav nav-tabs">
  <li class="active"><a href="#datos" data-toggle="tab">Datos</a></li>
  <li><a href="#cuerpo" data-toggle="tab">Cuerpo</a></li>
  <li><a href="#imagenes" data-toggle="tab">Imágenes</a></li>
</ul>
<form class="form-horizontal" id="form-editar" enctype="multipart/form-data">
  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="datos">
      <div class="form-group">
        <label for="titulo" class="col-sm-2 control-label">Título</label>
        <div class="col-sm-10">
          <input type="text" id="titulo" name="titulo" class="form-control validate[required]" value="<?php echo $noticia->titulo; ?>" />
          <input type="hidden" id="codigo" name="codigo" class="form-control validate[required, custom[integer]]" value="<?php echo $noticia->codigo; ?>" />
        </div>
      </div>
	  <div class="form-group">
        <label for="fecha" class="col-sm-2 control-label">Fecha</label>
        <div class="col-sm-10">
          <input type="text" id="fecha" name="fecha" class="form-control validate[required]" value="<?php echo date("d/m/Y", strtotime($noticia->fecha)); ?>"/>
        </div>
      </div>
      <div class="form-group">
        <label for="resumen" class="col-sm-2 control-label">Resumen</label>
        <div class="col-sm-10">
          <textarea rows="4" class="form-control validate[required]" id="resumen" name="resumen"><?php echo $noticia->resumen; ?></textarea>
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
      <textarea rows="6" class="form-control validate[required]" id="contenido" name="contenido"><?php echo $noticia->contenido; ?></textarea>
	  <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
		  <a href="#" class="tab btn btn-primary" rel="imagenes">Continuar con imágenes</a>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="imagenes">
      <center>
	  <div class="form-group" style="width:<?php echo $this->medidas->ancho_min; ?>px; height:<?php echo $this->medidas->alto_min; ?>px;">
			<label>Adjuntar imagen tamaño mínimo <?php echo $this->medidas->ancho_min.'px x '.$this->medidas->alto_min.'px'; ?></label>
            <div class="multi-imagen" style="margin-bottom:20px;">
                <div style="display:none;" id="replicar" class="box">
                    <div class="img" style="width:<?php echo $this->medidas->ancho_min; ?>px; height:<?php echo $this->medidas->alto_min; ?>px;" ></div>
                </div>
                <div id="cont-imagenes">
					<?php if($noticia->imagen){ ?>
                        <div class="box">
                            <div class="img" style="width:<?php echo $this->medidas->ancho_min; ?>px; height:<?php echo $this->medidas->alto_min; ?>px;" >
                                <img class="croppedImg" src="<?php echo $noticia->imagen; ?>" />
                                <div class="cropControls cropControlsUpload">
									<i class="cropControlRemoveCroppedImage eliminar_imagen" rel="<?php echo $noticia->codigo; ?>"></i>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
				</div>
                <div id="rutas-imagenes">
					<input type="hidden" class="imagenes" name="ruta_grande" id="img-grande-<?php echo $noticia->codigo; ?>" >
					<input type="hidden" name="ruta_interna" id="img-interna-<?php echo $noticia->codigo; ?>" value="<?php echo $noticia->imagen; ?>">
				</div>
            </div>
		</div>
		</center>
    </div>
  </div>
</form>
<script type="text/javascript">
CKEDITOR.replace('contenido');
<?php if(!$noticia->imagen){ ?>
			cargar_imagenes();
	<?php } ?>
	var codigo = $("#codigo").val();
	var id = 1;
	var urlDelete = '/noticias/eliminar_imagen/';
	
    
    $(function(){
        $(".eliminar_imagen").click(function(){
            var codigo = $(this).attr('rel');
            var cont = $(this).parent().parent().parent();
            $.ajax({
				type: "POST",
				data: "codigo="+codigo,
				dataType: "json",
				url: urlDelete,
				success: function(json){
					cont.remove();
					$("#img-interna-"+codigo).remove();
					$("#img-grande-"+codigo).remove();
					cargar_imagenes();
				}
			});
       });
    });
	
	function cargar_imagenes(){
		var replica = $("#replicar").clone();
		replica.attr('id','');
		replica.children().attr('id',"img"+id);
		replica.css('display','inline-block');
		$("#cont-imagenes").prepend(replica);
		
		var rutas = '<input type="hidden" class="imagenes" name="ruta_grande" id="img-grande-'+id+'" >'+
			'<input type="hidden" name="ruta_interna" id="img-interna-'+id+'" >';
			
		$("#rutas-imagenes").append(rutas);

		var croppicContainerModalOptions = {
			uploadUrl:'/noticias/cargar_imagen/',
			cropUrl:'/noticias/cortar_imagen/',
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
				var cargar = true;
				$(".imagenes").each(function(){
					if($(this).val() == "")
						cargar = false;
				});
			},
		}
		var cropContainerModal = new Croppic('img'+id, croppicContainerModalOptions);
		
		id += 1;
			
	}
</script>