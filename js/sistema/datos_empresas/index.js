var delay = (function(){
  var timer = 0;
  return function(callback, ms){
  clearTimeout (timer);
  timer = setTimeout(callback, ms);
 };
})();

$(document).ready(function(){
    $("#form-agregar").validationEngine('attach', {
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
				url: '/datos-empresa/',
				type: 'post',
				dataType: 'json',
				data: $("#form-agregar").serialize(),
				success: function(json){
					if(json.result){
						noty({
							text: "Registro actualizado con éxito.",
							layout: 'topCenter',
							type: 'success',
							timeout: 2000,
							killer: true
						});

						setTimeout(function(){
								window.location.href = '/datos-empresa/';
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
	
	/* cargar comunas por region */
	$("#region").change(function(){
		$("#comuna").html('<option value="">Cargando...</option>');
		$('.selectpicker').selectpicker('refresh');
		$.ajax({
			url: '/datos_empresa/listar_comunas/',
			type: 'post',
			dataType: "json",
			data: "region="+$(this).val(),
			success: function(html){
				$("#comuna").html(html);
				$('.selectpicker').selectpicker('refresh');
			}
		});
	});
	
	/* Google maps */
    if ($('#direccion').val() == '') {
        initialize('Santiago Chile');
    } else {
        initialize($('#direccion').val());
    }

    /* si cambia la direccion */
    $('#direccion').keyup(function() {
        if ($('#direccion').val() != '') {
            initialize($('#direccion').val());
        }
    });

});

function initialize(address) {
	
	if ($("#comuna").val() != '') {
        address += ' ' + $("#comuna option:selected").html();
    }
	
    if ($("#region").val() != '') {
        address += ' ' + $("#region option:selected").html();
        address += ' Chile';
    }
	
    var geoCoder = new google.maps.Geocoder(address)
    var request = {
        'address': address
    };
    geoCoder.geocode(request, function(result, status) {
        var lat = 0,
            lng = 0;
        if (typeof result[0] != 'undefined') {
            lat = result[0].geometry.location.lat();
            lng = result[0].geometry.location.lng();
        }

        $("#ltd").val(lat);
        $("#lng").val(lng);

        var latlng = new google.maps.LatLng(lat, lng);
        var myOptions = {
            zoom: 15,
            center: latlng,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("showMapa"), myOptions);
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: 'Ubicación'
        });
        var infowindow = new google.maps.InfoWindow({
            content: "(1.10, 1.10)"
        });
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            var yeri = event.latLng;
            var latlongi = "(" + yeri.lat().toFixed(6) + ", " + yeri.lng().toFixed(6) + ")";
            infowindow.setContent(latlongi);

            $('#ltd').val(yeri.lat().toFixed(6));
            $('#lng').val(yeri.lng().toFixed(6));
        });
    });
}
