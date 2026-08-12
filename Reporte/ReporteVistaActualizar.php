<form class="form-horizontal form_validate" method="POST" name="Form_Reporte" id="Form_Reporte" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$ReporteId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$ReporteQuery =  sprintf('SELECT * FROM Reporte WHERE id = %s;',
										GetSQLValueString($Key, 'int')
									);
		$ReporteResult = $Connection -> query($ReporteQuery);
		$ReporteRecord = $ReporteResult -> fetch_assoc();

		$ReporteId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContenDescripci_on = array(
					'<font color="red">*</font>Descripci&oacute;n',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"128", "mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}\' value="' .  $ReporteRecord['Descripci_on'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
				$ContenFormato = array(
					'Formato',
					'<input type="text" class=" form-control focused   e_longitud" name="Formato' . $Key . '" id="Formato' . $Key . '"  data-rango=\'{"minimo":"0", "maximo":"", "mensaje":"Introduce un valor entre 0 y  caracteres de longitud"}\' value="' .  $ReporteRecord['Formato'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenFormato, $InputsTemplate);
					
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$ReporteId = substr_replace($ReporteId, '', -1);
?>
		<input type="hidden" name="ReporteUpdate" value="ReporteUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($ReporteId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
	</fieldset>
</form>