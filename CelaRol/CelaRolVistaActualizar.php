<form class="form-horizontal form_validate" method="POST" name="Form_CelaRol" id="Form_CelaRol" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
<?php
	$Count=0;
	$CelaRolId='';
	/*Se carga la plantilla para los datos de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$CelaRolQuery = sprintf('SELECT * FROM CelaRol WHERE id = %s;',
								GetSQLValueString($Key, 'int')
							);
		$CelaRolResult = $Connection -> query($CelaRolQuery);
		$CelaRolRecord = $CelaRolResult -> fetch_assoc();

		$CelaRolId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContentNombre = array(
					'<font color="red">*</font> Nombre:',
					'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaRolRecord['Nombre'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

				$ContentSiglas = array(
					'<font color="red">*</font> Siglas:',
					'<input class="form-control focused e_requerido" name="Siglas' . $Key . '" id="Siglas' . $Key . '" type="text" value="' . $CelaRolRecord['Siglas'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContentSiglas, $InputTemplate);

				$ContentDescripci_on = array(
					'Descripci&oacute;n:',
					'<input class="form-control focused" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '" type="text" value="' . $CelaRolRecord['Descripci_on'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);

				$OpcStatus['Name']  = 'Status' . $Key;
				$OpcStatus['Class'] = 'form-control e_requerido';
				$Query              = CelaStatusQueryCombo();
				$ContentStatus = array(
					'<font color="red">*</font> Estado Actual:',
					SFillSelect($Query, $OpcStatus, $CelaRolRecord['Status'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContentStatus, $InputTemplate);

				$OpcGrupo['Name']          = 'Grupo' . $Key;
				$OpcGrupo['Class']         = 'form-control e_requerido';
				$OpcGrupo['EmptyValue']    = $SessionGroupId;
				$OpcGrupo['EmptyMessage']  = 'MI GRUPO';

				$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');
				$Query = CelaRolComboQuery('NotMe', $Group, $Key);
				$ContentGrupo = array(
					'<font color="red">*</font> Grupo:',
					SFillSelect($Query, $OpcGrupo, $CelaRolRecord['Grupo'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContentGrupo, $InputTemplate);

				$OpcTema['Name']  = 'Tema' . $Key;
				$OpcTema['Class'] = 'form-control';
				$OpcTema['EmptyValue'] = '';
				$OpcTema['EmptyMessage'] = 'DEFAULT';

				$Query = CelaTemaQueryCombo();
				$ContentTema = array(
					'<font color="red">*</font> Colores del Tema:',
					SFillSelect($Query, $OpcTema, $CelaRolRecord['Tema'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContentTema, $InputTemplate);

				if(isset($Privileges['Administrar']) && $Privileges['Administrar'] == 1){
					$Privilegio =   GetValue(
										sprintf('SELECT GROUP_CONCAT(Privilegios.Tupla) as Roles
												 FROM ( SELECT c.Tupla, 1 as Comodin
												        FROM CelaPrivilegios c
												        WHERE
														     c.TuplaAcceso = %s AND
														     c.Origen = %s AND
														     c.Privilegio = %s
												      ) as Privilegios
											      GROUP BY Privilegios.Comodin;',
										GetSQLValueString($Key, 'int'),
										GetSQLValueString(4, 'int'),
										GetSQLValueString(9, 'int')
									),
									'Roles'
								);

					$Privilegio = explode(',', $Privilegio);

					$OpcPrivilegios['Name']     = 'Privilegios' . $Key . '[]';
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
						SFillSelect($Query, $OpcPrivilegios, $Privilegio)
					);
					print ReplaceContentPage($TagsToReplace, $ContentPrivilegios, $InputTemplate);
				}
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$CelaRolId = substr_replace($CelaRolId, '', -1);
?>
		<input type="hidden" name="CelaRolUpdate" value="CelaRolUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaRolId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
