<?php
	/*Se obtienen las configuraciones*/
	$GlobalConfig = array();
	$Query = 'SELECT * FROM CelaConfiguraci_on';
	$Result = $Connection->query($Query);
	while($Record = $Result->fetch_assoc()){
		$GlobalConfig[(isset($Record['Code']) && $Record['Code'] != ''? $Record['Code']:$Record['Nombre'])] = $Record['Valor'];
	}

	if(!isset($_COOKIE['CelaRandom']) || $_COOKIE['CelaRandom'] == '' || !isset($_COOKIE['idUsuario']) || $_COOKIE['idUsuario'] == '' ){
		DestroySession();
		$ExitForm = 'index';
		header(sprintf('Location: %s', $ExitForm));
		exit();
	}else{
//		$Limit =    GetValue(
//						sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
//							GetSQLValueString('TiempoDeCaducidadDeLaSesi_on(EnMinutos)', 'varchar')
//						),
//						'Valor'
//					);
		$Limit = $GlobalConfig['TiempoDeCaducidadDeLaSesi_on(EnMinutos)'];

		$idUsuario  = $_COOKIE['idUsuario'];
		$Random     = $_COOKIE['CelaRandom'];

		setcookie('idUsuario', $idUsuario, time() + ($Limit * 60), '/');
		setcookie('CelaRandom', $Random, time() + ($Limit * 60), '/');

		$SessionRandom          = $Random;
	}

	//Obtenemos las variables del sistema
	$Session = new CelaSession();
	$Session -> SetUser( $_COOKIE['idUsuario'] );
	$Session -> SetCookie( $_COOKIE['CelaRandom'] );
	$Session -> SetConnection($Connection);
	$Session -> Start();

	$SessionsVars = $Session -> Dump();

	$SessionUser            = (isset($SessionsVars['CelaUser']['Valor']) ? $SessionsVars['CelaUser']['Valor']:'');
	$SessionUserId          = (isset($SessionsVars['CelaUserId']['Valor']) ? $SessionsVars['CelaUserId']['Valor']:'');
	$SessionGroup           = (isset($SessionsVars['CelaGroup']['Valor']) ? $SessionsVars['CelaGroup']['Valor']:'');
	$SessionGroupId         = (isset($SessionsVars['CelaGroupId']['Valor']) ? $SessionsVars['CelaGroupId']['Valor']:'');
	$SessionAuthenticated   = (isset($SessionsVars['CelaAuthenticated']['Valor']) ? $SessionsVars['CelaAuthenticated']['Valor']:'');
	$SessionHostName        = (isset($SessionsVars['CelaHostName']['Valor']) ? $SessionsVars['CelaHostName']['Valor']:'');
	$SessionCurrentUser     = (isset($SessionsVars['CelaCurrentUser']['Valor']) ? $SessionsVars['CelaCurrentUser']['Valor']:'');
	$SessionCurrentMenu     = (isset( $SessionsVars['CelaCurrentMenu']['Valor']) ? $SessionsVars['CelaCurrentMenu']['Valor']:'');
	$UsuarioActivo          = (isset( $SessionsVars['Activo']['Valor']) ? $SessionsVars['Activo']['Valor']:'');
?>