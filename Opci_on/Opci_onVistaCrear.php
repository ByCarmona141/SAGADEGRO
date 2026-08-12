<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Opci_on" id="Form_Opci_on" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Contenid = array(
			'<font color="red">*</font>id',
			'<input type="text" class=" form-control focused  e_requerido  e_rango e_numero" name="id" id="id"  data-rango=\'{"minimo":"-128", "maximo":"127", "mensaje":"Introduce un valor entre -128 y 127"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $Contenid, $InputsTemplate);
					
		$ContenDescripci_on = array(
			'<font color="red">*</font>Descripci&oacute;n',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Descripci_on" id="Descripci_on"  data-rango=\'{"minimo":"1", "maximo":"4", "mensaje":"Introduce un valor entre 1 y 4 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
	?>
		<input type="hidden" name="Opci_onInsert" value="Opci_onInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>