
<form class="form-horizontal form_validate" method="POST" name="Form_CelaCategor_iaConfiguraci_on" id="Form_CelaCategor_iaConfiguraci_on" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count = 0;
		$CelaCategor_iaConfiguraci_onId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaCategor_iaConfiguraci_onQuery =  sprintf('SELECT * FROM CelaCategor_iaConfiguraci_on WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaCategor_iaConfiguraci_onResult = $Connection -> query($CelaCategor_iaConfiguraci_onQuery);
			$CelaCategor_iaConfiguraci_onRecord = $CelaCategor_iaConfiguraci_onResult -> fetch_assoc();

			$CelaCategor_iaConfiguraci_onId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombreCategor_ia = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="NombreCategor_ia' . $Key . '" id="NombreCategor_ia' . $Key . '" type="text" value="' .  $CelaCategor_iaConfiguraci_onRecord['NombreCategor_ia'] . '"/>'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombreCategor_ia, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaCategor_iaConfiguraci_onId = substr_replace($CelaCategor_iaConfiguraci_onId, '', -1)
	?>
		<input type="hidden" name="CelaCategor_iaConfiguraci_onUpdate" value="CelaCategor_iaConfiguraci_onUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaCategor_iaConfiguraci_onId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
