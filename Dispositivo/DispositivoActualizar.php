<form class="form-horizontal form_validate" method="POST" name="Form_Dispositivo" id="Form_Dispositivo" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
    <fieldset>
        <span class="clearfix"></span>
        <hr />
        <?php
        $Count=0;
        $DispositivoId='';
        /*Se carga la plantilla para los datos de entrada*/
        $InputTemplate = LoadContentPage('../CelaTemplate/InputsTemplate.php');
        $TagsToReplace = array(
            '<!--#INPUTLABEL#-->',
            '<!--#INPUTELEMENT#-->'
        );

        /*Se obtienen todos los elementos que van a actualizar*/
        foreach($_GET['Key'] as $Key){
            $DispositivoQuery = sprintf('SELECT * FROM Dispositivo WHERE id = %s;',
                GetSQLValueString($Key, 'int')
            );
            $DispositivoResult = $Connection -> query($DispositivoQuery);
            $DispositivoRecord = $DispositivoResult -> fetch_assoc();

            $DispositivoId .= $Key . ',';
            ?>
            <div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
                <fieldset>
                    <legend>Registro <?= $Key; ?></legend>
                    <?php

                        $ContentNombre = array(
                            '<font color="red">*</font> Nombre:',
                            '<input class="form-control focused e_requerido" name="Nombre' . $Key . '" id="Nombre' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"32", "mensaje":"Introduce un valor entre 1 y 32 caracteres de longitud"}\' type="text" value="' . $DispositivoRecord['Nombre'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentNombre, $InputTemplate);

                        $OpcTipoDispositivo['Name']  = 'TipoDispositivo' . $Key;
                        $OpcTipoDispositivo['Class'] = 'form-control e_requerido';
                        $Query              = TipoDispositivoQueryCombo();

                        $ContentTipoDispositivo = array(
                                '<font color="red">*</font> Tipo de Dispositivo: ',
                                SFillSelect($Query, $OpcTipoDispositivo, $DispositivoRecord['TipoDispositivo'])
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentTipoDispositivo, $InputTemplate);

                        $OpcDispositivo['Name']  = 'Dispositivo' . $Key;
                        $OpcDispositivo['Class'] = 'form-control ';
                        $Query              = DispositivoQueryCombo();

                        $ContentDispositivo = array(
                                ' Dispositivo: ',
                                SFillSelect($Query, $OpcDispositivo, $DispositivoRecord['Dispositivo'], 1)
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentDispositivo, $InputTemplate);

                        $OpcModelo['Name']  = 'Modelo' . $Key;
                        $OpcModelo['Class'] = 'form-control e_requerido';
                        $Query              = ModeloQueryCombo();

                        $ContentModelo = array(
                                ' <font color="red">*</font> Modelo: ',
                                SFillSelect($Query, $OpcModelo, $DispositivoRecord['Modelo'])
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentModelo, $InputTemplate);

                        $OpcEstatus['Name']  = 'Estatus' . $Key;
                        $OpcEstatus['Class'] = 'form-control e_requerido';
                        $Query              = EstatusQueryCombo();

                        $ContentEstatus = array(
                                ' <font color="red">*</font> Estado Actual: ',
                                SFillSelect($Query, $OpcEstatus, $DispositivoRecord['Status'])
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentEstatus, $InputTemplate);

                        $ContentMAC = array(
                                ' MAC:',
                                '<input class="form-control focused" name="MAC' . $Key . '" id="MAC' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"17", "mensaje":"Introduce un valor entre 1 y 17 caracteres de longitud"}\' type="text" value="' . $DispositivoRecord['MAC'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentMAC, $InputTemplate);

                        $OpcUbicacion['Name']  = 'Ubicacion' . $Key;
                        $OpcUbicacion['Class'] = 'form-control e_requerido';
                        $Query              = UbicacionQueryCombo();

                        $ContentUbicacion = array(
                                ' <font color="red">*</font> Ubicacion del Dispositivo: ',
                                SFillSelect($Query, $OpcUbicacion, $DispositivoRecord['Ubicacion'])
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentUbicacion, $InputTemplate);

                        $OpcRack['Name']  = 'Rack' . $Key;
                        $OpcRack['Class'] = 'form-control';
                        $Query              = RackQueryCombo();

                        $ContentRack = array(
                                ' Rack (opcional): ',
                                SFillSelect($Query, $OpcRack, $DispositivoRecord['Rack'], 1)
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentRack, $InputTemplate);

                        $ContentIP = array(
                            ' IP (Si no existe es DHCP):',
                            '<input class="form-control focused" name="IP' . $Key . '" id="IP' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"45", "mensaje":"Introduce un valor entre 1 y 45 caracteres de longitud"}\' type="text" value="' . $DispositivoRecord['IP'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentIP, $InputTemplate);

                        $ContentSerial = array(
                            ' Numero de Serie:',
                            '<input class="form-control focused" name="Serial' . $Key . '" id="Serial' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"100", "mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}\' type="text" value="' . $DispositivoRecord['Serial'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentSerial, $InputTemplate);
                    ?>
                </fieldset>
            </div>
            <?php
            $Count++;
        }

        $DispositivoId = substr_replace($DispositivoId, '', -1);
        ?>
        <input type="hidden" name="DispositivoUpdate" value="DispositivoUpdate">
        <input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($DispositivoId, $Random); ?>">
        <span class="clearfix"></span>
        <hr />
        <?php
        $Back = 'Dispositivo';
        include '../CelaTemplate/ActiosnFormUpdate.php';
        ?>
</form>
