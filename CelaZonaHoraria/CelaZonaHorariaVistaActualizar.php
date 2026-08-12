<form class="form-horizontal form_validate" method="POST" name="Form_CelaZonaHoraria" id="Form_CelaZonaHoraria" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaZonaHorariaId = '';
		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaZonaHorariaQuery =  sprintf('SELECT * FROM CelaZonaHoraria WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaZonaHorariaResult = $Connection -> query($CelaZonaHorariaQuery);
			$CelaZonaHorariaRecord = $CelaZonaHorariaResult -> fetch_assoc();

			$CelaZonaHorariaId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php

					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaZonaHorariaRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaZonaHorariaId = substr_replace($CelaZonaHorariaId, '', -1);
	?>
		<input type="hidden" name="CelaZonaHorariaUpdate" value="CelaZonaHorariaUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaZonaHorariaId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
