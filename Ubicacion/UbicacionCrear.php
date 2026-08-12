<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Ubicacion" id="Form_Ubicacion" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContenArea = array(
			'<font color="red">*</font>Area',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Area" id="Area"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenArea, $InputsTemplate);

        $ContenPiso = array(
			'<font color="red">*</font>Piso',
			'<input type="number" class=" form-control focused  e_requerido  e_longitud" name="Piso" id="Piso"  data-rango=\'{"minimo":"0", "maximo":"255", "mensaje":"Introduce un valor entre 0 y 255 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenPiso, $InputsTemplate);
	?>
		<input type="hidden" name="UbicacionInsert" value="UbicacionInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>