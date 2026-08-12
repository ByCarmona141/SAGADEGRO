<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaFormulario" id="Form_CelaFormulario" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentNombre = array(
			'<font color="red">*</font> Nombre:',
			'<input class="form-control focused e_requerido" name="Nombre" id="Nombre" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentDescripci_on = array(
			'<font color="red">*</font> Descripci&oacute;n:',
			'<input class="form-control focused e_requerido" name="Descripci_on" id="Descripci_on" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);

		$ContentRuta = array(
			'<font color="red">*</font> Ruta:',
			'<input class="form-control focused e_requerido e_remoto" name="Ruta" id="Ruta" type="text" data-remote=\'{"table":"CelaFormulario","field":"Ruta"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentRuta, $InputTemplate);
	?>
		<input type="hidden" name="CelaFormularioInsert" value="CelaFormularioInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>