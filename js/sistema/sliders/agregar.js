$(document).ready(function() {
	
	var codigo = $("#codigo").val();
	var id = 1;
	var urlDelete = '/sliders/eliminar_imagen/';
	cargar_imagenes();
	
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
			uploadUrl:'/sliders/cargar_imagen/',
			cropUrl:'/sliders/cortar_imagen/',
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
			onAfterImgUpload: function(){},
			onAfterImgCrop: function(){},
			onReset: function(){cargar_imagenes();},
		}
		var cropContainerModal = new Croppic('img'+id, croppicContainerModalOptions);
		
		id += 1;
			
	}
	
	$(".tab").click(function(e){
		e.preventDefault();
		$(".tab-pane").removeClass("active");
		$("li").removeClass("active");
		$('a[href="#' + $(this).attr("rel") + '"]').parent("li").addClass("active");
		$("#" + $(this).attr("rel")).addClass("active");
	});

	$("#form-agregar").validationEngine("attach",{
			promptPosition:"topLeft",
			validationEventTrigger:false,
			prettySelect:0,
			useSuffix:"_chosen",
			onValidationComplete:function(e,o){
				if(o){
					noty({
						text:"Su slider está siendo enviado. Por favor, espere un momento.",
						layout:"topCenter",
						type:"alert",
						killer:true,
						closeWith:[],
						template: '<div class="noty_message"><img src="/imagenes/icons/ajax-loader.gif">&nbsp;&nbsp;<span class="noty_text"></span><div class="noty_close"></div></div>',
						fondo: '<div id="fondo" style=" position: fixed; top:0; height: 100%; width:100%; background-color: rgba(60, 56, 56, 0.38); display:block;z-index: 9999;"></div>'
					});
					
					var t=new FormData($("#form-agregar")[0]);

					$.ajax({
						url:"/sliders/agregar/",
						type:"post",
						data:t,
						dataType: "html",
						cache: false,
						contentType: false,
						processData: false,
						success:function(result){
							var e = JSON.parse(result);
							if(e.result){
								noty({
									text:"Slider enviado con éxito.",
									layout:"topCenter",
									type:"success",
									killer:true,
									closeWith:[],
								});
								setTimeout(function(){
									window.location = '/sliders/';
								}, 1000);
							
							}else{

								noty({
									text: e.msg,
									layout:"topCenter",
									type:"error",
									killer:true,
									closeWith:["click"],
								});
							}


							}
						});
					}
				}
	});

});
