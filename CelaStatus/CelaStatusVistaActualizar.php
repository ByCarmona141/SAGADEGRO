
<form class="form-horizontal form_validate" method="POST" name="Form_CelaStatus" id="Form_CelaStatus" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaStatusId = '';
		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaStatusQuery =  sprintf('SELECT * FROM CelaStatus WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaStatusResult = $Connection -> query($CelaStatusQuery);
			$CelaStatusRecord = $CelaStatusResult -> fetch_assoc();
			if($Count == 0){
				$CelaStatusId = $Key;
			}else{
				$CelaStatusId .= ',' . $Key;
			}
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaStatusRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaStatusId = substr_replace($CelaStatusId, '', -1);
	?>
		<input type="hidden" name="CelaStatusUpdate" value="CelaStatusUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaStatusId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
