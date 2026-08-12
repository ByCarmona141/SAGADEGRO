<?php
	function CelaProcesoCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									 CelaProceso
									 ( `id`, `Nombre`, `Script`, `Parametros`, `Recurrente`, `Periodo`, `Periodicidad`, `FechaDeInicio`, `FechaDeTermino`)
								 VALUES
								 	 ( %s, %s, %s, %s, %s, %s, %s , %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Script'], 'varchar'),
							GetSQLValueString($FormData['Parametros'], 'varchar'),
							GetSQLValueString($FormData['Recurrente'], 'int'),
							GetSQLValueString($FormData['Periodo'], 'int'),
							GetSQLValueString($FormData['Periodicidad'], 'varchar'),
							GetSQLValueString($FormData['FechaDeInicio'], 'datetime'),
							GetSQLValueString($FormData['FechaDeTermino'], 'datetime')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaProceso    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaProceso;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaProcesoEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaProceso WHERE `id` = %s;',
			GetSQLValueString($Key, 'int')
		);
		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaProcesoLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaProceso',
				'Alias' => ''
			),
			'Index'     => array(
				'IndexName' => 'id',
				'Alias' => ''
			),
			'Columns'   =>  array(
				0 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Actions',
					'Render'    => ''
				),
				1 => array(
					'Type'      => 2,
					'ColumName' => 'Nombre',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'Script',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'Parametros',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Recurrente',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'Boolean'
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'concat(\'+\', Periodo, \' \', Periodicidad )',
					'Alias'     => 'Periodo',
					'Extra'     => '',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 2,
					'ColumName' => 'FechaDeInicio',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'FechaHora'
				),
				7 => array(
					'Type'      => 2,
					'ColumName' => 'FechaDeTermino',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'FechaHora'
				)
			),
			'Condition'     => ' Original = 1',
			'Group'         => '',
			'Order'         => ' id ASC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 0
		);

		return $ServerQuery;
	}
	
	function CelaProcesoBackend(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaProceso',
				'Alias' => ''
			),
			'Index'     => array(
				'IndexName' => 'id',
				'Alias' => ''
			),
			'Columns'   =>  array(
				0 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'ActionsProcess',
					'Render'    => ''
				),
				1 => array(
					'Type'      => 2,
					'ColumName' => 'pid',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'Status',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'Script',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Parametros',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'Resultado',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 2,
					'ColumName' => 'FechaDeInicio',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'FechaHora'
				)
			),
			'Condition'     => '',
			'Group'         => '',
			'Order'         => ' id ASC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 0
		);
		
		return $ServerQuery;
	}

	function CelaProcesoActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaProceso
								 SET `Nombre` = %s, `Script` = %s, `Parametros` = %s, `Recurrente` = %s, `Periodo` = %s, `Periodicidad` = %s, `FechaDeInicio` = %s, `FechaDeTermino` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Script'], 'varchar'),
							GetSQLValueString($FormData['Parametros'], 'varchar'),
							GetSQLValueString($FormData['Recurrente'], 'int'),
							GetSQLValueString($FormData['Periodo'], 'int'),
							GetSQLValueString($FormData['Periodicidad'], 'varchar'),
							GetSQLValueString($FormData['FechaDeInicio'], 'datetime'),
							GetSQLValueString($FormData['FechaDeTermino'], 'datetime'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaProcesoQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaProceso  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaProceso  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>
