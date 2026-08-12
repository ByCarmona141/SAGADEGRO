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
<form class="form-horizontal form_validate" method="POST" name="Form_CelaMen_u" id="Form_CelaMen_u" action="<?php $FormAction . '?' . EncodeThis('Action=Crear'); ?>" enctype="multipart/form-data" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
		<div class="form-group">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Tipo de Elemento:
				</label>

				<div class="col-md-10">
					<div class="col-xs-12 validate">
					<?php
						$OpcTipoDeElemento['Name']  = 'TipoDeElemento';
						$OpcTipoDeElemento['Class'] = 'form-control e_requerido';
						$Query  =  CelaTipoDeElementoQueryCombo();
						print FillSelect($Query, $OpcTipoDeElemento, 1);
					?>
					</div>
				</div>
			</div>
			<!-- group-validate -->
		</div>
		<div class="form-group" id="EtiquetaDiv">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Etiqueta: </label>

				<div class="col-md-10">
					<div class="col-xs-12 validate">
						<input class="form-control focused e_requerido e_longitud" name="Nombre" id="Nombre" type="text" data-rango='{"minimo":1,"maximo":64,"mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}'/>
					</div>
				</div>
			</div>
			<!-- group-validate -->
		</div>
		<div class="form-group" id="Descripci_onDiv">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Descripci&oacute;n:
				</label>

				<div class="col-md-10">
					<div class="col-xs-12 validate">
						<input class="form-control focused e_longitud e_requerido" name="Descripci_on" id="Descripci_on" type="text" data-rango='{"minimo":1, "maximo":128, "mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}'/>
					</div>
				</div>
			</div>
			<!-- group-validate -->
		</div>
		<div class="form-group" id="ReferenciaDiv">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Referencia o Ruta:
				</label>

				<div class="col-md-10">
					<div class="col-xs-12 validate">
						<input class="form-control focused e_requerido e_longitud" name="Referencia" id="Referencia" type="text" data-rango='{"minimo":1,"maximo":256,"mensaje":"Introduce un valor entre 1 y 256 caracteres de longitud"}'/>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group" id="ArchivoDiv" hidden="hidden">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Archivo: </label>
				<div class="col-md-10">
					<div class="col-xs-12 validate">
						<input class=" focused e_requerido" name="Archivo" id="Archivo" type="file"/>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group" id="Categor_iaDiv">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"> Categor&iacute;a:
				</label>
				<div class="col-md-10">
					<div class="col-xs-12 validate">
					<?php
						$OpcCategor_ia['Name']          = 'Categor_ia';
						$OpcCategor_ia['Class']         = 'form-control';
						$OpcCategor_ia['EmptyMessage']  = 'MISMA CATEGOR&Iacute;A';
						$OpcCategor_ia['EmptyValue']    = '';
						$Query = CelaMen_uQueryCombo();
						print FillSelect($Query, $OpcCategor_ia, 1);
					?>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group" id="IconoDiv">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font>Icono: </label>
				<div class="col-md-10">
					<div class="col-xs-12 validate">
					<?php
						$OpcIcono['Name']   = 'Icono';
						$OpcIcono['Class']  = 'form-control show-tick focused';
						$OpcIcono['Custom'] = 'data-live-search="true"';
						$Query = CelaIconoQueryCombo('Icon');
						print FillSelect($Query, $OpcIcono, 1);
					?>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Orientaci&oacute;n:
				</label>

				<div class="col-md-10">
					<div class="col-xs-12 validate">
					<?php
						$OpcOrientaci_on['Name']    = 'Orientaci_on';
						$OpcOrientaci_on['Class']   = 'form-control e_requerido';
						$Query = array('1' => 'Vertical', '2' => 'Horizontal');
						print FillSelect($Query, $OpcOrientaci_on, 1);
					?>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Prioridad: </label>

				<div class="col-md-10">
					<div class="col-xs-12 validate">
						<input class="form-control focused e_numero e_requerido e_rango" name="Prioridad" id="Prioridad" type="text" data-rango='{"minimo":1,"maximo":2147483647,"mensaje":"Introduce un valor entre 1 y 2147483647"}'/>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group" id="RolesDiv">
			<div class="group-validate">
				<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Roles que ven este men&uacute;: </label>
				<div class="col-md-10">
					<div class="col-xs-12 validate">
					<?php
						$OpcRol['Name']     = 'Rol[]';
						$OpcRol['Class']    = 'form-control SelectRol';
						$OpcRol['Custom']   = 'multiple="multiple"';

						$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

						$Query = CelaRolComboQuery(false, $Group);
						print FillSelect($Query, $OpcRol);
					?>
					</div>
				</div>
			</div>
			<!-- group-validate -->
		</div>
		<input type="hidden" name="CelaMen_uInsert" value="CelaMen_uInsert"/>
		<span class="clearfix"></span>
		<hr/>
		<?php
			$Back = $FormAction;
			include '../CelaTemplate/CelaActionsForm.php';
		?>
	</fieldset>
</form>