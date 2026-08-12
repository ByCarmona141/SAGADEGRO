<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaFase" id="Form_CelaFase" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
		<?php
			$ContentNombre = array(
				'<font color="red">*</font> Nombre:',
				'<input class="form-control focused e_requerido" name="Nombre" id="Nombre" type="text" />'
			);
			print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
		?>
		<input type="hidden" name="CelaFaseInsert" value="CelaFaseInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>