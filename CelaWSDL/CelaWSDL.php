<?php
	function CelaWSDLCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaWSDL
									( `id`, `Nombre`, `URL`, `Usuario`, `Contrase_na`, `Tipo`)
								 VALUES ( %s, %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['URL'], 'varchar') ,
							GetSQLValueString($FormData['Usuario'], 'varchar') ,
							GetSQLValueString($FormData['Contrase_na'], 'varchar') ,
							GetSQLValueString($FormData['Tipo'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaWSDL    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaWSDL;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaWSDLEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaWSDL WHERE `id` = %s;',
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

	function CelaWSDLLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaWSDL',
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
					'ColumName' => 'URL',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'Usuario',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Contrase_na',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'Tipo',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => '',
			'Group'         => '',
			'Order'         => ' Nombre ASC',
			'RenderRow'     => '',	
			'Privileges'    => $Privileges,
			'Debug'         => 0
		);
		
		return $ServerQuery;
	}

	function CelaWSDLActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery = sprintf('UPDATE CelaWSDL
								SET `Nombre` = %s, `URL` = %s, `Usuario` = %s, `Contrase_na` = %s, `Tipo` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['URL'], 'varchar'),
							GetSQLValueString($FormData['Usuario'], 'varchar'),
							GetSQLValueString($FormData['Contrase_na'], 'varchar'),
							GetSQLValueString($FormData['Tipo'], 'varchar'), GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaWSDLQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaWSDL  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaWSDL  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>