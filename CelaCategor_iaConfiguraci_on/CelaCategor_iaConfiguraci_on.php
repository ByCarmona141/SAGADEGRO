<?php
	function CelaCategor_iaConfiguraci_onCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
										CelaCategor_iaConfiguraci_on
										( `id`, `NombreCategor_ia`)
									 VALUES ( %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['NombreCategor_ia'], 'varchar')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaCategor_iaConfiguraci_on    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaCategor_iaConfiguraci_on;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaCategor_iaConfiguraci_onEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaCategor_iaConfiguraci_on WHERE `id` = %s',
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

	function CelaCategor_iaConfiguraci_onLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaCategor_iaConfiguraci_on',
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
					'ColumName' => 'NombreCategor_ia',
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

	function CelaCategor_iaConfiguraci_onActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaCategor_iaConfiguraci_on
								 SET `NombreCategor_ia` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['NombreCategor_ia'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaCategor_iaConfiguraci_onQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `NombreCategor_ia`, `NombreCategor_ia` FROM CelaCategor_iaConfiguraci_on  ORDER BY `NombreCategor_ia` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `NombreCategor_ia` FROM CelaCategor_iaConfiguraci_on  ORDER BY `NombreCategor_ia` ASC');
		}

		return $Query;
	}
?>