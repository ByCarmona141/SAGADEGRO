<script >
	$(document ).ready(function(){
		// $('#Icono').selectpicker({
		// 	'selectedText': 'cat',
		// 	'iconBase': 'fa ',
		// 	'tickIcon': 'fa fa-check'
		// });

		$('#TipoDeElemento').change(function(){
			if($(this).val() == 1){
				/*Categoria de opciones*/
				$('#ReferenciaDiv').hide();
				$('#Referencia').val('#');
				
				$('#EtiquetaDiv').show();
				$('#Descripci_onDiv').show();
				
				$('#IconoDiv').show();
				$('#RolesDiv').show();
				
				$('#ArchivoDiv').hide();
				var btn = $('#Archivo').prev('button');
				$(btn).trigger('click');
			}else{
				if($(this).val() == 3){
					/*Separador*/
					$('#EtiquetaDiv').hide();
					$('#Etiqueta').val('_');

					$('#Descripci_onDiv').hide();
					$('#Descripci_on').val('_');

					$('#ReferenciaDiv').hide();
					$('#Referencia').val('#');

					$('#IconoDiv').hide();
					$('#Icono option[value=1]').attr('selected','selected');

					$('#RolesDiv').show();
					$('#Roles option[value=all]').attr('selected','selected');

					$('#ArchivoDiv').hide();
					var btn = $('#Archivo').prev('button');
					$(btn).trigger('click');
				}else{
					if($(this).val() == 5){
						/*Referencia a Archivo*/
						$('.form-group').show();
						$('#ReferenciaDiv').hide();
						$('#Referencia').val('#');
					}else{
						$('.form-group').show();
						$('#ArchivoDiv').hide();
						var btn = $('#Archivo').prev('button');
						$(btn).trigger('click');
					}

				}
			}
		});
	});

	function ValidaIcono(){
		if($('#Icono' ).val() == ''){
			return false;
		}else{
			return true;
		}
	}

	function ValidaRoles(){
		if($('#Rol').val() == null){
			return false;
		}else{
			return true;
		}
	}
</script>
<!-- end: Create Script-->
