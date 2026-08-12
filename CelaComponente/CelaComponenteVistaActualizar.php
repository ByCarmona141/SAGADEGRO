
<form class="form-horizontal form_validate" method="POST" name="Form_CelaComponente" id="Form_CelaComponente" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaComponenteId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaComponenteQuery =  sprintf('SELECT * FROM CelaComponente WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaComponenteResult = $Connection -> query($CelaComponenteQuery);
			$CelaComponenteRecord = $CelaComponenteResult -> fetch_assoc();

			$CelaComponenteId .= $Key . ',';

	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentFechaSolicitud = array(
						'<font color="red">*</font> Fecha de Solicitud:',
						'<input class="form-control focused e_requerido e_fecha_hora" name="FechaSolicitud' .  $Key . '" id="FechaSolicitud' . $Key . '" type="text" value="' . date('Y-m-d H:i:s') .'" data-rango=\'{"minimo":"1900-01-01 00:00:00", "msximo":"' . date('Y-m-d H:i:s') . '", "mensaje":"Fecha "}\' value="' . $CelaComponenteRecord['FechaSolicitud'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentFechaSolicitud, $InputTemplate);

					$ContentDescripci_on = array(
						'<font color="red">*</font> Descripci&oacute;n:',
						'<textarea class="form-control focused e_requerido" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '" rows="3">' . $CelaComponenteRecord['Descripci_on'] . '</textarea>'
					);
					print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);


					$OpcSolicitante['Name']     = 'Solicitante' . $Key;
					$OpcSolicitante['Class']    = 'form-control focused e_requerido';
					$Query = CelaUsuarioComboQuery();

					$ContentSolicitante = array(
						'<font color="red">*</font> Nombre de Quien Solicita:',
						SFillSelect($Query, $OpcSolicitante, $CelaComponenteRecord['Solicitante'], 1)
					);
					print ReplaceContentPage($TagsToReplace, $ContentSolicitante, $InputTemplate);

					$OpcTipoDeComponente['Name']  = 'TipoDeComponente' . $Key;
					$OpcTipoDeComponente['Class'] = 'form-control focused e_requerido';
					$Query = CelaTipoComponenteQueryCombo();

					$ContentSolicitante = array(
						'<font color="red">*</font> Tipo de Componente:',
						SFillSelect($Query, $OpcTipoDeComponente, $CelaComponenteRecord['TipoDeComponente'], 1)
					);
					print ReplaceContentPage($TagsToReplace, $ContentSolicitante, $InputTemplate);

				?>
				</fieldset>
			</div>
	<?php
			$Count++;
			$Component = $CelaComponenteRecord['Componente'];
		}

		$CelaComponenteId = substr_replace($CelaComponenteId, '', -1);
	?>
		<input type="hidden" name="CelaComponenteUpdate" value="CelaComponenteUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaComponenteId, $Random); ?>">
		<input type="hidden" name="Component" value="<?= $Component; ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$FormAction = $FormAction . '?' . EncodeThis('Component=' . $Component);
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
