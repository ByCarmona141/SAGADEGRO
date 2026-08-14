<?php
require_once('../Libraries/Functions.php');
require_once('../Libraries/Session.class.php');
require_once('../Dispositivo/Dispositivo.php');
require_once('../TipoAcceso/TipoAcceso.php');
require_once('../Acceso/Acceso.php');
require_once('../Libraries/Connection.php');
require_once('../Libraries/GetSession.php');
require_once('../Libraries/Security.php');

$ArgsAccesoVistaLeer = array(
    'Table'             => 'Acceso',
    'ServerSource'      => '../Acceso/Acceso.php',
    'ServerFunction'    => 'AccesoLeer',
    'RouteForm'         => $RouteForm
);

$ArgsCelaHeadContent['FormTitle'] = 'Listado de Accesos';

$MyScripts  = '';
$Content    = '';
$MyStyles   = '';

if(isset($_GET['Action'])) {
    switch($_GET['Action']) {
        case 'Crear':
            /*Se verifica si se tiene el privilegio de crear*/
            if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
                $ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Acceso';

                $Dispositivo = NULL;

                if (!empty($_GET['Dispositivo'])) {
                    $Dispositivo = $_GET['Dispositivo'];
                } else {
                    $Dispositivo = $_POST['Dispositivo'];
                }

                /*Se verifica que haya evento "submit"*/
                if((isset($_POST['AccesoInsert'])) && ($_POST['AccesoInsert'] == 'AccesoInsert')) {
                    /*Se invoca la funcion crear*/
                    $Data = array(
                        'TipoAcceso' => $_POST['TipoAcceso'],
                        'Host' => $_POST['Host'],
                        'Puerto' => $_POST['Puerto'],
                        'Usuario' => $_POST['Usuario'],
                        'Password' => $_POST['Password'],
                        'Dispositivo' => $Dispositivo
                    );
                    $Acceso = AccesoCrear($Data);

                    if($Acceso['Status'] == 'OK') {
                        /*Se registra la acción "Crear" en la bitacora*/
                        RecordLog('Acceso', $Acceso['idRecord'], 2, $SessionUserId, $Data);
                        $Status = true;

                        /*Se carga la vista de lectura con mensaje creación correcta*/
                        $ArgsCelaActionMessage = array(
                            'StatusMessage' => 'success',
                            'IconMessage'   => 'fa-check',
                            'TitleMessage'  => 'Registro exitoso!',
                            'TextMessage'   => 'El nuevo elemento se registr&oacute; correctamente.'
                        );

                        $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

                        if(isset($_POST['InsertBack']) && $_POST['InsertBack'] == 1) {
                            /*Se carga la vista de Creación*/
                            $ArgsAccesoVistaCrear = array(
                                'SessionGroupId'    => $SessionGroupId,
                                'Random'            => $SessionRandom,
                                'FormAction'        => $RouteForm
                            );

                            $Content .= LoadContentPage('../Acceso/AccesoCrear.php', $ArgsAccesoVistaCrear);
                        } else {
                            /*Se carga la vista de Leer*/
                            if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
                                $ArgsCelaHeadContent['FormTitle'] = 'Listado de Accesos';
                                $Content  .= LoadContentPage('../Acceso/AccesoLeer.php', $ArgsAccesoVistaLeer);

                                $ArgsAccesoJavascript = array(
                                    'Table' => 'Acceso',
                                );

                                $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsAccesoJavascript);
                            }
                        }
                    } else {
                        $Status = false;
                        /*Se carga la vista de lectura con mensaje de error de creación*/
                        $ArgsCelaActionMessage = array(
                            'StatusMessage' => 'danger',
                            'IconMessage'   => 'fa-times',
                            'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
                            'TextMessage'   => $Acceso['Error'].'<br />puede <a href="Acceso?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
                        );

                        $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                    }
                } else {
                    /*Se carga la vista de Creación*/
                    $ArgsAccesoVistaCrear = array(
                        'SessionGroupId'    => $SessionGroupId,
                        'Random'            => $SessionRandom,
                        'FormAction'        => $RouteForm
                    );

                    $Content .= LoadContentPage('../Acceso/AccesoCrear.php', $ArgsAccesoVistaCrear);
                }
            }
            break;
        case 'Eliminar':
            /*Se verifica que haya datos para eliminar y verifica si se tiene el privilegio de crear*/
            if(isset($_GET['Key']) && $_GET['Key'] != '' && isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1) {
                $Status = true;

                /*Se recorre cada uno de los elementos que se van a eliminar*/
                foreach ($_GET['Key'] as $Key) {
                    /*Se invoca la funcion eliminar*/
                    $Data = GetValue(
                        sprintf('SELECT * FROM Acceso WHERE `id` = %s;',
                            GetSQLValueString($Key, 'tinyint unsigned')
                        )
                    );
                    $Acceso = AccesoEliminar($Key);

                    if($Acceso['Status'] == 'ERROR'){
                        /*Se guarda el error para mostrarlo*/
                        $Status = false;
                        $Result = array(
                            'Index' => $Key,
                            'Error' => $Acceso['Error']
                        );

                        $BadKeys[] = $Result;
                    }else{
                        /*Se registra la acción "Eliminar" en la bitacora*/
                        RecordLog('Acceso', $Key, 3, $SessionUserId, $Data);
                    }
                }
                if($Status) {
                    /*Se carga la vista de lectura con mensaje de eliminación correcta*/
                    $ArgsCelaActionMessage = array(
                        'StatusMessage' => 'success',
                        'IconMessage'   => 'fa-check',
                        'TitleMessage'  => 'Eliminaci&oacute;n correcta!',
                        'TextMessage'   => 'El/Los elemento(s) se eliminar&oacute;n correctamente.'
                    );
                    $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

                    if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
                        $ArgsCelaHeadContent['FormTitle'] = 'Listado de Accesos';
                        $Content .= LoadContentPage('../Acceso/AccesoLeer.php', $ArgsAccesoVistaLeer);

                        $ArgsAccesoJavascript = array(
                            'Table' => 'Acceso',
                        );
                        $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsAccesoJavascript);
                    }
                } else {
                    /*Se carga la vista con el mensaje de error de eliminación*/
                    $ArgsCelaActionMessage = array(
                        'StatusMessage' => 'danger',
                        'IconMessage'   => 'fa-times',
                        'TitleMessage'  => 'Oops!... Ocurrio un error eliminando el/los elemento(s)',
                        'TextMessage'   => 'Algunos elementos pudieron no haberse eliminado'
                    );

                    $ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
                    
                    for($i = 0; $i < count($BadKeys); $i++) {
                        $ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
                    }
                    
                    $ArgsCelaActionMessage['TextMessage'] .= '</div>';
                    $ArgsCelaActionMessage['TextMessage'] .= '<a href="Acceso"
	class="btn btn-danger">Aceptar</a>';

                    $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                }
            } else {
                $Connection -> close();
                header(sprintf('Location: %s', 'Acceso'));
            }
            break;
        case 'Actualizar':
            /*Se verifica si se tiene el privilegio de actualizar*/
            if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
                $ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Accesos';

                /*Se verifica que haya evento "submit"*/
                if(isset($_POST['AccesoUpdate']) && $_POST['AccesoUpdate'] == 'AccesoUpdate'){
                    /*Se decodicia el arreglo de las claves para actualizar*/
                    $EncryptedKey = (isset($_POST[Encrypt('id', $SessionRandom)]) ? $_POST[Encrypt('id', $SessionRandom)]:'');

                    if($EncryptedKey != '') {
                        $Keys = Decrypt($EncryptedKey, $SessionRandom);
                        $Keys = explode(',', $Keys);
                        $Status = true;
                    } else {
                        $Status = false;
                    }

                    $BadKeys = array();
                    if($Status) {
                        $Status = true;
                        /*Se recorre cada uno de los elementos que se van a actualizar*/
                        foreach($Keys as $Key) {
                            /*Se invoca la funcion actulizar*/
                            $Data = array(
                                'TipoAcceso' => $_POST['TipoAcceso' . $Key],
                                'Host' => $_POST['Host' . $Key],
                                'Puerto' => $_POST['Puerto' . $Key],
                                'Usuario' => $_POST['Usuario' . $Key],
                                'Password' => $_POST['Password' . $Key],
                                'Dispositivo' => $_POST['Dispositivo' . $Key]
                            );
                            $Acceso = AccesoActualizar($Key, $Data);

                            if($Acceso['Status'] == 'ERROR') {
                                /*Se guarda el error para mostrarlo*/
                                $Status = false;
                                $Result = array(
                                    'Index' => $Key,
                                    'Error' => $Acceso['Error']
                                );

                                $BadKeys[] = $Result;
                            } else {
                                /*Se registra la acción "Actualizar" en la bitacora*/
                                RecordLog('Acceso', $Key, 5, $SessionUserId, $Data);
                            }
                        }

                        if($Status) {
                            /*Se carga la vista de lectura con mensaje de actualización correcta*/
                            $ArgsCelaActionMessage = array(
                                'StatusMessage' => 'success',
                                'IconMessage'   => 'fa-check',
                                'TitleMessage'  => 'Actualizaci&oacute;n exitosa!',
                                'TextMessage'   => 'El/Los elemento(s) se actualizar&oacute;n correctamente.'
                            );
                            $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

                            if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
                                $ArgsCelaHeadContent['FormTitle'] = 'Listado de Tipos de Accesos';
                                $Content .= LoadContentPage('../Acceso/AccesoLeer.php', $ArgsAccesoVistaLeer);

                                $ArgsAccesoJavascript = array(
                                    'Table' => 'Acceso',
                                );
                                $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsAccesoJavascript);
                            }
                        } else {
                            /*Se carga la vista con el mensaje de error de actualización*/
                            $ArgsCelaActionMessage = array(
                                'StatusMessage' => 'danger',
                                'IconMessage'   => 'fa-times',
                                'TitleMessage'  => 'Oops!... Ocurrio un error actualizando el/los elemento(s)',
                                'TextMessage'   => 'Algunos elementos pudieron no haberse actualizado'
                            );

                            $ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
                            for($i = 0; $i < count($BadKeys); $i++){
                                $ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
                            }
                            $ArgsCelaActionMessage['TextMessage'] .= '</div>';
                            $ArgsCelaActionMessage['TextMessage'] .= '<a href="Acceso"
	class="btn btn-danger">Aceptar</a>';

                            $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                        }
                    } else {
                        /*Se carga la vista con error obtención de datos para eliminar*/
                        $ArgsCelaActionMessage = array(
                            'StatusMessage' => 'danger',
                            'IconMessage'   => 'fa-times',
                            'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
                            'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="Acceso"
	class="btn btn-danger">Aceptar</a>'
                        );
                        $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                    }
                } else {
                    /*Se verifica que haya datos para actualizar*/
                    if(isset($_GET['Key']) && $_GET['Key'] != '') {
                        /*Se carga la vista de actualización*/
                        $ArgsAccesoVistaActualizar = array(
                            'SessionGroupId'    => $SessionGroupId,
                            'Random'            => $SessionRandom,
                            'FormAction'        => $RouteForm
                        );
                        $Content .= LoadContentPage('../Acceso/AccesoActualizar.php', $ArgsAccesoVistaActualizar);
                    } else {
                        /*Se carga la vista de lectura*/
                        $Connection -> close();
                        header(sprintf('Location: %s', 'Acceso'));
                    }
                }
            }
            break;
    }
} else {
    /*Se carga la vista de lectura*/
    if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
        if(isset($_GET['Dispositivo']) && $_GET['Dispositivo'] != ''){
            $ArgsAccesoJavascript['Params']['Dispositivo']    = $_GET['Dispositivo'];
            $ArgsAccesoVistaLeer['Params']['Dispositivo']     = $_GET['Dispositivo'];
        }

        $ArgsAccesoVistaLeer['SessionRandom']  = $SessionRandom;

        $Content  .= LoadContentPage('../Acceso/AccesoLeer.php', $ArgsAccesoVistaLeer);
        $MyScripts  .= LoadContentPage('../Acceso/AccesoJavascriptLeer.php');

        $ArgsAccesoJavascript = array(
            'Table' => 'Acceso',
        );
        $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsAccesoJavascript);
    }
}

/*---Se carga el contenido de la pagina---*/
$Header         = LoadContentPage('../CelaTemplate/CelaHead.php', $ArgsCelaHead);
$HeadBar        = LoadContentPage('../CelaTemplate/CelaHeadBar.php', $ArgsCelaHeadBar);
$SideBar        = LoadContentPage('../CelaTemplate/CelaSideBar.php', $ArgsCelaSideBar);
$About          = LoadContentPage('../CelaTemplate/CelaAbout.php', $ArgsCelaAbout);
$LockSession    = LoadContentPage('../CelaTemplate/CelaLockSession.php', $ArgsCelaLockSession);
$Breadcrumb     = LoadContentPage('../CelaTemplate/CelaBreadcrumb.php', $ArgsCelaBreadcrumb);
$HeaderForm     = LoadContentPage('../CelaTemplate/CelaHeadContent.php', $ArgsCelaHeadContent);
$FooterForm     = LoadContentPage('../CelaTemplate/CelaFooterContent.php');
$Footer         = LoadContentPage('../CelaTemplate/CelaFooter.php', $ArgsCelaFooter);
$Scripts        = LoadContentPage('../CelaTemplate/CelaJavascript.php');

/*---Se carga la plantilla HTML---*/
$HTML   = LoadTemplatePage('../CelaTemplate/CelaTemplate.php');

$TemplateTag = array(
    '<!--#HEADER#-->',
    '<!--#MYSTYLE#-->',
    '<!--#HORIZONTALMENU#-->',
    '<!--#VERTICALMENU#-->',
    '<!--#ABOUT#-->',
    '<!--#LOCKSESSION#-->',
    '<!--#BREADCRUMBS#-->',
    '<!--#HEADCONTENT#-->',
    '<!--#BODYCONTENT#-->',
    '<!--#FOOTERCONTENT#-->',
    '<!--#FOOTERPAGE#-->',
    '<!--#SCRIPTS#-->',
    '<!--#MYSCRIPTS#-->'
);

$HTMLContent = array(
    $Header,
    $MyStyles,
    $HeadBar,
    $SideBar,
    $About,
    $LockSession,
    $Breadcrumb,
    $HeaderForm,
    $Content,
    $FooterForm,
    $Footer,
    $Scripts,
    $MyScripts
);

$HTML   = ReplaceContentPage($TemplateTag, $HTMLContent, $HTML);
$Connection -> close();
ViewPage($HTML);
?>