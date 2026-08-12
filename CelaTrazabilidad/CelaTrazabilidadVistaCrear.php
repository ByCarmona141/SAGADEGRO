<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaTrazabilidad" id="Form_CelaTrazabilidad" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php

		$ContentFecha = array(
			'<font color="red">*</font> Fecha:',
			'<input class="form-control focused e_requerido e_fecha_hora" name="Fecha" id="Fecha" type="text" data-rango=\'{"minimo":"1900-01-01 00:00:00", "maximo":"' . date('Y-m-d H:i:s') . '", "mensaje":"Fecha "}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentFecha, $InputTemplate);

		$OpcTrazabilidad['Name']    = 'Fase';
		$OpcTrazabilidad['Class']   = 'form-control e_requerido';
		$Query = CelaFaseQueryCombo();
		$ContentFase = array(
			'<font color="red">*</font> Fase:',
			FillSelect($Query, $OpcTrazabilidad, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentFase, $InputTemplate);

		$OpcProgramador['Name']    = 'Programador';
		$OpcProgramador['Class']   = 'form-control e_requerido';
		$Query = CelaUsuarioComboQuery();
		$ContentProgramador = array(
			'<font color="red">*</font> Programador:',
			FillSelect($Query, $OpcProgramador, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentProgramador, $InputTemplate);
	?>
		<input type="hidden" name="CelaTrazabilidadInsert" value="CelaTrazabilidadInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>