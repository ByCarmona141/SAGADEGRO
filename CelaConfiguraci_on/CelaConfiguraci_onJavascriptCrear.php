<script>
	$(document ).ready(function(){

		$('#Tipo').change(function(){
			if($('#Tipo').val() == 'select'){
				$('.Referencia').show();
				$('.Referencia').removeAttr('hidden');
			}else{
				$('.Referencia').hide();
				$('#Referencia').val('');
			}
		});
	});

	function ValidaRoles(){
		if($('#Rol').val() == null){
			return false;
		}else{
			return true;
		}
	}
</script>
<!-- end: Create Script-->