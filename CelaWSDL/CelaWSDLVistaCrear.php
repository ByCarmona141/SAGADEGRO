<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaWSDL" id="Form_CelaWSDL" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentNombre = array(
			'<font color="red">*</font> Nombre:',
			'<input class="form-control focused e_requerido" name="Nombre" id="Nombre" type="text" />'
		);

		$ContentNombre = array(
			'<font color="red">*</font> URL:',
			'<input class="form-control focused e_requerido" name="URL" id="URL" type="text" />'
		);

		$ContentURL = array(
			'<font color="red">*</font> URL:',
			'<input class="form-control focused e_requerido " name="URL" id="URL" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentURL, $InputTemplate);

		$ContentUsuario = array(
			'Usuario:',
			'<input class="form-control focused e_usuario" name="Usuario" id="Usuario" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentUsuario, $InputTemplate);

		$ContentContrase_na = array(
			'Contrase&ntilde;a:',
			'<input class="form-control focused" name="Contrase_na" id="Contrase_na" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentContrase_na, $InputTemplate);

		$ContentTipo = array(
			'<font color="red">*</font> Tipo:',
			'<input class="form-control focused e_requerido" name="Tipo" id="Tipo" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentTipo, $InputTemplate);
	?>
		<input type="hidden" name="CelaWSDLInsert" value="CelaWSDLInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>