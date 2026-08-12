<form class="form-horizontal form_validate" method="POST" name="Form_CelaAcci_on" id="Form_CelaAcci_on" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaAcci_onId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaAcci_onQuery = sprintf('SELECT * FROM CelaAcci_on WHERE id = %s;',
									GetSQLValueString($Key, 'int')
								);
			$CelaAcci_onResult = $Connection -> query($CelaAcci_onQuery);
			$CelaAcci_onRecord = $CelaAcci_onResult -> fetch_assoc();

			$CelaAcci_onId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key .'" id="Nombre' . $Key .'" type="text" value="' . $CelaAcci_onRecord['Nombre'] .'"/>'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaAcci_onId = substr_replace($CelaAcci_onId, '', -1);
	?>
		<input type="hidden" name="CelaAcci_onUpdate" value="CelaAcci_onUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaAcci_onId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
		<?php
			$Back = $FormAction;
			include '../CelaTemplate/ActiosnFormUpdate.php';
		?>
	</fieldset>
</form>
