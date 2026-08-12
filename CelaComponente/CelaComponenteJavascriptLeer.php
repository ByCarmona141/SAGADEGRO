<script>
	var SelectedItems = [];
	$(document).ready(function(){
		/*Se Carga la vista de Creación de nueva trazabilidad*/
		$(document).delegate('#CelaTrazabilidadBot_onCrear', 'click', function(e){
			e.preventDefault();
			$.post('AjaxsFunctions.php', {
				'Function': 'LoadContentPage',
				'FormAction': 'CelaTrazabilidad',
				'File': '../CelaTrazabilidad/CelaTrazabilidadVistaCrear.php',
				'Require': '../CelaFase/CelaFase.php;../CelaUsuario/CelaUsuario.php'
			},function(data){
				$('#CelaTrazabilidadModalBody').html((data));
				/*Se carga la validación para el formulario*/
				LoadFormValidate('Form_CelaTrazabilidad', ':hidden');

				/*Se carga la validación para los elementos del formulario*/
				e_fecha_horaLoadValidate('Fecha');
				e_requeridoLoadValidation('Fecha');
				e_requeridoLoadValidation('Fase');
				e_requeridoLoadValidation('Programador');

				/*Agregamos la funcion que se ejecutara on submit*/
				$('#Form_CelaTrazabilidad').data('onsubmit', 'SaveTrazabilidad()');

				/*Quitamos el evento click del boton cancelar*/
				var CancelButton = document.getElementById('CancelaCelaTrazabilidad');
				CancelButton.onclick = null;
			});
		});

		/*Se carga la eliminación de elementos*/
		$(document).delegate('.deleteCelaTrazabilidad', 'click', function(e){
			e.preventDefault();

			/*Se vacia el arreglo por si tiene almacenado datos anteriores*/
			SelectedItems.length = 0;

			/*Se obtienen los elementos seleccionados*/
			var Flag = false;
			$('.SelectedCelaTrazabilidad').each(function(){
				if($(this).is(':checked')){
					SelectedItems.push($(this).data('index'));
					Flag = true;
				}
			});

			/*Se verifica que haya seleccionado elementos, si no es el elemento actual*/
			if(!Flag){
				SelectedItems.push($(this).data('index'));
			}
		});

		$(document).delegate('#CelaTrazabilidadBot_onEliminarAceptar', 'click', function(e){
			e.preventDefault();

			/*Se cierra el modal de eliminacion*/
			$('#CelaTrazabilidadModalEliminar').modal('hide');

			var Status = true;
			var View = '';
			var Cont = 0;
			$.each(SelectedItems, function(Index, Value){
				$.post('AjaxsFunctions.php', {
					'Function': 'GetValueAjaxs',
					'Value': '',
					'Query': 'SELECT * FROM CelaTrazabilidad WHERE id = ' + Value
				},function(dataDelete){
					$.post('AjaxsFunctions.php', {
						'Function': 'CelaTrazabilidadEliminar',
						'Key': Value,
						'Require': '../CelaTrazabilidad/CelaTrazabilidad.php'
					},function(data){
						Cont ++;
						if(data.Status == 'OK'){
							/*Se registra la accion en la bitacora*/
							$.post('AjaxsFunctions.php', {
								'Function': 'RecordLog',
								'Table': 'CelaTrazabilidad',
								'Record': Value,
								'Action': 2,
								'UserId': <?= $_COOKIE['idUsuario']; ?>,
								'LogData': dataDelete
							}, function(data){

							}, 'json');
						}else{
							Status = false;
						}

						if(Cont == SelectedItems.length){
							if(Status){
								/*Se carga el mensaje de eliminación correcta*/
								$.post('AjaxsFunctions.php', {
									'Function': 'LoadContentPage',
									'File': '../CelaTemplate/CelaActionMessage.php',
									'StatusMessage': 'success',
									'IconMessage': 'fa-check',
									'TitleMessage': 'Registro exitoso!',
									'TextMessage': 'El/Los elemento(s) se eliminar&oacute;n correctamente.'
								}, function(data){
									View += data;
								});

								/*Se carga la vista de lectura*/
								$.post('AjaxsFunctions.php', {
									'Function': 'LoadContentPage',
									'Table': 'CelaTrazabilidad',
									'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
									'ServerFunction': 'CelaTrazabilidadLeer',
									'RouteForm': 'CelaTrazabilidad',
									'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
									'GetPrivileges': 1,
									'Params': {
										'Component': $('#CelaTrazabilidadIndex').val()
									}
								},function(data){
									var Sorting = [];
									View += data;

									$('#CelaTrazabilidadModalBody').html(View);
									$('#Table_CelaTrazabilidad thead th').each(function(){
										if($(this).hasClass('sortable'))
											Sorting.push(null);
										else
											Sorting.push({'bSortable':false});
									});

									var RecordLength = -1;
									var TableId = 'Table_CelaTrazabilidad';

									LoadDataTable(TableId, Sorting, RecordLength);
								});
							}else{
								/*se carga el mensaje de error en la eliminacion*/
								$.post('AjaxsFunctions.php', {
									'Function': 'LoadContentPage',
									'File': '../CelaTemplate/CelaActionMessage.php',
									'StatusMessage': 'danger',
									'IconMessage': 'fa-times',
									'TitleMessage': 'Oops!... Ocurrio un error eliminado el elemento',
									'TextMessage': 'Algunos elementos pudieron no haberse eliminado'
								}, function(data){
									$('#CelaTrazabilidadModalBody').html((data));
									/*Se carga la vista de lectura*/
									$.post('AjaxsFunctions.php', {
										'Function': 'LoadContentPage',
										'Table': 'CelaTrazabilidad',
										'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
										'ServerFunction': 'CelaTrazabilidadLeer',
										'RouteForm': 'CelaTrazabilidad',
										'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
										'GetPrivileges': 1,
										'Params': {
											'Component': $('#CelaTrazabilidadIndex').val()
										}
									}, function(data){
										var Sorting = [];
										$('#CelaTrazabilidadModalBody').append(data);
										$('#Table_CelaTrazabilidad thead th').each(function(){
											if($(this).hasClass('sortable'))
												Sorting.push(null);
											else
												Sorting.push({'bSortable': false});
										});

										var RecordLength = -1;
										var TableId = 'Table_CelaTrazabilidad';

										LoadDataTable(TableId, Sorting, RecordLength);
									});
								});
							}
							SelectedItems.length = 0;
						}
					}, 'json');
				});
			});
		});

		/*Se carga la vista del lectura de la trazabilidad del componente seleccionado*/
		$('#Table_CelaComponente').delegate('.show_trazabilidad', 'click', function(){
			$('#CelaTrazabilidadIndex').val($(this).data('index'));

			/*Se vacia el arreglo por si tiene almacenado datos anteriores*/
			SelectedItems.length = 0;

			$.post('AjaxsFunctions.php', {
				'Function': 'LoadContentPage',
				'Table': 'CelaTrazabilidad',
				'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
				'ServerFunction': 'CelaTrazabilidadLeer',
				'RouteForm': 'CelaTrazabilidad',
				'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
				'GetPrivileges': 1,
				'Params': {
					'Component': $('#CelaTrazabilidadIndex').val()
				}
			},function(data){
				var Sorting = [];
				$('#CelaTrazabilidadModalBody').html((data));
				$('#Table_CelaTrazabilidad thead th').each(function(){
					if($(this).hasClass('sortable'))
						Sorting.push(null);
					else
						Sorting.push({'bSortable':false});
				});

				var RecordLength = -1;
				var TableId = 'Table_CelaTrazabilidad';

				LoadDataTable(TableId, Sorting, RecordLength);

				$.post('AjaxsFunctions.php', {
					'Function': 'LoadContentPage',
					'Table': 'CelaTrazabilidad',
					'File': '../CelaTemplate/CelaTableToolsScript.php',
				},function(data){
					$('#CelaTrazabilidadModalBody').append(data);
					CelaTable['CelaTrazabilidad'].fnFilter();
					$('#CelaTrazabilidadModal').modal('show');
				});
			});
		});

		/*Se Carga la vista de actualización de elementos*/
		$(document).delegate('.updateCelaTrazabilidad', 'click', function(e){
			e.preventDefault();

			if(!$(this).is('[disabled]')){
				/*Se vacia el arreglo por si tiene almacenado datos anteriores*/
				SelectedItems.length = 0;

				/*Se optine la variable $_GET del elemento*/
				var Get = $(this).attr('href').split('?');

				/*Se obtienen los elementos seleccionados*/
				var Flag = false;
				$('.SelectedCelaTrazabilidad').each(function(){
					if($(this).is(':checked')){
						SelectedItems.push($(this).data('index'));
						Flag = true;
					}
				});

				/*Se verifica que haya seleccionado elementos, si no es el elemento actual*/
				if(!Flag){
					SelectedItems.push($(this).data('index'));
				}

				$.post('AjaxsFunctions.php?' + Get.pop(), {
					'Function': 'LoadContentPage',
					'FormAction': 'CelaTrazabilidad',
					'File': '../CelaTrazabilidad/CelaTrazabilidadVistaActualizar.php',
					'Require': '../CelaFase/CelaFase.php;../CelaUsuario/CelaUsuario.php',
					'Random': '<?= $_COOKIE['CelaRandom']; ?>'
				}, function(data){
					$('#CelaTrazabilidadModalBody').html((data));
					/*Se carga la validación para el formulario*/
					LoadFormValidate('Form_CelaTrazabilidad', ':hidden');

					/*Se carga la validación para los elementos del formulario*/
					$.each(SelectedItems, function(Index, Value){
						e_fecha_horaLoadValidate('Fecha' + Value);
						e_requeridoLoadValidation('Fecha' + Value);
						e_requeridoLoadValidation('Fase' + Value);
						e_requeridoLoadValidation('Programador' + Value);
					});

					/*Agregamos la funcion que se ejecutara on submit*/
					$('#Form_CelaTrazabilidad').data('onsubmit', 'UpdateTrazabilidad()');

					/*Quitamos el evento click del boton cancelar*/
					var CancelButton = document.getElementById('CancelaCelaTrazabilidad');
					CancelButton.onclick = null;
				});
			}
		});

		/*Se previene el Insert Back*/
		$(document).delegate('#GuardarCelaTrazabilidad', 'click', function(){
			$('.InsertBack').prop('checked', false);
		});

		/*Se carga la vista de lectura si se cancela la acción del formulario*/
		$(document).delegate('#CancelaCelaTrazabilidad', 'click', function(e){
			e.preventDefault();
			$.post('AjaxsFunctions.php', {
				'Function': 'LoadContentPage',
				'Table': 'CelaTrazabilidad',
				'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
				'ServerFunction': 'CelaTrazabilidadLeer',
				'RouteForm': 'CelaTrazabilidad',
				'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
				'GetPrivileges': 1,
				'Params': {
					'Component': $('#CelaTrazabilidadIndex').val()
				}
			},function(data){

				var Sorting = [];
				$('#CelaTrazabilidadModalBody').html((data));
				$('#Table_CelaTrazabilidad thead th').each(function(){
					if($(this).hasClass('sortable'))
						Sorting.push(null);
					else
						Sorting.push({'bSortable':false});
				});

				var RecordLength = -1;
				var TableId = 'Table_CelaTrazabilidad';

				LoadDataTable(TableId, Sorting, RecordLength);

				$('#CelaTrazabilidadModal').modal('show');
			});
		});
	});

	/*Se guarda el nuevo registro*/
	function SaveTrazabilidad(){
		$.post('AjaxsFunctions.php', {
			'Function': 'CelaTrazabilidadCrear',
			'Componente': $('#CelaTrazabilidadIndex').val(),
			'Fecha': $('#Fecha').val(),
			'Fase': $('#Fase').val(),
			'Programador': $('#Programador').val(),
			'Require': '../CelaTrazabilidad/CelaTrazabilidad.php'
		},function(data){
			var View = '';
			if(data.Status == 'OK'){
				/*Se registra la accion en la bitacora*/
				$.post('AjaxsFunctions.php', {
					'Function': 'RecordLog',
					'Table': 'CelaTrazabilidad',
					'Record': data.idRecord,
					'Action': 2,
					'UserId': <?= $_COOKIE['idUsuario']; ?>,
					'LogData': {
						'Componente': $('#CelaTrazabilidadIndex').val(),
						'Fecha': $('#Fecha').val(),
						'Fase': $('#Fase').val(),
						'Programador': $('#Programador').val()
					}
				},function(data){

				}, 'json');

				/*Se carga el mensaje de creacion correcta*/
				$.post('AjaxsFunctions.php', {
					'Function': 'LoadContentPage',
					'File': '../CelaTemplate/CelaActionMessage.php',
					'StatusMessage': 'success',
					'IconMessage': 'fa-check',
					'TitleMessage': 'Registro exitoso!',
					'TextMessage': 'El nuevo elemento se registr&oacute; correctamente.'
				}, function(data){
					View += data;
				});

				if($('.InsertBack').is(':checked')){
					$.post('AjaxsFunctions.php', {
						'Function': 'LoadContentPage',
						'FormAction': 'CelaTrazabilidad',
						'File': '../CelaTrazabilidad/CelaTrazabilidadVistaCrear.php',
						'Require': '../CelaFase/CelaFase.php;../CelaUsuario/CelaUsuario.php'
					},function(data){
						View += data;

						$('#CelaTrazabilidadModalBody').html(View);
						LoadFormValidate('Form_CelaTrazabilidad', ':hidden');

						e_fecha_horaLoadValidate('Fecha');
						e_requeridoLoadValidation('Fecha');
						e_requeridoLoadValidation('Fase');
						e_requeridoLoadValidation('Programador');

						/*Agregamos la funcion que se ejecutara on submit*/
						$('#Form_CelaTrazabilidad').data('onsubmit', 'SaveTrazabilidad()');

						/*Quitamos el evento click del boton cancelar*/
						var CancelButton = document.getElementById('CancelaCelaTrazabilidad');
						CancelButton.onclick = null;
					});
				}else{
					/*Se carga la vista de lectura*/
					$.post('AjaxsFunctions.php', {
						'Function': 'LoadContentPage',
						'Table': 'CelaTrazabilidad',
						'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
						'ServerFunction': 'CelaTrazabilidadLeer',
						'RouteForm': 'CelaTrazabilidad',
						'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
						'GetPrivileges': 1,
						'Params': {
							'Component': $('#CelaTrazabilidadIndex').val()
						}
					},function(data){
						var Sorting = [];
						View += data;

						$('#CelaTrazabilidadModalBody').html(View);
						$('#Table_CelaTrazabilidad thead th').each(function(){
							if($(this).hasClass('sortable'))
								Sorting.push(null);
							else
								Sorting.push({'bSortable':false});
						});

						var RecordLength = -1;
						var TableId = 'Table_CelaTrazabilidad';

						LoadDataTable(TableId, Sorting, RecordLength);
					});
				}
			}else{
				/*se carga el mensaje de error en la creación*/
				$.post('AjaxsFunctions.php', {
					'Function': 'LoadContentPage',
					'File': '../CelaTemplate/CelaActionMessage.php',
					'StatusMessage': 'danger',
					'IconMessage': 'fa-times',
					'TitleMessage': 'Oops!... Ocurrio un error registrando el elemento',
					'TextMessage': data.Error
				}, function(data){
					$('#CelaTrazabilidadModalBody').html((data));

					/*Se carga la vista de lectura*/
					$.post('AjaxsFunctions.php', {
						'Function': 'LoadContentPage',
						'Table': 'CelaTrazabilidad',
						'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
						'ServerFunction': 'CelaTrazabilidadLeer',
						'RouteForm': 'CelaTrazabilidad',
						'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
						'GetPrivileges': 1,
						'Params': {
							'Component': $('#CelaTrazabilidadIndex').val()
						}
					},function(data){
						var Sorting = [];
						$('#CelaTrazabilidadModalBody').append(data);
						$('#Table_CelaTrazabilidad thead th').each(function(){
							if($(this).hasClass('sortable'))
								Sorting.push(null);
							else
								Sorting.push({'bSortable':false});
						});

						var RecordLength = -1;
						var TableId = 'Table_CelaTrazabilidad';

						LoadDataTable(TableId, Sorting, RecordLength);
					});
				});
			}
		}, 'json');
	}

	/*Se actualiza el registro*/
	function UpdateTrazabilidad(){
		var Status = true;
		var View = '';
		var Cont = 0;
		$.each(SelectedItems, function(Index, Value){
			$.post('AjaxsFunctions.php', {
				'Function': 'CelaTrazabilidadActualizar',
				'FormData': {
					'Componente': $('#CelaTrazabilidadIndex').val(),
					'Fecha': $('#Fecha' + Value).val(),
					'Fase': $('#Fase' + Value).val(),
					'Programador': $('#Programador' + Value).val()
				},
				'Key': Value,
				'Require': '../CelaTrazabilidad/CelaTrazabilidad.php'
			},function(data){
				Cont ++;
				if(data.Status == 'OK'){
					/*Se registra la accion en la bitacora*/
					$.post('AjaxsFunctions.php', {
						'Function': 'RecordLog',
						'Table': 'CelaTrazabilidad',
						'Record': Value,
						'Action': 5,
						'UserId': <?= $_COOKIE['idUsuario']; ?>,
						'LogData': {
							'Componente': $('#CelaTrazabilidadIndex').val(),
							'Fecha': $('#Fecha' + Value).val(),
							'Fase': $('#Fase' + Value).val(),
							'Programador': $('#Programador' + Value).val()
						}
					}, function(data){

					}, 'json');
				}else{
					Status = false;
				}

				if(Cont == SelectedItems.length){
					if(Status){
						/*Se carga el mensaje de actualizacion correcta*/
						$.post('AjaxsFunctions.php', {
							'Function': 'LoadContentPage',
							'File': '../CelaTemplate/CelaActionMessage.php',
							'StatusMessage': 'success',
							'IconMessage': 'fa-check',
							'TitleMessage': 'Registro exitoso!',
							'TextMessage': 'El/Los elemento(s) se actualizar&oacute;n correctamente.'
						}, function(data){
							View += data;
						});

						/*Se carga la vista de lectura*/
						$.post('AjaxsFunctions.php', {
							'Function': 'LoadContentPage',
							'Table': 'CelaTrazabilidad',
							'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
							'ServerFunction': 'CelaTrazabilidadLeer',
							'RouteForm': 'CelaTrazabilidad',
							'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
							'GetPrivileges': 1,
							'Params': {
								'Component': $('#CelaTrazabilidadIndex').val()
							}
						},function(data){
							var Sorting = [];
							View += data;

							$('#CelaTrazabilidadModalBody').html(View);
							$('#Table_CelaTrazabilidad thead th').each(function(){
								if($(this).hasClass('sortable'))
									Sorting.push(null);
								else
									Sorting.push({'bSortable':false});
							});

							var RecordLength = -1;
							var TableId = 'Table_CelaTrazabilidad';

							LoadDataTable(TableId, Sorting, RecordLength);
						});
					}else{
						/*se carga el mensaje de error en la actualización*/
						$.post('AjaxsFunctions.php', {
							'Function': 'LoadContentPage',
							'File': '../CelaTemplate/CelaActionMessage.php',
							'StatusMessage': 'danger',
							'IconMessage': 'fa-times',
							'TitleMessage': 'Oops!... Ocurrio un error actualizando el elemento',
							'TextMessage': 'Algunos elementos pudieron no haberse actualizado'
						}, function(data){
							$('#CelaTrazabilidadModalBody').html((data));
							/*Se carga la vista de lectura*/
							$.post('AjaxsFunctions.php', {
								'Function': 'LoadContentPage',
								'Table': 'CelaTrazabilidad',
								'File': '../CelaTrazabilidad/CelaTrazabilidadVistaLeer.php',
								'ServerFunction': 'CelaTrazabilidadLeer',
								'RouteForm': 'CelaTrazabilidad',
								'ServerSource': '../CelaTrazabilidad/CelaTrazabilidad.php',
								'GetPrivileges': 1,
								'Params': {
									'Component': $('#CelaTrazabilidadIndex').val()
								}
							}, function(data){
								var Sorting = [];
								$('#CelaTrazabilidadModalBody').append(data);
								$('#Table_CelaTrazabilidad thead th').each(function(){
									if($(this).hasClass('sortable'))
										Sorting.push(null);
									else
										Sorting.push({'bSortable': false});
								});

								var RecordLength = -1;
								var TableId = 'Table_CelaTrazabilidad';

								LoadDataTable(TableId, Sorting, RecordLength);
							});
						});
					}

					SelectedItems.length = 0;
				}
			}, 'json');
		});
	}
</script>