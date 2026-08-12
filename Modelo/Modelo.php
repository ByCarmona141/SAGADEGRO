<?php
	function ModeloCrear($FormData) {
		global $Connection;

		$InsertQuery =  sprintf('INSERT INTO Modelo
									( `id`, `Marca`, `Nombre` )
								 VALUES
								    (  %s,  %s,  %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Marca'], 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)) {
			$idRecordModelo    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordModelo;
		} else {
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function ModeloEliminar($Key) {
		global $Connection;

		$ConsultaElimina =  sprintf('DELETE FROM Modelo WHERE `id` = %s;',
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

	function ModeloLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'Modelo',
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
					'ColumName' => '(SELECT Nombre FROM Marca WHERE Marca.id = Modelo.Marca)',
					'Alias'     => 'Marca',
					'Extra'     => '',
					'Render'    => ''
				],
                [
                    'Type'      => 2,
                    'ColumName' => 'Nombre',
                    'Alias'     => '',
                    'Extra'     => '',
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

	function ModeloActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Modelo
								SET `Marca` = %s, `Nombre` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Marca'], 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar'),
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

	function ModeloQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM Modelo  ORDER BY `Nombre` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Nombre` FROM Modelo  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>