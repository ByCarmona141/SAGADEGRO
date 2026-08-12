
<form class="form-horizontal form_validate" method="POST" name="Form_CelaWSDL" id="Form_CelaWSDL" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
<?php
	$Count=0;
	$CelaWSDLId='';
	/*Se carga la plantilla para los datos de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$CelaWSDLQuery =  sprintf('SELECT * FROM CelaWSDL WHERE id = %s;',
									GetSQLValueString($Key, 'int')
								);
		$CelaWSDLResult = $Connection -> query($CelaWSDLQuery);
		$CelaWSDLRecord = $CelaWSDLResult -> fetch_assoc();

		$CelaWSDLId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContentNombre = array(
					'<font color="red">*</font> Nombre:',
					'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaWSDLRecord['Nombre'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

				$ContentURL = array(
					'<font color="red">*</font> URL:',
					'<input class="form-control focused e_requerido " name="URL' . $Key . '" id="URL' . $Key . '" type="text" value="' . $CelaWSDLRecord['URL'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentURL, $InputTemplate);

				$ContentUsuario = array(
					' Usuario:',
					'<input class="form-control focused e_usuario " name="Usuario' . $Key . '" id="Usuario' . $Key . '" type="text" value="' . $CelaWSDLRecord['Usuario'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentUsuario, $InputTemplate);

				$ContentContrase_na = array(
					' Contrase&ntilde;a:',
					'<input class="form-control focused " name="Contrase_na' . $Key . '" id="Contrase_na' . $Key . '" type="text" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentContrase_na, $InputTemplate);

				$ContentTipo = array(
					'<font color="red">*</font> Tipo:',
					'<input class="form-control focused e_requerido" name="Tipo' . $Key . '" id="Tipo' . $Key . '" type="text" value="' . $CelaWSDLRecord['Tipo'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContentTipo, $InputTemplate);
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$CelaWSDLId = substr_replace($CelaWSDLId, '', -1);
?>
		<input type="hidden" name="CelaWSDLUpdate" value="CelaWSDLUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaWSDLId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
