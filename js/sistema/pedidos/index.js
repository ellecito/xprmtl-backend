$(window).on('load', function(){
	$(".aprobar").click(function(e){
		$("#orden_e").html($(this).val());
		$("#orden_e").trigger("chosen:updated");
		$("#codigo_e").val($(this).val());
	});
	
	$("#form-aceptar").validationEngine('attach', {
        promptPosition:'topLeft',
		validationEventTrigger:false,
        showOneMessage:true,
		prettySelect:true,
        usePrefix:"selectBox_",
	    onValidationComplete: function(form, status){
		if(status) {
			noty({
				text: 'Guardando. Por favor, espere un momento.',
				layout: 'topCenter',
				type: 'alert',
				closeWith: [],
				killer:true,
				template: '<div class="noty_message"><img src="/imagenes/icons/ajax-loader.gif">&nbsp;&nbsp;<span class="noty_text"></span><div class="noty_close"></div></div>',
				fondo: '<div id="fondo" style=" position: fixed; top:0; height: 100%; width:100%; background-color: rgba(60, 56, 56, 0.38); display:block;z-index: 9999;"></div>'
			});
            
			$.ajax({
				url: '/pedidos/tracking/',
				type: 'post',
				dataType: 'json',
                data: $("#form-aceptar").serialize(),
				success: function(json){
					if(json.result){
						noty({
							text: "Guardado con éxito.",
							layout: 'topCenter',
							type: 'success',
							killer: true,
                            timeout: 3000
						});
						
                        setTimeout(function(){
                            window.location.href = "/pedidos/";
                        }, 1000);
					}
					else
					{
						noty({
							text: json.msg,
							layout: 'topCenter',
							type: 'error',
							timeout: 3000,
							killer: true
						});
					}
				}
			});
		}
	  }
	});
});