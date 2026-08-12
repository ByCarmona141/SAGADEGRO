<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Connection.php');

	$SessionUserId = $_POST['Us'];
	$Password = $_POST['Password'];

	$Response = GetValue(sprintf('SELECT 1 as response FROM CelaHistoriaContrase_na WHERE Contrase_na = %s AND Usuario = %s;', GetSQLValueString($Password, 'varchar'), GetSQLValueString($SessionUserId, 'int')), 'response');

	if($Response == 'NULL'){
		/*Se compara la contraseña actual*/
		$Response = GetValue(sprintf('SELECT id FROM CelaUsuario WHERE id = %s AND Contrase_na = %s;', GetSQLValueString($SessionUserId, 'int'), GetSQLValueString($Password, 'varchar')), 'id');
	}

	print $Response == 'NULL' ? 'true':'false';