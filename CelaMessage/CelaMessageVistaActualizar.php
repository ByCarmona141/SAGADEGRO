
<form class="form-horizontal form_validate" method="POST" name="Form_CelaMessage" id="Form_CelaMessage" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaMessageId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaMessageQuery =  sprintf('SELECT * FROM CelaMessage WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaMessageResult = $Connection -> query($CelaMessageQuery);
			$CelaMessageRecord = $CelaMessageResult -> fetch_assoc();
			
			$CelaMessageId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaMessageRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

					$ContentTabla = array(
						'<font color="red">*</font> Tabla:',
						'<input class="form-control focused e_requerido" name="Tabla' . $Key . '" id="Tabla' . $Key . '" type="text" value="' . $CelaMessageRecord['Tabla'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentTabla, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaMessageId = substr_replace($CelaMessageId, '', -1);
	?>
		<input type="hidden" name="CelaMessageUpdate" value="CelaMessageUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaMessageId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
