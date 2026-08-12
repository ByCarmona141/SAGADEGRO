<?php
	function TipoAccesoCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO TipoAcceso
									( `id`, `Nombre` )
								 VALUES
								    (  %s,  %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Nombre'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordTipoAcceso    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordTipoAcceso;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function TipoAccesoEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM TipoAcceso WHERE `id` = %s;',
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

	function TipoAccesoLeer() {
		global $Privileges;

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'TipoAcceso',
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

	function TipoAccesoActualizar($Key, $FormData) {
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE TipoAcceso
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

	function TipoAccesoQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM TipoAcceso  ORDER BY `Nombre` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Nombre` FROM TipoAcceso  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>