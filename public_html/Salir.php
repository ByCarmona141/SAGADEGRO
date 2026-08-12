<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');

	date_default_timezone_set(  GetValue(
			sprintf('SELECT `Nombre`
						 FROM CelaZonaHoraria
						 WHERE `id` = (	SELECT `Valor`
									    FROM CelaConfiguraci_on
									    WHERE `Nombre` = %s
								      );',
				GetSQLValueString('ZonaHoraria', 'varchar')
			),
			'Nombre'
		)
	);

	$ExitForm = "index";

	if( $SessionCurrentUser != -1 ){
		$ExitForm = 'CelaUsuario?' . EncodeThis('Action=LoginAs&Key=' . $SessionCurrentUser . '&Activar=1');
	}else{
		DestroySession();
		$Session -> Destroy();
		$ExitForm = 'index';
		$Connection -> close();
		header(sprintf('Location: %s', $ExitForm));
		exit();
	}
	$Connection -> close();
	header(sprintf("Location: %s", $ExitForm));
	exit();
?> 
