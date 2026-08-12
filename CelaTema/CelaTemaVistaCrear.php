<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaTema" id="Form_CelaTema" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentNombre = array(
			'<font color="red">*</font> Nombre:',
			'<input class="form-control focused e_requerido" name="Nombre" id="Nombre" type="text"/>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentRuta = array(
			'<font color="red">*</font> Ruta:',
			'<input class="form-control focused e_requerido" name="Ruta" id="Ruta" type="text"/>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentRuta, $InputTemplate);

		$ContentImagen = array(
			'Imagen:',
			'<input class="form-control focused" name="Imagen" id="Imagen" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentImagen, $InputTemplate);
	?>
		<input type="hidden" name="CelaTemaInsert" value="CelaTemaInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>