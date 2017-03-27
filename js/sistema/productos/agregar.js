$(document).ready(function() {
	CKEDITOR.replace('descripcion');
	CKEDITOR.replace('esp_tecnicas');
	
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
						text:"Su producto está siendo enviado. Por favor, espere un momento.",
						layout:"topCenter",
						type:"alert",
						killer:true,
						closeWith:[],
						template: '<div class="noty_message"><img src="/imagenes/icons/ajax-loader.gif">&nbsp;&nbsp;<span class="noty_text"></span><div class="noty_close"></div></div>',
						fondo: '<div id="fondo" style=" position: fixed; top:0; height: 100%; width:100%; background-color: rgba(60, 56, 56, 0.38); display:block;z-index: 9999;"></div>'
					});
					
					$("#descripcion").val(CKEDITOR.instances['descripcion'].getData());
					$("#esp_tecnicas").val(CKEDITOR.instances['esp_tecnicas'].getData());
					var t=new FormData($("#form-agregar")[0]);

					$.ajax({
						url:"/productos/agregar/",
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
									text:"Producto enviado con éxito.",
									layout:"topCenter",
									type:"success",
									killer:true,
									closeWith:[],
								});
								setTimeout(function(){
									window.location = '/productos/';
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
