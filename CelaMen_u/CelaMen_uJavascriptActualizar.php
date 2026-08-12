<script>
	$(document).ready(function(){
<?php
	foreach($_GET['Key'] as $Valor){
?>
		//$('#Icono<?//= $Valor; ?>//').selectpicker({
		//	'selectedText': 'cat',
		//	'iconBase': 'fa ',
		//	'tickIcon': 'fa fa-check'
		//});


		$('#TipoDeElemento<?= $Valor; ?>').change(function(){
			if($(this).val() == 1){
				/*Categoria de opciones*/
				$('#ReferenciaDiv<?= $Valor; ?>').hide();
				$('#Referencia<?= $Valor; ?>').val('#');
				
				$('#EtiquetaDiv<?= $Valor; ?>').show();
				$('#Descripci_onDiv<?= $Valor; ?>').show();
				
				$('#IconoDiv<?= $Valor; ?>').show();
				$('#RolesDiv<?= $Valor; ?>').show();
				
				$('#ArchivoDiv<?= $Valor; ?>').hide();
				var btn = $('#Archivo<?= $Valor; ?>').prev('button');
				$(btn).trigger('click');
			}else{
				if($(this).val() == 3){
					/*Separador*/
					$('#EtiquetaDiv<?= $Valor; ?>').hide();
					$('#Etiqueta<?= $Valor; ?>').val('_');

					$('#Descripci_onDiv<?= $Valor; ?>').hide();
					$('#Descripci_on<?= $Valor; ?>').val('_');

					$('#ReferenciaDiv<?= $Valor; ?>').hide();
					$('#Referencia<?= $Valor; ?>').val('#');

					$('#IconoDiv<?= $Valor; ?>').hide();
					$('#Icono<?= $Valor; ?> option[value=1]').attr('selected','selected');

					$('#RolesDiv<?= $Valor; ?>').show();
					$('#Roles<?= $Valor; ?> option[value=all]').attr('selected','selected');

					$('#ArchivoDiv<?= $Valor; ?>').hide();
					var btn = $('#Archivo<?= $Valor; ?>').prev('button');
					$(btn).trigger('click');
				}else{
					if($(this).val() == 5){
						/*Referencia a Archivo*/
						$('.form-group<?= $Valor; ?>').show();
						$('#ReferenciaDiv<?= $Valor; ?>').hide();
						$('#Referencia<?= $Valor; ?>').val('#');
					}else{
						$('.form-group<?= $Valor; ?>').show();
						$('#ArchivoDiv<?= $Valor; ?>').hide();
						var btn = $('#Archivo<?= $Valor; ?>').prev('button');
						$(btn).trigger('click');
					}

				}
			}
		});
<?php
	}
?>
	});

//	function ValidaIcono(){
//		if($('.Icono').val() == ''){
//			return false;
//		}else{
//			return true;
//		}
//	}
//
//	function ValidaRoles(){
//		if($('.Rol').val() == null){
//			return false;
//		}else{
//			return true;
//		}
//	}
</script>
<!-- end: Create Script-->
