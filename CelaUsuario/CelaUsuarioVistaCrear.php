<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaUsuario" id="Form_CelaUsuario" action="<?= $FormAction . '?' . EncodeThis('Action=Crear' . (isset($_GET['OnSave']) ? '&OnSave=' . $_GET['OnSave']:'')); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentNombreCompleto = array(
			'<font color="red">*</font> Nombre Completo:',
			'<input class="form-control focused e_nombre e_requerido" name="NombreCompleto" id="NombreCompleto" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombreCompleto, $InputTemplate);

		$ContentUsuario = array(
			'<font color="red">*</font> Usuario:',
			'<input class="form-control focused e_usuario e_requerido e_remoto" name="Usuario" id="Usuario" type="text" data-remote=\'{"table":"CelaUsuario","field":"Usuario"}\'/>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentUsuario, $InputTemplate);

		$ContentContrase_na = array(
			'<font color="red">*</font> Contrase&ntilde;a:',
			'<input class="form-control focused e_requerido" name="Contrase_na" id="Contrase_na" type="password" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentContrase_na, $InputTemplate);

		$ContentContrase_na1 = array(
			'<font color="red">*</font> Confirma Contrase&ntilde;a:',
			'<input class="form-control focused e_requerido e_igual" name="Contrase_na1" id="Contrase_na1" type="password" data-igual_a="Contrase_na" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentContrase_na1, $InputTemplate);

		$ContentCorreoElectr_onico = array(
			'<font color="red">*</font> Correo Electr&oacute;nico:',
			'<input class="form-control focused e_requerido e_correo" name="CorreoElectr_onico" id="CorreoElectr_onico" type="text"/>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentCorreoElectr_onico, $InputTemplate);

		$OpcStatus['Name']  = "Status";
		$OpcStatus['Class'] = "form-control e_requerido";
		$Query              = CelaStatusQueryCombo();
		$ContentStatus = array(
			'<font color="red">*</font> Estado Actual:',
			FillSelect($Query, $OpcStatus, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentStatus, $InputTemplate);

		$OpcRol['Name']     = 'Rol';
		$OpcRol['Class']    = 'form-control  e_requerido';
		$OpcRol['EmptyValue']   = $SessionGroupId;
		$OpcRol['EmptyMessage'] = 'Mi Rol';

		$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

		$Query = CelaRolComboQuery(false, $Group);
		$ContentRol = array(
			'<font color="red">*</font> Rol:',
			FillSelect($Query, $OpcRol, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentRol, $InputTemplate);

//		$OpcAsesorAsignado['Name'] = 'Empleado';
//		$OpcAsesorAsignado['Class'] = ' form-control focused ';
//		$OpcAsesorAsignado['EmptyValue'] = '';
//		$OpcAsesorAsignado['EmptyMessage'] = 'SIN EMPLEADO ASIGNADO';
//
//		//$Empleados = str_replace(array('[', ']'), array(''), $GlobalConfig['eprs']);
//		$ConsultaAsesor = EmpleadoQueryCombo();
//
//		$ContentEmpleado = array(
//			'Empleado Asignado al Usuario',
//			'<div class="input-group">
//				' . FillSelect($ConsultaAsesor, $OpcAsesorAsignado, 1) . '
//				<div class="input-group-btn">
//					<a class="btn btn-success" id="EmpleadoCrear"><i class="fa fa-plus"></i></a>
//					<a class="btn btn-primary" id="EmpleadoActualiza"><i class="fa fa-refresh"></i></a>
//				</div>
//			</div>'
//		);
//		print ReplaceContentPage($TagsToReplace, $ContentEmpleado, $InputTemplate);

	?>
		<input type="hidden" name="CelaUsuarioInsert" value="CelaUsuarioInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction . (isset($_GET['OnSave']) ? '?' . EncodeThis('&OnSave=' . $_GET['OnSave']):'');
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>