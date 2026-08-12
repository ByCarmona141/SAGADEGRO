<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaPrivilegios" id="Form_CelaPrivilegios" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$OpcPrivilegio['Name']  = 'Privilegio';
		$OpcPrivilegio['Class'] = 'form-control e_requerido focused';
		$Query = CelaPrivilegioQueryCombo();
		$ContentNombre = array(
			'<font color="red">*</font> Privilegio:',
			FillSelect($Query, $OpcPrivilegio, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$OpcOrigen['Name']  = 'Origen';
		$OpcOrigen['Class'] = 'form-control e_requerido focused';
		$Query = CelaOrigenQueryCombo();$ContentNombre = array(
			'<font color="red">*</font> Origen:',
			FillSelect($Query, $OpcOrigen, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentNombre = array(
			'<font color="red">*</font> Tupla:',
			'<input class="form-control focused e_requerido" name="Tupla" id="Tupla" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentNombre = array(
			'<font color="red">*</font> TuplaAcceso:',
			'<input class="form-control focused e_requerido" name="TuplaAcceso" id="TuplaAcceso" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
	?>
		<input type="hidden" name="CelaPrivilegiosInsert" value="CelaPrivilegiosInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>