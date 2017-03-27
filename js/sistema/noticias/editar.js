$(document).ready(function() {
	$("#fecha").datepicker();
	$(".tab").click(function(e){
		e.preventDefault();
		$(".tab-pane").removeClass("active");
		$("li").removeClass("active");
		$('a[href="#' + $(this).attr("rel") + '"]').parent("li").addClass("active");
		$("#" + $(this).attr("rel")).addClass("active");
	});


	$("#form-editar").validationEngine("attach",{
			promptPosition:"topLeft",
			validationEventTrigger:false,
			prettySelect:0,
			useSuffix:"_chosen",
			onValidationComplete:function(e,o){
				if(o){
					noty({
						text:"Su noticia está siendo enviada. Por favor, espere un momento.",
						layout:"topCenter",
						type:"alert",
						killer:true,
						closeWith:[],
						template: '<div class="noty_message"><img src="/imagenes/icons/ajax-loader.gif">&nbsp;&nbsp;<span class="noty_text"></span><div class="noty_close"></div></div>',
						fondo: '<div id="fondo" style=" position: fixed; top:0; height: 100%; width:100%; background-color: rgba(60, 56, 56, 0.38); display:block;z-index: 9999;"></div>'
					});
					
					$("#contenido").val(CKEDITOR.instances['contenido'].getData());
					var t=new FormData($("#form-editar")[0]);

					$.ajax({
						url:"/noticias/editar/",
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
									text:"Noticia editada con éxito.",
									layout:"topCenter",
									type:"success",
									killer:true,
									closeWith:[],
								});

							setTimeout(function(){
								window.location="/noticias/";
								},500);
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
