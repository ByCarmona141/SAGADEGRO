<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../Libraries/Connection.php');

	/*Se obtienen las configuraciones*/
	$GlobalConfig = array();
	$Query = 'SELECT * FROM CelaConfiguraci_on';
	$Result = $Connection->query($Query);
	while($Record = $Result->fetch_assoc()){
		$GlobalConfig[(isset($Record['Code']) && $Record['Code'] != ''? $Record['Code']:$Record['Nombre'])] = $Record['Valor'];
	}

	/*Se obtienen las variables del enlace*/
	if($_GET){
		$_GET = NULL;
		$String = Decrypt(substr(strrchr($_SERVER['REQUEST_URI'], '?'), 1)); //Obtener la url desde el ?

		$GET = preg_split('[&]', $String); //separo la url por &
		foreach($GET as $Value){
			$GET = preg_split ('[=]', $Value); //asigno los valores al GET

			if(substr_count($GET[0], '[]') == 1){
				//Es un arreglo
				$GET[0]             = str_replace('[]', '', $GET[0]);
				$_GET[$GET[0]][]    = (isset($GET[1]) ? $GET[1]:'');
			}
			else{
				$_GET[$GET[0]]      = (isset($GET[1]) ? $GET[1]:'');
			}
		}

		/*Se comprueba la autenticidad de la cadena*/
		$Aut = GetValue(sprintf('SELECT * FROM CelaSecureLogin WHERE id = %s AND Token BINARY = %s AND MaxTime > %s;',
					GetSQLValueString($_GET['id'], 'int'),
					GetSQLValueString($_GET['Token'], 'varchar'),
					GetSQLValueString(date('Y-m-d H:i:s'), 'datetime')
				)
			);

		/*Se autoriza la entrada del usuario*/
		if($Aut['Result'] != 'NULL'){
			/*Se obtiene la sessión del usuario*/
			$RedirectLoginFailed    = 'index';

			$LoginQuery             =   sprintf('SELECT
													c.`id` as idUsuario, c.`Status` as Status,
													c1.`id` as idRol, c1.`Siglas` as SiglasDelRol, c1.Status as StatusRol
												 FROM CelaUsuario c
													INNER JOIN CelaRol c1 ON ( c.`Rol` = c1.`id` )
												 WHERE
													c.`id` = %s;',
				GetSQLValueString($_GET['User'], 'int')
			);

			$LoginResult = $Connection -> query($LoginQuery);
			$LoginRecord = $LoginResult -> fetch_assoc();
			if( $LoginResult -> num_rows == 1 ){ // Si existe el usuario
				if($LoginRecord['Status'] == 1 && $LoginRecord['StatusRol'] == 1){
//					$NombreSistema  =   GetValue(
//						sprintf('SELECT `Valor` FROM CelaConfiguraci_on WHERE `Nombre` = %s;',
//							GetSQLValueString('NombreSistema', 'varchar')
//						),
//						'Valor'
//					);
					$NombreSistema  =   $GlobalConfig['NombreSistema'];

//					$TiempoSession  =   GetValue(
//						sprintf('SELECT `Valor` FROM CelaConfiguraci_on WHERE `Nombre` = %s;',
//							GetSQLValueString('TiempoDeCaducidadDeLaSesi_on(EnMinutos)', 'varchar')
//						),
//						'Valor'
//					);
					$TiempoSession  =   $GlobalConfig['TiempoDeCaducidadDeLaSesi_on(EnMinutos)'];

					$Status = true;

					$Random = $_GET['Token'];
					setcookie('CelaRandom', $Random, time() + ($TiempoSession * 60), '/');
					setcookie('idUsuario', $LoginRecord['idUsuario'], time() + ($TiempoSession * 60), '/');

					$Session = new CelaSession();
					$Session -> SetUser($LoginRecord['idUsuario']);
					$Session -> SetCookie($Random);
					$Session -> SetConnection($Connection);

					$Session -> Start();

					if(!$Session -> Add('CelaUser', $User)){
						$Status = false;
					}
					if(!$Session -> Add('CelaUserId', $LoginRecord['idUsuario'])){
						$Status = false;
					}
					if(!$Session -> Add('CelaGroup', $LoginRecord['SiglasDelRol'])){
						$Status = false;
					}
					if(!$Session -> Add('CelaGroupId', $LoginRecord['idRol'])){
						$Status = false;
					}
					if(!$Session -> Add('CelaAuthenticated', 'SI')){
						$Status = false;
					}
					if(!$Session -> Add('CelaCurrentUser', -1)){
						$Status = false;
					}
					if(!$Session -> Add('CelaCurrentMenu', '')){
						$Status = false;
					}
					if(!$Session -> Add('CelaHostName', $NombreSistema)){
						$Status = false;
					}
					if(!$Session -> Add('CelaFormAction', '')){
						$Status = false;
					}

					$Log =  RecordLog(
						$NombreSistema,
						$LoginRecord['idUsuario'],
						1,
						$LoginRecord['idUsuario'],
						array(
							'Message' => 'SECURE LOGIN',
							'Data' => $_GET
						)
					);

					if($Log['Status'] == 'OK' && $Status === true){
						$RedirectLoginSuccess = $_GET['Form'] . '?' . EncodeThis2('Action=' . $_GET['Action'] . '&Key[]=' . $_GET['Record'], $Random);

						$Connection -> close();
						header(sprintf('Location: %s', $RedirectLoginSuccess));
					}else{
						// Error de acceso
						RecordLog(
							'ACCESO A SISTEMA',
							$_POST['txtusuario'],
							1,
							'',
							array(
								'Message' => 'USER NOT FOUND',
								'Data' => $_POST
							)
						);
						//DestroySession();
						$Session -> Destroy();
						$Connection -> close();
						header(sprintf('Location: %s', 'index?' . Encrypt('LogFail=1', 'b5s1i4t5a1316')));
					}
				}else{
					// EL usuario esta bloqueado o cancelado
					RecordLog(
						'ACCESO A SISTEMA',
						$_POST['txtusuario'],
						$LoginRecord['idUsuario'],
						'',
						array(
							'Message' => 'UNAUTHORIZED USER',
							'Data' => $_POST
						)
					);
					DestroySession();
					$Connection -> close();
					header(sprintf('Location: %s', 'index?' . Encrypt('UserLock=1', 'b5s1i4t5a1316')));
				}
			}else{
				$Connection -> close();
				header('Location: index');
			}
		}else{
			$Connection -> close();
			header('Location: index');
		}
	}else{
		$Connection -> close();
		header('Location: index');
	}
?>