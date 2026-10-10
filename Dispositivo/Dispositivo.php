<?php
// Archivo: Dispositivo/Dispositivo.php
// Descripción: Archivo que contiene las funciones para la gestión de dispositivos
// Autor: M. Carlos Alvarez Carmona
// Fecha: 2026-10-10
// Versión: 1.0.0

// Función para crear un dispositivo
	function DispositivoCrear($FormData){
	global $Connection;
	$InsertQuery =  sprintf('INSERT INTO Dispositivo
				( `id`, `Nombre`, `MAC`, `Modelo`, `Ubicacion`, `Rack`, `TipoDispositivo`, `IP`, `Serial`, `Estatus` )
				 VALUES
				    (  %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s,  %s );',
			GetSQLValueString(NULL, 'int'),
			GetSQLValueString($FormData['Nombre'], 'varchar'),
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

		/*Se guardan los dispositivos padre (relacion muchos a muchos)*/
		DispositivoPadreGuardar($idRecordDispositivo, isset($FormData['Dispositivo']) ? $FormData['Dispositivo'] : array());

		$Data['Status']           = 'OK';
		$Data['idRecord']         = $idRecordDispositivo;
	}else{
		$Data['Status']         = 'ERROR';
		$Data['Error']          = $Connection -> error;
	}

	return $Data;
	}

	function DispositivoPadreGuardar($DispositivoId, $Padres){
	global $Connection;

	/*Se eliminan las relaciones de padres anteriores del dispositivo*/
	$DeleteQuery = sprintf('DELETE FROM DispositivoPadre WHERE `Dispositivo` = %s;',
			GetSQLValueString($DispositivoId, 'int')
		);
	$Connection -> query($DeleteQuery);

	if(!is_array($Padres)){
		return;
	}

	/*Se insertan las nuevas relaciones con los dispositivos padre*/
	foreach($Padres as $PadreId){
		$PadreId = (int) $PadreId;

		/*No se permiten valores vacios ni ser padre de si mismo*/
		if($PadreId <= 0 || $PadreId === (int) $DispositivoId){
			continue;
		}

		$InsertQuery = sprintf('INSERT IGNORE INTO DispositivoPadre ( `Dispositivo`, `Padre` ) VALUES ( %s, %s );',
				GetSQLValueString($DispositivoId, 'int'),
				GetSQLValueString($PadreId, 'int')
			);
		$Connection -> query($InsertQuery);
	}
	}

	function DispositivoObtenerPadres($Key) {
	global $Connection;

	$Padres = array();
	$Query = sprintf('SELECT `Padre` FROM DispositivoPadre WHERE `Dispositivo` = %s ORDER BY `Padre` ASC;',
			GetSQLValueString($Key, 'int')
		);

	if($Result = $Connection -> query($Query)){
		while($Row = $Result -> fetch_assoc()){
			$Padres[] = (int) $Row['Padre'];
		}
	}

	return $Padres;
	}

	function DispositivoEliminar($Key){
	global $Connection;

	/*Se eliminan las relaciones de jerarquia donde participa el dispositivo*/
	$Connection -> query(sprintf('DELETE FROM DispositivoPadre WHERE `Dispositivo` = %s OR `Padre` = %s;',
			GetSQLValueString($Key, 'int'),
			GetSQLValueString($Key, 'int')
		));

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

// Función para crear un dispositivo

	function DispositivoLeer() {
		global $Privileges;

		$Table =  sprintf('(SELECT d.id, d.Nombre, d.MAC, m.Nombre AS Modelo, ma.Nombre AS Marca, u.Area AS Ubicacion, u.Piso, r.Nombre AS Rack, td.Nombre AS TipoDispositivo, d.IP, d.Serial, e.Nombre AS Estatus, padres.DispositivosPadre FROM Dispositivo d LEFT JOIN Modelo m ON d.Modelo = m.id LEFT JOIN Marca ma ON m.Marca = ma.id LEFT JOIN Ubicacion u ON d.Ubicacion = u.id LEFT JOIN Rack r ON d.Rack = r.id LEFT JOIN TipoDispositivo td ON d.TipoDispositivo = td.id LEFT JOIN CelaIcono ci ON td.Icono = ci.id LEFT JOIN Estatus e ON d.Estatus = e.id LEFT JOIN (SELECT dp.Dispositivo, GROUP_CONCAT(p.Nombre ORDER BY p.Nombre SEPARATOR ", ") AS DispositivosPadre FROM DispositivoPadre dp INNER JOIN Dispositivo p ON p.id = dp.Padre GROUP BY dp.Dispositivo) padres ON padres.Dispositivo = d.id)');

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
                    'ColumName' => 'DispositivosPadre',
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
				SET `Nombre` = %s, `MAC` = %s, `Modelo` = %s, `Ubicacion` = %s, `Rack` = %s, `TipoDispositivo` = %s, `IP` = %s, `Serial` = %s, `Estatus` = %s
				WHERE `id` = %s;',
			GetSQLValueString($FormData['Nombre'], 'varchar'),
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
		/*Se guardan los dispositivos padre (relacion muchos a muchos)*/
		DispositivoPadreGuardar($Key, isset($FormData['Dispositivo']) ? $FormData['Dispositivo'] : array());

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
				(SELECT GROUP_CONCAT(dp.Padre ORDER BY dp.Padre SEPARATOR ",") FROM DispositivoPadre dp WHERE dp.Dispositivo = d.id) AS parents_raw
			FROM Dispositivo d
			LEFT JOIN TipoDispositivo td ON d.TipoDispositivo = td.id
			LEFT JOIN Ubicacion u        ON d.Ubicacion      = u.id
			LEFT JOIN Estatus e          ON d.Estatus        = e.id
			ORDER BY d.id ASC';

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

		$parents = array();
		if(!empty($Row['parents_raw'])){
			foreach(explode(',', $Row['parents_raw']) as $ParentId){
				$parents[] = (int) $ParentId;
			}
		}

		return array(
			'id'       => (int) $Row['id'],
			'parents'  => $parents,
			'name'     => $Row['name'],
			'type'     => $tipoSaga,
			'status'   => $estado,
			'ip'       => !empty($Row['ip'])     ? $Row['ip']     : 'N/A',
			'mac'      => !empty($Row['mac'])    ? $Row['mac']    : 'N/A',
			'location' => !empty($Row['location'])? $Row['location']: 'Sin ubicación',
			'parentId' => count($parents) ? $parents[0] : NULL, /*Compatibilidad: primer padre*/
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
				(SELECT GROUP_CONCAT(dp.Padre ORDER BY dp.Padre SEPARATOR ",") FROM DispositivoPadre dp WHERE dp.Dispositivo = d.id) AS parents_raw
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
				(SELECT GROUP_CONCAT(dp.Padre ORDER BY dp.Padre SEPARATOR ",") FROM DispositivoPadre dp WHERE dp.Dispositivo = d.id) AS parents_raw
			FROM Dispositivo d
			LEFT JOIN TipoDispositivo td ON d.TipoDispositivo = td.id
			LEFT JOIN Ubicacion u        ON d.Ubicacion      = u.id
			LEFT JOIN Estatus e          ON d.Estatus        = e.id
			WHERE EXISTS (SELECT 1 FROM DispositivoPadre dp WHERE dp.Padre = %s AND dp.Dispositivo = d.id)
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