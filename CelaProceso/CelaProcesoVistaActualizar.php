
<form class="form-horizontal form_validate" method="POST" name="Form_CelaProceso" id="Form_CelaProceso" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
	<?php
		$Count=0;
		$CelaProcesoId = '';

		/*Se carga la plantilla para los datos de entrada*/
		$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
		$TagsToReplace = array(
			'<!--#INPUTLABEL#-->',
			'<!--#INPUTELEMENT#-->'
		);

		/*Se obtienen todos los elementos que van a actualizar*/
		foreach($_GET['Key'] as $Key){
			$CelaProcesoQuery =  sprintf('SELECT * FROM CelaProceso WHERE id = %s;',
				GetSQLValueString($Key, 'int')
			);
			$CelaProcesoResult = $Connection -> query($CelaProcesoQuery);
			$CelaProcesoRecord = $CelaProcesoResult -> fetch_assoc();
			
			$CelaProcesoId .= $Key . ',';
	?>
			<div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
				<fieldset>
					<legend>Registro <?= $Key; ?></legend>
				<?php
                    $ContenNombre = array(
                        '<font color="red">*</font>Nombre',
                        '<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre' . $Key . '" id="Nombre' . $Key . '"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' value="' .  $CelaProcesoRecord['Nombre'] . '"/>'
                    );
                    print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputTemplate);
                    
                    $ContenScript = array(
                        '<font color="red">*</font>Script (ruta del script que se ejecutar&aacute; en segundo plano)',
                        '<textarea class=" form-control focused  e_requerido  " name="Script' . $Key . '" id="Script' . $Key . '"  rows="3">' . $CelaProcesoRecord['Script'] . '</textarea>'
                    );
                    print ReplaceContentPage($TagsToReplace, $ContenScript, $InputTemplate);
                    
                    $ContenParametros = array(
                        'Parametros (Parametros con los que trabajar&aacute; el script en formato json)',
                        '<textarea class=" form-control focused  " name="Parametros' . $Key . '" id="Parametros' . $Key . ' rows="3">' . $CelaProcesoRecord['Parametros'] . '</textarea>'
                    );
                    print ReplaceContentPage($TagsToReplace, $ContenParametros, $InputTemplate);
                    
                    $OpcRecurrente['Name'] = 'Recurrente' . $Key;
                    $OpcRecurrente['Class'] = 'e_requerido form-control';
                    $Query = array(
                        '1' => 'SI',
                        '0' => 'NO'
                    );
                    
                    $ContenRecurrente = array(
                        '<font color="red">*</font>&iquest;Es Recurrente? (Solo si el script se ejecuta cada determinado tiempo hasta la fecha de termino)',
                        SFillSelect($Query, $OpcRecurrente, $CelaProcesoRecord['Recurrente'], 1)
                    );
                    print ReplaceContentPage($TagsToReplace, $ContenRecurrente, $InputTemplate);
                    
                    $OpcUnidadPeriodo['Name'] = 'Periodicidad' . $Key;
                    $OpcUnidadPeriodo['Class'] = 'form-control e_requerido ';
                    $Query = array(
                        '' => 'No Aplica',
                        'seconds' => 'Segundos del periodo',
                        'minute' => 'Minutos del periodo',
                        'hours' => 'Horas del periodo',
                        'days' => 'D&iacute;as del periodo',
                        'week' => 'Semanas del periodo',
                        'month' => 'Meses del periodo',
                        'year' => 'A&ntilde;os del periodo'
                    );
                    
                    print '
				<div class="row mb-15px offset-2">
					<label class="form-label col-md-5">
						'. SFillSelect($Query, $OpcUnidadPeriodo, $CelaProcesoRecord['Periodicidad']) . '
					</label>
					<div class="col-md-7"></div>
					<div class="col-md-5">
						<input type="text" class=" form-control focused  e_requerido  e_numero" name="Periodo' . $Key . '" id="Periodo' . $Key . '" value="' .  $CelaProcesoRecord['Periodo'] . '"/>
					</div>
				</div>';
                    
                    $ContenFechaInicio = array(
                        '<font color="red">*</font>Fecha de Inicio (A partir de cuando se inicia el proceso)',
                        '<input type="text" class=" form-control focused  e_requerido e_fecha_hora" name="FechaDeInicio' . $Key . '" id="FechaInicio' . $Key . '"  data-rango=\'{"minimo":"1900-01-01 00:00:00", "maximo":"2999-12-31 24:59:57", "mensaje":"Seleccione una fecha entre 1900-01-01 00:00:00 y 2999-12-31 24:59:57"}\' value="' .  $CelaProcesoRecord['FechaDeInicio'] . '"/>'
                    );
                    print ReplaceContentPage($TagsToReplace, $ContenFechaInicio, $InputTemplate);
                    
                    $ContenFechaTermino = array(
                        'Fecha de Termino (si es recurrente, hasta cuando deja de ejecutarse)',
                        '<input type="text" class=" form-control focused  e_fecha_hora" name="FechaDeTermino' . $Key . '" id="FechaDeTermino' . $Key . '"  data-rango=\'{"minimo":"1900-01-01 00:00:00", "maximo":"2999-12-31 24:59:57", "mensaje":"Seleccione una fecha entre 1900-01-01 00:00:00 y 2999-12-31 24:59:57"}\' value="' .  $CelaProcesoRecord['FechaDeTermino'] . '"/>'
                    );
                    print ReplaceContentPage($TagsToReplace, $ContenFechaTermino, $InputTemplate);
				?>
				</fieldset>
			</div>
	<?php
			$Count++;
		}

		$CelaProcesoId = substr_replace($CelaProcesoId, '', -1);
	?>
		<input type="hidden" name="CelaProcesoUpdate" value="CelaProcesoUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaProcesoId, $Random); ?>">
		<span class="clearfix"></span>
		<hr />
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/ActiosnFormUpdate.php';
	?>
</form>
