<?php
	function RackCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Rack
									( `id`, `Nombre` )
								 VALUES
								    (  %s,  %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Nombre'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordRack    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordRack;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function RackEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM Rack WHERE `id` = %s;',
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

	function RackLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'Rack',
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

	function RackActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Rack
								SET `Nombre` = %s
								WHERE `id` = %s;',
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

	function RackQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM Rack  ORDER BY `Nombre` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Nombre` FROM Rack  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>