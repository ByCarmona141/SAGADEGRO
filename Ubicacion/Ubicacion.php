<?php
	function UbicacionCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Ubicacion
									( `id`, `Area`, `Piso` )
								 VALUES
								    (  %s,  %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Area'], 'varchar'),
							GetSQLValueString($FormData['Piso'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordUbicacion    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordUbicacion;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function UbicacionEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM Ubicacion WHERE `id` = %s;',
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

	function UbicacionLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'Ubicacion',
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
					'ColumName' => 'Area',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Piso',
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

	function UbicacionActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Ubicacion
								SET `Area` = %s, `Piso` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Area'], 'varchar'),
							GetSQLValueString($FormData['Piso'], 'tinyint'),
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

	function UbicacionQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Area`, `Area` FROM Ubicacion  ORDER BY `Area` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Area` FROM Ubicacion  ORDER BY `Area` ASC');
		}

		return $Query;
	}
?>