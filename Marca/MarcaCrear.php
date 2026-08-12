<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Marca" id="Form_Marca" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContenNombre = array(
			'<font color="red">*</font>Nombre',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre" id="Nombre"  data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);
	?>
		<input type="hidden" name="MarcaInsert" value="MarcaInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>