<?php
$InputsTemplate = LoadTemplatePage('../CelaTemplate/InputsTemplate.php');
$TagsToReplace = array(
        '<!--#INPUTLABEL#-->',
        '<!--#INPUTELEMENT#-->'
);
?>
<form class="form-horizontal form_validate" method="POST" name="Form_Acceso" id="Form_Acceso" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" >
    <fieldset>
        <span class="clearfix"></span>
        <hr/>
        <?php
        $OpcTipoAcceso['Name']     = 'TipoAcceso';
        $OpcTipoAcceso['Class']    = 'form-control focused e_requerido';
        $OpcTipoAcceso['Custom'] = 'data-live-search="true"';
        $Query = TipoAccesoQueryCombo();

        $ContentTipoAcceso = array(
                '<font color="red">*</font> Tipo de Acceso:',
                FillSelect($Query, $OpcTipoAcceso, 1)
        );
        print ReplaceContentPage($TagsToReplace, $ContentTipoAcceso, $InputsTemplate);

        global $Dispositivo;

        if ($Dispositivo === NULL) {
                $OpcDispositivo['Name']     = 'Dispositivo';
                $OpcDispositivo['Class']    = 'form-control focused e_requerido';
                $OpcDispositivo['Custom'] = 'data-live-search="true"';
                $Query = DispositivoQueryCombo();

                $ContentDispositivo = array(
                        '<font color="red">*</font> Dispositivo:',
                        FillSelect($Query, $OpcDispositivo, 1)
                );
                print ReplaceContentPage($TagsToReplace, $ContentDispositivo, $InputsTemplate);
        } else {
                $ContenDispositivo = array(
                        '',
                        '<input type="hidden" name="Dispositivo" id="Dispositivo" value="' . $Dispositivo . '" />'
                );
                print ReplaceContentPage($TagsToReplace, $ContenDispositivo, $InputsTemplate);
        }

        $ContenHost = array(
                '<font color="red">*</font>Host: ',
                '<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Host" id="Host"  data-rango=\'{"minimo":"1", "maximo":"100", "mensaje":"Introduce un valor entre 1 y 100 caracteres de longitud"}\' />'
        );
        print ReplaceContentPage($TagsToReplace, $ContenHost, $InputsTemplate);

        $ContenPuerto = array(
                ' Puerto: ',
                '<input type="number" class=" form-control focused" name="Puerto" id="Puerto" />'
        );
        print ReplaceContentPage($TagsToReplace, $ContenPuerto, $InputsTemplate);

        $ContenUsuario = array(
                '<font color="red">*</font>Usuario: ',
                '<input type="text" class=" form-control focused  e_requerido  e_longitud" name="Usuario" id="Usuario"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' />'
        );
        print ReplaceContentPage($TagsToReplace, $ContenUsuario, $InputsTemplate);

        $ContenPassword = array(
                '<font color="red">*</font>Password: ',
                '<input type="password" class=" form-control focused  e_requerido  e_longitud" name="Password" id="Password"  data-rango=\'{"minimo":"1", "maximo":"64", "mensaje":"Introduce un valor entre 1 y 64 caracteres de longitud"}\' />'
        );
        print ReplaceContentPage($TagsToReplace, $ContenPassword, $InputsTemplate);
        ?>
        <input type="hidden" name="AccesoInsert" value="AccesoInsert"/>
        <span class="clearfix"></span>
        <hr/>
        <?php
        $Back = $FormAction;
        include '../CelaTemplate/CelaActionsForm.php';
        ?>
    </fieldset>
</form>