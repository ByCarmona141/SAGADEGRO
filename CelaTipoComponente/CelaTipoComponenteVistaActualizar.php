
<form class="form-horizontal form_validate" method="POST" name="Form_CelaTipoComponente" id="Form_CelaTipoComponente" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaTipoComponenteId = '';
		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaTipoComponenteQuery =  sprintf('SELECT * FROM CelaTipoComponente WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaTipoComponenteResult = $Connection -> query($CelaTipoComponenteQuery);
			$CelaTipoComponenteRecord = $CelaTipoComponenteResult -> fetch_assoc();

			$CelaTipoComponenteId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre: </label>',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value=" '. $CelaTipoComponenteRecord['Nombre'] . '"/>'
					);

					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaTipoComponenteId = substr_replace($CelaTipoComponenteId, '', -1);
	?>
		<input type="hidden" name="CelaTipoComponenteUpdate" value="CelaTipoComponenteUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaTipoComponenteId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>