<?php
	function DispositivoCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Dispositivo
									( `id`, `Nombre`, Dispositivo, `MAC`, `Modelo`, `Ubicacion`, `Rack`, `TipoDispositivo`, `IP`, `Serial`, `Estatus` )
								 VALUES
								    (  %s,  %s, %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Dispositivo'], 'int'),
							GetSQLValueString($FormData['MAC'], 'varchar'),
							GetSQLValueString($FormData['Modelo'], 'int'),
							GetSQLValueString($FormData['Ubicacion'], 'int'),
							GetSQLValueString($FormData['Rack'], 'int'),
							GetSQLValueString($FormData['TipoDispositivo'], 'int'),
							GetSQLValueString($FormData['IP'], 'varchar'),
							GetSQLValueString($FormData['Serial'], 'varchar'),
							GetSQLValueString($FormData['Estatus'], 'int')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordDispositivo    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordDispositivo;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function DispositivoEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM Dispositivo WHERE `id` = %s;',
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

	function DispositivoLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'Dispositivo',
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
					'ColumName' => 'Nombre',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
                [
                    'Type'      => 2,
                    'ColumName' => '(SELECT Nombre FROM TipoDispositivo WHERE TipoDispositivo.id = Dispositivo.TipoDispositivo)',
                    'Alias'     => 'TipoDispositivo',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => '(SELECT Nombre FROM Dispositivo WHERE Dispositivo.id = Dispositivo.Dispositivo)',
                    'Alias'     => 'Dispositivo',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => '(SELECT (SELECT Nombre FROM Marca WHERE Marca.id = Modelo.Marca) AS Marca FROM Modelo WHERE Modelo.id = Dispositivo.Modelo)',
                    'Alias'     => 'Marca',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => '(SELECT Nombre FROM Modelo WHERE Modelo.id = Dispositivo.Modelo)',
                    'Alias'     => 'Modelo',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => '(SELECT Nombre FROM Estatus WHERE Estatus.id = Dispositivo.Estatus)',
                    'Alias'     => 'Estatus',
                    'Extra'     => '',
                    'Render'    => ''
                ],
				[
					'Type'      => 2,
					'ColumName' => 'MAC',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => '(SELECT Area FROM Ubicacion WHERE Ubicacion.id = Dispositivo.Ubicacion)',
					'Alias'     => 'Ubicacion',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => '(SELECT Nombre FROM Rack WHERE Rack.id = Dispositivo.Rack)',
					'Alias'     => 'Rack',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'IP',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Serial',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Accesos',
					'Render'    => ''
				]
			],
			'Condition'     => '',
			'Group'         => '',
			'Order'         => ' id ASC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 0
        ];

		return $ServerQuery;
	}

	function DispositivoActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Dispositivo
								SET `Nombre` = %s, `Dispositivo` = %s, `MAC` = %s, `Modelo` = %s, `Ubicacion` = %s, `Rack` = %s, `TipoDispositivo` = %s, `IP` = %s, `Serial` = %s, `Estatus` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Dispositivo'], 'int'),
							GetSQLValueString($FormData['MAC'], 'varchar'),
							GetSQLValueString($FormData['Modelo'], 'varchar'),
							GetSQLValueString($FormData['Ubicacion'], 'int'),
							GetSQLValueString($FormData['Rack'], 'int'),
							GetSQLValueString($FormData['TipoDispositivo'], 'int'),
							GetSQLValueString($FormData['IP'], 'varchar'),
							GetSQLValueString($FormData['Serial'], 'varchar'),
							GetSQLValueString($FormData['Estatus'], 'int'),
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

	function DispositivoQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM Dispositivo  ORDER BY `Nombre` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Nombre` FROM Dispositivo  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>