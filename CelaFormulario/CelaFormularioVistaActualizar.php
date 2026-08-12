
<form class="form-horizontal form_validate" method="POST" name="Form_CelaFormulario" id="Form_CelaFormulario" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaFormularioId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaFormularioQuery =  sprintf('SELECT * FROM CelaFormulario WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaFormularioResult = $Connection -> query($CelaFormularioQuery);
			$CelaFormularioRecord = $CelaFormularioResult -> fetch_assoc();
			
			$CelaFormularioId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaFormularioRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

					$ContentDescripci_on = array(
						'<font color="red">*</font> Descripci&oacute;n:',
						'<input class="form-control focused e_requerido" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '" type="text" value="' . $CelaFormularioRecord['Descripci_on'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);

					$ContentRuta = array(
						'<font color="red">*</font> Ruta:',
						'<input class="form-control focused e_requerido" name="Ruta' . $Key . '" id="Ruta' . $Key . '" type="text" value="' . $CelaFormularioRecord['Ruta'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentRuta, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaFormularioId = substr_replace($CelaFormularioId, '', -1);
	?>
		<input type="hidden" name="CelaFormularioUpdate" value="CelaFormularioUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaFormularioId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
