/* ---------- Additional functions for data table ---------- */
var CelaTable=[];
var CelaQuery=[];

function LoadDataTable(TableId, Sorting, RecordLength, Privileges){
	if(typeof Privileges === 'undefined'){
		Privileges = 'GetPrivileges'
	}
	if(typeof($('#' + TableId).data('source')) == 'undefined'){
		if(typeof($('#' + TableId).data('options')) == 'undefined'){
			Options = {
				'sDom': '<"row"<"col-md-12"r>><"row" <"col-md-12 overflow table-responsive" t>><"row text-center " <"col-sm-6 text-left"i><"col-sm-6 text-right"p>>',
				'sPaginationType': 'bootstrap',
				'iDisplayLength': RecordLength,
				'oLanguage': {
					'sZeroRecords': 'No se encontrarón registros',
					'sInfo': 'Mostrando _START_ a _END_ de _TOTAL_ registros',
					'sInfoEmpty': 'Mostrando 0 a 0 de 0 registros',
					'sInfoFiltered': '(filtrado de _MAX_ registros totales)',
					'sEmptyTable': 'No se encontrarón registros',
					'sLoadingRecords': 'Cargando...',
					'sProcessing': '<strong>Procesando . . .</strong>',
					'sSearch': 'Buscar:&nbsp;',
					'oPaginate': {
						'sFirst': '1&ordf; Pag.',
						'sLast': 'Ultima.',
						'sNext': 'Sig. &raquo;',
						'sPrevious': '&laquo; Ant.'
					}
				},
				'sScrollY'          : '480px',
				'sScrollX'          : '640px',
				'bScrollCollapse'   : true,
				'aoColumns': Sorting,
				'order': []
			};
		}else{
			Options = $('#' + TableId).data('options');
		}
	}else{
		if(typeof($('#' + TableId).data('options')) == 'undefined'){
			Options = {
				'sDom': '<"row text-center " r><"row" <"col-md-12 overflow table-responsive" t>><"row text-center " <"col-sm-6 text-left"i><"col-sm-6 text-right"p>>',
				'sPaginationType': 'bootstrap',
				'iDisplayLength' : RecordLength,
				'oLanguage': {
					'sZeroRecords': 'No se encontrarón registros',
					'sInfo': 'Mostrando _START_ a _END_ de _TOTAL_ registros',
					'sInfoEmpty': 'Mostrando 0 a 0 de 0 registros',
					'sInfoFiltered': '(filtrado de _MAX_ registros totales)',
					'sEmptyTable':'No se encontrarón registros',
					'sLoadingRecords':'Cargando...',
					'sProcessing':'<strong>Procesando . . .</strong>',
					'sSearch':'Buscar:&nbsp;',
					'oPaginate':{
						'sFirst':'1&ordf; Pag.',
						'sLast':'Ultima.',
						'sNext':'Sig. &raquo;',
						'sPrevious':'&laquo; Ant.'
					}
				},
				'sScrollY'          : '480px',
				'sScrollX'          : '640px',
				'bScrollCollapse'   : true,
				'aoColumns': Sorting,
				'bProcessing': true,
				'bServerSide': true,
				'sAjaxSource': 'DataTableServer.php',
				'fnServerData': function (sSource, aoData, fnCallback) {
					$('.DataTablePrint').each(function(){
						if($(this).data('table') == TableId){
							$(this).attr('disabled','disabled');
						}
					});
					aoData.push({name: 'Function', value: $('#' + TableId).data('function')});
					aoData.push({name: 'Source', value: $('#' + TableId).data('source')});
					aoData.push({name: 'RouteForm', value: $('#' + TableId).data('form')});
					aoData.push({name: 'Privileges', value: Privileges});

					if(typeof($('#' + TableId).data('params')) != 'undefined'){
						aoData.push({name: 'Params', value: $('#' + TableId).data('params')});
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
								CelaQuery[TableId] = data.Response.Query;

								if(data.Response['iTotalDisplayRecords'] == 0){
									$('.DataTablePrint').each(function(){
										if($(this).data('table') == TableId){
											$(this).attr('disabled', 'disabled');
										}
									});
								}else{
									$('.DataTablePrint').each(function(){
										if($(this).data('table') == TableId){
											$(this).removeAttr('disabled');
										}
									});
								}
							}
						},
						error:function(e,i){
							//console.log(e);
							if(e['ResponseText'] == 'EXIT'){
								location.href = 'Salir.php';
							}else{
								var Out = '';
								for (var i in e) {
									Out += i + ': ' + e[i] + '\n';
								}
								console.log(Out);
							}
						}
					});
				},
				'order': []
			}
		}else{
			Options = $('#' + TableId).data('options');
		}
	}
	/*console.log(Options);
	 console.log(Sorting);*/
	AuxTable = $('#' + TableId).dataTable(Options);
	CelaTable[TableId]=(AuxTable);
	CelaQuery[TableId]='';
	$('#' + TableId ).css({
		'width' : '100%'
	});

	/* Processing message style */
	$('.dataTables_processing').each(function(){
		var $content    = $(this).parent();
		var Id          = $(this).attr('id');
		//console.log(Id);


		$(this).addClass('alert alert-info alert-dismissible fade show text-center');
		$(this).removeClass('card');

		$(this).css({
			'top': function (){
				return (($content.height() / 2) - ($content.height() / 2) + 300) + 'px';
			},
			'left': function (){
				return (($content.width() / 2) - ($(this).width() / 2)) + 'px';
			}
		});
	});

}

/* Default class modification */
$.extend($.fn.dataTableExt.oStdClasses, {
	'sWrapper'  : 'dataTables_wrapper form-inline',
	'sFilter'   : 'form-group',
	'sLength'   : 'form-group'
});

/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings){
	return {
		'iStart'            : oSettings._iDisplayStart,
		'iEnd'              : oSettings.fnDisplayEnd(),
		'iLength'           : oSettings._iDisplayLength,
		'iTotal'            : oSettings.fnRecordsTotal(),
		'iFilteredTotal'    : oSettings.fnRecordsDisplay(),
		'iPage'             : oSettings._iDisplayLength === -1 ? 0:Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
		'iTotalPages'       : oSettings._iDisplayLength === -1 ? 0:Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
	};
};

/*$.extend($.fn.dataTable.defaults, {
	'bLengthChange': false,
	'bFilter': false,
	'iSortCol_0': 3,
	'sSortDir_0': 'asc'
});*/

/* Bootstrap style pagination control */
$.extend($.fn.dataTableExt.oPagination, {
	'bootstrap': {
		'fnInit': function(oSettings, nPaging, fnDraw) {
			var oLang = oSettings.oLanguage.oPaginate;
			var fnClickHandler = function (e) {
				e.preventDefault();
				if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
					fnDraw(oSettings);
				}
			};

			$(nPaging).append(
				'<ul class="pagination" data-intro="Barra de paginaci&oacute;n" data-position="left">'+
					'<li class="btn btn-xs-pag first disabled"><a href="#">' + oLang.sFirst + '</a></li>' +
					'<li class="btn btn-xs-pag prev disabled"><a href="#">' + oLang.sPrevious + '</a></li>' +
					'<li class="btn btn-xs-pag next disabled"><a href="#">' + oLang.sNext + ' </a></li>' +
					'<li class="btn btn-xs-pag last disabled"><a href="#">' + oLang.sLast + '</a></li>' +
				'</ul>'
			);
			var els = $('a', nPaging);
			$(els[0]).bind('click.DT', { action: 'first' }, fnClickHandler);
			$(els[1]).bind('click.DT', { action: 'previous' }, fnClickHandler);
			$(els[2]).bind('click.DT', { action: 'next' }, fnClickHandler);
			$(els[3]).bind('click.DT', { action: 'last' }, fnClickHandler);
		},
		
		'fnUpdate': function (oSettings, fnDraw) {
			var iListLength = 3;
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
				$('li:gt(1)', an[i]).filter('.middle').remove();
				
				// Add the new list items and their event handlers
				for (j = iStart; j <= iEnd; j++) {
					sClass = (j == oPaging.iPage + 1) ? 'class="btn btn-xs-pag  active middle"' : 'class="btn btn-xs-pag middle"';
					$('<li ' + sClass + '><a href="#">' + j + '</a></li>')
						.insertBefore($('li:last', an[i]).prev()[0])
							.bind('click', function (e) {
								e.preventDefault();
								oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) -1) * oPaging.iLength;
								fnDraw(oSettings);
							});
				}
				// Add / remove disabled classes from the static elements
				if (oPaging.iPage === 0) {
					$('li:first', an[i]).addClass('disabled');
					$('li:first', an[i]).next().addClass('disabled');
				} else {
					$('li:first', an[i]).removeClass('disabled');
					$('li:first', an[i]).next().removeClass('disabled');
				}
				if (oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0) {
					$('li:last', an[i]).addClass('disabled');
					$('li:last', an[i]).prev().addClass('disabled');
				} else {
					$('li:last', an[i]).removeClass('disabled');
					$('li:last', an[i]).prev().removeClass('disabled');
				}
			}
		}
	}
});

$(function(){
	/*Cookie menu hidden*/
	if(typeof $.cookie('HiddenMenu') == 'undefined'){
		$.cookie('HiddenMenu', 0);
	}
	
	if($.cookie('HiddenMenu') == 1){
		$('#MainContainer').removeClass('col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2').addClass('col-sm-11 col-md-12');
			$('.sidebar').hide();
	}
	
	if(! $('#nav-container').length){
		$('#toggle-min').hide();
	}
	
	/* Initialise the DataTable */
	$('.datatable').each(function(){
		var Sorting = [];
		var Options = {};
		var TableId = $(this).attr('id');
		
		var Complex = 0;
		$('.ComplexHeader' + TableId).each(function(){
			if($(this).hasClass('sortable'))
				Sorting.push(null);
			else
				Sorting.push({'bSortable':false});
			/* */
			Complex++;
		});

		if(Complex == 0){
			$('#' + TableId + ' thead th').each(function(){
				if($(this).hasClass('sortable'))
					Sorting.push(null);
				else
					Sorting.push({'bSortable':false});
			});
		}
		var AuxTable        = null;
		var RecordLength    = 0;
		if(typeof($('#' + TableId).data('record_length')) == 'undefined'){
			RecordLength = 20;
		}else{
			RecordLength = $('#' + TableId).data('record_length');
		}

		LoadDataTable(TableId, Sorting, RecordLength);
	});

	/* Row hover Options */
	$(document).delegate('.dataTableRow','mouseover', function(){
		var id = $(this).attr('id');
		$('#Actions_' + id).show();
	});

	$(document).delegate('.dataTableRow','mouseleave', function(){
		var id = $(this).attr('id');
		$('#Actions_' + id).hide();
	});

	$(document).delegate('.dataTableRow','dblclick', function(){
		var id          = $(this).attr('id');
		var children    = $('#' + id + ' :input[type=checkbox]');
		if($(children).is(':checked'))
			$(children).attr('checked','checked');
		else
			$(children).removeAttr('checked');
			
		$(children).trigger('change');
		$(children).trigger('click');
	});
	
	/* Filter filter if use custom filter*/
	/*$('.DataTableFilter').each(function(){
		var id      = $(this).attr('id');
		var Table   = $(this).data('tablesearch');

		/*var Boton="<span class="input-group-btn' title="Buscar'><a class="btn btn-default btn-filter' id="btnSearch-'+Tabla+''><i class="fa fa-search'></i></span></a>';
		
		var $target = $(this).parent();
		var $content = $(this).parent();

		$content.attr('align','right');
		
		$target.addClass('input-group');
		$target.append(Boton);*/
		
	$(document).delegate('.DataTableFilter', 'keyup', function(e){
		e.preventDefault();
		var Table   = $(this).data('tablesearch');
		if (e.keyCode == 13){
			$(this).blur();
			CelaTable[Table].fnFilter($(this).val());
			return false;
		}else{
			return;
		}
	});
	//});

	$(document).delegate('.DataTableFilter', 'focus', function(){
		 $(this).one('mouseup', function(event){
	        event.preventDefault();
	    }).select();
	});
	
	$(document).delegate('.btn-filter', 'click', function(){
		var Table   = $(this).data('tablesearch');

		var $InputSearch = $(this).parent().parent().find('input');
		//alert($target.val());
		CelaTable[Table].fnFilter($InputSearch.val());
	});

	$('.DataTableFilter').focusin(function(){
		$(this).select();
		 return false;
	});

	/* Default export table*/
	$(document).delegate('.DataTablePrint', 'click', function(e){
		e.preventDefault();
		if(!$(this).is('[disabled]')){
			e.preventDefault();
			$(this).button('loading');
			$('.DataTablePrint').attr('disabled','disabled');
			var $Button = $(this);
			var Tabla   = $(this).data('table');

			var FunctionReport = '';
			var Params = '';
			var Query = '';
			var MyTemplate = '';
			var TagsToReplace = '';
			var ReportContent = '';
			var GetConfig = '';
			var FileName = '';
			var MimeType = '';
			var ConfigPage = {};
			var PageSize = '';
			var Orientation = '';
			var PageType = '';
			var FooterStyle = '';
			var ServerSource = '';

			if(typeof(CelaQuery[Tabla]) == 'undefined')
				return false;
			else
				Query = CelaQuery[Tabla];

			if(typeof($(this).data('function_report')) == 'undefined')
				return false;
			else
				FunctionReport = $(this).data('function_report');

			if(typeof($(this).data('file_name')) == 'undefined')
				return false;
			else
				FileName = $(this).data('file_name');

			if(typeof($(this).data('mime_type')) == 'undefined')
				return false;
			else
				MimeType = $(this).data('mime_type');

			if(typeof($(this).data('params')) == 'undefined')
				Params = '';
			else
				Params = $(this).data('params');

			if(typeof($(this).data('template')) == 'undefined')
				MyTemplate = '';
			else
				MyTemplate = $(this).data('template');

			if(typeof($(this).data('tags_replace')) == 'undefined')
				TagsToReplace = '';
			else
				TagsToReplace = $(this).data('tags_replace');

			if(typeof($(this).data('content')) == 'undefined')
				ReportContent = '';
			else
				ReportContent = $(this).data('content');

			if(typeof($(this).data('config')) == 'undefined')
				GetConfig = '';
			else
				GetConfig = $(this).data('config');

			if(typeof($(this).data('page_size')) == 'undefined')
				ConfigPage['PageSize'] = '';
			else
				ConfigPage['PageSize'] = $(this).data('page_size');

			if(typeof($(this).data('orientation')) == 'undefined')
				ConfigPage['Orientation'] = '';
			else
				ConfigPage['Orientation'] = $(this).data('orientation');

			if(typeof($(this).data('page_type')) == 'undefined')
				ConfigPage['PageType'] = '';
			else
				ConfigPage['PageType'] = $(this).data('page_type');

			if(typeof($(this).data('footer_style')) == 'undefined')
				ConfigPage['FooterStyle'] = '';
			else
				ConfigPage['FooterStyle'] = $(this).data('footer_style');

			if(typeof($(this).data('server_source')) == 'undefined')
				ServerSource = '';
			else
				ServerSource = $(this).data('server_source');

			$.post('AjaxsFunctions.php',{
					'Function': 'DataTableExported',
					'Require': ServerSource,
					'FunctionReport': FunctionReport,
					'Params': Params,
					'Query': Query,
					'MyTemplate': MyTemplate,
					'TagsToReplace': TagsToReplace,
					'ReportContent': ReportContent,
					'GetConfig': GetConfig,
					'FileName': FileName,
					'MimeType': MimeType,
					'ConfigPage': ConfigPage
				},
				function(data){
					if(data.Status == 'OK'){
						switch(MimeType){
							case 'application/vnd.ms-excel':
							case 'application/pdf':
								/*$Button.after('<iframe id="Downloader" src="' + data.FileSource + '" style="display:none;"></iframe>');*/
								/*
								 setTimeout(function(){
								 $.post('AjaxsFunctions.php', {
								 'Function': 'DeleteTempFile',
								 FileSource: data.TempFile
								 }, function(data) {
								 if(data == 'OK'){
								 $('#Downloader').remove();
								 }
								 });
								 }, 15000);
								 */
								location.href=data.FileSource;
								break;
							case 'text/html':
								$Button.after('<div id="Downloader" style="display:none;"></div>');
								$('#Downloader').html(data.Content);
								var Options = { mode : 'iframe', popClose : true };
								$('#Downloader').printArea(Options);
								setTimeout(function(){
									$('#Downloader').remove();
								}, 15000);
								break;
						}

						$('.DataTablePrint').button('reset');
						$('.DataTablePrint').removeAttr('disabled');
					}else{
						console.log('Ocurrio un error generando el archivo: ' + data.Error);
					}
				},'json');
		}
	});

	/* Tools form buttons */
	$('.btn-close').click(function(e){
		e.preventDefault();
		$(this).parent().parent().parent().parent().fadeOut();
	});

	$('.btn-minimize').click(function(e){
		e.preventDefault();
		var $target = $(this).parent().parent().parent().next('.box-content');
		if($target.is(':visible')) 
			$('i',$(this)).removeClass('fa fa-chevron-up').addClass('fa fa-chevron-down');
		else 
			$('i',$(this)).removeClass('fa fa-chevron-down').addClass('fa fa-chevron-up');
		
		$target.slideToggle();
	});

	$('.btn-help').click(function(e){
		e.preventDefault();
		$('body').chardinJs('start');
	});

	/* Trigger combo */
	/*
		example of the structure to create a combo desecandenado
		$Datos= array(
					'cadena1'=>array(
					'tabla'=>'Localidad',
					'campo'=>'Nombre',
					'filtro'=>'IdMunicipio',
					'hijo'=>'Localidad',
					'indice'=>'IdLocalidad'
				),
				//Only if is multiple 
				'cadena2'=>array(
					'tabla'=>'CodigoPostal',
					'campo'=>'concat_ws(' ',CodigoPostal,NombreAsentamiento)',											'filtro'=>'IdMunicipio',
					'hijo'=>'CodigoPostal',
					'indice'=>'IdCodigo'
				)
			);
			//Parent
			<select name="Localidad' id="Localidad' class="form-control e_requerido combo_padre' data-combo_cadena=\''.json_encode($Datos).'\'>
			</select>
			//Children
			<select name="Localidad' id="Localidad' class="form-control e_requerido Localidad'>
			</select>
			//Same Children other item
			<select name="Localidad2' id="Localidad2' class="form-control e_requerido Localidad'>
			</select>
			//Other Children only if is multiple
			<select name="CodigoPostal' id="CodigoPostal' class="form-control e_requerido CodigoPostal'>
			</select>
	*/
	//$(document).delegate('.combo_padre', 'change', function(){
	//	var id = $(this).attr('id');
	//	$.each($('#' + id).data('combo_cadena'), function(indice, valor){
	//		var Table   = valor.tabla;
	//		var Field   = valor.campo;
	//		var Filter  = valor.filtro;
	//		var Son     = valor.hijo;
	//		var Index   = valor.indice;
	//		var Value   = $('#'+id).val();
	//		$('.' + Son).each(function(){
	//			$(this).hide();
	//			var $content = $(this).parent();
	//			$content.append('<img id="Loading' + id + '" src="bootstrap/img/loading.gif"/>');
	//		});
	//
	//		$.post('AjaxsFunctions.php',{
	//			Function : 'TriggerSelect',
	//			Table   : Table,
	//			Field   : Field,
	//			Filter  : Filter,
	//			Index  : Index,
	//			Value   : Value,
	//			Empty   : function(){
	//				if(typeof valor.vacio == 'undefined')
	//					return '';
	//				else
	//					return valor.vacio;
	//			},
	//			EmptyValue   : function(){
	//				if(typeof valor.vacio == 'undefined')
	//					return '';
	//				else
	//					return '';
	//			},
	//			EmptyMessage : function(){
	//				if(typeof valor.mensajevacio == 'undefined')
	//					return '';
	//				else
	//					return valor.mensajevacio;
	//			},
	//			Where :function(){
	//				if(typeof valor.condicion == 'undefined')
	//					return '';
	//				else
	//					return valor.condicion;
	//			}
	//		},function(data){
	//			$('.' + Son).each(function(){
	//				$(this).html(data);
	//				$('#Loading' + id).remove();
	//				$(this).show();
	//			});
	//		});
	//	});
	//});

	$(document).on('change', '.combo_padre', function(){
		var id = $(this).attr('id');
		$.each($('#' + id).data('combo_cadena'), function(indice, valor){
			var Table   = valor.tabla;
			var Field   = valor.campo;
			var Filter  = valor.filtro;
			var Son     = valor.hijo;
			var Index   = valor.indice;
			var Value   = $('#'+id).val();
			$('select.' + Son).each(function(){
				$(this).next().hide();
				var $content = $(this).parent();
				$content.append('<img id="Loading' + id + '" src="assets/img/loading.gif"/>');
			});

			$.post('AjaxsFunctions.php',{
				Function : 'TriggerSelect',
				Table   : Table,
				Field   : Field,
				Filter  : Filter,
				Index  : Index,
				Value   : Value,
				Empty   : function(){
					if(typeof valor.vacio == 'undefined')
						return '';
					else
						return valor.vacio;
				},
				EmptyValue   : function(){
					if(typeof valor.vacio == 'undefined')
						return '';
					else
						return '';
				},
				EmptyMessage : function(){
					if(typeof valor.mensajevacio == 'undefined')
						return '';
					else
						return valor.mensajevacio;
				},
				Where :function(){
					if(typeof valor.condicion == 'undefined')
						return '';
					else
						return valor.condicion;
				}
			},function(data){
				$('select.' + Son).each(function(){
					$(this).html(data);
					$('#Loading' + id).remove();
					$(this).selectpicker('refresh');
					$(this).next().show();
				});
			});
		});
	});

	$(document).delegate('.alert-msn', 'mouseover', function(){
		$('.alert-msn').stop().animate({opacity:'100'});
	});

	$(document).delegate('.alert-msn', 'mouseleave', function(){
		$('.alert-msn').fadeOut(30000);
	});
	
	/*Side Bar*/
	$('.MenuOption').click(function(){
		var Content	= $(this).parent().parent();
		var ul		= $(this).next().next('ul');
		if($(ul).length){
			if($(ul).is(':visible')){
				$(ul).slideToggle('fast', function(){
					$(this).closest('li').removeClass('open');
					$(this).prev('.fa').removeClass('fa-caret-up').addClass('fa-caret-right');
				});
			}else{
				$(Content).children('li').each(function(){
					var MenuOption = $(this).children('.MenuOption');
					if($(MenuOption).hasClass('MenuOption')){
						if($(MenuOption).closest('li').hasClass('open')){
							$(MenuOption).next().next('ul').slideToggle('fast', function(){
								$(MenuOption).closest('li').removeClass('open');
								$(MenuOption).next('.fa').removeClass('fa-caret-up').addClass('fa-caret-right');
							});
						}
					}
				});
				$(ul).slideToggle('fast', function(){
					$(this).closest('li').addClass('open');
					$(this).prev('.fa').removeClass('fa-caret-right').addClass('fa-caret-up');
				});
			}
		}
	});

	/*Hide side bar*/
	var pull = $('.toggle-min');
	var menu = $('.sidebar');
	menuHeight = menu.height();

	$(pull).on('click', function(e) {
		//console.log('ewewe');
		e.preventDefault();
		if(menu.is(':visible')){
			menu.slideUp(0,function(){
				$.cookie('HiddenMenu',1);
				$('#MainContainer').removeClass('col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10').addClass('col-sm-12 col-md-12');
			});
		}else{
			$.cookie('HiddenMenu',0);
			$('#MainContainer').removeClass('col-sm-12 col-md-12').addClass('col-sm-offset-3 col-sm-9 col-md-offset-2 col-md-10');
			menu.slideDown(0);
		}
	});

	/*Responsive side bar*/
	$(window).resize(function(){
		var w = $(window).width();
		if(w > 320 && menu.is(':hidden') && $.cookie('HiddenMenu') == 0) {
			menu.removeAttr('style');
		}
	});
	
	$('#nav-container').perfectScrollbar({
		suppressScrollX: true
	});
});

$(document).ready(function(){
	$('.alert-msn').fadeOut(30000);

	if($('#CelaModalLockSession').length){
		var Transcurrido = 0;
		setInterval(function(){
			Transcurrido++;
			if(Transcurrido == Limit && !$('#CelaModalLockSession').is(':visible')){
				$.post('AjaxsFunctions.php',{
					Function: 'LockSession'
				},function(data){
					if(data == 1){
						$('#CelaModalLockSession').modal('show');
						Transcurrido = 0;
					}
				});
			}
			//console.log(Transcurrido);
		},1000);
	}

	$(document).on('click mousemove keypress',function(){
		//Indica que aun estan trabajando, acutalizamos la sesion y el tiempo transcurrido.
		if($('#CelaModalLockSession').length && !$('#CelaModalLockSession').is(':visible')){
			Transcurrido = 0;
		}
	});
	
	/**
	setInterval(function(){
		if($('#CelaModalLockSession').length && !$('#CelaModalLockSession').is(':visible')){
			if(Transcurrido != Limit){
				$.post('AjaxsFunctions.php', {
					Function: 'UpdateSESSION'
				}, function(data){
					if(data != 'OK'){
						//location.href="Salir.php";
					}
				});
			}
		}
	},60000);
	/**/

	$('#CelaBoto_oUnLockSession').click(function(){
		UnLockSession();
	});

	$('#txtcontrasena').unbind('keyup').bind('keyup', function(e){
		if (e.keyCode == 13){
			UnLockSession();
		}else{
			return;
		}
	});

	function UnLockSession(){
		if($('#CelaModalLockSession').length){
			$.post('AjaxsFunctions.php',{
				Function: 'UnLockSession',
				Contrase_na: CryptoJS.MD5($('#txtcontrasena').val()).toString()
			},function(data){
				if(data == 'OK'){
					$('#CelaModalLockSession').modal('hide');
					$('#Message').html('');
					$('#txtcontrasena').val('');
					Transcurrido = 0;
				}else{
					if(data == 'ERROR')
						$('#CelaLabelMessageLockSession').html('Contrase&ntilde;a incorrecta.');
					else
						$('#CelaLabelMessageLockSession').html('La sesi&oacute;n ha caducado.<br />Presione la tecla F5 <br />&Oacute; haga click <a href="Salir.php" >aqu&iacute;</a> para iniciar.');
				}						
			});	
		}
	}

	$('input:text:visible:first').focus();
	$(function () {
		if($('input:text:visible:first').hasClass('e_fecha') || $('input:text:visible:first').hasClass('e_fecha_hora') || $('input:text:visible:first').hasClass('e_hora')){
			$('input:text:visible:first').datepicker('show');
		}

		$('input[type=file]').before(
			'<button style="z-index: 4;" class="close limpiar-inputfile" type="button" title="Quitar archivo"> <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'
		);
		
		$('.limpiar-inputfile').click(function () {
			var input = $(this).next('input[type=file]');
			//console.log(input);
			input.val(null);
			var clon = input.clone();
			input.replaceWith(clon);
			return false;
		});

		$('select').each(function(){
			$(this).addClass('custom-select');
			//if(!$(this).hasClass('no-custom')){
			//	LoadSelectpicker($(this));
			//}
		});


	});
});

$(document).ready(function(){
	//$(document).delegate('th :input', 'click', function(e){
	//	e.stopPropagation();
	//	return;
	//});


	$(document).delegate('.e_moneda', 'change', function(){
		var n= $(this).val();
		n=n.replace(/,/gi, "");
		/*Buscamos el numero de caracteres que tiene el elemento*/
		var g = n.indexOf('.') + 1;
		if(g == 0){
			l = 2;
		}else{
			var l = n.length - g;
		}
		if(!isNaN(n)){
			var h = n.substr(g, l);
			var fixed = (h.length > 1 ? h.length:2);
			n = parseFloat(n);
			value = n.toFixed(fixed).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
			$(this).val(value);
		}

	});

	$('th :input').on('click', function(e){
		e.stopPropagation();
		//e.stopImmediatePropagation();
		//$(this).trigger('click');
		//return;
	});
	
	/*$('select').selectpicker({
        'selectedText': 'cat',
        'iconBase': 'fa ',
        'tickIcon': 'fa fa-check',
        'style': 'form-control'
    });*/
});

function generateUUID(Parttern) {
	var d = new Date().getTime();
	var uuid = Parttern.replace(/[xy]/g, function(c) {
		var r = (d + Math.random() * 16) % 16 | 0;
		d = Math.floor(d / 16);
		return (c=='x' ? r : (r&0x3|0x8)).toString(16);
	});

	return uuid;
};

function LoadMessage(idModal, Title, Message){

	var HTML = '' +
		'<div class="modal-dialog" role="document">' +
			'<div class="modal-content">' +
				'<div class="modal-header">' +
					'<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
						'<span aria-hidden="true">&times;</span>' +
					'</button>' +
					'<h4 class="modal-title" id="myModalLabel">' + Title + ' </h4>' +
				'</div>' +
				'<div class="modal-body">' +
					Message +
				'</div>' +
				'<div class="modal-footer">' +
					'<button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>' +
				'</div>' +
			'</div>' +
		'</div>';

	$('#' + idModal).html(HTML);
	$('#' + idModal).modal('show');
}

function ShowWindowsPopup(page, h, w){
	// Fixes dual-screen position                         Most browsers      Firefox
	var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
	var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

	var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

	var left = ((width / 2) - (w / 2)) + dualScreenLeft;
	var top = ((height / 2) - (h / 2)) + dualScreenTop;

	var myWindow = window.open(page, '_blank', 'location=no,height=' + h + ',width=' + w + ',scrollbars=yes,status=yes, resizable=yes, top=' + top + ', left=' + left);

	return myWindow;
}