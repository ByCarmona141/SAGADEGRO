<?php
	$BeforeSubmit = array(
		'0' => array(
			'function'  => 'ValidaIcono();',
			'element'   => 'Icono',
			'message'   => 'Seleccione una opci&oacute;n'
		),
		'1' => array(
			'function'  => 'ValidaRoles();',
			'element'   => 'Rol',
			'message'   => 'Seleccione una opci&oacute;n'
		)
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaMen_u" id="Form_CelaMen_u" action="<?php $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" enctype="multipart/form-data" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
		<?php
			$Count=0;
			$CelaMen_uId='';

			/*Se carga la plantilla para los datos de entrada*/
			$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
			$TagsToReplace = array(
				'<!--#INPUTLABEL#-->',
				'<!--#INPUTELEMENT#-->'
			);

			/*Se obtienen todos los elementos que van a actualizar*/
			foreach($_GET['Key'] as $Key){
				$CelaMen_uQuery = sprintf('SELECT * FROM CelaMen_u WHERE id = %s;',
					GetSQLValueString($Key, 'int')
				);
				$CelaMen_uResult = $Connection -> query($CelaMen_uQuery);
				$CelaMen_uRecord = $CelaMen_uResult -> fetch_assoc();
				
				$CelaMen_uId .= $Key . ',';
		?>
				<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
					<fieldset>
						<legend>Registro <?= $Key; ?></legend>
						<div class="form-group form-group<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Tipo de Elemento:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<?php
											$OpcTipoDeElemento['Name']  = 'TipoDeElemento' . $Key;
											$OpcTipoDeElemento['Class'] = 'form-control e_requerido';
											$Query  =  CelaTipoDeElementoQueryCombo();
											print SFillSelect($Query, $OpcTipoDeElemento, $CelaMen_uRecord['TipoDeElemento'], 1);
										?>
									</div>
								</div>
							</div>
							<!-- group-validate -->
						</div>
						<div class="form-group form-group<?= $Key; ?>" id="EtiquetaDiv<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Etiqueta: </label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<input class="form-control focused e_requerido e_longitud" name="Nombre<?= $Key; ?>" id="Nombre<?= $Key; ?>" type="text" data-rango='{"minimo":1,"maximo":64,"mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}' value="<?= $CelaMen_uRecord['Nombre']; ?>" />
									</div>
								</div>
							</div>
							<!-- group-validate -->
						</div>
						<div class="form-group form-group<?= $Key; ?>" id="Descripci_onDiv<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Descripci&oacute;n:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<input class="form-control focused e_longitud e_requerido" name="Descripci_on<?= $Key; ?>" id="Descripci_on<?= $Key; ?>" type="text" data-rango='{"minimo":1, "maximo":128, "mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}' value="<?= $CelaMen_uRecord['Descripci_on']; ?>" />
									</div>
								</div>
							</div>
							<!-- group-validate -->
						</div>
						<div class="form-group form-group<?= $Key; ?>" id="ReferenciaDiv<?= $Key; ?>" <?= ($CelaMen_uRecord['TipoDeElemento'] == 5 ? 'hidden="hidden"':''); ?> >
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Referencia o Ruta:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<input class="form-control focused e_requerido e_longitud" name="Referencia<?= $Key; ?>" id="Referencia<?= $Key; ?>" type="text" data-rango='{"minimo":1,"maximo":256,"mensaje":"Introduce un valor entre 1 y 256 caracteres de longitud"}' value="<?= $CelaMen_uRecord['Referencia']; ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group form-group<?= $Key; ?>" id="ArchivoDiv<?= $Key; ?>" <?= ($CelaMen_uRecord['TipoDeElemento'] != 5 ? 'hidden="hidden"':''); ?> >
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Archivo: </label>
								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<input class=" focused" name="Archivo<?= $Key; ?>" id="Archivo<?= $Key; ?>" type="file" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group form-group<?= $Key; ?>" id="Categor_iaDiv<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"> Categor&iacute;a:
								</label>
								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<?php
											$OpcCategor_ia['Name']          = 'Categor_ia' . $Key;
											$OpcCategor_ia['Class']         = 'form-control';
											$OpcCategor_ia['EmptyMessage']  = 'MISMA CATEGOR&Iacute;A';
											$OpcCategor_ia['EmptyValue']    = '';
											$Query = CelaMen_uQueryCombo();
											print SFillSelect($Query, $OpcCategor_ia, $CelaMen_uRecord['Categor_ia'], 1);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group form-group<?= $Key; ?>" id="IconoDiv<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font>Icono: </label>
								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<?php
											$Icono =    GetValue(
															sprintf('SELECT Nombre FROM CelaIcono WHERE id = %s;',
																GetSQLValueString($CelaMen_uRecord['Icono'], 'int')
															),
															'Nombre'
														);
											$OpcIcono['Name']   = 'Icono' . $Key;
											$OpcIcono['Class']  = 'form-control show-tick focused';
											$OpcIcono['Custom'] = 'data-live-search="true"';
											$Query = CelaIconoQueryCombo('Icon');
											print SFillSelect($Query, $OpcIcono, $CelaMen_uRecord['Icono'] . '" data-icon="' . $Icono, 1);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group form-group<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Orientaci&oacute;n:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<?php
											$OpcOrientaci_on['Name']    = 'Orientaci_on' . $Key;
											$OpcOrientaci_on['Class']   = 'form-control e_requerido';
											$Query = array('1' => 'Vertical', '2' => 'Horizontal');
											print SFillSelect($Query, $OpcOrientaci_on, $CelaMen_uRecord['Orientaci_on'], 1);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group form-group<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Prioridad: </label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<input class="form-control focused e_numero e_requerido e_rango" name="Prioridad<?= $Key; ?>" id="Prioridad<?= $Key; ?>" type="text" data-rango='{"minimo":1,"maximo":2147483647,"mensaje":"Introduce un valor entre 1 y 2147483647"}' value="<?= $CelaMen_uRecord['Prioridad']; ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group form-group<?= $Key; ?>" id="RolesDiv<?= $Key; ?>">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Roles que ven este men&uacute;: </label>
								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<?php
											$Roles =    GetValue(
															sprintf('SELECT GROUP_CONCAT(Roles.TuplaAcceso) as Roles
																	 FROM ( SELECT c.TuplaAcceso, 1 as Comodin
																	 		FROM CelaPrivilegios c
																	 		WHERE
																			     c.Tupla = %s AND
																			     c.Origen = %s AND
																			     c.Privilegio = %s
																	      ) as Roles
																      GROUP BY Roles.Comodin;',
																GetSQLValueString($Key, 'int'),
																GetSQLValueString(1, 'int'),
																GetSQLValueString(1, 'int')
															),
															'Roles'
														);
											$Roles = explode(',', $Roles);

											$OpcRol['Name']     = 'Rol' . $Key . '[]';
											$OpcRol['Class']    = 'form-control SelectRol';
											$OpcRol['Custom']   = 'multiple="multiple"';

											$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

											$Query  = CelaRolComboQuery(false, $Group);
											print SFillSelect($Query, $OpcRol, $Roles);
										?>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
		<?php
				$Count++;
			}
			
			$CelaMen_uId = substr_replace($CelaMen_uId, '', -1);
		?>
		<input type="hidden" name="CelaMen_uUpdate" value="CelaMen_uUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaMen_uId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
