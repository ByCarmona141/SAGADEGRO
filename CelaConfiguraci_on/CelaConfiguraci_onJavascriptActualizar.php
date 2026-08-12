<script>
	$(document ).ready(function(){
<?php
	foreach($_GET['Key'] as $Key){
?>
		$('#Tipo<?= $Key; ?>').change(function(){
			if($('#Tipo<?= $Key; ?>').val() == 'select'){
				$('.Referencia<?= $Key; ?>').show();
				$('.Referencia<?= $Key; ?>').removeAttr('hidden');
			}else{
				$('.Referencia<?= $Key; ?>').hide();
				$('#Referencia<?= $Key; ?>').val('');
			}
		});
<?php
	}
?>
	});

	function ValidaRoles(){
		if($('.Rol').val() == null){
			return false;
		}else{
			return true;
		}
	}
</script>
<!-- end: Create Script-->