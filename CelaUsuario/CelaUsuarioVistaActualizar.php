<form class="form-horizontal form_validate" method="POST" name="Form_CelaUsuario" id="Form_CelaUsuario" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
<?php
	$Count=0;
	$CelaUsuarioId='';
	/*Se carga la plantilla para los datos de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$CelaUsuarioQuery = sprintf('SELECT * FROM CelaUsuario WHERE id = %s;',
								GetSQLValueString($Key, 'int')
							);
		$CelaUsuarioResult = $Connection -> query($CelaUsuarioQuery);
		$CelaUsuarioRecord = $CelaUsuarioResult -> fetch_assoc();
		
		$CelaUsuarioId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php

				$ContentNombreCompleto = array(
					'<font color="red">*</font> Nombre Completo:',
					'<input class="form-control focused e_requerido" name="NombreCompleto' . $Key . '" id="NombreCompleto' . $Key . '" type="text" value="' . $CelaUsuarioRecord['NombreCompleto'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentNombreCompleto, $InputTemplate);

				$ContentUsuario = array(
					' <font color="red">*</font> Usuario:',
					'<input class="form-control focused e_usuario e_requerido" name="Usuario' . $Key . '" id="Usuario' . $Key . '" type="text" value="' . $CelaUsuarioRecord['Usuario'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentUsuario, $InputTemplate);

				$ContentContrase_na = array(
					'<font color="red">*</font> Contrase&ntilde;a:',
					'<input class="form-control focused e_password" name="Contrase_na' . $Key . '" id="Contrase_na' . $Key . '" type="password" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentContrase_na, $InputTemplate);

				$ContentCorreoElectr_onico = array(
					'<font color="red">*</font> Correo Electr&oacute;nico:',
					'<input class="form-control focused e_requerido " name="CorreoElectr_onico' . $Key . '" id="CorreoElectr_onico' . $Key . '" type="text" value="' . $CelaUsuarioRecord['CorreoElectr_onico'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentCorreoElectr_onico, $InputTemplate);

				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1){
					$OpcStatus['Name']  = 'Status' . $Key;
					$OpcStatus['Class'] = 'form-control e_requerido';
					$Query              = CelaStatusQueryCombo();
					$ContentStatus = array(
						' <font color="red">*</font> Estado Actual: ',
						SFillSelect($Query, $OpcStatus, $CelaUsuarioRecord['Status'])
					);
					print ReplaceContentPage($TagsToReplace, $ContentStatus, $InputTemplate);

					$OpcRol['Name']         = 'Rol' . $Key;
					$OpcRol['Class']        = 'form-control  e_requerido';
					$OpcRol['EmptyValue']   = $SessionGroupId;
					$OpcRol['EmptyMessage'] = 'Mi Rol';

					$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

					$Query  = CelaRolComboQuery(false, $Group);
					$ContentRol = array(
						' <font color="red">*</font> Rol: ',
						SFillSelect($Query, $OpcRol, $CelaUsuarioRecord['Rol'], 1)    
					);
					print ReplaceContentPage($TagsToReplace, $ContentRol, $InputTemplate);

				}

//				$OpcAsesorAsignado['Name'] = 'Empleado' . $Key;
//				$OpcAsesorAsignado['Class'] = ' form-control focused ';
//				$OpcAsesorAsignado['EmptyValue'] = '';
//				$OpcAsesorAsignado['EmptyMessage'] = 'SIN EMPLEADO ASIGNADO';
//
//				//$Empleados = str_replace(array('[', ']'), array(''), $GlobalConfig['eprs']);
//				$ConsultaAsesor = EmpleadoQueryCombo();
//
//				$ContentEmpleado = array(
//					'Empleado Asignado al Usuario',
//					'<div class="input-group">
//				' . SFillSelect($ConsultaAsesor, $OpcAsesorAsignado, $CelaUsuarioRecord['Empleado'], 1) . '
//				<div class="input-group-btn">
//					<a class="btn btn-success" id="EmpleadoCrear' . $Key . '"><i class="fa fa-plus"></i></a>
//					<a class="btn btn-primary" id="EmpleadoActualiza' . $Key . '"><i class="fa fa-refresh"></i></a>
//				</div>
//			</div>'
//				);
//				print ReplaceContentPage($TagsToReplace, $ContentEmpleado, $InputTemplate);
			?>
				<input type="hidden" name="<?= Encrypt('Contrase_naant' . $Key, $Random); ?>" value="<?= Encrypt($CelaUsuarioRecord['Contrase_na'], $Random); ?>">
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$CelaUsuarioId = substr_replace($CelaUsuarioId, '', -1);
?>
		<input type="hidden" name="CelaUsuarioUpdate" value="CelaUsuarioUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaUsuarioId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = 'CelaUsuario';
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
