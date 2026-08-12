
<form class="form-horizontal form_validate" method="POST" name="Form_CelaOrigen" id="Form_CelaOrigen" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaOrigenId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaOrigenQuery =  sprintf('SELECT * FROM CelaOrigen WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaOrigenResult = $Connection -> query($CelaOrigenQuery);
			$CelaOrigenRecord = $CelaOrigenResult -> fetch_assoc();
			
			$CelaOrigenId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaOrigenRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

					$ContentTabla = array(
						'<font color="red">*</font> Tabla:',
						'<input class="form-control focused e_requerido" name="Tabla' . $Key . '" id="Tabla' . $Key . '" type="text" value="' . $CelaOrigenRecord['Tabla'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentTabla, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaOrigenId = substr_replace($CelaOrigenId, '', -1);
	?>
		<input type="hidden" name="CelaOrigenUpdate" value="CelaOrigenUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaOrigenId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
