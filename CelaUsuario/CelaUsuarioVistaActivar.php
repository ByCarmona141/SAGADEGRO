<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaUsuario" id="Form_CelaUsuario" action="<?= $FormAction . '?' . EncodeThis('Action=Activar&Activar=1'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
		<?php
			$ContentContrase_na = array(
				'<font color="red">*</font> Contrase&ntilde;a:',
				'<input class="form-control focused e_requerido e_password" name="Contrase_na" id="Contrase_na" type="password" />'
			);
			print ReplaceContentPage($TagsToReplace, $ContentContrase_na, $InputTemplate);
			print '<br>';
			$ContentContrase_na1 = array(
				'<font color="red">*</font> Confirma la  Contrase&ntilde;a:',
				'<input class="form-control focused e_requerido e_igual" name="Contrase_na1" id="Contrase_na1" type="password" data-igual_a="Contrase_na" />'
			);
			print ReplaceContentPage($TagsToReplace, $ContentContrase_na1, $InputTemplate);
		?>
		<input type="hidden" name="CelaUsuarioUpdate" value="CelaUsuarioUpdate"/>
		<span class="clearfix"></span>
		<hr/>
		<?php
			$Back = $FormAction;
			include '../CelaTemplate/CelaActionsForm.php';
		?>
	</fieldset>
</form>
