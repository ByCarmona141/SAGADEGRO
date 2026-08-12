<script>
	$(document).delegate('.deleteCelaMessage','click',function(e){
		e.preventDefault();
		if(!$(this).is('[disabled]')){
			$('#CelaMessageBot_onEliminarAceptar').attr('href', $(this).attr('href'));
			$('#CelaMessageModalEliminar').modal('show');
		}
	});

	$('#Table_CelaMessage').on('change', '.SelectedCelaMessage', function(e){
		e.preventDefault();
		if($(this).is(':checked')){
			$(this).parent().parent().parent().parent().addClass('active');
		}else{
			$(this).parent().parent().parent().parent().removeClass('active');
		}
		GetAllSelectedCelaMessage();
	});

	$('#Table_CelaMessage').on('mouseover', '.SelectableCheck', function(e){
		$('#Table_CelaMessage').off('click', '.mailbox-attachment');
	});

	$('#Table_CelaMessage').on('mouseleave', '.SelectableCheck', function(e){
		$('#Table_CelaMessage').on('click', '.mailbox-attachment', function(e){
			var id = $(this).attr('id').replace('CelaMessage', '');
			$.post('AjaxsFunctions', {
				Function : 'EncodeThisAjaxs',
				String : 'Action=VistaPrevia&Key=' + id
			},
			function(data){
				location.href = 'CelaMessage?' + data;
				//console.log('CelaMessage?' + data);
			});
		});
	});

	$(document).delegate('#AllCelaMessage', 'change', function(){
		$('.SelectedCelaMessage').each(function(){
			$(this).prop('checked', $('#AllCelaMessage').is(':checked'));
			if($('#AllCelaMessage').is(':checked')){
				$(this).parent().parent().parent().parent().addClass('active');
			}else{
				$(this).parent().parent().parent().parent().removeClass('active');
			}
		});
		GetAllSelectedCelaMessage();
	});

	function GetAllSelectedCelaMessage(){
		var Get = '', Cont=0;
		$('.SelectedCelaMessage').each(function(){
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
				String:     Get + '&Action=Mover'
			},
			function(data){
				if(Cont < 1){
					var href = '#';
					$('#CelaMessageBot_onMover').attr('disabled', 'disabled');
				}else{
					$('#CelaMessageBot_onMover').removeAttr('disabled');
					$('#CelaMessageBot_onMover').attr('href', 'CelaMessage?' + data);
				}
			});

			$.post('AjaxsFunctions', {
				Function:   'EncodeThisAjaxs',
				String:     Get + '&Action=Eliminar'
			},
			function(data){
				if(Cont < 1){
					var href = '#';
					$('#CelaMessageBot_onEliminar').attr('disabled', 'disabled');
					$('#CelaMessageBot_onEliminarAceptar').attr('href', href);
				}else{
					$('#CelaMessageBot_onEliminar').removeAttr('disabled');
					$('#CelaMessageBot_onEliminar').attr('href', 'CelaMessage?' + data);
				}
			});
		}else{
			var href = '#';
			$('#CelaMessageBot_onActualizar').attr('disabled', 'disabled');
			$('#CelaMessageBot_onEliminar').attr('disabled', 'disabled');
			$('#CelaMessageBot_onEliminarAceptar').attr('href', href);
		}
	}
</script>
<script>
	$(document).ready(function(){
		$('#UpdateMailbox').click(function(e){
			e.preventDefault();
			CelaTable['Table_CelaMessage'].fnFilter();
		});

		$('#Table_CelaMessage').on('click', '.mailbox-attachment', function(e){
			var id = $(this).attr('id').replace('CelaMessage', '');
			$.post('AjaxsFunctions', {
				Function : 'EncodeThisAjaxs',
				String : 'Action=VistaPrevia&Key=' + id
			},
			function(data){
				location.href = 'CelaMessage?' + data;
				//console.log('CelaMessage?' + data);
			});
		});

		$(function(){
			/*Se oculta el menu para tener mas espacio de trabajo*/
			$('body').addClass('sidebar-collapse');

			/* Bootstrap style pagination control */
			$.extend($.fn.dataTableExt.oPagination, {
				'bootstrapmail': {
					'fnInit': function(oSettings, nPaging, fnDraw) {
						var oLang = oSettings.oLanguage.oPaginate;
						var fnClickHandler = function (e) {
							e.preventDefault();
							if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
								fnDraw(oSettings);
							}
						};

						$(nPaging).append(
							'<div class="btn-group" data-intro="Barra de paginaci&oacute;n" data-position="left">'+
							'<button type="button" class="btn btn-default btn-sm prev disabled"><i class="fa fa-chevron-left"></i></button>' +
							'<button type="button" class="btn btn-default btn-sm next disabled"><i class="fa fa-chevron-right"></i></button>' +
							'</div>'
						);
						var els = $('button', nPaging);

						$(els[0]).bind('click.DT', { action: 'previous' }, fnClickHandler);
						$(els[1]).bind('click.DT', { action: 'next' }, fnClickHandler);
					},

					'fnUpdate': function (oSettings, fnDraw) {
						var iListLength = 0;
						var oPaging     = oSettings.oInstance.fnPagingInfo();
						var an          = oSettings.aanFeatures.p;
						var i, ien, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);

						if (oPaging.iTotalPages < iListLength) {
							iStart  = 1;
							iEnd    = oPaging.iTotalPages;
						}else if (oPaging.iPage <= iHalf) {
							iStart  = 1;
							iEnd    = iListLength;
						} else if (oPaging.iPage >= (oPaging.iTotalPages-iHalf)) {
							iStart  = oPaging.iTotalPages - iListLength + 1;
							iEnd    = oPaging.iTotalPages;
						} else {
							iStart  = oPaging.iPage - iHalf + 1;
							iEnd    = iStart + iListLength - 1;
						}

						for (i = 0, ien = an.length; i < ien; i++) {
							// Remove the middle elements
							$('button:gt(1)', an[i]).filter('.middle').remove();

							// Add the new list items and their event handlers
							for (j = iStart; j <= iEnd; j++) {
								sClass = (j == oPaging.iPage + 1) ? 'class="active middle btn btn-default btn-sm"' : 'class="middle btn btn-default btn-sm"';
								$('<button type="button" ' + sClass + '>' + j + '</button>')
									.insertBefore($('button:last', an[i]).prev()[0])
									.bind('click', function (e) {
										e.preventDefault();
										oSettings._iDisplayStart = (parseInt($('button', this).text(), 10) -1) * oPaging.iLength;
										fnDraw(oSettings);
									});
							}
							// Add / remove disabled classes from the static elements
							if (oPaging.iPage === 0) {
								$('button:first', an[i]).addClass('disabled');
								$('button:first', an[i]).next().addClass('disabled');
							} else {
								$('button:first', an[i]).removeClass('disabled');
								$('button:first', an[i]).next().removeClass('disabled');
							}
							if (oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0) {
								$('button:last', an[i]).addClass('disabled');
								$('button:last', an[i]).prev().addClass('disabled');
							} else {
								$('button:last', an[i]).removeClass('disabled');
								$('button:last', an[i]).prev().removeClass('disabled');
							}
						}
					}
				}
			});

			var Options = {
				sDom: '<"row"<"col-md-9"r>><"table-responsive mailbox-messages" t><"row mailbox-controls" <"col-md-12 pull-right"<"col-md-4 text-right"><"col-md-2 text-right"i><"col-md-2 text-left"p><"col-md-4 text-left">>>',
				sPaginationType: 'bootstrapmail',
				iDisplayLength: 25,
				oLanguage: {
					sZeroRecords: 'No se encontrarón mensajes',
					sInfo: '_START_- _END_/_TOTAL_',
					sInfoEmpty: '0-0/0',
					sInfoFiltered: '(de _MAX_ totales)',
					sEmptyTable: 'No se encontrarón mensajes',
					sLoadingRecords: 'Cargando...',
					sProcessing: '<strong>Buscando . . .</strong>',
					sSearch: 'Buscar:&nbsp;',
				},
				aoColumns: [
					{bSortable: false},
					{bSortable: false},
					{bSortable: false},
					{bSortable: false},
					{bSortable: false},
					{bSortable: false}
				],
				bProcessing: true,
				bServerSide: true,
				sAjaxSource: 'DataTableServer.php',
				fnServerData: function(sSource, aoData, fnCallback){
					$('.DataTablePrint').each(function(){
						if($(this).data('table') == 'Table_CelaMessage'){
							$(this).attr('disabled', 'disabled');
						}
					});

					aoData.push({name: 'Function', value: $('#Table_CelaMessage').data('function')});
					aoData.push({name: 'Source', value: $('#Table_CelaMessage').data('source')});
					aoData.push({name: 'RouteForm', value: $('#Table_CelaMessage').data('form')});

					if(typeof($('#Table_CelaMessage').data('params')) !== 'undefined'){
						aoData.push({name: 'Params', value: $('#Table_CelaMessage').data('params')});
					}

					$.ajax({
						'dataType': 'json',
						'type': 'POST',
						'url': sSource,
						'data': aoData,
						'success': function(data){
							if(data == 'EXIT'){
								location.href = 'Salir.php';
							}else{
								fnCallback(data.Response);
								CelaQuery['Table_CelaMessage'] = data.Response.Query;

								if(data.Response['iTotalDisplayRecords'] == 0){
									$('.DataTablePrint').each(function(){
										if($(this).data('table') == 'Table_CelaMessage'){
											$(this).attr('disabled', 'disabled');
										}
									});
								}else{
									$('.DataTablePrint').each(function(){
										if($(this).data('table') == 'Table_CelaMessage'){
											$(this).removeAttr('disabled');
										}
									});
								}
							}
						},
						error: function(e, i){
							//console.log(e);
							if(e['ResponseText'] == 'EXIT'){
								location.href = 'Salir.php';
							}else{
								var Out = '';
								for(var i in e){
									Out += i + ': ' + e[i] + '\n';
								}
								console.log(Out);
							}
						}
					});
				}
			}

			LoadDataTable('Table_CelaMessage', Options);

			//LoadDataTablev2('Table_CelaMessage', [{'bSortable' :false}, {'bSortable' :false}, {'bSortable' :false}, {'bSortable' :false}, {'bSortable' :false}, {'bSortable' :false}], 20, , 'bootstrapmail', '_START_-_END_/_TOTAL_');
		});
	});
</script>