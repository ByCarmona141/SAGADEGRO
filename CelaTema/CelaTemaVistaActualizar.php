<form class="form-horizontal form_validate" method="POST" name="Form_CelaTema" id="Form_CelaTema" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
<?php
	$Count=0;
	$CelaTemaId='';
	/*Se carga la plantilla para los datos de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$CelaTemaQuery =  sprintf('SELECT * FROM CelaTema WHERE id = %s;',
									GetSQLValueString($Key, 'int')
								);
		$CelaTemaResult = $Connection -> query($CelaTemaQuery);
		$CelaTemaRecord = $CelaTemaResult -> fetch_assoc();

		$CelaTemaId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContentNombre = array(
					'<font color="red">*</font> Nombre:',
					'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaTemaRecord['Nombre'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

				$ContentRuta = array(
					'<font color="red">*</font> Ruta:',
					'<input class="form-control focused e_requerido " name="Ruta' . $Key . '" id="Ruta' . $Key . '" type="text" value="' . $CelaTemaRecord['Ruta'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentRuta, $InputTemplate);

				$ContentImagen = array(
					'Imagen:',
					'<input class="form-control focused " name="Imagen' . $Key . '" id="Imagen' . $Key . '" type="text" value="' . $CelaTemaRecord['Imagen'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContentImagen, $InputTemplate);
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$CelaTemaId = substr_replace($CelaTemaId, '', -1);
?>
		<input type="hidden" name="CelaTemaUpdate" value="CelaTemaUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaTemaId, $Random);	?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
