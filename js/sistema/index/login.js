$(document).ready(function() {

	$("#form-login").validationEngine('attach', {
		promptPosition:'topLeft',
		validationEventTrigger:false,
        scroll: false,
        showOneMessage:true,
	    onValidationComplete: function(form, status){
		if(status) {
			noty({
				text: 'Verificando datos. Por favor, espere un momento.',
				layout: 'topCenter',
				closeWith: [],
				type: 'alert',
				killer:true,
				template: '<div class="noty_message"><img src="/imagenes/icons/ajax-loader.gif">&nbsp;&nbsp;<span class="noty_text"></span><div class="noty_close"></div></div>',
				fondo: '<div id="fondo" style=" position: fixed; top:0; height: 100%; width:100%; background-color: rgba(60, 56, 56, 0.38); display:block;z-index: 9999;"></div>'
			});

			$.ajax({
				url: '/inicio/login/',
				type: 'post',
				dataType: 'json',
				data: $("#form-login").serialize(),
				success: function(json){
					if(json.result){
						noty({
							text: "Bienvenido.",
							layout: 'topCenter',
							type: 'success',
							killer: true
						});
						setTimeout(function() {
							window.location.href = '/productos/';
						}, 1000);
					}
					else
					{
						noty({
							text: json.msg,
							layout: 'topCenter',
							type: 'error',
							killer: true
						});
					}
				}
			});
		}
	  }
	});


	/* recuperar contraseña */
    $("#form-recuperar").validationEngine('attach', {
        promptPosition:'topLeft',
		validationEventTrigger:false,
        scroll: false,
        showOneMessage:true,
	    onValidationComplete: function(form, status){
		if(status) {
			noty({
				text: 'Enviando contraseña. Por favor, espere un momento.',
				layout: 'topCenter',
				type: 'alert',
				closeWith: [],
				killer:true,
				template: '<div class="noty_message"><img src="/imagenes/icons/ajax-loader.gif">&nbsp;&nbsp;<span class="noty_text"></span><div class="noty_close"></div></div>',
				fondo: '<div id="fondo" style=" position: fixed; top:0; height: 100%; width:100%; background-color: rgba(60, 56, 56, 0.38); display:block;z-index: 9999;"></div>'
			});

			$.ajax({
				url: '/inicio/recuperar/',
				type: 'post',
				dataType: 'json',
				data: $("#form-recuperar").serialize(),
				success: function(json){
					if(json.result){
						noty({
							text: "Su nueva contraseña ha sido enviada al email indicado.",
							layout: 'topCenter',
							type: 'success',
							killer: true
						});

						setTimeout(function(){
                            $(".close").trigger('click');
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
