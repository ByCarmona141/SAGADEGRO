<?php /*<link type="text/css" rel="stylesheet" href="bootstrap/css/jquery.multiselect.filter.css" />
<link type="text/css" rel="stylesheet" href="bootstrap/css/jquery.multiselect.css" />
<script src="bootstrap/js/jquery.multiselect.filter.js" type="text/javascript"></script>
<script src="bootstrap/js/jquery.multiselect.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		$('#Fecha').change(function(){
			var valor='';
			var value='';
			$('#Fecha').multiselect('getChecked').each(function(){
				valor += $(this).val() + '/@&/';
				value += $(this).data('value') + "','";
			});
			valor=valor.substring(0, valor.length - 4);
			value=value.substring(0, value.length - 3);

			/*Filtramos los datos de la tabla* /
			CelaTable['Table_CelaAcceso'].fnFilter(valor, 0);
		})
//			.multiselect({
//			checkAllText: 'Todo',
//			uncheckAllText: 'Nada',
//			//header: '',
//			noneSelectedText: 'Fecha/Mes',
//			selectedText: 'Fecha/Mes',
//			classesBtn: 'form-control input-sm'
//		});

		$('#Usuario').change(function(){
			var valor = '';
			var value = '';
			$('#Usuario').multiselect('getChecked').each(function(){
				valor += $(this).val() + '/@&/';
				value += $(this).data('value') + "','";
			});
			valor=valor.substring(0, valor.length - 4);
			value=value.substring(0, value.length - 3);

			/*Filtramos los datos de la tabla* /
			CelaTable['Table_CelaAcceso'].fnFilter(valor, 1);
		})
//			.multiselect({
//			checkAllText: 'Todo',
//			uncheckAllText: 'Nada',
//			noneSelectedText: 'Usuario',
//			selectedText: 'Usuario',
//			classesBtn: 'form-control input-sm'
//		}).multiselectfilter({
//			label: 'Buscar',
//			placeholder: 'Teclee el Usuario a Buscar'
//		});

		$('#Origen').change(function(){
			var valor = '';
			var value = '';
			$('#Origen').multiselect('getChecked').each(function(){
				valor += $(this).val() + '/@&/';
				value += $(this).data('value') + "','";
			});
			valor=valor.substring(0, valor.length - 4);
			value=value.substring(0, value.length - 3);

			/*Filtramos los datos de la tabla* /
			CelaTable['Table_CelaAcceso'].fnFilter(valor, 2);
		})
//			.multiselect({
//			checkAllText: 'Todo',
//			uncheckAllText: 'Nada',
//			noneSelectedText: 'Tabla',
//			selectedText: 'Tabla',
//			classesBtn: 'form-control input-sm'
//		}).multiselectfilter({
//			label: 'Buscar',
//			placeholder: 'Teclee la Tabla a Buscar'
//		});

		$('#Accion').change(function(){
			var valor = '';
			var value = '';
			$('#Accion').multiselect('getChecked').each(function(){
				valor += $(this).val() + '/@&/';
				value += $(this).data('value') + "','";
			});
			valor=valor.substring(0, valor.length - 4);
			value=value.substring(0, value.length - 3);

			/*Filtramos los datos de la tabla* /
			CelaTable['Table_CelaAcceso'].fnFilter(valor, 4);
		})
//			.multiselect({
//			checkAllText: 'Todo',
//			uncheckAllText: 'Nada',
//			noneSelectedText: 'Accion',
//			selectedText: 'Accion',
//			classesBtn: 'form-control input-sm'
//		}).multiselectfilter({
//			label: 'Buscar',
//			placeholder: 'Teclee la Accion a Buscar'
//		});

		$('#Registro').change(function(){
			/*Filtramos los datos de la tabla* /
			CelaTable['Table_CelaAcceso'].fnFilter($(this ).val(), 3);
		});
*/?><script>
		$('#Table_CelaAcceso').delegate('.show_record', 'click', function(){
			/*Obtenemos los datos regstrados en la acción*/
			$('#CelaAccesoRecordModalBody').html('<img src="landing/img/select2-spinner.gif" />');
			$('#CelaAccesoRecordModal').modal('show');
			$.post('AjaxsFunctions',{
				Function: 'GetValueAjaxs',
				Query: 'SELECT Datos FROM CelaAcceso WHERE id = ' + $(this).data('index'),
				Value: 'Datos'
			},function(data){
				$('#CelaAccesoRecordModalBody').html('<pre>' + JSON.stringify(eval('(' + data + ')'), undefined, 2) + '</pre>');
				//console.log(data);
			});
		});

		<?php /*$(function(){
			$('#Fecha').multiselect('uncheckAll');
			$('#Usuario').multiselect('uncheckAll');
			$('#Tabla').multiselect('uncheckAll');
			$('#Accion').multiselect('uncheckAll');
		});*/ ?>

</script>