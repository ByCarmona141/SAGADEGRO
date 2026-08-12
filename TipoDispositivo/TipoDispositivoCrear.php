<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_TipoDispositivo" id="Form_TipoDispositivo" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContenNombre = array(
			'<font color="red">*</font>Nombre',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre" id="Nombre"  data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);

		$OpcIcono['Name']     = 'Icono';
        $OpcIcono['Class']    = 'form-control show-tick focused e_requerido';
		$OpcIcono['Custom'] = 'data-live-search="true"';
        $Query = CelaIconoQueryCombo('Icon');

        $ContentIcono = array(
            '<font color="red">*</font> Tipo de Dispositivo:',
            FillSelect($Query, $OpcIcono, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentIcono, $InputsTemplate);
	?>
		<input type="hidden" name="TipoDispositivoInsert" value="TipoDispositivoInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>