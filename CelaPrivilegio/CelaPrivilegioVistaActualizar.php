
<form class="form-horizontal form_validate" method="POST" name="Form_CelaPrivilegio" id="Form_CelaPrivilegio" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaPrivilegioId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaPrivilegioQuery =  sprintf('SELECT * FROM CelaPrivilegio WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaPrivilegioResult = $Connection -> query($CelaPrivilegioQuery);
			$CelaPrivilegioRecord = $CelaPrivilegioResult -> fetch_assoc();
			
			$CelaPrivilegioId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaPrivilegioRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

					$ContentDescripci_on = array(
						'<font color="red">*</font>Descripci&oacute;n:',
						'<input class="form-control focused e_requerido" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '" type="text" value="' . $CelaPrivilegioRecord['Descripci_on'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);


					$ContentAcci_on = array(
						'Acci&oacute;n:',
						'<input class="form-control focused" name="Acci_on' . $Key . '" id="Acci_on' . $Key . '" type="text" value="' . $CelaPrivilegioRecord['Acci_on'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentAcci_on, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaPrivilegioId = substr_replace($CelaPrivilegioId, '', -1);
	?>
		<input type="hidden" name="CelaPrivilegioUpdate" value="CelaPrivilegioUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaPrivilegioId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
