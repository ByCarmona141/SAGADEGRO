<?php
	function CelaUsuarioCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaUsuario
									(`id`, `NombreCompleto`, `Usuario`, `Contrase_na`, `CorreoElectr_onico`, `Status`, `Rol`)
								 VALUES
									(%s, %s, %s, %s, %s, %s, %s);',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['NombreCompleto'], 'varchar') ,
							GetSQLValueString($FormData['Usuario'], 'varchar') ,
							GetSQLValueString($FormData['Contrase_na'], 'varchar') ,
							GetSQLValueString($FormData['CorreoElectr_onico'], 'varchar') ,
							GetSQLValueString($FormData['Status'], 'int') ,
							GetSQLValueString($FormData['Rol'], 'int')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaUsuario    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaUsuario;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaUsuarioEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('UPDATE CelaUsuario
									 SET `Status` = %s
									 WHERE `id` = %s;',
								GetSQLValueString(2, 'int'), GetSQLValueString($Key, 'int'));
		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		
		return $Data;
	}

	function CelaUsuarioLeer($Params = null){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaUsuario',
				'Alias' => ''
			),
			'Index'     => array(
				'IndexName' => 'id',
				'Alias' => ''
			),
			'Columns'   =>  array(
				0 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Actions',
					'Render'    => ''
				),
				1 => array(
					'Type'      => 2,
					'ColumName' => 'NombreCompleto',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'Usuario',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'CorreoElectr_onico',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => '(select Nombre from  CelaRol where CelaRol.id = CelaUsuario.Rol)',
					'Alias'     => 'NombreRol',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Privilege',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 0,
					'ColumName' => 'Status',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				7 => array(
					'Type'      => 0,
					'ColumName' => '3',
					'Alias'     => 'Origen',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => ' Rol IN ( ' . $Params['Group'] . ' )',
			'Group'         => '',
			'Order'         => ' NombreCompleto ASC ',
			'RenderRow'     => 'Status',
			'Privileges'    => $Privileges,
			'Debug'         => 1
		);
		
		return $ServerQuery;
	}

	function CelaUsuarioActualizar($Key, $FormData){
		global $Connection;
		global $Privileges;

		$UpdateQuery =  sprintf('UPDATE CelaUsuario
								 SET `NombreCompleto` = %s, `Usuario` = %s, `Contrase_na` = %s, `CorreoElectr_onico` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['NombreCompleto'], 'varchar') ,
							GetSQLValueString($FormData['Usuario'], 'varchar'),
							GetSQLValueString($FormData['Contrase_na'], 'varchar'),
							GetSQLValueString($FormData['CorreoElectr_onico'], 'varchar'), GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1){
				$UpdateQuery =  sprintf('UPDATE CelaUsuario
										 SET `Status` = %s, `Rol` = %s
										 WHERE `id` =  %s;',
									GetSQLValueString($FormData['Status'], 'int'),
									GetSQLValueString($FormData['Rol'], 'int'), GetSQLValueString($Key, 'int')
								);

				if($UpdateResult = $Connection -> query($UpdateQuery)){
					$Data['Status'] = 'OK';
				}else{
					$Data['Status'] = 'ERROR';
					$Data['Error']  = $Connection -> error;
				}
			}
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaUsuarioLoginAs($UserId){
		global $Connection;
		global $Session;
		$Status     = false;
		
		$LoginQuery =   sprintf('SELECT
									c.`id` as idUsuario, c.`Status` as Status, c.`Usuario` as NombreDeUsuario,
									c1.`id` as idRol, c1.`Siglas` as SiglasDelRol
								 FROM CelaUsuario c
									INNER JOIN CelaRol c1 ON (c.`Rol` = c1.`id` )
								 WHERE
									c.`id` = %s;',
							GetSQLValueString($UserId, 'int')
						);

		$LoginResult = $Connection -> query($LoginQuery);
		$LoginRecord = $LoginResult -> fetch_assoc();
		if($LoginResult -> num_rows == 1) { // Si existe el usuario

			//Obtenemos las variables del sistema
			$Session = new CelaSession();
			$Session -> SetUser( $_COOKIE['idUsuario'] );
			$Session -> SetCookie( $_COOKIE['CelaRandom'] );
			$Session -> SetConnection($Connection);
			$Session -> Start();

			$SessionsVars = $Session -> Dump();

			if($SessionsVars['CelaCurrentUser']['Valor'] != -1) {
				$Session -> Update('CelaCurrentUser', -1);
			} else {
				$Session -> Update('CelaCurrentUser', $SessionsVars['CelaUserId']['Valor']);
			}

			$Session -> Update('CelaUser', $LoginRecord['NombreDeUsuario']);
			$Session -> Update('CelaUserId', $LoginRecord['idUsuario']);
			$Session -> Update('CelaGroup', $LoginRecord['SiglasDelRol']);
			$Session -> Update('CelaGroupId', $LoginRecord['idRol']);
			$Session -> Update('CelaLastAccess', date('Y-m-d H:i:s'));
			$Session -> Update('CelaCurrentMenu', '');

			RecordLog($SessionsVars['CelaHostName']['Valor'], $LoginRecord['idUsuario'], 1, $LoginRecord['idUsuario']);
			$Status = true;
		}else{
			$Status = false;
		}
		return $Status;
	}

	function CelaUsuarioComboQuery($InText = false, $Filter = ''){
		if($InText === true){
			$Query = sprintf('SELECT `NombreCompleto`, `NombreCompleto` FROM CelaUsuario  ORDER BY `NombreCompleto` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `NombreCompleto` FROM CelaUsuario  ORDER BY `NombreCompleto` ASC');
		}elseif($InText == 'ByGroup'){
			$Query = sprintf('SELECT `id`, `NombreCompleto` FROM CelaUsuario WHERE  Rol IN ( ' . $Filter . ' ) ORDER BY `NombreCompleto` ASC');
		}

		return $Query;
	}

	function CelaSecureLoginCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaSecureLogin
									(`id`, `Usuario`, `Token`, `MaxTime`)
								 VALUES
									(%s, %s, %s, %s);',
			GetSQLValueString(NULL, 'int'),
			GetSQLValueString($FormData['Usuario'], 'varchar') ,
			GetSQLValueString($FormData['Token'], 'varchar') ,
			GetSQLValueString($FormData['MaxTime'], 'varchar')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaUsuario    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaUsuario;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}
	
	function CelaUsuarioGetData($Key, $Filter = 'id'){
		global $Connection;

		if(is_array($Key)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($Key, EXTR_OVERWRITE);
		}

		if($Filter == 'MyQuery'){
			$CelaUsuarioQuery = $Key;
		}else{
			$CelaUsuarioQuery = sprintf('SELECT * FROM CelaUsuario WHERE %s IN (%s)',
				GetSQLValueString($Filter, 'SQL'),
				$Key
			);
		}

		if($CelaUsuarioResult = $Connection -> query($CelaUsuarioQuery)){
			$CelaUsuarios = array();
			while($CelaUsuarioRecord = $CelaUsuarioResult -> fetch_assoc()){
				$CelaUsuarios[] = $CelaUsuarioRecord;
			}

			$Data = array(
				'Status' => 'OK',
				'Data' => $CelaUsuarios
			);
		}else{
			$Data = array(
				'Status'    => 'ERROR',
				'Error'     => $Connection -> error
			);
		}

		return $Data;
	}
?>