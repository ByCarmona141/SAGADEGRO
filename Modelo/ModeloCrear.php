<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Modelo" id="Form_Modelo" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
        $OpcMarca['Name']     = 'Marca';
        $OpcMarca['Class']    = 'form-control focused e_requerido';
        $OpcMarca['Custom'] = 'data-live-search="true"';
        $Query = MarcaQueryCombo();

        $ContentMarca = array(
                '<font color="red">*</font> Marca:',
                FillSelect($Query, $OpcMarca, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentMarca, $InputsTemplate);

		$ContenNombre = array(
			'<font color="red">*</font>Nombre',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre" id="Nombre"  data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);
	?>
		<input type="hidden" name="ModeloInsert" value="ModeloInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>