<form class="form-horizontal form_validate" method="POST" name="Form_CelaPrivilegios" id="Form_CelaPrivilegios" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
<?php
	$Count=0;
	$CelaPrivilegiosId='';

	/*Se carga la plantilla para los datos de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$CelaPrivilegiosQuery =  sprintf('SELECT * FROM CelaPrivilegios WHERE id = %s;',
									GetSQLValueString($Key, 'int')
								);
		$CelaPrivilegiosResult = $Connection -> query($CelaPrivilegiosQuery);
		$CelaPrivilegiosRecord = $CelaPrivilegiosResult -> fetch_assoc();
		
		$CelaPrivilegiosId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$OpcPrivilegio['Name']  = 'Privilegio' . $Key;
				$OpcPrivilegio['Class'] = 'form-control e_requerido focused';
				$Query = CelaPrivilegioQueryCombo();

				$ContentPrivilegio = array(
					'<font color="red">*</font> Privilegio:',
					SFillSelect($Query, $OpcPrivilegio, $CelaPrivilegiosRecord['Privilegio'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContentPrivilegio, $InputTemplate);

				$OpcOrigen['Name']  = 'Origen' . $Key;
				$OpcOrigen['Class'] = 'form-control e_requerido focused';
				$Query = CelaOrigenQueryCombo();

				$ContentOrigen = array(
					'<font color="red">*</font> Origen:',
					SFillSelect($Query, $OpcOrigen, $CelaPrivilegiosRecord['Origen'], 1)
				);
				print ReplaceContentPage($TagsToReplace, $ContentOrigen, $InputTemplate);

				$ContentTupla = array(
					'Tupla:',
					'<input class="form-control focused " name="Tupla' . $Key . '" id="Tupla' . $Key . '" type="text" value="' . $CelaPrivilegiosRecord['Tupla'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentTupla, $InputTemplate);


				$ContentTuplaAcceso= array(
					'Tupla Acceso:',
					'<input class="form-control focused " name="TuplaAcceso' . $Key . '" id="TuplaAcceso' . $Key . '" type="text" value="' . $CelaPrivilegiosRecord['TuplaAcceso'] . '" />'
				);
				print ReplaceContentPage($TagsToReplace, $ContentTuplaAcceso, $InputTemplate);
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}
	
	$CelaPrivilegiosId = substr_replace($CelaPrivilegiosId, '', -1);
?>
		<input type="hidden" name="CelaPrivilegiosUpdate" value="CelaPrivilegiosUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaPrivilegiosId, $Random);	?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
