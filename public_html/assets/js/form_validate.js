var CelaForm=[];

jQuery.validator.addMethod(
	'lettersonly',
	function(value, element) {
		return this.optional(element) || /^[a-zA-Z.\xc1\xc9\xcd\xd3\xda\xe1\xe9\xed\xd1\xf1\xf3\xfa\s]+$/i.test(value);
	},
	'Letters only please'
);
jQuery.validator.addMethod(
	'password_check',
	function(value, element) {
		return this.optional(element) || /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$/i.test(value);
	},
	'Use a valid password'
);
jQuery.validator.addMethod(
	'user',
	function(value, element) {
		return this.optional(element) || /^[0-9a-zA-Z.\x5f]+$/i.test(value);
	},
	'Use a valid user name'
);
jQuery.validator.addMethod(
	'alphanumeric',
	function(value, element) {
		return this.optional(element) || /^[0-9A-Z\xc1\xc9\xcd\xd3\xda\xe1\xe9\xed\xf3\xfa\s]+$/i.test(value);
	},
	'alphanumeric only please'
);

jQuery.validator.addMethod(
	'positive',
	function(value, element) {
		return (value >= 0 || value == '' ? true:false);
	},
	'positive number are required'
);

jQuery.validator.addMethod(
	'valid_date',
	function(value, element){
		var date    = new Date();
		var y       = date.getFullYear();
		var ms      = date.getMonth() + 1;
		var d       = date.getDate();
		var h       = date.getHours() + 2;
		var m       = date.getMinutes();
		var s       = date.getSeconds();

		ms = (ms < 10) ? '0' + ms:ms;
		d = (d < 10 ? '0' + d:d);

		var format      = '' + y + '-' + ms + '-' + d + ' ' + h + ':' + m + ':' + s +'';
		var today       = $.datepicker.formatDate(format, new Date());
		var select_date = $.datepicker.formatDate(value, new Date());
		var valid       = true;

		if (select_date <= today)
			valid = false;
		return valid;
	},
	'Select a date greater'
);

jQuery.validator.addMethod(
	'valid_size',
	function(value, element){
		var file = $(element)[0].files[0];
		if(typeof(file) != 'undefined'){
			var fileSize    = (file.size * 1) / 1048576;
			var maxSize     = $(element).data('size');
			var flotante    = parseFloat(fileSize);
			var resultado   = Math.round(flotante * Math.pow(10, 5)) / Math.pow(10, 5);
			if(resultado <= maxSize)
				return true;
			else
				return false;
		}else{
			return true;
		}
	},
	'Select a valid size file'
);

jQuery.validator.addMethod(
	'valid_repetido',
	function(value, element){
		var actual  = value;
		var valid   = true;
		$(element).removeClass('unico');
		$('.unico').each(function(){
			//console.log(actual+':::'+value);
			if(actual == $(this).val() ){
				//console.log('repetido');
				valid = false;
				return;
			}
		});
		$(element).addClass('unico');
		return valid;
	},
	'Select a valid size file'
);

$.validator.addMethod(
	'required_one',
	function(value, element) {
		var maxCheck;
		var classGroup;
		if(typeof ($(element).data('max-check')) == 'undefined')
			maxCheck = 100;
		else
			maxCheck = $(element).data('max-check');

		if(typeof ($(element).data('class-group')) == 'undefined')
			classGroup = 'e_grupo';
		else
			classGroup = $(element).data('class-group');

		return ($('.' + classGroup + ':checked').size() > 0 && $('.' + classGroup + ':checked').size() <= maxCheck ? true:false);
	},
	'Check an option.'
);

jQuery.validator.addMethod(
	'valid_repetido',
	function(value, element){
		var actual  = value;
		var valid   = true;
		$(element).removeClass('unico');
		$('.unico').each(function(){
			//console.log(actual+':::'+value);
			if(actual == $(this).val() ){
				//console.log('repetido');
				valid = false;
				return;
			}
		});
		$(element).addClass('unico');
		return valid;
	},
	'Select a unique value'
);

jQuery.validator.addMethod(
	'curp_validate',
	function(value, element) {
		return this.optional(element) || /^(([A-Z][A,E,I,O,U,X][A-Z]{2})(\d{2})((01|03|05|07|08|10|12)(0[1-9]|[12]\d|3[01])|02(0[1-9]|[12]\d)|(04|06|09|11)(0[1-9]|[12]\d|30))([M,H])(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)([B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3})([0-9,A-Z][0-9]))+$/i.test(value);
	},
	'Se requiere una estructura correcta de la CURP'
);

jQuery.validator.addMethod(
	'rfc_validate',
	function(value, element) {
		return this.optional(element) || /^[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]+$/i.test(value);
	},
	'Se requiere una estructura correcta de la RFC'
);

function LoadFormValidate(FormId, Ignore){
	var AuxForm = null;

	AuxForm = $('#' + FormId).validate( {
		debug: true,
		Ignore: Ignore,
		highlight: function(element){

			var ElementId = $(element).attr('id');

			/*
			*
			* is-invalid
			* is-valid
			* <div class="valid-feedback">Looks good!</div>
			 <div class="valid-tooltip">Looks good!</div>*/

			$('#success_' + ElementId).remove();
			$('#error_' + ElementId).remove();

			$(element).removeClass('is-valid').addClass('is-invalid ');
		},
		errorPlacement: function(error, element) {
			var ElementId = $(element).attr('id');

			//$(element).tooltip({
			//	'template': '<div class="tooltip tooltip-me" id="tooltip_' + ElementId + '" role="tooltip">' +
			//	'<div class="tooltip-arrow" ></div>' +
			//	'<div class="tooltip-inner tooltip-inner-me"></div>' +
			//	'</div>',
			//	'placement':'bottom',
			//	'title': $(error).html(),
			//	'trigger': 'manual'
			//});
			//
			//$(element).tooltip('show');

			$(element).parent().append('<div id="error_' + ElementId + '" class="invalid-tooltip">' + $(error).html() + '</div>');
		},
		unhighlight: function(element) {
			var ElementId = $(element).attr('id');

			//$('#error_' + ElementId).remove();
			//$('#success_' + ElementId).remove();
			//
			//$(element).closest('.group-validate').removeClass('has-error has-feedback').addClass('has-success has-feedback');
			//$(element).closest('.validate').append('<span id="success_' + ElementId + '" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
			//$(element).next('label').remove();
			//
			//$(element).tooltip('hide');

			$('#success_' + ElementId).remove();
			$('#error_' + ElementId).remove();

			$(element).removeClass('is-invalid').addClass('is-valid ');
		},
		submitHandler: function(form) {
			var submit = true;
			//console.log($(form).data('before_submit'));
			//console.log('oooooo');
			//if(typeof($(form).data('before_submit')) != 'undefined'){
			//	$.each($(form).data('before_submit'), function(Index, Value){
			//		if(!eval(Value.function)){
			//			submit = false;
			//		}
			//	});
			//}

			/*Se evaluan los eventos antes del submit*/
			if(typeof($(form).data('before_submit')) !== 'undefined'){
				$.each($(form).data('before_submit'), function(Index, Value){
					//console.log(eval(Value.function) + '___' + Value.function);
					if(!eval(Value.function)){
						submit = false;
						//console.log(Value);
						if(typeof(Value.element) !== 'undefined'){
							var ElementId = Value.element;
							var Visible = $('#' + ElementId).is('visible');

							if(!Visible){
								$('#' + ElementId).show();
							}

							$(element).removeClass('is-valid').addClass('is-invalid ');

							$(element).parent().append('<div id="error_' + ElementId + '" class="invalid-tooltip">' + Value.message + '</div>');

							//$('#' + ElementId).tooltip({
							//	'template': '<div class="tooltip tooltip-me" id="tooltip_' + ElementId + '" role="tooltip" >'+
							//	'<div class="tooltip-arrow" ></div>' +
							//	'<div class="tooltip-inner tooltip-inner-me"></div>' +
							//	'</div>',
							//	'placement':'bottom',
							//	'title': Value.message,
							//	'trigger': 'manual'
							//});

							//$('#' + ElementId).tooltip('show');
							if(!Visible){
								$('#' + ElementId).hide();
							}
						}
					}
				});
			}

			/*Se validan los combos modificados*/
			$('select').each(function(){
				if($(this).hasClass('e_requerido') && ($(this).val() == '' || $(this).val() == null)){
					var ElementId = $(this).attr('id');
					$('#' + ElementId).show();
					$(element).removeClass('is-valid').addClass('is-invalid ');

					$(element).parent().append('<div id="error_' + ElementId + '" class="invalid-tooltip">' + Value.message + '</div>');
					//$('#' + ElementId).tooltip({
					//	'template': '<div class="tooltip tooltip-me" id="tooltip_' + ElementId + '" role="tooltip" >'+
					//	'<div class="tooltip-arrow" ></div>' +
					//	'<div class="tooltip-inner tooltip-inner-me"></div>' +
					//	'</div>',
					//	'placement':'bottom',
					//	'title': 'Campo requerido',
					//	'trigger': 'manual'
					//});
					//
					//$('#' + ElementId).tooltip('show');
					$('#' + ElementId).hide();
					submit = false;
				}
			});

			if(submit){
				$('.Save').button('loading');
				if(typeof($(form).data('onsubmit')) != 'undefined'){
					eval($(form).data('onsubmit'))
				}else{
					form.submit();
				}
			}else{
				if(typeof($(form).data('before_submit')) != 'undefined'){
					$.each($(form).data('before_submit'), function(Index, Value){
						if(typeof(Value.element) != 'undefined'){
							if(!eval(Value.function)){
								var ElementId = Value.element;
								$(element).removeClass('is-valid').addClass('is-invalid ');

								$(element).parent().append('<div id="error_' + ElementId + '" class="invalid-tooltip">' + Value.message + '</div>');

								//$('#' + ElementId).tooltip({
								//	'template': '<div class="tooltip tooltip-me" id="tooltip_' + ElementId + '" role="tooltip">'+
								//	'<div class="tooltip-arrow" ></div>' +
								//	'<div class="tooltip-inner tooltip-inner-me"></div>' +
								//	'</div>',
								//	'placement':'bottom',
								//	'title': Value.message,
								//	'trigger': 'manual'
								//});
								//
								//$('#' + ElementId).tooltip('show');
							}
						}
					});
				}
			}
		},
		invalidHandler: function(event, validator) {
			$('.Save').button('reset');
		}
	});

	CelaForm[FormId]=(AuxForm);
	$('.Save').removeAttr('disabled');
	$('.InsertBack').hide();
}

function LoadSelectpicker(Element){
	if(typeof($(Element).data('select_width')) !== 'undefined' && $(Element).data('select_width') != ''){
		width = $(Element).data('select_width');
	}else{
		width = false;
	}

	if(typeof($(Element).data('select_height')) !== 'undefined' && $(Element).data('select_height') != ''){
		height = $(Element).data('select_height');
	}else{
		height = '';
	}

	if($(Element).attr('multiple') == 'multiple'){
		if($(Element).hasClass('filter')){
			$(Element).selectpicker({
				'selectedText': 'cat',
				'iconBase': 'fa ',
				'tickIcon': 'fa fa-check',
				'liveSearch': 'true',
				'actionsBox': 'true',
				'noneSelectedText': 'Ningun elemento',
				'selectedTextFormat': 'count',
				'countSelectedText': '{0} de {1} elementos',
				'selectAllText': 'Todos',
				'deselectAllText': 'Ninguno &nbsp;',
				'width': width,
				'height': height,
				actionsbox: {
					tooltip: {
						selectAll: 'Seleccionar todos los elementos',
						deselectAll: 'Des-Seleccionar todos los elementos'
					},
					icon: {
						selectAll: 'fa-check',
						deselectAll: 'fa-times'
					},
					class: 'pull-right',
					contentSize: 'col-xs-4'
				},
				searchbox: {
					class: 'col-xs-8'
				},
				size: 10,
				styleBase: 'btn btn-white'
			});
		}else{
			$(Element).selectpicker({
				'selectedText': 'cat',
				'iconBase': 'fa ',
				'tickIcon': 'fa fa-check',
				'actionsBox': 'true',
				'selectAllText': 'Todos',
				'deselectAllText': 'Ninguno &nbsp;',
				'selectedTextFormat': 'static',
				'noneSelectedText': 'Ningun elemento seleccionado',
				'selectedTextFormat': 'count',
				'countSelectedText': '{0} de {1} elementos',
				'width': width,
				'height': height,
				actionsbox: {
					tooltip: {
						selectAll: 'Seleccionar todos los elementos',
						deselectAll: 'Des-Seleccionar todos los elementos'
					},
					icon: {
						selectAll: 'fa-check',
						deselectAll: 'fa-times'
					},
					class: 'pull-left',
					contentSize: 'col-xs-4'
				},
				size: 10,
				styleBase: 'btn btn-white'
			});
		}
	}else{
		if($(Element).hasClass('filter')){
			$(Element).selectpicker({
				'selectedText': 'cat',
				'iconBase': 'fa ',
				'tickIcon': 'fa fa-check',
				'liveSearch': 'true',
				'width': width,
				'height': height,
				searchbox: {
					class: 'col-xs-12'
				},
				size: 10,
				styleBase: 'btn btn-white'
			});
		}else{
			$(Element).selectpicker({
				'selectedText': 'cat',
				'iconBase': 'fa ',
				'tickIcon': 'fa fa-check',
				'width': width,
				'height': height,
				size: 10,
				styleBase: 'btn btn-white'
			});
		}
	}
}

function e_horaLoadValidation(id, minimo, maximo){
	$('#' + id).timepicker({
		timeFormat: 'HH:mm:ss',
		currentText: 'Ahora',
		closeText: 'Listo',
		timeOnlyTitle: 'Selecciona la Hora',
		hourText: 'Hora',
		minuteText: 'Minuto',
		secondText: 'Segundo',
		hourMin: parseInt(minimo[0]),
		minuteMin: parseInt(minimo[1]),
		secondMin: parseInt(minimo[2]),
		hourMax: parseInt(maximo[0]),
		minuteMax: parseInt(maximo[1]),
		secondMax: parseInt(maximo[2])
	});
}

function e_fechaLoadValidation(id){
	$('#' + id).rules('add', {
		minlength: 10,
		date: true,
		messages: {
			date: 'Formato no v&aacute;lido: yyyy-mm-dd',
			minlength: 'Minimo 10 caracteres'
		}
	});

	$('#' + id).datepicker({
		monthNames: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
		monthNamesShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
		dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		format: 'yyyy-mm-dd',
		prevText: 'Anterior',
		nextText: 'Siguiente',
		changeMonth: true,
		changeYear: true,
		minDate: $('#' + id).data('rango').minimo,
		maxDate: $('#' + id).data('rango').maximo,
		firstDay: 1,
		yearRange: '1900:2999',
		showOtherMonths: true,
		selectOtherMonths: true
	});
}

function e_fecha_horaLoadValidate(id){
	$('#' + id).datetimepicker({
		format: 'yyyy-mm-dd',
		timeFormat: 'HH:mm:ss',
		monthNames: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
		monthNamesShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
		dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		currentText: 'Ahora',
		closeText: 'Listo',
		timeText: 'Hora Seleccionada',
		hourText: 'Hora',
		minuteText: 'Minuto',
		secondText: 'Segundo',
		prevText: 'Anterior',
		nextText: 'Siguiente',
		changeMonth: true,
		changeYear: true,
		minDate: $('#' + id).data('rango').minimo,
		maxDate: $('#' + id).data('rango').maximo,
		firstDay: 1,
		yearRange: '1900:2999',
		showOtherMonths: true,
		selectOtherMonths: true
	});

	$('#' + id).rules('add', {
		minlength: 16,
		messages: {
			date: 'Formato no v&aacute;lido: yyyy-mm-dd 00:00',
			minlength: 'Minimo 16 caracteres',
		}
	});
}

function e_unicoLoadValidation(id){
	$('#' + id).rules('add', {
		valid_repetido: true,
		messages: {
			valid_repetido: 'El orden esta repetido'
		}
	});
}

function e_nombreLoadValidation(id){
	$('#' + id).rules('add', {
		minlength:3,
		lettersonly: true,
		messages: {
			minlength: 'Introduzaca mas de 3 caracteres',
			lettersonly: 'Deben ser solo letras'
		}
	});
}
function e_passwordLoadValidation(id){
	$('#' + id).rules('add', {
		password_check: true,
		messages: {
			password_check: 'Debe introducir una valor que contenga: Mínimo ocho caracteres, al menos una letra mayúscula, una letra minúscula, un número y un carácter especial'
		}
	});
}

function e_requeridoLoadValidation(id){
	$('#' + id).rules('add', {
		required: true,
		messages: {
			required: 'Campo requerido'
		}
	});
}

function e_correoLoadValidation(id){
	$('#' + id).rules('add', {
		email: true,
		messages: {
			email: 'Introduzca una direcci&oacute;n correcta ejem: ejemplo@ejemplo.com'
		}
	});
}

function e_usuarioLoadValidation(id){
	$('#' + id).rules('add', {
		user: true,
		messages: {
			user: 'Utilice un nombre de usuario sin caracteres especiales ni espacios'
		}
	});
}

function e_direccionLoadValidation(id){
	$('#' + id).rules('add', {
		minlength: 10,
		messages: {
			minlength: 'Necesita almenos 10 caracteres'
		}
	});
}

function e_telefonoLoadValidation(id){
	$('#' + id).rules('add', {
		minlength: 10,
		number: true,
		messages: {
			minlength: 'Tel&eacute;fono no v&aacute;lido, necesita almenos 10 caracteres',
			number: 'N&uacute;mero de t&eacute;lefono no v&aacute;lido'
		}
	});
}

function e_textoLoadValidation(id){
	$('#' + id).rules('add', {
		lettersonly: true,
		messages: {
			lettersonly: 'Solo texto plano'
		}
	});
}

function e_alfa_numericoLoadValidation(id){
	$('#' + id).rules('add', {
		alphanumeric: true,
		minlength:5,
		messages: {
			alphanumeric: 'Solo texto alfa-n&uacute;merico'
		}
	});
}

function e_numeroLoadValidation(id){
	$('#' + id).rules('add', {
		number: true,
		messages: {
			number: 'Solo n&uacute;meros',
		}
	});
}

function e_grupoLoadValidation(id){
	var maxCheck;
	if(typeof ($('#' + id).data('max-check')) == 'undefined')
		maxCheck = 100;
	else
		maxCheck = $('#' + id).data('max-check');

	$('#' + id).rules('add', {
		required_one: true,
		messages: {
			required_one: 'Seleccione entre un una y ' + maxCheck + ' opciones.'
		}
	});
}

function e_comboLoadValidation(id){
	$('#' + id).rules('add', {
		positive: true,
		messages: {
			positive: 'Seleccione una Opci&oacute;n'
		}
	});
}

function e_solo_siLoadValidation(id, check, flag){
	$('#' + id).rules('add', {
		required: function(){
			if(flag == true){
				return '#' + check + ':not(:checked)';
			}else{
				return '#' + check + ':checked'
			}
		},
		messages: {
			required: 'Campo requerido'
		}
	});
}

function e_positivoLoadValidation(id){
	$('#' + id).rules('add', {
		positive: true,
		messages: {
			positive: 'Se requiere un n&uacute;mero positivo'
		}
	});
}

function e_remotoLoadValidation(id){
	$('#' + id).rules('add', {
		remote: {
			url: 'AjaxsFunctions.php',
			type: 'post',
			data: {
				Value: function(){
					return $('#' + id).val();
				},
				Table: function(){
					return $('#' + id).data('remote').table;
				},
				Field: function(){
					return $('#' + id).data('remote').field;
				},
				Function: 'FieldValidator',
				Valid: function(){
					if(typeof $('#' + id).data('remote').response == 'undefined'){
						return 'false';
					}else{
						return $('#' + id).data('remote').response;
					}
				},
				Condition: function(){
					if(typeof($('#' + id).data('remote').condition) == 'undefined'){
						return '';
					}else{
						return $('#' + id).data('remote').condition;
					}
				}
			}
		},
		messages:{
			remote: function(){
				if(typeof $('#' + id).data('remote').message == 'undefined'){
					return 'Este Valor ya existe, intente con algun otro';
				}else{
					return $('#' + id).data('remote').message;
				}
			}
		}
	});
}

function e_igualLoadValidation(id){
	$('#' + id).rules('add', {
		equalTo: '#' + $('#'+id).data('igual_a'),
		messages: {
			equalTo: 'El Value no coincide con el de ' + $('#'+id).data('igual_a')
		}
	});
}

function e_rangoLoadValidation(id){
	$('#' + id).rules('add',{
		range: [$('#' + id).data('rango').minimo,$('#' + id).data('rango').maximo],
		messages:{
			range: $('#' + id).data('rango').mensaje
		}
	});
}

function e_longitudLoadValidation(id){
	$('#' + id).rules('add',{
		rangelength: [$('#' + id).data('rango').minimo,$('#' + id).data('rango').maximo],
		messages:{
			rangelength:$('#' + id).data('rango').mensaje
		}
	});
}

function e_archivoLoadValidation(id){
	$('#' + id).rules('add',{
		extension: $('#' + id).data('extension'),
		valid_size: true,
		messages:{
			extension: 'Seleccione un archivo con extensi&oacute;n ' + $('#' + id).data('extension'),
			valid_size: 'Seleccione un archivo no mayor a ' + $('#' + id).data('size') + ' MB de tama&ntilde;o'
		}
	});
}

function e_curpLoadValidation(id){
	$('#' + id).rules('add', {
		curp_validate: true,
		messages: {
			curp_validate: 'Se requiere de una estructura correcta de la CURP'
		}
	});
}

function e_rfcLoadValidation(id){
	$('#'+id).rules('add',{
		rfc_validate: true,
		messages: {
			rfc_validate: 'Se requiere de una estructura correcta de la RFC'
		}
	});
}

$(document).ready(function(id){
	$('.form_validate').each(function(){
		var FormId  = $(this).attr('id');
		if(typeof($(this).data('ignore')) != 'undefined')
			var Ignore = $(this).data('ignore');
		else
			var Ignore = ':hidden';

		LoadFormValidate(FormId, Ignore);
	});

	$('.e_unico').each(function () {
		e_unicoLoadValidation($(thi).attr('id'));
	});

	$('.e_nombre').each(function () {
		e_nombreLoadValidation($(this).attr('id'));
	});

	$('.e_requerido').each(function () {
		//console.log($(this).attr('id'));
		e_requeridoLoadValidation($(this).attr('id'));
	});

	$('.e_correo').each(function () {
		e_correoLoadValidation($(this).attr('id'));
	});

	$('.e_usuario').each(function () {
		e_usuarioLoadValidation($(this).attr('id'));
	});

	$('.e_direccion').each(function () {
		e_direccionLoadValidation($(this).attr('id'));
	});

	$('.e_telefono').each(function () {
		e_telefonoLoadValidation($(this).attr('id'));
	});

	$('.e_texto').each( function(){
		e_textoLoadValidation($(this).attr('id'));
	});

	$('.e_alfa_numerico').each( function(){
		e_alfa_numericoLoadValidation($(this).attr('id'));
	});

	$('.e_numero').each( function(){
		//console.log($(this).attr('id'));
		e_numeroLoadValidation($(this).attr('id'));
	});

	$('.e_grupo').each( function(){
		e_grupoLoadValidation($(this).attr('id'));
	});

	$('.e_combo').each( function(){
		e_comboLoadValidation($(this).attr('id'));
	});

	$('.e_solo_si').each( function(){
		var check   = $(this).data('check');
		var flag    = $(this).data('flag');
		var id      = $(this).attr('id');
		e_solo_siLoadValidation(id, check, flag);
	});

	$('.e_positivo').each( function(){
		e_positivoLoadValidation($(this).attr('id'));
	});

	$('.e_remoto').each( function(){
		var id = $(this).attr('id');
		e_remotoLoadValidation(id);
	});


	$('.e_igual').each(function(){
		var id=$(this).attr('id');
		e_igualLoadValidation(id);
	});

	$('.e_rango').each(function(){
		var id=$(this).attr('id');
		e_rangoLoadValidation(id);
	});

	$('.e_longitud').each(function(){
		var id=$(this).attr('id');
		e_longitudLoadValidation(id);
	});

	$('.e_archivo').each(function(){
		var id=$(this).attr('id');
		e_archivoLoadValidation(id);
	});

	$( '.e_fecha_hora' ).each( function(){
		var id = $(this).attr('id');
		e_fecha_horaLoadValidate(id);
	});

	$('.e_fecha').each( function(){
		var id=$(this).attr('id');
		e_fechaLoadValidation(id);
	});

	$('.e_hora').each( function(){
		var id=$(this).attr('id');
		var minimo=$('#' + id).data('rango').minimo;
		var maximo=$('#' + id).data('rango').maximo;
		minimo=minimo.split(':');
		maximo=maximo.split(':');
		e_horaLoadValidation(id, minimo, maximo);
	});

	$('.e_curp').each( function(){
		var id=$(this).attr('id');
		e_curpLoadValidation(id);
	});

	$('.e_rfc').each( function(){
		var id=$(this).attr('id');
		e_rfcLoadValidation(id);
	});

	$('.e_password').each( function(){
		var id=$(this).attr('id');
		e_passwordLoadValidation(id);
	});

	$(document).delegate('.SaveBack', 'click', function(){
		if($('.InsertBack').is(':checked')){
			$('.InsertBack').prop('checked', false);
		}else{
			$('.InsertBack').prop('checked', true);
		}
	});

	$('select').each(function(){
		$(this).addClass('custom-select');
		if(!$(this).hasClass('no-custom')){
			LoadSelectpicker($(this));
		}
	});
}); // end document.ready
