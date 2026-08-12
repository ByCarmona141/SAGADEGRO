<?php

	/*Ventana emergente para captura del nuevo cliente vendedor*
?>
	<script>
		$(document).ready(function(){
			$('#EmpleadoActualiza').click(function(){
				/*Se actualizan los Inmuebles* /
				refreshEmpleados();
			});

			$('#EmpleadoCrear').click(function(){
				/*Se abre la ventana para crear nuevo Inmueble* /
				//onclick="window.open(\'Inmueble?' . EncodeThis('Action=Crear') . '\', \'\', \');"
				$.cookie('HiddenMenu', 1);

				var myWindow = ShowWindowsPopup('Empleado?<?= EncodeThis('Action=Crear&OnSave=Close'); ?>', 640, 1000);

				var popupTick = setInterval(function() {
					if (myWindow.closed) {
						clearInterval(popupTick);
						$.cookie('HiddenMenu', 0);
						refreshEmpleados();
					}
				}, 500);
			});
		});

		function refreshEmpleados(){
			$.post('AjaxsFunctions.php', {
				'Function': 'EmpleadoGetData',
				'Key': '1',
				'Filter' : '1',
				'Require': '../Empleado/Empleado.php'
			}, function(data){
				var Items = '';
				if(typeof data.Data !== 'undefined'){
					for(var r = 0; r < data.Data.length; r++){
						Items += '<option value="' + data.Data[r]['id'] + '">' + data.Data[r]['NoEmpleado'] + ' - ' + data.Data[r]['NombreORaz_onSocial'] + ' - ' + (data.Data[r]['Tel_efonoCelular'] != null ? data.Data[r]['Tel_efonoCelular']:'') + (data.Data[r]['RFC'] != null ?  ' | ' + data.Data[r]['RFC']:'') + (data.Data[r]['Tel_efonoCelular'] != null ?  ' | ' + data.Data[r]['Tel_efonoCelular']:'') + '</option>';
					}

					Items += '<option value="">SIN EMPLEADO ASIGNADO</option>';
					$('#Empleado').html(Items);
					$('#Empleado').selectpicker('refresh');
				}
			}, 'json');
		}
	</script>

<?php

//<!-- start: Create Script-->
//<link type="text/css" rel="stylesheet" href="bootstrap/css/jquery.multiselect.filter.css" />
//<link type="text/css" rel="stylesheet" href="bootstrap/css/jquery.multiselect.css" />
//
//<script src="bootstrap/js/jquery.multiselect.filter.js" type="text/javascript"></script>
//<script src="bootstrap/js/jquery.multiselect.js" type="text/javascript"></script>
//<script>
//	$(document ).ready(function(){
////		$('#Proyecto').multiselect({
////			checkAllText: 'Todos',
////			uncheckAllText: 'Ninguno',
////			//header: '',
////			noneSelectedText: 'Proyectos asignadas al usuario',
////			selectedText: 'Proyectos asignadas al usuario',
////			classesBtn: 'form-control SelectPrivilege',
////			selectedList: 1,
////			validate: true,
////			classvalid: ''
////		}).multiselectfilter({
////			label: 'Buscar',
////			placeholder: 'Teclee el proyecto a Buscar'
////		});
//
////		$(function(){
////			$("#Proyecto").multiselect("uncheckAll");
////		});
////
////		$('#Almac_en').multiselect({
////			checkAllText: 'Todos',
////			uncheckAllText: 'Ninguno',
////			//header: '',
////			noneSelectedText: 'Almacenes asignados al usuario',
////			selectedText: 'Almacenes asignados al usuario',
////			classesBtn: 'form-control SelectPrivilege',
////			selectedList: 1,
////			validate: true,
////			classvalid: ''
////		}).multiselectfilter({
////			label: 'Buscar',
////			placeholder: 'Teclee el almacén a Buscar'
////		});
////
////		$('#Empleado').multiselect({
////			checkAllText: 'Todos',
////			multiple: false,
////			uncheckAllText: 'Ninguno',
////			//header: '',
////			noneSelectedText: 'Empleado asignado al usuario',
////			selectedText: 'Empleado asignado al usuario',
////			classesBtn: 'form-control SelectPrivilege',
////			selectedList: 1,
////			validate: true,
////			classvalid: ''
////		}).multiselectfilter({
////			label: 'Buscar',
////			placeholder: 'Teclee el Empleado a Buscar'
////		});
////
////		$(function(){
////			$("#Almac_en").multiselect("uncheckAll");
////		});
//
//
//	});
//</script>
//<!-- end: Create Script-->*/ ?>