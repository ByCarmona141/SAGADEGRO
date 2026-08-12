<form class="form-horizontal form_validate" method="POST" name="Form_CelaRepositorio" id="Form_CelaRepositorio" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar&Source=' . $_GET['Source'] . '&Tupla=' . $_GET['Tupla']); ?>" enctype="multipart/form-data">
	<fieldset>
		<span class="clearfix"></span>
		<hr />
<?php
	$Count=0;
	$CelaRepositorioId='';

	/*Se carga la plantilla para los datos de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$CelaRepositorioQuery =  sprintf('SELECT * FROM CelaRepositorio WHERE id = %s;',
									GetSQLValueString($Key, 'int')
								);
		$CelaRepositorioResult = $Connection -> query($CelaRepositorioQuery);
		$CelaRepositorioRecord = $CelaRepositorioResult -> fetch_assoc();

		$CelaRepositorioId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContentDescripci_on = array(
					'<font color="red">*</font> Descripci&oacute;n:',
					'<input class="form-control focused e_requerido" name="Descripci_on' . $Key . '" id="Descripci_on' . $Key . '" value="' . $CelaRepositorioRecord['Descripci_on'] . '" type="text" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentDescripci_on, $InputTemplate);
			?>
				<div class="form-group">
					<div class="group-validate">
						<label class="col-md-2 control-label" for="focusedInput"> <font color="red">*</font> Archivo:</label>
						<div class="col-md-10">
							<div class="col-xs-10 validate">
								<input class=" focused" name="Archivo<?= $Key; ?>" id="Archivo<?= $Key; ?>" type="file" />
							</div>
							<div class="col-xs-1 text-right">
								<a data-intro="Vista Previa del Archivo" data-position="right" target="_blank" class="btn btn-info" title="Vista Previa" href="CelaRepositorio.php?<?= EncodeThis("Key=" . $Key . '&Action=VistaPrevia'); ?>">
									<i class="fa fa-eye"></i>
								</a>
							</div>
							<div class="col-xs-1 text-right">
								<a data-intro="Descarga el Archivo seleccionado" data-position="bottom" class="btn btn-success" title="Descargar" href="CelaRepositorio.php?<?= EncodeThis('Key=' . $Key . "&Source=" . $CelaRepositorioRecord['Origen'] . "&Tupla=" . $CelaRepositorioRecord['Tupla'] . '&Action=Descargar'); ?>">
									<i class="fa fa-download"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="Source<?= $Key; ?>" value="<?= $_GET['Source']; ?>"/>
				<input type="hidden" name="Tupla<?= $Key; ?>" value="<?= $_GET['Tupla']; ?>"/>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$CelaRepositorioId = substr_replace($CelaRepositorioId, '', -1);
?>
		<input type="hidden" name="CelaRepositorioUpdate" value="CelaRepositorioUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaRepositorioId, $Random);	?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$FormAction = $FormAction . '?' . EncodeThis('Source=' . $_GET['Source'] . '&Tupla=' . $_GET['Tupla']);
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
