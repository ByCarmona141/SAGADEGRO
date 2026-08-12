<form class="form-horizontal form_validate" method="POST" name="Form_Acceso" id="Form_Acceso" action="<?= $FormAction . '?' . EncodeThis('Action=Actualizar'); ?>" >
    <fieldset>
        <span class="clearfix"></span>
        <hr />
        <?php
        $Count=0;
        $AccesoId='';
        /*Se carga la plantilla para los datos de entrada*/
        $InputTemplate = LoadContentPage('../CelaTemplate/InputsTemplate.php');
        $TagsToReplace = array(
            '<!--#INPUTLABEL#-->',
            '<!--#INPUTELEMENT#-->'
        );

        /*Se obtienen todos los elementos que van a actualizar*/
        foreach($_GET['Key'] as $Key){
            $AccesoQuery = sprintf('SELECT * FROM Acceso WHERE id = %s;',
                GetSQLValueString($Key, 'int')
            );
            $AccesoResult = $Connection -> query($AccesoQuery);
            $AccesoRecord = $AccesoResult -> fetch_assoc();

            $AccesoId .= $Key . ',';
            ?>
            <div class="thumbnail" style="background-color: <?= $Count%2==0?'#F9F9F9':'#FFFFF'; ?>">
                <fieldset>
                    <legend>Registro <?= $Key; ?></legend>
                    <?php
                        $OpcTipoAcceso['Name']  = 'TipoAcceso' . $Key;
                        $OpcTipoAcceso['Class'] = 'form-control e_requerido';
                        $OpcTipoAcceso['Custom'] = 'data-live-search="true"';
                        $Query              = TipoAccesoQueryCombo();

                        $ContentTipoAcceso = array(
                            '<font color="red">*</font> Tipo de Acceso: ',
                            SFillSelect($Query, $OpcTipoAcceso, $AccesoRecord['TipoAcceso'])
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentTipoAcceso, $InputTemplate);

                        $OpcDispositivo['Name']  = 'Dispositivo' . $Key;
                        $OpcDispositivo['Class'] = 'form-control e_requerido';
                        $OpcDispositivo['Custom'] = 'data-live-search="true"';
                        $Query              = DispositivoQueryCombo();

                        $ContentDispositivo = array(
                            '<font color="red">*</font> Dispositivo: ',
                            SFillSelect($Query, $OpcDispositivo, $AccesoRecord['Dispositivo'])
                        );

                        print ReplaceContentPage($TagsToReplace, $ContentDispositivo, $InputTemplate);

                        $ContentHost = array(
                            '<font color="red">*</font> Host:',
                            '<input class="form-control focused e_requerido" name="Host' . $Key . '" id="Host' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"100", "mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}\' type="text" value="' . $AccesoRecord['Host'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentHost, $InputTemplate);

                        $ContentPuerto = array(
                            'Puerto:',
                            '<input class="form-control focused" name="Puerto' . $Key . '" id="Puerto' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"100", "mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}\' type="number" value="' . $AccesoRecord['Puerto'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentPuerto, $InputTemplate);

                        $ContentUsuario = array(
                            '<font color="red">*</font> Usuario:',
                            '<input class="form-control focused e_requerido" name="Usuario' . $Key . '" id="Usuario' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' type="text" value="' . $AccesoRecord['Usuario'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentUsuario, $InputTemplate);

                        $ContentPassword = array(
                            '<font color="red">*</font> Password:',
                            '<input type="password" class="form-control focused e_requerido" name="Password' . $Key . '" id="Password' . $Key . '" data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' type="text" value="' . $AccesoRecord['Password'] . '" />'
                        );
                        print ReplaceContentPage($TagsToReplace, $ContentPassword, $InputTemplate);

                    ?>
                </fieldset>
            </div>
            <?php
            $Count++;
        }

        $AccesoId = substr_replace($AccesoId, '', -1);
        ?>
        <input type="hidden" name="AccesoUpdate" value="AccesoUpdate">
        <input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($AccesoId, $Random); ?>">
        <span class="clearfix"></span>
        <hr />
        <?php
        $Back = 'Acceso';
        include '../CelaTemplate/ActiosnFormUpdate.php';
        ?>
</form>
