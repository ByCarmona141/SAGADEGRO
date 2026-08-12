<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaRol" id="Form_CelaRol" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentNombre = array(
			'<font color="red">*</font> Nombre:',
			'<input class="form-control focused e_requerido" name="Nombre" id="Nombre" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentSiglas = array(
			'<font color="red">*</font> Siglas:',
			'<input class="form-control focused e_requerido" name="Siglas" id="Siglas" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentSiglas, $InputTemplate);

		$ContentDescripci_on = array(
			'Descripci&oacute;n:',
			'<input class="form-control focused" name="Descripci_on" id="Descripci_on" type="text"/>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);

		$OpcStatus['Name']  = 'Status';
		$OpcStatus['Class'] = 'form-control e_requerido';
		$Query              = CelaStatusQueryCombo();
		$ContentStatus = array(
			'<font color="red">*</font> Estado Actual:',
			FillSelect($Query, $OpcStatus, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentStatus, $InputTemplate);

		$OpcRol['Name']  = 'Grupo';
		$OpcRol['Class'] = 'form-control e_requerido';
		$OpcRol['EmptyValue'] = $SessionGroupId;
		$OpcRol['EmptyMessage'] = 'MI GRUPO';

		$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');
		$Query = CelaRolComboQuery(false, $Group);
		$ContentGrupo = array(
			'<font color="red">*</font> Grupo:',
			FillSelect($Query, $OpcRol, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentGrupo, $InputTemplate);

		$OpcTema['Name']  = 'Tema';
		$OpcTema['Class'] = 'form-control';
		$OpcTema['EmptyValue'] = '';
		$OpcTema['EmptyMessage'] = 'DEFAULT';

		$Query = CelaTemaQueryCombo();
		$ContentTema = array(
			'<font color="red">*</font> Colores del Tema:',
			FillSelect($Query, $OpcTema, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentTema, $InputTemplate);

		if(isset($Privileges['Administrar']) && $Privileges['Administrar'] == 1){
			$ContenClonarPrivilegios = array(
				'&iquest;Clonar Privilegios de Alg&uacute;n Otro Grupo?:',
				'<input type="checkbox" value="1" name="ClonarPrivilegios" id="ClonarPrivilegios">'
			);
			print ReplaceContentPage($TagsToReplace, $ContenClonarPrivilegios, $InputTemplate);

			$OpcRol['Name']  = 'Rol';
			$OpcRol['Class'] = 'form-control e_requerido';
			$OpcRol['EmptyValue'] = $SessionGroupId;
			$OpcRol['EmptyMessage'] = 'MI GRUPO';

			$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');
			$Query = CelaRolComboQuery(false, $Group);

			print '
			<div class="form-group ClonePrivileges" hidden="hidden">
				<div class="row mb-15px offset-2">
					<label class="form-label col-md-12"><font color="red">*</font> Clonar Privilegios de:</label>
					<div class="col-md-5">
						<div class="input-group">
							' . FillSelect($Query, $OpcRol, 1) . '
						</div>

					</div>
				</div>
			</div>';

			$OpcPrivilegios['Name']     = 'Privilegios[]';
			$OpcPrivilegios['Class']    = 'form-control';
			$OpcPrivilegios['Custom']   = 'multiple="multiple"';

			$Admin  =   strtoupper(
				GetValue(
					sprintf('SELECT `Nombre` FROM CelaRol WHERE `id` = %s;',
						GetSQLValueString($SessionGroupId, 'int')
					),
					'Nombre'
				)
			);

			if($Admin == "DEVELOPER" || $Admin == "DESARROLLADOR"){
				$Query = CelaPrivilegioQueryCombo();
			}else{
				$Query = CelaPrivilegioQueryCombo('InPrivilege', $SessionGroupId, 4);
			}
			$ContentPrivilegios = array(
				'Privilegios que Adminsitra este Rol:',
				FillSelect($Query, $OpcPrivilegios)
			);
			print ReplaceContentPage($TagsToReplace, $ContentPrivilegios, $InputTemplate);
		}

	?>
		<input type="hidden" name="CelaRolInsert" value="CelaRolInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>