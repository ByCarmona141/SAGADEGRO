<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Status" id="Form_Status" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContenDescripci_on = array(
			'<font color="red">*</font>Descripci&oacute;n',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Descripci_on" id="Descripci_on"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
		$ContenOrigen = array(
			'<font color="red">*</font>Origen',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Origen" id="Origen"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenOrigen, $InputsTemplate);
					
		$OpcAcotaci_on['Name'] = 'Acotaci_on';
		$OpcAcotaci_on['Class'] = ' form-control focused  e_requerido  e_longitud';
		$OpcAcotaci_on['Custom'] = ' data-rango=\'{"minimo":"1", "maximo":"", "mensaje":"Introduce un valor entre 1 y  caracteres de longitud"}\'';
		$Consulta =  array('success' => 'success', 'warning' => 'warning', 'danger' => 'danger', 'info' => 'info', 'active' => 'active', 'default' => 'default');
		$ContenAcotaci_on = array(
			'<font color="red">*</font>Acotaci&oacute;n',
			FillSelect($Consulta, $OpcAcotaci_on,1)
		);
		print ReplaceContentPage($TagsToReplace, $ContenAcotaci_on, $InputsTemplate);
					
	?>
		<input type="hidden" name="StatusInsert" value="StatusInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>