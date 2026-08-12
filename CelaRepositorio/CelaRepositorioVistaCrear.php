<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaRepositorio" id="Form_CelaRepositorio" action="<?= $FormAction . '?' . EncodeThis('Action=Crear&Source=' . $_GET['Source'] . '&Tupla=' . $_GET['Tupla']); ?>"  enctype="multipart/form-data">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContentNombre = array(
			'<font color="red">*</font> Nombre:',
			'<input class="form-control focused e_requerido" name="Nombre" id="Nombre" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentNombre = array(
			'<font color="red">*</font> Descripci&oacute;n:',
			'<input class="form-control focused e_requerido" name="Descripci_on" id="Descripci_on" type="text" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		$ContentNombre = array(
			'<font color="red">*</font> Archivo:',
			'<input class=" focused e_requerido" name="Archivo" id="Archivo" type="file" />'
		);
		print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

		if(!isset($_GET['Source']) || $_GET['Source'] == ''){
			$ContentNombre = array(
				'<font color="red">*</font> Tabla Origen del Archivo:',
				'<input class=" focused e_requerido form-control" name="Source" id="Source" type="text " />'
			);
			print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
		}else{
			print '<input type="hidden" name="Source" value="' . $_GET['Source'] . '" />';
		}
		if(!isset($_GET['Tupla']) || $_GET['Tupla'] == ''){
			$ContentNombre = array(
				'<font color="red">*</font> Tupla Origen del Archivo:',
				'<input class=" focused e_requerido form-control" name="Tupla" id="Tupla" type="text " />'
			);
			print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);
		}else{
			print '<input type="hidden" name="Tupla" value="' . $_GET['Tupla'] . '" />';
		}
	?>
		<input type="hidden" name="CelaRepositorioInsert" value="CelaRepositorioInsert"/>
		<span class="clearfix"></span>
		<hr/>
		<?php
			$Back = $FormAction . '?' . EncodeThis( (isset($_GET['Source']) && $_GET['Source'] != '' ? 'Source=' . $_GET['Source']:'') . '&' . (isset($_GET['Tupla']) && $_GET['Tupla'] != '' ? 'Tupla=' . $_GET['Tupla']:''));
			include '../CelaTemplate/CelaActionsForm.php';
		?>
	</fieldset>
</form>