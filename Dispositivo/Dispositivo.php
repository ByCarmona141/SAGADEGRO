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

		$Table =  sprintf('(SELECT d.id, d.Nombre, d.MAC, m.Nombre AS Modelo, ma.Nombre AS Marca, u.Area AS Ubicacion, u.Piso, r.Nombre AS Rack, td.Nombre AS TipoDispositivo, d.IP, d.Serial, e.Nombre AS Estatus, padre.Nombre AS DispositivoPadre FROM Dispositivo d LEFT JOIN Modelo m ON d.Modelo = m.id LEFT JOIN Marca ma ON m.Marca = ma.id LEFT JOIN Ubicacion u ON d.Ubicacion = u.id LEFT JOIN Rack r ON d.Rack = r.id LEFT JOIN TipoDispositivo td ON d.TipoDispositivo = td.id LEFT JOIN CelaIcono ci ON td.Icono = ci.id LEFT JOIN Estatus e ON d.Estatus = e.id LEFT JOIN Dispositivo padre ON d.Dispositivo = padre.id)');

		$ServerQuery = [
			'Table'     => [
				'TableName'  => $Table,
				'Alias' => 'Dispositivo'
			],
			'Index'     => [
				'IndexName' => 'd.id',
				'Alias' => 'id'
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
                    'ColumName' => 'TipoDispositivo',
                    'Alias'     => '',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => 'DispositivoPadre',
                    'Alias'     => '',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => 'Marca',
                    'Alias'     => '',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => 'Modelo',
                    'Alias'     => '',
                    'Extra'     => '',
                    'Render'    => ''
                ],
                [
                    'Type'      => 2,
                    'ColumName' => 'Estatus',
                    'Alias'     => '',
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
					'ColumName' => 'Ubicacion',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Rack',
					'Alias'     => '',
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
			'Debug'         => 1
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

	/* ============================================================
	   FUNCIONES PARA SAGAGRAPH — Topología de Dispositivos
	   ============================================================ */

	/**
	 * DispositivoTopologia
	 * Obtiene todos los dispositivos formateados para la librería SagaGraph.
	 * Incluye joins a TipoDispositivo, Ubicacion y Estatus.
	 *
	 * @return array  Arreglo de dispositivos listos para JSON/SagaGraph
	 */
	function DispositivoTopologia() {
		global $Connection;

		$Query  = 'SELECT 
						d.id,
						d.Nombre AS name,
						d.IP AS ip,
						d.MAC AS mac,
						e.Nombre AS status_raw,
						td.Nombre AS tipo_raw,
						u.Area AS location,
						d.Dispositivo AS parentId
					FROM Dispositivo d
					LEFT JOIN TipoDispositivo td ON d.TipoDispositivo = td.id
					LEFT JOIN Ubicacion u        ON d.Ubicacion      = u.id
					LEFT JOIN Estatus e          ON d.Estatus        = e.id
					ORDER BY d.Dispositivo IS NULL DESC, d.id ASC';

		$Result = $Connection->query($Query);
		$Devices = array();

		if ($Result) {
			while ($Row = $Result->fetch_assoc()) {
				$Devices[] = DispositivoFormatearSagaGraph($Row);
			}
		}

		return $Devices;
	}

	/**
	 * DispositivoFormatearSagaGraph
	 * Mapea un registro de la BD al formato que espera SagaGraph.
	 *
	 * @param array $Row  Fila de la consulta SQL
	 * @return array
	 */
	function DispositivoFormatearSagaGraph($Row) {
		$tipoMap = array(
			'Modem'        => 'modem',
			'Cámara'       => 'camera',
			'Camara'       => 'camera',
			'Router'       => 'router',
			'Access Point' => 'ap',
			'AccessPoint'  => 'ap',
			'AP'           => 'ap',
			'Switch'       => 'switch',
			'Firewall'     => 'firewall',
			'Servidor'     => 'server',
			'Server'       => 'server',
			'Otro'         => 'other',
		);

		$tipoRaw   = isset($Row['tipo_raw']) ? trim($Row['tipo_raw']) : 'Otro';
		$tipoSaga  = isset($tipoMap[$tipoRaw]) ? $tipoMap[$tipoRaw] : 'other';

		$estadoRaw = isset($Row['status_raw']) ? strtolower(trim($Row['status_raw'])) : '';
		$estado    = ($estadoRaw === 'activo') ? 'active' : 'inactive';

		return array(
			'id'       => (int) $Row['id'],
			'name'     => $Row['name'],
			'type'     => $tipoSaga,
			'status'   => $estado,
			'ip'       => !empty($Row['ip'])     ? $Row['ip']     : 'N/A',
			'mac'      => !empty($Row['mac'])    ? $Row['mac']    : 'N/A',
			'location' => !empty($Row['location'])? $Row['location']: 'Sin ubicación',
			'parentId' => ($Row['parentId'] !== NULL) ? (int) $Row['parentId'] : NULL,
		);
	}

	/**
	 * DispositivoObtenerPorId
	 * Obtiene un dispositivo por su ID, formateado para SagaGraph.
	 *
	 * @param int $Key  ID del dispositivo
	 * @return array|null
	 */
	function DispositivoObtenerPorId($Key) {
		global $Connection;

		$Query = sprintf(
			'SELECT 
				d.id,
				d.Nombre AS name,
				d.IP AS ip,
				d.MAC AS mac,
				e.Nombre AS status_raw,
				td.Nombre AS tipo_raw,
				u.Area AS location,
				d.Dispositivo AS parentId
			FROM Dispositivo d
			LEFT JOIN TipoDispositivo td ON d.TipoDispositivo = td.id
			LEFT JOIN Ubicacion u        ON d.Ubicacion      = u.id
			LEFT JOIN Estatus e          ON d.Estatus        = e.id
			WHERE d.id = %s
			LIMIT 1',
			GetSQLValueString($Key, 'int')
		);

		$Result = $Connection->query($Query);
		if ($Result && $Result->num_rows > 0) {
			return DispositivoFormatearSagaGraph($Result->fetch_assoc());
		}
		return NULL;
	}

	/**
	 * DispositivoObtenerHijos
	 * Obtiene los dispositivos hijos de un dispositivo padre.
	 *
	 * @param int $Key  ID del dispositivo padre
	 * @return array
	 */
	function DispositivoObtenerHijos($Key) {
		global $Connection;

		$Query = sprintf(
			'SELECT 
				d.id,
				d.Nombre AS name,
				d.IP AS ip,
				d.MAC AS mac,
				e.Nombre AS status_raw,
				td.Nombre AS tipo_raw,
				u.Area AS location,
				d.Dispositivo AS parentId
			FROM Dispositivo d
			LEFT JOIN TipoDispositivo td ON d.TipoDispositivo = td.id
			LEFT JOIN Ubicacion u        ON d.Ubicacion      = u.id
			LEFT JOIN Estatus e          ON d.Estatus        = e.id
			WHERE d.Dispositivo = %s
			ORDER BY d.id ASC',
			GetSQLValueString($Key, 'int')
		);

		$Result = $Connection->query($Query);
		$Devices = array();

		if ($Result) {
			while ($Row = $Result->fetch_assoc()) {
				$Devices[] = DispositivoFormatearSagaGraph($Row);
			}
		}

		return $Devices;
	}
?>