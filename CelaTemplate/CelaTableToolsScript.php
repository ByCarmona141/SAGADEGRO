<!-- start: Table Script-->
<script>
	$(document).delegate('.delete<?= $Table; ?>','click',function(e){
		e.preventDefault();
		if(!$(this).is('[disabled]')){
			$('#<?= $Table; ?>Bot_onEliminarAceptar').attr('href', $(this).attr('href'));
			$('#<?= $Table; ?>ModalEliminar').modal('show');

		<?php
			if(isset($EncodeId) && $EncodeId == 1){
		?>
			$.post('AjaxsFunctions', {
				Function: 'Encrypt',
				Require: '../Libraries/Functions.php',
				String: $(this).data('index'),
				Key: <?= "'" . $_COOKIE['CelaRandom'] . "'"; ?>
			},
			function(data){
				$('#<?= $Table; ?>Key').val(data);
			});
		<?php
			}
		?>
		}
	});

	$('#<?= $Table; ?>Bot_onActualizar').click(function(e){
		e.preventDefault();
		if(!$(this).is('[disabled]')){
			location.href= $(this).attr('href');;
		}
	});

	$(document).delegate('.Selected<?= $Table; ?>', 'change', function(){
		GetAllSelected<?= $Table; ?>();
	});

	$(document).delegate('#All<?= $Table; ?>', 'change', function(){
		$('.Selected<?= $Table; ?>').each(function(){
			$(this).prop('checked', $('#All<?= $Table; ?>').is(':checked'));
		});
		GetAllSelected<?= $Table; ?>();
	});

	$(document).delegate('.show_some', 'click', function(){
		/*Obtenemos los datos regstrados en la acción*/
		var Container = $(this).data('container');
		$('#' + Container + 'Body').html('<img src="landing/img/select2-spinner.gif" />');
		$('#' + Container).modal('show');
		$.post('AjaxsFunctions',{
			Function: $(this).data('function'),
			Require: $(this).data('source'),
			Key: $(this).data('index')
		},function(data){
			$('#' + Container + 'Body').html(data);
		});
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
					String:     Get + '&Action=Actualizar'
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
					String:     Get + '&Action=Eliminar'
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