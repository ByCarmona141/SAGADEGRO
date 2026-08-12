<form class="form-horizontal form_validate" method="POST" name="Form_Status" id="Form_Status" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$StatusId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$StatusQuery =  sprintf('SELECT * FROM Status WHERE `id` = %s;',
										GetSQLValueString($Key, 'int')
									);
		$StatusResult = $Connection -> query($StatusQuery);
		$StatusRecord = $StatusResult -> fetch_assoc();

		$StatusId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
					<?php
				$ContenDescripci_on = array(
					'<font color="red">*</font>Descripci&oacute;n',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' value="' .  $StatusRecord['Descripci_on'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
				$ContenOrigen = array(
					'<font color="red">*</font>Origen',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Origen' . $Key . '" id="Origen' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' value="' .  $StatusRecord['Origen'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenOrigen, $InputsTemplate);
					
				$OpcAcotaci_on['Name'] = 'Acotaci_on' . $Key;
				$OpcAcotaci_on['Class'] = ' form-control focused  e_requerido  e_longitud';
				$OpcAcotaci_on['Custom'] = ' data-rango=\'{"minimo":"1", "maximo":"", "mensaje":"Introduce un valor entre 1 y  caracteres de longitud"}\'';
				$Consulta =  array('success' => 'success', 'warning' => 'warning', 'danger' => 'danger', 'info' => 'info', 'active' => 'active', 'default' => 'default');
				$ContenAcotaci_on = array(
					'<font color="red">*</font>Acotaci&oacute;n',
					SFillSelect($Consulta, $OpcAcotaci_on, $StatusRecord['Acotaci_on'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContenAcotaci_on, $InputsTemplate);
					
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$StatusId = substr_replace($StatusId, '', -1);
?>
		<input type="hidden" name="StatusUpdate" value="StatusUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($StatusId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
				$Back = $FormAction;
				include '../CelaTemplate/ActiosnFormUpdate.php';
			?>
	</fieldset>
</form>