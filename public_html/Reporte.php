<?php
    require_once('../Libraries/Functions.php');
    require_once('../Libraries/ExceptionThrower.php');
    require_once('../Libraries/Session.class.php');
    require_once('../RegistroDeEntregables/RegistroDeEntregables.php');
    require_once('../Libraries/Connection.php');
    require_once('../Libraries/GetSession.php');
    require_once('../Libraries/Security.php');
    
    $ArgsCelaHeadContent['FormTitle'] = 'Reportes';
    
    $MyScripts  = '';
    $Content    = '';
    
    
    if(isset($_GET['Action']) && isset($Privileges['Leer']) && $Privileges['Leer'] == 1){
        switch($_GET['Action']){
            case 'ComparaNomina':
                $ArgsCelaHeadContent['FormTitle'] = 'Comparativo de Nominas';
                /*Se carga la vista para generar el reporte de productos*/
                $Content .= LoadContentPage('../Reporte/Nomina/ReporteNominaVistaLeer.php');
                $MyScripts .= LoadContentPage('../Reporte/Nomina/ReporteNominaJavascript.php');
                break;
            case 'Entregas':
                $ArgsCelaHeadContent['FormTitle'] = 'Comparativo de Nominas';
                /*Se carga la vista para generar el reporte de productos*/
                $Content .= LoadContentPage('../Reporte/Entregas/ReporteEntregasVistaLeer.php');
                $MyScripts .= LoadContentPage('../Reporte/Entregas/ReporteEntregasJavascript.php');
                break;
            case 'Cortes':
                $ArgsCelaHeadContent['FormTitle'] = 'Cortes por Mes';
                /*Se carga la vista para generar el reporte de productos*/
                $Content .= LoadContentPage('../Reporte/Cortes/ReporteCortesVistaLeer.php');
                $MyScripts .= LoadContentPage('../Reporte/Cortes/ReporteCortesJavascript.php');
                break;
            case 'XMLSucursal':
                $ArgsCelaHeadContent['FormTitle'] = 'XML\'s por Sucursal';
                /*Se carga la vista para generar el reporte de productos*/
                $Content .= LoadContentPage('../Reporte/XMLSucursal/ReporteXMLSucursalVistaLeer.php');
                $MyScripts .= LoadContentPage('../Reporte/XMLSucursal/ReporteXMLSucursalJavascript.php');
                break;
            case 'ResultadoNomina':
                $ArgsCelaHeadContent['FormTitle'] = 'Resumen de RFC/Mes sin archivos de facturacion o XML';
                /*Se carga la vista para generar el reporte de productos*/
                $Content .= LoadContentPage('../Reporte/Nomina/ReporteResultadoVistaLeer.php');
                $MyScripts .= LoadContentPage('../Reporte/Nomina/ReporteResultadoJavascript.php');
                break;
            case 'CargaTotal':
                $ArgsCelaHeadContent['FormTitle'] = 'Carga Total de XML\'s de ' . $_GET['Tipo'];
                /*Se carga la vista para generar el reporte de productos*/
                $Args = array(
                    'Tipo' => $_GET['Tipo'],
                    'Params' => array('Tipo' => $_GET['Tipo']),
                    'SessionRandom' => $SessionRandom
                );
                $Content .= LoadContentPage('../Reporte/XMLs/ReporteXMLsVistaLeer.php', $Args);
                $MyScripts .= LoadContentPage('../Reporte/XMLs/ReporteXMLsJavascript.php', $Args);
                break;
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
    $Scripts        = LoadContentPage('../CelaTemplate/CelaJavascript.php', $ArgsScript);
    
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
