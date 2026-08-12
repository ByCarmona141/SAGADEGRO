<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaComponente" id="Form_CelaComponente" action="<?= $FormAction . '?' . EncodeThis('Action=Crear&' . (isset($_GET['Component']) && $_GET['Component'] != '' ? 'Component=' . $_GET['Component']:'')); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentFechaSolicitud = array(
			'<font color="red">*</font> Fecha de Solicitud:',
			'<input class="form-control focused e_requerido e_fecha_hora" name="FechaSolicitud" id="FechaSolicitud" type="text" value="' . date('Y-m-d H:i:s') . '" data-rango=\'{"minimo":"1900-01-01 00:00:00", "maximo":"' . date('Y-m-d H:i:s') . '", "mensaje":"Fecha "}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentFechaSolicitud, $InputTemplate);


		$ContentDescripci_on = array(
			'<font color="red">*</font> Descripci&oacute;n:',
			'<textarea class="form-control focused e_requerido" name="Descripci_on" id="Descripci_on" rows="3"></textarea>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);

		$OpcSolicitante['Name']     = 'Solicitante';
		$OpcSolicitante['Class']    = 'form-control focused e_requerido';
		$Query = CelaUsuarioComboQuery();
		$ContentSolicitante = array(
			'<font color="red">*</font> Nombre de Quien Solicita:',
			FillSelect($Query, $OpcSolicitante, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentSolicitante, $InputTemplate);

		$OpcTipoDeComponente['Name']  = 'TipoDeComponente';
		$OpcTipoDeComponente['Class'] = 'form-control focused e_requerido';
		$Query = CelaTipoComponenteQueryCombo();
		$ContentNombre = array(
			'<font color="red">*</font> Tipo de Componente:',
			FillSelect($Query, $OpcTipoDeComponente, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		if(!isset($_GET['Component']) || $_GET['Component'] == ''){
			$ContentFormulario = array(
				'<font color="red">*</font> Formulario:',
				'<input class="form-control focused e_requerido" name="Componente" id="Componente" type="text" />'
			);
			print ReplaceContentPage($TagsToReplace, $ContentFormulario, $InputTemplate);
		}else{
			print '<input name="Componente" type="hidden" value="' . $_GET['Component'] . '" />';

		}
	?>
		<input type="hidden" name="CelaComponenteInsert" value="CelaComponenteInsert"/>
		<span class="clearfix"></span>
		<hr/>
<?php
		$Back = $FormAction . '?' . EncodeThis((isset($_GET['Component']) && $_GET['Component'] != '' ? 'Component=' . $_GET['Component']:''));
		include '../CelaTemplate/CelaActionsForm.php';
?>
	</fieldset>
</form>