<?php
	function EstatusCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Estatus
									( `id`, `Nombre` )
								 VALUES
								    (  %s,  %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Nombre'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordEstatus    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordEstatus;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function EstatusEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM Estatus WHERE `id` = %s;',
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

	function EstatusLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'Estatus',
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

	function EstatusActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Estatus
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

	function EstatusQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM Estatus  ORDER BY `Nombre` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Nombre` FROM Estatus  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>