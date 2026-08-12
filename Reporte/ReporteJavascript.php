<script>
	$(document).ready(function(){
		$('.ComboSearch').selectpicker({
			'selectedText': 'cat',
			'iconBase': 'fa ',
			'tickIcon': 'fa fa-check',
			'actionsBox': 'true',
			'selectAllText': '<i class="fa fa-check"></i>&nbsp; Todos',
			'deselectAllText': '<i class="fa fa-times"></i>&nbsp; Ninguno &nbsp;',
			'selectedTextFormat': 'static',
			'noneSelectedText': 'Ningun elemento seleccionado',
			'selectedTextFormat': 'count',
			'countSelectedText': '{0} de {1} elementos seleccionados'
		});

		$('.ComboPuesto').selectpicker({
			'selectedText': 'cat',
			'iconBase': 'fa ',
			'tickIcon': 'fa fa-check',
			'maxOptions': 1
		});

		$('#Generate').click(function(e){
			e.preventDefault();
			var form = $('#<?= $Form_id; ?>');
			form.validate();
			if (form.valid()){
		<?php
			if(get_browser_() == 'FIREFOX' || get_browser_() == 'CHROME'){
		?>
				$('#VistaPrevia').load(function() {
					$('#DownLoadXLSDiv').show();
					$('#Generate').removeAttr('disabled');
					$('#ProgressContent').attr('hidden', 'hidden');

					if($('#Finiquito').val() == 0){
						$('#BajaEmpleado').hide();
					}else{
						$('#BajaEmpleado').show();
					}
				});
		<?php
			}else{
		?>
				setTimeout(function(){
					$('#DownLoadXLSDiv').show();
					$('#Generate').removeAttr('disabled');
					$('#ProgressContent').attr('hidden', 'hidden');

					if($('#Finiquito').val() == 0){
						$('#BajaEmpleado').hide();
					}else{
						$('#BajaEmpleado').show();
					}
				}, '5000');
		<?php
			}
		?>
				$('#XLSFile').val('ReporteXLS' + generateUUID('xxxx_yyyy_xxxx_yyyy') + '.html');
				$('#idN_omina').val(generateUUID('xxxx-yyyy-xxxx-yyyy'));
				$('#idReporte').val(generateUUID('xxxx-yyyy-xxxx-yyyy'));

				$.post('AjaxsFunctions.php', {
					Function: 'EncodeThisAjaxs',
					String: 'Action=Descargar&Key=repositorio/temporal/' + $('#XLSFile').val()
				}, function(data) {
					$('#DownLoadXLS').attr('href','CelaRepositorio?' + data);
				});

				$('#<?= $Form_id; ?>').submit();

		<?php
			if(get_browser_() == 'FIREFOX' || get_browser_() == 'CHROME'){
		?>
				$('#ProgressMessage').html('Procesando Archivo...<br />Esto podr&iacute;a tardar algunos minutos, por favor tenga paciencia');
				$('#Generate').attr('disabled','disabled');
				$('#ProgressContent').removeAttr('hidden');
				$('#Progress').css({'width': '100%'});
		<?php
			}else{
		?>
				$('#ProgressMessage').html('Procesando Archivo...<br />Esto podr&iacute;a tardar algunos minutos, por favor tenga paciencia. El reporte ser&aacute; enviado a descargas, por favor este pendiente de su descarga');
				$('#Generate').attr('disabled','disabled');
				$('#ProgressContent').removeAttr('hidden');
				$('#Progress').css({'width': '100%'});
		<?php
			}
		?>
			}
		});

		$('#SaveN_omina').click(function(e){
			e.preventDefault();
			/*Se actualiza el Status de la nomina*/
			$.post('AjaxsFunctions.php', {
				'Function': 'UpdateValue',
				'Table': 'N_omina',
				'ArgsUpdate': {
					'Status': '1'
				},
				'KeyUpdate': {
					'UUID': '"' + $('#idN_omina').val() + '"'
				}
			},
			function(data){
				if(data.Status == 'OK'){
					/*Se actualiza la nomina del empleado*/
					LoadMessage('CELAMessageModal', 'Exito', 'El reporte se ha guardando como nómina.');
				}else{
					LoadMessage('CELAMessageModal', 'Error', 'Ocurrio un error guardando la nómina.');
				}
			}, 'json');
		});

		$('#BajaEmpleado').click(function(e){
			e.preventDefault();
			/*Se dan de baja los empleados*/
			$.post('AjaxsFunctions.php', {
				'Require': '../Empleado/Empleado.php;../Contrato/Contrato.php',
				'Function': 'EmpleadoBajaEliminar',
				'Key': $('#idReporte').val(),
				'Fecha': $('#FechaDePago').val()
			},
			function(data){
				if(data.Status == 'OK'){
					LoadMessage('CELAMessageModal', 'Exito', 'Los empleados se dierón de baja correctamente..');
				}else{
					LoadMessage('CELAMessageModal', 'Error', 'Ocurrio un error dando de baja a los empleados: ' + data.Error);
				}
			}, 'json');
		});

		$('#PuestoEmpleado').change(function(){
			var Puesto  = '';
			var Puestos = parseInt($('#PuestoEmpleado').val());

			/*Se seleccionan todos los empleados del puesto seleccionado*/
			$('.PuestoEmpleado').each(function(){
				Puesto = parseInt($(this).data('puesto'));

				if(Puestos == Puesto){
					$(this).attr('selected', 'selected');
				}else{
					$(this).removeAttr('selected');
				}
			});

			$('#Empleado').selectpicker('refresh');
		});

			$('#ComboProyecto').change(function(){
				/*Valor de la caja de texto*/
				var ValorBusqueda = $(this).val();
				/*Posición en la que se encuentra la columna donde se realizara la búsqueda*/
				/*Los indicen comienzan en el cero "0"*/
				var Indice = 2;
				/*Se invoca la función de búsqueda*/
				CelaTable['Table_N_omina'].fnFilter(ValorBusqueda, Indice);
			});

			$('#ComboTipo').change(function(){
				/*Valor de la caja de texto*/
				var ValorBusqueda = $(this).val();
				/*Posición en la que se encuentra la columna donde se realizara la búsqueda*/
				/*Los indicen comienzan en el cero "0"*/
				var Indice = 5;
				/*Se invoca la función de búsqueda*/
				CelaTable['Table_N_omina'].fnFilter(ValorBusqueda, Indice);
			});

	});
</script>