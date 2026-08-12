<?php
	function CelaIconoCrear($FormData) {
		global $Connection;

		$InsertQuery =  sprintf('INSERT INTO
									CelaIcono ( `id`, `Nombre`, `Codigo`)
								 VALUES ( %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Codigo'], 'varchar')
		);

		if($InsertResult = $Connection -> query($InsertQuery)) {
			$idRecordCelaIcono    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaIcono;
		} else {
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaIconoEliminar($Key) {
		global $Connection;

		$ConsultaElimina =  sprintf('DELETE FROM CelaIcono WHERE `id` = %s;',
			GetSQLValueString($Key, 'int')
		);

		if($ResultadoElimina = $Connection -> query($ConsultaElimina)) {
			$Data['Status'] = 'OK';
		} else {
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaIconoLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'CelaIcono',
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
					'ColumName' => 'id',
					'Alias'     => '',
					'Extra'     => '',
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
					'ColumName' => 'Nombre',
					'Alias'     => '',
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

	function CelaIconoActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaIcono
								 SET `Nombre` = %s, `Codigo` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Codigo'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)) {
			$Data['Status'] = 'OK';
		} else {
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaIconoQueryCombo($InText = false) {
		if($InText == 'Icon') {
			$Query =    sprintf('SELECT CONCAT(`id`, %s, `Nombre` ) as id, `Nombre` FROM CelaIcono ORDER BY `Nombre` ASC',
				GetSQLValueString('" data-icon="', 'varchar')
			);
		} else if($InText === true) {
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaIcono  ORDER BY `Nombre` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaIcono  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>