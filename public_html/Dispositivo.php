<?php
require_once('../Libraries/Functions.php');
require_once('../Libraries/Session.class.php');
require_once('../Ubicacion/Ubicacion.php');
require_once('../Rack/Rack.php');
require_once('../TipoDispositivo/TipoDispositivo.php');
require_once('../Estatus/Estatus.php');
require_once('../Modelo/Modelo.php');
require_once('../Dispositivo/Dispositivo.php');
require_once('../Libraries/Connection.php');
require_once('../Libraries/GetSession.php');
require_once('../Libraries/Security.php');

$ArgsDispositivoVistaLeer = array(
    'Table'             => 'Dispositivo',
    'ServerSource'      => '../Dispositivo/Dispositivo.php',
    'ServerFunction'    => 'DispositivoLeer',
    'RouteForm'         => $RouteForm
);

$ArgsCelaHeadContent['FormTitle'] = 'Listado de Dispositivos';

$MyScripts  = '';
$Content    = '';
$MyStyles   = '';

if(isset($_GET['Action'])) {
    switch($_GET['Action']) {
        case 'Crear':
            /*Se verifica si se tiene el privilegio de crear*/
            if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
                $ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Dispositivo';

                /*Se verifica que haya evento "submit"*/
                if((isset($_POST['DispositivoInsert'])) && ($_POST['DispositivoInsert'] == 'DispositivoInsert')) {
                    /*Se invoca la funcion crear*/
                    $Data = array(
                        'Nombre' => $_POST['Nombre'],
                        'TipoDispositivo' => $_POST['TipoDispositivo'],
                        'Dispositivo' => $_POST['Dispositivo'],
                        'Modelo' => $_POST['Modelo'],
                        'Estatus' => $_POST['Estatus'],
                        'MAC' => strtoupper($_POST['MAC']),
                        'Ubicacion' => $_POST['Ubicacion'],
                        'Rack' => $_POST['Rack'],
                        'IP' => ($_POST['IP'] == '' || $_POST['IP'] == NULL) ? 'DHCP' : $_POST['IP'],
                        'Serial' => $_POST['Serial']
                    );
                    $Dispositivo = DispositivoCrear($Data);

                    if($Dispositivo['Status'] == 'OK') {
                        /*Se registra la acción "Crear" en la bitacora*/
                        RecordLog('Dispositivo', $Dispositivo['idRecord'], 2, $SessionUserId, $Data);
                        $Status = true;

                        /*Se carga la vista de lectura con mensaje creación correcta*/
                        $ArgsCelaActionMessage = array(
                            'StatusMessage' => 'success',
                            'IconMessage'   => 'fa-check',
                            'TitleMessage'  => 'Registro exitoso!',
                            'TextMessage'   => 'El nuevo elemento se registr&oacute; correctamente.'
                        );

                        $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

                        if(isset($_POST['InsertBack']) && $_POST['InsertBack'] == 1){
                            /*Se carga la vista de Creación*/
                            $ArgsDispositivoVistaCrear = array(
                                'SessionGroupId'    => $SessionGroupId,
                                'Random'            => $SessionRandom,
                                'FormAction'        => $RouteForm
                            );

                            $Content .= LoadContentPage('../Dispositivo/DispositivoCrear.php', $ArgsDispositivoVistaCrear);
                        }else{
                            /*Se carga la vista de Leer*/
                            if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
                                $ArgsCelaHeadContent['FormTitle'] = 'Listado de Dispositivos';
                                $Content  .= LoadContentPage('../Dispositivo/DispositivoLeer.php', $ArgsDispositivoVistaLeer);

                                $ArgsDispositivoJavascript = array(
                                    'Table' => 'Dispositivo',
                                );

                                $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsDispositivoJavascript);
                            }
                        }
                    }else{
                        $Status = false;
                        /*Se carga la vista de lectura con mensaje de error de creación*/
                        $ArgsCelaActionMessage = array(
                            'StatusMessage' => 'danger',
                            'IconMessage'   => 'fa-times',
                            'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
                            'TextMessage'   => $Dispositivo['Error'].'<br />puede <a href="Dispositivo?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
                        );

                        $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                    }
                }else{
                    /*Se carga la vista de Creación*/
                    $ArgsDispositivoVistaCrear = array(
                        'SessionGroupId'    => $SessionGroupId,
                        'Random'            => $SessionRandom,
                        'FormAction'        => $RouteForm
                    );

                    $Content .= LoadContentPage('../Dispositivo/DispositivoCrear.php', $ArgsDispositivoVistaCrear);
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
                        sprintf('SELECT * FROM Dispositivo WHERE `id` = %s;',
                            GetSQLValueString($Key, 'tinyint unsigned')
                        )
                    );
                    $Dispositivo = DispositivoEliminar($Key);

                    if($Dispositivo['Status'] == 'ERROR'){
                        /*Se guarda el error para mostrarlo*/
                        $Status = false;
                        $Result = array(
                            'Index' => $Key,
                            'Error' => $Dispositivo['Error']
                        );

                        $BadKeys[] = $Result;
                    }else{
                        /*Se registra la acción "Eliminar" en la bitacora*/
                        RecordLog('Dispositivo', $Key, 3, $SessionUserId, $Data);
                    }
                }
                if($Status){
                    /*Se carga la vista de lectura con mensaje de eliminación correcta*/
                    $ArgsCelaActionMessage = array(
                        'StatusMessage' => 'success',
                        'IconMessage'   => 'fa-check',
                        'TitleMessage'  => 'Eliminaci&oacute;n correcta!',
                        'TextMessage'   => 'El/Los elemento(s) se eliminar&oacute;n correctamente.'
                    );
                    $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

                    if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
                        $ArgsCelaHeadContent['FormTitle'] = 'Listado de Dispositivos';
                        $Content .= LoadContentPage('../Dispositivo/DispositivoLeer.php', $ArgsDispositivoVistaLeer);

                        $ArgsDispositivoJavascript = array(
                            'Table' => 'Dispositivo',
                        );
                        $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsDispositivoJavascript);
                    }
                }else{
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
                    $ArgsCelaActionMessage['TextMessage'] .= '<a href="Dispositivo"
	class="btn btn-danger">Aceptar</a>';

                    $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                }
            }else{
                $Connection -> close();
                header(sprintf('Location: %s', 'Dispositivo'));
            }
            break;
        case 'Actualizar':
            /*Se verifica si se tiene el privilegio de actualizar*/
            if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
                $ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Dispositivos';

                /*Se verifica que haya evento "submit"*/
                if(isset($_POST['DispositivoUpdate']) && $_POST['DispositivoUpdate'] == 'DispositivoUpdate'){
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
                                'Nombre' => $_POST['Nombre' . $Key],
                                'TipoDispositivo' => $_POST['TipoDispositivo' . $Key],
                                'Dispositivo' => $_POST['Dispositivo' . $Key],
                                'Modelo' => $_POST['Modelo' . $Key],
                                'Estatus' => $_POST['Estatus' . $Key],
                                'MAC' => strtoupper($_POST['MAC' . $Key]),
                                'Ubicacion' => $_POST['Ubicacion' . $Key],
                                'Rack' => $_POST['Rack' . $Key],
                                'IP' => ($_POST['IP' . $Key] == '' || $_POST['IP' . $Key] == NULL) ? 'DHCP' : $_POST['IP' . $Key],
                                'Serial' => $_POST['Serial' . $Key]
                            );
                            $Dispositivo = DispositivoActualizar($Key, $Data);

                            if($Dispositivo['Status'] == 'ERROR') {
                                /*Se guarda el error para mostrarlo*/
                                $Status = false;
                                $Result = array(
                                    'Index' => $Key,
                                    'Error' => $Dispositivo['Error']
                                );

                                $BadKeys[] = $Result;
                            } else {
                                /*Se registra la acción "Actualizar" en la bitacora*/
                                RecordLog('Dispositivo', $Key, 5, $SessionUserId, $Data);
                            }
                        }

                        if($Status){
                            /*Se carga la vista de lectura con mensaje de actualización correcta*/
                            $ArgsCelaActionMessage = array(
                                'StatusMessage' => 'success',
                                'IconMessage'   => 'fa-check',
                                'TitleMessage'  => 'Actualizaci&oacute;n exitosa!',
                                'TextMessage'   => 'El/Los elemento(s) se actualizar&oacute;n correctamente.'
                            );
                            $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

                            if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
                                $ArgsCelaHeadContent['FormTitle'] = 'Listado de Tipos de Dispositivos';
                                $Content .= LoadContentPage('../Dispositivo/DispositivoLeer.php', $ArgsDispositivoVistaLeer);

                                $ArgsDispositivoJavascript = array(
                                    'Table' => 'Dispositivo',
                                );
                                $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsDispositivoJavascript);
                            }
                        }else{
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
                            $ArgsCelaActionMessage['TextMessage'] .= '<a href="Dispositivo"
	class="btn btn-danger">Aceptar</a>';

                            $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                        }
                    }else{
                        /*Se carga la vista con error obtención de datos para eliminar*/
                        $ArgsCelaActionMessage = array(
                            'StatusMessage' => 'danger',
                            'IconMessage'   => 'fa-times',
                            'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
                            'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="Dispositivo"
	class="btn btn-danger">Aceptar</a>'
                        );
                        $Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
                    }
                }else{
                    /*Se verifica que haya datos para actualizar*/
                    if(isset($_GET['Key']) && $_GET['Key'] != '') {
                        /*Se carga la vista de actualización*/
                        $ArgsDispositivoVistaActualizar = array(
                            'SessionGroupId'    => $SessionGroupId,
                            'Random'            => $SessionRandom,
                            'FormAction'        => $RouteForm
                        );
                        $Content .= LoadContentPage('../Dispositivo/DispositivoActualizar.php', $ArgsDispositivoVistaActualizar);
                    }else{
                        /*Se carga la vista de lectura*/
                        $Connection -> close();
                        header(sprintf('Location: %s', 'Dispositivo'));
                    }
                }
            }
            break;
        case 'Topologia':
            /*Se carga la vista de lectura*/
            if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
                $ArgsCelaHeadContent['FormTitle'] = 'Topolog&iacute;a de Dispositivos';
        
                /*Se obtienen los dispositivos formateados para SagaGraph*/
                $dispositivos = DispositivoTopologia();
                
                /*Argumentos para la vista de topología*/
                $ArgsDispositivoVistaTopologia = array(
                    'Dispositivos' => $dispositivos
                );
                
                /*Se carga la vista de topología*/
                $Content .= LoadContentPage('../Dispositivo/DispositivoTopologia.php', $ArgsDispositivoVistaTopologia);
                
                /*Se carga el CSS de SagaGraph en MYSTYLE*/
                $MyStyles .= '<link rel="stylesheet" href="assets/css/sagagraph.css">' . "\n";
            } 
        break;
    }
}else{
    /*Se carga la vista de lectura*/
    if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
        $Content  .= LoadContentPage('../Dispositivo/DispositivoLeer.php', $ArgsDispositivoVistaLeer);

        $ArgsDispositivoJavascript = array(
            'Table' => 'Dispositivo',
        );
        $MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsDispositivoJavascript);
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