<script>
	$(document).ready(function(){
		$('#PrintMessage').on('click', function(e){
			e.preventDefault();
			var Options = { mode : 'iframe', popClose : true };
			$('#MessageArea').printArea(Options);
		});

		$(document).on('click', '#DeleteMessage', function(e){
			e.preventDefault();
			if(!$(this).is('[disabled]')){
				$('#CelaMessageBot_onEliminarAceptar').attr('href', $(this).attr('href'));
				$('#CelaMessageModalEliminar').modal('show');
			}
		});
	});
</script>