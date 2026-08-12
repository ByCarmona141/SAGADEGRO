<?php
	function AccesoCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Acceso
									( `id`, `TipoAcceso`, `Host`, `Puerto`, `Usuario`, `Password`, `Dispositivo` )
								 VALUES
								    (  %s,  %s,  %s,  %s,  %s,  %s,  %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['TipoAcceso'], 'int'),
							GetSQLValueString($FormData['Host'], 'varchar'),
							GetSQLValueString($FormData['Puerto'], 'int'),
							GetSQLValueString($FormData['Usuario'], 'varchar'),
							GetSQLValueString($FormData['Password'], 'varchar'),
							GetSQLValueString($FormData['Dispositivo'], 'int')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordAcceso    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordAcceso;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function AccesoEliminar($Key) {
		global $Connection;

		$ConsultaElimina =  sprintf('DELETE FROM Acceso WHERE `id` = %s;',
								GetSQLValueString($Key, 'tinyint unsigned')
							);
		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function AccesoLeer($Params = NULL) {
		global $Privileges;

        $Condition = '';

        if ($Params != NULL) {
            $Condition = ' Dispositivo = ' . $Params['Dispositivo'];
        }

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'Acceso',
				'Alias' => ''
			],
			'Index'     => [
				'IndexName' => 'id',
				'Alias' => ''
			],
			'Columns'   =>  [
				[
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Actions',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => '(SELECT Nombre FROM TipoAcceso WHERE TipoAcceso.id = Acceso.TipoAcceso)',
					'Alias'     => 'TipoAcceso',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Host',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Puerto',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Usuario',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Password',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'Contrasena'
				],
				[
					'Type'      => 2,
					'ColumName' => '(SELECT Nombre FROM Dispositivo WHERE Dispositivo.id = Acceso.Dispositivo)',
					'Alias'     => 'Dispositivo',
					'Extra'     => '',
					'Render'    => ''
				]
			],
			'Condition'     => $Condition,
			'Group'         => '',
			'Order'         => ' id ASC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 0
        ];

		return $ServerQuery;
	}

	function AccesoActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Acceso
								SET `TipoAcceso` = %s, `Host` = %s, `Puerto` = %s, `Usuario` = %s, `Password` = %s, `Dispositivo` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['TipoAcceso'], 'int'),
							GetSQLValueString($FormData['Host'], 'varchar'),
							GetSQLValueString($FormData['Puerto'], 'int'),
							GetSQLValueString($FormData['Usuario'], 'varchar'),
							GetSQLValueString($FormData['Password'], 'int'),
							GetSQLValueString($FormData['Dispositivo'], 'int'),
							GetSQLValueString($Key, 'tinyint unsigned')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)) {
			$Data['Status'] = 'OK';
		} else {
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function AccesoQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Dispositivo`, `Dispositivo` FROM Acceso  ORDER BY `Dispositivo` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Dispositivo` FROM Acceso  ORDER BY `Dispositivo` ASC');
		}

		return $Query;
	}
?>