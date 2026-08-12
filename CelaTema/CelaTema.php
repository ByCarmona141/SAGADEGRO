<?php
	function CelaTemaCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO CelaTema
									 ( id, Nombre, Ruta, Imagen)
								 VALUES
								     ( %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Ruta'], 'varchar') ,
							GetSQLValueString($FormData['Imagen'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaTema    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaTema;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaTemaEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaTema WHERE `id` = %s;',
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

	function CelaTemaLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaTema',
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
					'ColumName' => 'id',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'Nombre',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'Ruta',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Imagen',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
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

	function CelaTemaActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery = sprintf('UPDATE CelaTema
								SET `Nombre` = %s, `Ruta` = %s, `Imagen` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Ruta'], 'varchar'),
							GetSQLValueString($FormData['Imagen'], 'varchar'), GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaTemaQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaTema  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaTema  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>