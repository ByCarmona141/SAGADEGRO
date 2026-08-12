<?php
	function CelaOrigenCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaOrigen ( `id`, `Nombre`, `Tabla`)
								 VALUES ( %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Tabla'], 'varchar')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaOrigen    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaOrigen;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaOrigenEliminar($Key){
		global $Connection;
		$DeleteQuery =  sprintf('DELETE FROM CelaOrigen WHERE `id` = %s;',
			GetSQLValueString($Key, 'int')
		);
		if($ResultadoElimina = $Connection -> query($DeleteQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaOrigenLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaOrigen',
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
					'ColumName' => 'Tabla',
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

	function CelaOrigenActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaOrigen
								 SET `Nombre` = %s, `Tabla` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Tabla'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaOrigenQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaOrigen  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaOrigen  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>