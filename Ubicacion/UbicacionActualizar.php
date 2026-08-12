<form class="form-horizontal form_validate" method="POST" name="Form_Ubicacion" id="Form_Ubicacion" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$UbicacionId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$UbicacionQuery =  sprintf('SELECT * FROM Ubicacion WHERE id = %s;',
										GetSQLValueString($Key, 'int')
									);
		$UbicacionResult = $Connection -> query($UbicacionQuery);
		$UbicacionRecord = $UbicacionResult -> fetch_assoc();

		$UbicacionId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContenArea = array(
					'<font color="red">*</font>Area',
					'<input type="text" class=" form-control focused  e_requerido e_longitud" name="Area' . $Key . '" id="Area' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' value="' .  $UbicacionRecord['Area'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenArea, $InputsTemplate);

                $ContenPiso = array(
					'<font color="red">*</font>Piso',
					'<input type="number" class=" form-control focused  e_requerido e_numero  e_longitud" name="Piso' . $Key . '" id="Piso' . $Key . '"  data-rango=\'{"minimo":"0", "maximo":"255", "mensaje":"Introduce un valor entre 0 y 255 caracteres de longitud"}\' value="' .  $UbicacionRecord['Piso'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenPiso, $InputsTemplate);
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$UbicacionId = substr_replace($UbicacionId, '', -1);
?>
		<input type="hidden" name="UbicacionUpdate" value="UbicacionUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($UbicacionId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
	</fieldset>
</form>