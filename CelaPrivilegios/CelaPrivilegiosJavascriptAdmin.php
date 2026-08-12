<!-- start: Create Script-->
<link type="text/css" rel="stylesheet" href="bootstrap/css/fixedColumns.dataTables.min.css" />
<script src="bootstrap/js/dataTables.fixedColumns.min.js" type="text/javascript"></script>
<script>
	$('#All').change(function(){
		$('.All').each(function(){
			$(this).prop('checked', $('#All').is(':checked'));
		});
	});

	$('.Privilegio').change(function(){
		var Privilegio = $(this).attr('id');
		$('.' + Privilegio).each(function(){
			$(this).prop('checked', $('#' + Privilegio).is(':checked'));
		});
	});

	$('.Form').change(function(){
		var Formulario=$(this).attr("id");
		$('.Form_' + Formulario).each(function(){
			$(this).prop('checked', $('#' + Formulario).is(':checked'));
		});
	});

	$(function(){
		var TableWidth = $('#TableCelaPrivilegios').width();
		$('.dataTables_scrollHeadInner').css({
			'width': TableWidth
		});

		$('.dataTables_scrollHeadInner table').css({
			'width': TableWidth
		});
	});
</script>
<!-- end: Create Script-->