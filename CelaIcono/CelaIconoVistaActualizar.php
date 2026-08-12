
<form class="form-horizontal form_validate" method="POST" name="Form_CelaIcono" id="Form_CelaIcono" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaIconoId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaIconoQuery =  sprintf('SELECT * FROM CelaIcono WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaIconoResult = $Connection -> query($CelaIconoQuery);
			$CelaIconoRecord = $CelaIconoResult -> fetch_assoc();
			
			$CelaIconoId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaIconoRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);


					$ContentCodigo = array(
						'Codigo:',
						'<input class="form-control focused" name="Codigo' . $Key . '" id="Codigo' . $Key . '" type="text" value="' . $CelaIconoRecord['Codigo'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentCodigo, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaIconoId = substr_replace($CelaIconoId, '', -1);
	?>
		<input type="hidden" name="CelaIconoUpdate" value="CelaIconoUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaIconoId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
