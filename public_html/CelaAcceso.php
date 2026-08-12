<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaAcceso".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaAcceso/CelaAcceso.php');
	require_once('../CelaUsuario/CelaUsuario.php');
	require_once('../CelaAcci_on/CelaAcci_on.php');
	require_once('../CelaAcci_on/CelaAcci_on.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaAccesoVistaLeer = array(
		'Table'             => 'CelaAcceso',
		'ServerSource'      => '../CelaAcceso/CelaAcceso.php',
		'ServerFunction'    => 'CelaAccesoLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Accesos';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';
	/*Se carga la vista de lectura*/
	if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
		$Content    = LoadContentPage('../CelaAcceso/CelaAccesoVistaLeer.php', $ArgsCelaAccesoVistaLeer);
		$MyScripts  .= LoadContentPage('../CelaAcceso/CelaAccesoJavascript.php');
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