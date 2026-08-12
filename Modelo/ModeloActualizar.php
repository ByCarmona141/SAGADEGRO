<form class="form-horizontal form_validate" method="POST" name="Form_Modelo" id="Form_Modelo" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$ModeloId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$ModeloQuery =  sprintf('SELECT * FROM Modelo WHERE id = %s;',
										GetSQLValueString($Key, 'int')
									);
		$ModeloResult = $Connection -> query($ModeloQuery);
		$ModeloRecord = $ModeloResult -> fetch_assoc();

		$ModeloId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
                $OpcMarca['Name']  = 'Marca' . $Key;
                $OpcMarca['Class'] = 'form-control e_requerido';
                $OpcMarca['Custom'] = 'data-live-search="true"';
                $Query              = MarcaQueryCombo();

                $ContentMarca = array(
                        ' Marca: ',
                        SFillSelect($Query, $OpcMarca, $ModeloRecord['Marca'])
                );

                print ReplaceContentPage($TagsToReplace, $ContentMarca, $InputsTemplate);

				$ContenNombre = array(
					'<font color="red">*</font>Nombre',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre' . $Key . '" id="Nombre' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' value="' .  $ModeloRecord['Nombre'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$ModeloId = substr_replace($ModeloId, '', -1);
?>
		<input type="hidden" name="ModeloUpdate" value="ModeloUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($ModeloId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
	</fieldset>
</form>