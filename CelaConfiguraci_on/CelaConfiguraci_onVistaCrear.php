
<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaConfiguraci_on" id="Form_CelaConfiguraci_on" action="<?= 'CelaConfiguraci_on.php?' . EncodeThis('Action=Crear'); ?>" data-before_submit='<?= json_encode(array('0' => array('function'
=> 'ValidaRoles();', 'element' => 'Rol', 'message' => 'Seleccione una opci&oacute;n'))); ?>'>
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentCode = array(
			'<font color="red">*</font> C&oacute;digo:',
			'<input class="form-control focused e_requerido e_remoto" name="Code" id="Code" type="text" data-remote=\'{"table":"CelaConfiguraci_on","field":"Code"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentCode, $InputTemplate);

		$ContentNombre = array(
			'<font color="red">*</font> Nombre:',
			'<input class="form-control focused e_requerido " name="Nombre" id="Nombre" type="text"/>'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentValor = array(
			'<font color="red">*</font> Valor:',
			'<input class="form-control focused " name="Valor" id="Valor" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentValor, $InputTemplate);

		$OpcTipo['Name']    = 'Tipo';
		$OpcTipo['Class']   = 'form-control e_requerido focused';
		$Query = array('text' => 'text', 'select' => 'select', 'textarea' => 'textarea', 'file' => 'file', 'checkbox' => 'checkbox');
		$ContentTipo = array(
			'<font color="red">*</font> Tipo:',
			FillSelect($Query, $OpcTipo, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentTipo, $InputTemplate);

		$ContentReferencia = array(
			'<font color="red">*</font> Referencia:',
			'<input class="form-control focused e_requerido" name="Referencia" id="Referencia" type="text" />'
		);

		$InputTemplate1 = '
			<div class="row mb-15px offset-2 Referencia" hidden="hidden">
				<label class="form-label col-md-12"><!--#INPUTLABEL#--></label>
				<div class="col-md-5">
					<div class="input-group">
						<!--#INPUTELEMENT#-->
					</div>

				</div>
			</div>
		';
		print ReplaceContentPage($TagsToReplace, $ContentReferencia, $InputTemplate1);

		$ContentClass = array(
			'Clase html:',
			'<input class="form-control focused " name="Class" id="Class" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentClass, $InputTemplate);

		$OpcCategor_ia['Name']  = 'Categor_ia';
		$OpcCategor_ia['Class'] = 'form-control focused e_requerido';
		$Query = CelaCategor_iaConfiguraci_onQueryCombo();
		$ContentCategor_ia = array(
			'<font color="red">*</font> Categor&iacute;a:',
			FillSelect($Query, $OpcCategor_ia, 1)
		);
		print ReplaceContentPage($TagsToReplace, $ContentCategor_ia, $InputTemplate);

		$OpcRol['Name']     = 'Rol[]';
		$OpcRol['Class']    = 'form-control SelectRol';
		$OpcRol['Custom']   = 'multiple="multiple"';

		$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

		$Query = CelaRolComboQuery(false, $Group);
		$ContentRol = array(
			'<font color="red">*</font> Roles que tienen acceso a esta Configuraci&oacute;n:',
			FillSelect($Query, $OpcRol)
		);
		print ReplaceContentPage($TagsToReplace, $ContentRol, $InputTemplate);
	?>
		<input type="hidden" name="CelaConfiguraci_onInsert" value="CelaConfiguraci_onInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = 'CelaConfiguraci_on.php?' . EncodeThis('Action=Leer');
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>