<?php
	/*Se carga la plantilla para las cajas de entrada*/
	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaProceso" id="Form_CelaProceso" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
        <?php
            $ContenNombre = array(
                '<font color="red">*</font>Nombre',
                '<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre" id="Nombre"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' />'
            );
            print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputTemplate);
            
            $ContenScript = array(
                '<font color="red">*</font>Script (ruta del script que se ejecutar&aacute; en segundo plano)',
                '<textarea class=" form-control focused  e_requerido  " name="Script" id="Script"  rows="3"></textarea>'
            );
            print ReplaceContentPage($TagsToReplace, $ContenScript, $InputTemplate);
            
            $ContenParametros = array(
                'Parametros (Parametros con los que trabajar&aacute; el script en formato json)',
                '<textarea class=" form-control focused  " name="Parametros" id="Parametros" rows="3"></textarea>'
            );
            print ReplaceContentPage($TagsToReplace, $ContenParametros, $InputTemplate);
            
            $OpcRecurrente['Name'] = 'Recurrente';
            $OpcRecurrente['Class'] = 'e_requerido form-control';
            $Query = array(
                '1' => 'SI',
                '0' => 'NO'
            );
            
            $ContenRecurrente = array(
                '<font color="red">*</font>&iquest;Es Recurrente? (Solo si el script se ejecuta cada determinado tiempo hasta la fecha de termino)',
                FillSelect($Query, $OpcRecurrente, 1)
            );
            print ReplaceContentPage($TagsToReplace, $ContenRecurrente, $InputTemplate);
            
            $OpcUnidadPeriodo['Name'] = 'Periodicidad';
            $OpcUnidadPeriodo['Class'] = 'form-control ';
            $Query = array(
                '' => 'No aplica',
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
						'. FillSelect($Query, $OpcUnidadPeriodo) . '
					</label>
					<div class="col-md-7"></div>
					<div class="col-md-5">
						<input type="text" class=" form-control focused  e_numero" name="Periodo" id="Periodo" />
					</div>
				</div>';
            
            $ContenFechaInicio = array(
                '<font color="red">*</font>Fecha de Inicio (A partir de cuando se inicia el proceso)',
                '<input type="text" class=" form-control focused  e_requerido e_fecha_hora" name="FechaDeInicio" id="FechaInicio"  data-rango=\'{"minimo":"1900-01-01 00:00:00", "maximo":"2999-12-31 24:59:57", "mensaje":"Seleccione una fecha entre 1900-01-01 00:00:00 y 2999-12-31 24:59:57"}\' />'
            );
            print ReplaceContentPage($TagsToReplace, $ContenFechaInicio, $InputTemplate);
            
            $ContenFechaTermino = array(
                'Fecha de Termino (si es recurrente, hasta cuando deja de ejecutarse)',
                '<input type="text" class=" form-control focused  e_fecha_hora" name="FechaDeTermino" id="FechaDeTermino"  data-rango=\'{"minimo":"1900-01-01 00:00:00", "maximo":"2999-12-31 24:59:57", "mensaje":"Seleccione una fecha entre 1900-01-01 00:00:00 y 2999-12-31 24:59:57"}\' />'
            );
            print ReplaceContentPage($TagsToReplace, $ContenFechaTermino, $InputTemplate);
        ?>
		<input type="hidden" name="CelaProcesoInsert" value="CelaProcesoInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>
