<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaCategor_iaConfiguraci_on" id="Form_CelaCategor_iaConfiguraci_on" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentNombre = array(
			'<font color="red">*</font> Nombre:',
			'<input class="form-control focused e_requerido" name="NombreCategor_ia" id="NombreCategor_ia" type="text"/>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
	?>
		<input type="hidden" name="CelaCategor_iaConfiguraci_onInsert" value="CelaCategor_iaConfiguraci_onInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>