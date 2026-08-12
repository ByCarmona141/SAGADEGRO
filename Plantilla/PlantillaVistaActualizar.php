<form class="form-horizontal form_validate" method="POST" name="Form_Plantilla" id="Form_Plantilla" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$PlantillaId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$PlantillaQuery =  sprintf('SELECT * FROM Plantilla WHERE id = %s;',
										GetSQLValueString($Key, 'int')
									);
		$PlantillaResult = $Connection -> query($PlantillaQuery);
		$PlantillaRecord = $PlantillaResult -> fetch_assoc();

		$PlantillaId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContenNombre = array(
					'<font color="red">*</font>Nombre',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre' . $Key . '" id="Nombre' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' value="' .  $PlantillaRecord['Nombre'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);
					
				$ContenDescripci_on = array(
					'Descripci&oacute;n',
					'<textarea class=" form-control focused   e_longitud" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '"  data-rango=\'{"minimo":"0", "maximo":"512", "mensaje":"Introduce un valor entre 0 y 512 caracteres de longitud"}\' rows="3">' . $PlantillaRecord['Descripci_on'] . '</textarea>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
				$ContenPlantilla = array(
					'<font color="red">*</font>Plantilla',
					'<textarea type="text" class=" form-control focused  e_requerido " name="Plantilla' . $Key . '" id="Plantilla' . $Key . '" rows="30" >' .  $PlantillaRecord['Plantilla'] . '</textarea>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenPlantilla, $InputsTemplate);
					
				$OpcTipoPlantilla['Name'] = 'TipoPlantilla' . $Key;
				$OpcTipoPlantilla['Class'] = ' form-control focused  e_requerido  e_rango e_numero';
				$OpcTipoPlantilla['Custom'] = ' data-rango=\'{"minimo":"0", "maximo":"255", "mensaje":"Introduce un valor entre 0 y 255"}\'';
				$Consulta = TipoPlantillaQueryCombo();
				$ContenTipoPlantilla = array(
					'<font color="red">*</font>Tipo Plantilla',
					SFillSelect($Consulta, $OpcTipoPlantilla, $PlantillaRecord['TipoPlantilla'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContenTipoPlantilla, $InputsTemplate);
					
				$OpcEstaVigente['Name'] = 'EstaVigente' . $Key;
				$OpcEstaVigente['Class'] = ' form-control focused  e_requerido  e_rango e_numero';
				$OpcEstaVigente['Custom'] = ' data-rango=\'{"minimo":"-128", "maximo":"127", "mensaje":"Introduce un valor entre -128 y 127"}\'';
				$Consulta = Opci_onQueryCombo();
				$ContenEstaVigente = array(
					'<font color="red">*</font>Esta Vigente',
					SFillSelect($Consulta, $OpcEstaVigente, $PlantillaRecord['EstaVigente'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContenEstaVigente, $InputsTemplate);

				$OpcTama_no['Name'] = 'Tama_no' . $Key;
				$OpcTama_no['Class'] = ' form-control focused  e_requerido';
				$Consulta = array(
					'Legal' => 'Oficio',
					'Letter' => 'Carta',
					'Tabloid' => 'Doble Carta',
					'A4' => 'A4',
					'A3' => 'A3',
					'B5' => 'B5',
					'B4' => 'B4',
					'B3' => 'B3'
				);

				$ContenTama_no = array(
					'<font color="red">*</font>Tama&ntilde;o',
					SFillSelect($Consulta, $OpcTama_no, $PlantillaRecord['Tama_no'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContenTama_no, $InputsTemplate);
					
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$PlantillaId = substr_replace($PlantillaId, '', -1);
?>
		<input type="hidden" name="PlantillaUpdate" value="PlantillaUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($PlantillaId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
	</fieldset>
</form>