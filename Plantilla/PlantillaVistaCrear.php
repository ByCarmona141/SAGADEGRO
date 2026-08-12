<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Plantilla" id="Form_Plantilla" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContenNombre = array(
			'<font color="red">*</font>Nombre',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre" id="Nombre"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);
					
		$ContenDescripci_on = array(
			'Descripci&oacute;n',
			'<textarea class=" form-control focused   e_longitud" name="Descripci_on" id="Descripci_on"  data-rango=\'{"minimo":"0", "maximo":"512", "mensaje":"Introduce un valor entre 0 y 512 caracteres de longitud"}\' rows="3"></textarea>'
		);
		print ReplaceContentPage($TagsToReplace, $ContenDescripci_on, $InputsTemplate);
					
		$ContenPlantilla = array(
			'<font color="red">*</font>Plantilla',
			'<textarea type="text" class=" form-control focused  e_requerido " name="Plantilla" id="Plantilla" rows="30" placeholder="Escriba aqu&iacute; el texto HTML de la plantilla"></textarea>'
		);
		print ReplaceContentPage($TagsToReplace, $ContenPlantilla, $InputsTemplate);
					
		$OpcTipoPlantilla['Name'] = 'TipoPlantilla';
		$OpcTipoPlantilla['Class'] = ' form-control focused  e_requerido ';
		$Consulta = TipoPlantillaQueryCombo();
		$ContenTipoPlantilla = array(
			'<font color="red">*</font>Tipo Plantilla',
			FillSelect($Consulta, $OpcTipoPlantilla,1)
		);
		print ReplaceContentPage($TagsToReplace, $ContenTipoPlantilla, $InputsTemplate);
					
		$OpcEstaVigente['Name'] = 'EstaVigente';
		$OpcEstaVigente['Class'] = ' form-control focused  e_requerido';
		$Consulta = Opci_onQueryCombo();
		$ContenEstaVigente = array(
			'<font color="red">*</font>Esta Vigente',
			FillSelect($Consulta, $OpcEstaVigente,1)
		);
		print ReplaceContentPage($TagsToReplace, $ContenEstaVigente, $InputsTemplate);

		$OpcTama_no['Name'] = 'Tama_no';
		$OpcTama_no['Class'] = ' form-control focused  e_requerido';
		$Consulta = array(
			'Legal' => 'Oficio',
            'Letter' => 'Carta',
            'Tabloid' => 'Doble Carta',
            'A4' => 'A4',
            'A3' => 'A3',
            'B5' => 'B5',
            'B4' => 'B4',
            'B3' => 'B3'
		);

		$ContenTama_no = array(
			'<font color="red">*</font>Tama&ntilde;o',
			FillSelect($Consulta, $OpcTama_no,1)
		);
		print ReplaceContentPage($TagsToReplace, $ContenTama_no, $InputsTemplate);
	?>
		<input type="hidden" name="PlantillaInsert" value="PlantillaInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>