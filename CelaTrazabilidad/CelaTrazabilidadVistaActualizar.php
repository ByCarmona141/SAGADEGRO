
<form class="form-horizontal form_validate" method="POST" name="Form_CelaTrazabilidad" id="Form_CelaTrazabilidad" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaTrazabilidadId = '';
		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaTrazabilidadQuery =  sprintf('SELECT * FROM CelaTrazabilidad WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaTrazabilidadResult = $Connection -> query($CelaTrazabilidadQuery);
			$CelaTrazabilidadRecord = $CelaTrazabilidadResult -> fetch_assoc();

			$CelaTrazabilidadId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
					$ContentFecha = array(
						'<font color="red">*</font> Fecha:',
						'<input class="form-control focused e_requerido e_fecha_hora" name="Fecha' . $Key . '" id="Fecha' . $Key . '" type="text" data-rango=\'{"minimo":"1900-01-01 00:00:00", "maximo":"' . date('Y-m-d H:i:s') . '", "mensaje":"Fecha "}\' value="' . $CelaTrazabilidadRecord['Fecha'] . '" />'
					);
					print ReplaceContentPage($TagsToReplace, $ContentFecha, $InputTemplate);

					$OpcFase['Name']    = 'Fase' . $Key;
					$OpcFase['Class']   = 'form-control e_requerido';
					$Query = CelaFaseQueryCombo();
					$ContentFase = array(
						'<font color="red">*</font> Fase:',
						FillSelect($Query, $OpcFase, $CelaTrazabilidadRecord['Fase'], 1)
					);
					print ReplaceContentPage($TagsToReplace, $ContentFase, $InputTemplate);

					$OpcProgramador['Name']    = 'Programador' . $Key;
					$OpcProgramador['Class']   = 'form-control e_requerido';
					$Query = CelaUsuarioComboQuery();
					$ContentProgramador = array(
						'<font color="red">*</font> Programador:',
						SFillSelect($Query, $OpcProgramador, $CelaTrazabilidadRecord['Programador'], 1)
					);
					print ReplaceContentPage($TagsToReplace, $ContentProgramador, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}
		
		$CelaTrazabilidadId = substr_replace($CelaTrazabilidadId, '', -1);
	?>
		<input type="hidden" name="CelaTrazabilidadUpdate" value="CelaTrazabilidadUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaTrazabilidadId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
