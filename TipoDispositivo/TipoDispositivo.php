<?php
	function TipoDispositivoCrear($FormData) {
		global $Connection;

		$InsertQuery =  sprintf('INSERT INTO TipoDispositivo
									( `id`, `Nombre`, `Icono` )
								 VALUES
								    (  %s,  %s,  %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Icono'], 'int')
						);

		if($InsertResult = $Connection -> query($InsertQuery)) {
			$idRecordTipoDispositivo    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordTipoDispositivo;
		} else {
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function TipoDispositivoEliminar($Key) {
		global $Connection;

		$ConsultaElimina =  sprintf('DELETE FROM TipoDispositivo WHERE `id` = %s;',
								GetSQLValueString($Key, 'tinyint unsigned')
							);

		if($ResultadoElimina = $Connection -> query($ConsultaElimina)) {
			$Data['Status'] = 'OK';
		} else {
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function TipoDispositivoLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'TipoDispositivo',
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
					'ColumName' => '(SELECT Nombre FROM CelaIcono WHERE CelaIcono.id = TipoDispositivo.Icono)',
					'Alias'     => 'Icono',
					'Extra'     => '',
					'Render'    => 'Icon'
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

	function TipoDispositivoActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE TipoDispositivo
								SET `Nombre` = %s, `Icono` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Icono'], 'int'),
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

	function TipoDispositivoQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM TipoDispositivo  ORDER BY `Nombre` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Nombre` FROM TipoDispositivo  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>