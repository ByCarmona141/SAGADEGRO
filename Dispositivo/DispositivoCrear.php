<?php
	$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Dispositivo" id="Form_Dispositivo" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$ContenNombre = array(
			'<font color="red">*</font>Nombre',
			'<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Nombre" id="Nombre"  data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenNombre, $InputsTemplate);

        $OpcTipoDispositivo['Name']     = 'TipoDispositivo';
        $OpcTipoDispositivo['Class']    = 'form-control focused e_requerido';
        $OpcTipoDispositivo['Custom'] = 'data-live-search="true"';
        $Query = TipoDispositivoQueryCombo();

        $ContentTipoDispositivo = array(
                '<font color="red">*</font> Tipo de Dispositivo:',
                FillSelect($Query, $OpcTipoDispositivo, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentTipoDispositivo, $InputsTemplate);

        $OpcDispositivo['Name']     = 'Dispositivo';
        $OpcDispositivo['Class']    = 'form-control focused';
        $OpcDispositivo['Custom'] = 'data-live-search="true"';
        $Query = DispositivoQueryCombo();

        $ContentDispositivo = array(
            ' Dispositivo:',
            FillSelect($Query, $OpcDispositivo, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentDispositivo, $InputsTemplate);

        $OpcModelo['Name']     = 'Modelo';
        $OpcModelo['Class']    = 'form-control focused e_requerido';
        $OpcModelo['Custom'] = 'data-live-search="true"';
        $Query = ModeloQueryCombo();

        $ContentModelo = array(
            '<font color="red">*</font> Modelo:',
            FillSelect($Query, $OpcModelo, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentModelo, $InputsTemplate);

        $OpcEstatus['Name']     = 'Estatus';
        $OpcEstatus['Class']    = 'form-control focused e_requerido';
        $Query = EstatusQueryCombo();

        $ContentEstatus = array(
            '<font color="red">*</font> Estatus:',
            FillSelect($Query, $OpcEstatus, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentEstatus, $InputsTemplate);

        $ContenMAC = array(
			' MAC: ',
			'<input type="text" class=" form-control focused  e_longitud" name="MAC" id="MAC" placeholder="00:1A:2B:3C:4D:5E"  data-rango=\'{"minimo":"1", "maximo":"17", "mensaje":"Introduce un valor entre 1 y 17 caracteres de longitud"}\' />'
		);
		print ReplaceContentPage($TagsToReplace, $ContenMAC, $InputsTemplate);

        $OpcUbicacion['Name']     = 'Ubicacion';
        $OpcUbicacion['Class']    = 'form-control focused e_requerido';
        $OpcUbicacion['Custom'] = 'data-live-search="true"';
        $Query = UbicacionQueryCombo();

        $ContentUbicacion = array(
            '<font color="red">*</font> Ubicacion:',
            FillSelect($Query, $OpcUbicacion, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentUbicacion, $InputsTemplate);

        $OpcRack['Name']     = 'Rack';
        $OpcRack['Class']    = 'form-control focused';
        $Query = RackQueryCombo();

        $ContentRack = array(
            ' Rack:',
            FillSelect($Query, $OpcRack, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentRack, $InputsTemplate);

        $ContenIP = array(
            ' IP: ',
            '<input type="text" class=" form-control focused  e_longitud" name="IP" id="IP" placeholder="192.168.1.1" data-rango=\'{"minimo":"1", "maximo":"45", "mensaje":"Introduce un valor entre 1 y 45 caracteres de longitud"}\' />'
        );
        print ReplaceContentPage($TagsToReplace, $ContenIP, $InputsTemplate);

        $ContenSerial = array(
            'Serial: ',
            '<input type="text" class=" form-control focused  e_longitud" name="Serial" id="Serial"  data-rango=\'{"minimo":"1", "maximo":"100", "mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}\' />'
        );
        print ReplaceContentPage($TagsToReplace, $ContenSerial, $InputsTemplate);
	?>
		<input type="hidden" name="DispositivoInsert" value="DispositivoInsert"/>
		<span class="clearfix"></span>
		<hr/>
	<?php
		$Back = $FormAction;
		include '../CelaTemplate/CelaActionsForm.php';
	?>
	</fieldset>
</form>