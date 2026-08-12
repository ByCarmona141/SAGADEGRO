<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Reporte" id="Form_Reporte" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContenDescripci_on = array(
			'<font color="red">*</font>Descripci&oacute;n',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Descripci_on" id="Descripci_on"  data-rango=\'{"minimo":"1", "maximo":"128", "mensaje":"Introduce un valor entre 1 y 128 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
		$ContenFormato = array(
			'Formato',
			'<input type="text" class=" form-control focused   e_longitud" name="Formato" id="Formato"  data-rango=\'{"minimo":"0", "maximo":"", "mensaje":"Introduce un valor entre 0 y  caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenFormato, $InputsTemplate);
					
	?>
		<input type="hidden" name="ReporteInsert" value="ReporteInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>