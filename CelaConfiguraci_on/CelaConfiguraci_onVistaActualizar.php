<form class="form-horizontal form_validate" method="POST" name="Form_CelaConfiguraci_on" id="Form_CelaConfiguraci_on" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
		<?php
			$Count=0;
			$CelaConfiguraci_onId='';

			/*Se carga la plantilla para los datos de entrada*/
			$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
			$TagsToReplace = array(
				'<!--#INPUTLABEL#-->',
				'<!--#INPUTELEMENT#-->'
			);

			/*Se obtienen todos los elementos que van a actualizar*/
			foreach($_GET['Key'] as $Key){
				$CelaConfiguraci_onQuery =  sprintf('SELECT * FROM CelaConfiguraci_on WHERE id = %s;',
												GetSQLValueString($Key, 'int')
											);
				$CelaConfiguraci_onResult = $Connection -> query($CelaConfiguraci_onQuery);
				$CelaConfiguraci_onRecord = $CelaConfiguraci_onResult -> fetch_assoc();
				
				$CelaConfiguraci_onId .= $Key . ',';
		?>
				<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
					<fieldset>
						<legend>Registro <?= $Key; ?></legend>
					<?php
						$ContentCode = array(
							'<font color="red">*</font> C&oacute;digo:',
							'<input class="form-control focused e_requerido" name="Code' . $Key . '" id="Code' . $Key . '" type="text" value="' . $CelaConfiguraci_onRecord['Code'] . '" />'
						);
						print ReplaceContentPage($TagsToReplace, $ContentCode, $InputTemplate);

						$ContentNombre = array(
							'<font color="red">*</font> Nombre:',
							'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaConfiguraci_onRecord['Nombre'] . '" />'
						);
						print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

						$ContentValor = array(
							'<font color="red">*</font> Valor:',
							'<input class="form-control focused " name="Valor' . $Key . '" id="Valor' . $Key . '" type="text" value="' . str_replace('"', '&quot;', $CelaConfiguraci_onRecord['Valor']) . '" />'
						);

						print ReplaceContentPage($TagsToReplace, $ContentValor, $InputTemplate);

						$OpcTipo['Name']    = 'Tipo' . $Key;
						$OpcTipo['Class']   = 'form-control e_requerido focused';
						$Query = array('text' => 'text', 'select' => 'select', 'textarea' => 'textarea', 'file' => 'file', 'checkbox' => 'checkbox');

						$ContentTipo = array(
							'<font color="red">*</font> Tipo:',
							SFillSelect($Query, $OpcTipo, $CelaConfiguraci_onRecord['Tipo'], 1)
						);
						print ReplaceContentPage($TagsToReplace, $ContentTipo, $InputTemplate);
					?>
						<div class="row mb-15px offset-2 Referencia<?= $Key; ?>" <?= ($CelaConfiguraci_onRecord['Tipo'] == 'select' ?'':'hidden="hidden"'); ?>>
							<label class="form-label col-md-12"><font color="red">*</font> Referencia:</label>
							<div class="col-md-5">
								<div class="input-group">
									<input class="form-control focused e_requerido" name="Referencia<?= $Key; ?>" id="Referencia<?= $Key; ?>" type="text" value="<?= $CelaConfiguraci_onRecord['Referencia']; ?>"/>
								</div>

							</div>
						</div>
					<?php
						$ContentClass = array(
							'Clases html',
							'<input class="form-control focused " name="Class' . $Key . '" id="Class' . $Key . '" type="text" value="' .  $CelaConfiguraci_onRecord['Class'] . '" />'
						);

						print ReplaceContentPage($TagsToReplace, $ContentClass, $InputTemplate);

						$OpcCategor_ia['Name']  = 'Categor_ia' . $Key;
						$OpcCategor_ia['Class'] = 'form-control focused e_requerido';
						$Query = CelaCategor_iaConfiguraci_onQueryCombo();

						$ContentValor = array(
							'<font color="red">*</font> Categor&iacute;a:',
							SFillSelect($Query, $OpcCategor_ia, $CelaConfiguraci_onRecord['Categor_ia'], 1)
						);
						print ReplaceContentPage($TagsToReplace, $ContentValor, $InputTemplate);

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
								GetSQLValueString(5, 'int'),
								GetSQLValueString(9, 'int')
							),
							'Roles'
						);
						$Roles = explode(',', $Roles);

						$OpcRol['Name']     = 'Rol' . $Key . '[]';
						$OpcRol['Class']    = 'form-control SelectRol';
						$OpcRol['Custom']   = 'multiple="multiple"';

						$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

						$Query = CelaRolComboQuery(false, $Group);
						;
						$ContentValor = array(
							'<font color="red">*</font> Roles que tienen acceso a esta Configuraci&oacute;n:',
							SFillSelect($Query, $OpcRol, $Roles)
						);
						print ReplaceContentPage($TagsToReplace, $ContentValor, $InputTemplate);
					?>
					</fieldset>
				</div>
		<?php
				$Count++;
			}

			$CelaConfiguraci_onId = substr_replace($CelaConfiguraci_onId, '', -1);
		?>
		<input type="hidden" name="CelaConfiguraci_onUpdate" value="CelaConfiguraci_onUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaConfiguraci_onId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction . '?' . EncodeThis('Action=Leer');
		$FormAction = isset($Privileges['Crear']) && $Privileges['Crear'] == 1 ? $FormAction . '?' . EncodeThis('Action=Leer'):'Escritorio';
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>