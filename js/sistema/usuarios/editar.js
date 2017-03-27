$(function(){
	/*$("#pais").change(function(e){
		e.preventDefault();
		$("#region").html("<option disabled selected>Buscando...</option>");
		$("#region").trigger("chosen:updated");
		$.ajax({
			url: '/usuarios/regiones/',
			type: 'post',
			dataType: 'json',
			data: "pais="+$(this).val(),
			success: function(json){
				if(json.result){
					$("#region").html(json.html);
					$("#region").trigger("chosen:updated");
				}
			}
		});
	});*/
	
	$('#changepass').val('');
	
	$("#form-editar").validationEngine('attach', {
        		promptPosition:'topLeft',
		validationEventTrigger:false,
        		showOneMessage:true,
	    	onValidationComplete: function(form, status){
		if(status) {
			noty({
				text: 'Actualizando registro. Por favor, espere un momento.',
				layout: 'topCenter',
				type: 'alert',
				closeWith: [],
				killer:true,
				template: '<div class="noty_message"><img src="/imagenes/icons/ajax-loader.gif">&nbsp;&nbsp;<span class="noty_text"></span><div class="noty_close"></div></div>',
				fondo: '<div id="fondo" style=" position: fixed; top:0; height: 100%; width:100%; background-color: rgba(60, 56, 56, 0.38); display:block;z-index: 9999;"></div>'
			});

			$.ajax({
				url: '/usuarios/editar/',
				type: 'post',
				dataType: 'json',
				data: $("#form-editar").serialize(),
				success: function(json){
					if(json.result){
						noty({
							text: "Registro actualizado con éxito.",
							layout: 'topCenter',
							type: 'success',
							killer: true
						});

						setTimeout(function(){
							window.location.href = '/usuarios/';
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
