<?php
	function CelaFaseCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaFase ( `id`, `Nombre`)
								 VALUES ( %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaFase    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaFase;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaFaseEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaFase WHERE `id` = %s;',
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

	function CelaFaseLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaFase',
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

	function CelaFaseActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaFase
								 SET `Nombre` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaFaseQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaFase  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaFase  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>