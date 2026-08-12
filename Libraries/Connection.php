<?php
	/**

	 * Fecha: 29/12/2014 23:24:24
	 * Descripci�n: Archivo de Conexi�n del sistema.
	 * Comentario: Archivo requerido para el sistema para ofrecer la conectividad con la base de datos.
	 * Librer�as: NO
	 * Base de datos y permisos: No
	 **/

	$RemoteServer   = 'db';
	$DataBase       = 'sagadegro';
	$User           = 'root';
	$Password       = 'root';

	mysqli_report(MYSQLI_REPORT_STRICT);
	try {
		$Connection = new mysqli($RemoteServer, $User, $Password, $DataBase);
		//Establecemos la zona horaria con la que esta trabajando.
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

		setlocale(LC_ALL, 'es_MX.utf8');

		define('DIR_APPLICATION', '/home/mspv/web/mspv.mx/public_html/');
		define('BASE_DIR', '/home/mspv/web/mspv.mx/');
		define('LOCAL_DIR', '../');
		define('TEMP_DIR', '/home/mspv/web/mspv.mx/public_html/repositorio/temp/');
		define('HTTP_SERVER', 'http://mspv.mx/');
		define('HTTPS_SERVER', 'https://mspv.mx/');

	} catch (mysqli_sql_exception $e) {
		$Error = $e ->getMessage();
		include 'CelaDataBaseError.php';
		exit();
	}

?>
