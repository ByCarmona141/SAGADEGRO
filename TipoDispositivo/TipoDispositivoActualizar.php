<form class="form-horizontal form_validate" method="POST" name="Form_TipoDispositivo" id="Form_TipoDispositivo" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
	$Count = 0;
	$TipoDispositivoId = '';
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	/*Se obtienen todos los elementos que se van a actualizar*/
	foreach($_GET['Key'] as $Key){
		$TipoDispositivoQuery =  sprintf('SELECT * FROM TipoDispositivo WHERE id = %s;',
										GetSQLValueString($Key, 'int')
									);
		$TipoDispositivoResult = $Connection -> query($TipoDispositivoQuery);
		$TipoDispositivoRecord = $TipoDispositivoResult -> fetch_assoc();

		$TipoDispositivoId .= $Key . ',';
?>
		<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
			<fieldset>
				<legend>Registro <?= $Key; ?></legend>
			<?php
				$ContenNombre = array(
					'<font color="red">*</font>Nombre',
					'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre' . $Key . '" id="Nombre' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' value="' .  $TipoDispositivoRecord['Nombre'] . '"/>'
				);
				print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);

				$Icono =    GetValue(
								sprintf('SELECT Nombre FROM CelaIcono WHERE id = %s;',
										GetSQLValueString($TipoDispositivoRecord['Icono'], 'int')
								),
								'Nombre'
							);
				$OpcIcono['Name']   = 'Icono' . $Key;
				$OpcIcono['Class']  = 'form-control show-tick focused';
				$OpcIcono['Custom'] = 'data-live-search="true"';
				$Query = CelaIconoQueryCombo('Icon');

				$ContentIcono = array(
                    ' <font color="red">*</font> Icono: ',
                    SFillSelect($Query, $OpcIcono, $TipoDispositivoRecord['Icono'] . '" data-icon="' . $Icono, 1)
                );

                print ReplaceContentPage($TagsToReplace, $ContentIcono, $InputsTemplate);
			?>
			</fieldset>
		</div>
<?php
		$Count++;
	}

	$TipoDispositivoId = substr_replace($TipoDispositivoId, '', -1);
?>
		<input type="hidden" name="TipoDispositivoUpdate" value="TipoDispositivoUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($TipoDispositivoId, $Random); ?>">
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
	</fieldset>
</form>