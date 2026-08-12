<form class="form-horizontal form_validate" method="POST" name="Form_Rack" id="Form_Rack" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$RackId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$RackQuery =  sprintf('SELECT * FROM Rack WHERE id = %s;',
										GetSQLValueString($Key, 'int')
									);
		$RackResult = $Connection -> query($RackQuery);
		$RackRecord = $RackResult -> fetch_assoc();

		$RackId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContenNombre = array(
					'<font color="red">*</font>Nombre',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre' . $Key . '" id="Nombre' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' value="' .  $RackRecord['Nombre'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$RackId = substr_replace($RackId, '', -1);
?>
		<input type="hidden" name="RackUpdate" value="RackUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($RackId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
	</fieldset>
</form>