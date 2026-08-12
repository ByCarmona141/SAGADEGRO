<form class="form-horizontal form_validate" method="POST" name="Form_Opci_on" id="Form_Opci_on" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$Opci_onId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$Opci_onQuery =  sprintf('SELECT * FROM Opci_on WHERE id = %s;',
										GetSQLValueString($Key, 'int')
									);
		$Opci_onResult = $Connection -> query($Opci_onQuery);
		$Opci_onRecord = $Opci_onResult -> fetch_assoc();

		$Opci_onId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$Contenid = array(
					'<font color="red">*</font>id',
					'<input type="text" class=" form-control focused  e_requerido  e_rango e_numero" name="id' . $Key . '" id="id' . $Key . '"  data-rango=\'{"minimo":"-128", "maximo":"127", "mensaje":"Introduce un valor entre -128 y 127"}\' value="' .  $Opci_onRecord['id'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $Contenid, $InputsTemplate);
					
				$ContenDescripci_on = array(
					'<font color="red">*</font>Descripci&oacute;n',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"4", "mensaje":"Introduce un valor entre 1 y 4 caracteres de longitud"}\' value="' .  $Opci_onRecord['Descripci_on'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$Opci_onId = substr_replace($Opci_onId, '', -1);
?>
		<input type="hidden" name="Opci_onUpdate" value="Opci_onUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($Opci_onId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
	</fieldset>
</form>