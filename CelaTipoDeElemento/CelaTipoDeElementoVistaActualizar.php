
<form class="form-horizontal form_validate" method="POST" name="Form_CelaTipoDeElemento" id="Form_CelaTipoDeElemento" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaTipoDeElementoId = '';
		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaTipoDeElementoQuery =  sprintf('SELECT * FROM CelaTipoDeElemento WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaTipoDeElementoResult = $Connection -> query($CelaTipoDeElementoQuery);
			$CelaTipoDeElementoRecord = $CelaTipoDeElementoResult -> fetch_assoc();

			$CelaTipoDeElementoId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentNombre = array(
						'<font color="red">*</font> Nombre:',
						'<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" type="text" value="' . $CelaTipoDeElementoRecord['Nombre'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaTipoDeElementoId = substr_replace($CelaTipoDeElementoId, '', -1);
	?>
		<input type="hidden" name="CelaTipoDeElementoUpdate" value="CelaTipoDeElementoUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaTipoDeElementoId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
