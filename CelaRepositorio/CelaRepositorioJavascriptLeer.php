<!-- start: Table Script-->
<script>
    $(document).ready(function(){
        $('#FiltraNombre').change(function (){
            CelaTable['Table_CelaRepositorio'].fnFilter($('#FiltraNombre').val(), 1);
        });

        $('#FechaCreacion').change(function (){
            CelaTable['Table_CelaRepositorio'].fnFilter($('#FechaCreacion').val(), 4);
        });
    });
    
	$(document).delegate('.delete<?= $Table; ?>','click',function(e){
		e.preventDefault();
		if(!$(this).is('[disabled]')){
			$('#<?= $Table; ?>Bot_onEliminarAceptar').attr('href', $(this).attr('href'));
			$('#<?= $Table; ?>ModalEliminar').modal('show');
		}
	});

	$(document).delegate('.Selected<?= $Table; ?>', 'change', function(){
		GetAllSelected<?= $Table; ?>();
	});

	$('#All<?= $Table; ?>').change(function(){
		$('.Selected<?= $Table; ?>').each(function(){
			$(this).prop('checked', $('#All<?= $Table; ?>').is(':checked'));
		});
		GetAllSelected<?= $Table; ?>();
	});

	function GetAllSelected<?= $Table; ?>(){
		var Get = '', Cont=0;
		$('.Selected<?= $Table; ?>').each(function(){
			if($(this).is(':checked')){
				var id  = $(this).data('index');
				Get += 'Key[]='+id+'&';
				Cont++;
			}
		});

		Get = Get.substring(0, Get.length - 1);
		if(Get != ''){
			$.post('AjaxsFunctions', {
					Function:   'EncodeThisAjaxs',
					String:     Get + '&Action=Actualizar&<?= (isset($Params['Source']) && $Params['Source'] != '' ? 'Source=' . $Params['Source']:''); ?>&<?= (isset($Params['Tupla']) && $Params['Tupla'] != '' ? 'Tupla=' . $Params['Tupla']:''); ?>'
				},
				function(data){
					if(Cont < 2){
						var href = '#';
						$('#<?= $Table; ?>Bot_onActualizar').attr('disabled', 'disabled');
					}else{
						$('#<?= $Table; ?>Bot_onActualizar').removeAttr('disabled');
						$('#<?= $Table; ?>Bot_onActualizar').attr('href', '<?= $Table; ?>?' + data);
					}
				});

			$.post('AjaxsFunctions', {
					Function:   'EncodeThisAjaxs',
					String:     Get + '&Action=Eliminar&<?= (isset($Params['Source']) && $Params['Source'] != '' ? 'Source=' . $Params['Source']:''); ?>&<?= (isset($Params['Tupla']) && $Params['Tupla'] != '' ? 'Tupla=' . $Params['Tupla']:''); ?>'
				},
				function(data){
					if(Cont < 2){
						var href = '#';
						$('#<?= $Table; ?>Bot_onEliminar').attr('disabled', 'disabled');
						$('#<?= $Table; ?>Bot_onEliminarAceptar').attr('href', href);
					}else{
						$('#<?= $Table; ?>Bot_onEliminar').removeAttr('disabled');
						$('#<?= $Table; ?>Bot_onEliminar').attr('href', '<?= $Table; ?>?' + data);
					}
				});
		}else{
			var href = '#';
			$('#<?= $Table; ?>Bot_onActualizar').attr('disabled', 'disabled');
			$('#<?= $Table; ?>Bot_onEliminar').attr('disabled', 'disabled');
			$('#<?= $Table; ?>Bot_onEliminarAceptar').attr('href', href);
		}
	}
</script>
<!-- end: Table Script-->
