<?php
	function CelaConfiguraci_onCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaConfiguraci_on
									( `id`, `Nombre`, `Valor`, `Tipo`, `Categor_ia`, `Referencia`, `Class`, `Code`)
								 VALUES ( %s, %s, %s, %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Valor'], 'varchar') ,
							GetSQLValueString($FormData['Tipo'], 'varchar') ,
							GetSQLValueString($FormData['Categor_ia'], 'int') ,
							GetSQLValueString($FormData['Referencia'], 'varchar') ,
							GetSQLValueString($FormData['Class'], 'varchar') ,
							GetSQLValueString($FormData['Code'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaConfiguraci_on = $Connection -> insert_id;
			$Data['Status']             = 'OK';
			$Data['idRecord']           = $idRecordCelaConfiguraci_on;
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	/*Deprecate*/
	/*function CelaRolConfiguraci_onCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaRolConfiguraci_on
									( id, Configuraci_on, Rol)
								 VALUES ( %s, %s, %s );',
			GetSQLValueString(NULL, 'int'),
			GetSQLValueString($FormData['Configuraci_on'], 'varchar') ,
			GetSQLValueString($FormData['Rol'], 'varchar')
		);

		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaConfiguraci_on = $Connection -> insert_id;
			$Data['Status']             = 'OK';
			$Data['idRecord']           = $idRecordCelaConfiguraci_on;
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaRolConfiguraci_onEliminar($Config){
		global $Connection;
		$DeleteQuery =  sprintf('DELETE FROM CelaRolConfiguraci_on
								 WHERE Configuraci_on = %s;',
							GetSQLValueString($Config, 'int')
						);

		if($DeleteResult = $Connection -> query($DeleteQuery)){
			$Data['Status']             = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}*/

	function CelaConfiguraci_onEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaConfiguraci_on
									 WHERE `id` = %s;',
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

	function CelaConfiguraci_onLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaConfiguraci_on',
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
					'ColumName' => 'Valor',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'Tipo',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Referencia',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'Class',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 2,
					'ColumName' => '(select
										group_concat(
											(select
												Nombre
											from
												CelaRol
											where
												CelaRol.id = CelaPrivilegios.TuplaAcceso
											) separator "; "
										)
									 from
									    CelaPrivilegios
								     where
								        CelaPrivilegios.Tupla = CelaConfiguraci_on.id and
								        CelaPrivilegios.Origen = 5 and
								        CelaPrivilegios.Privilegio = 9
							        group by CelaPrivilegios.Origen)',
					'Alias'     => 'Roles',
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

	function CelaConfiguraci_onActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaConfiguraci_on
								 SET `Nombre` = %s, `Valor` = %s, `Tipo` = %s, `Categor_ia` = %s, `Referencia` = %s, `Class` = %s, `Code` = %s
								 WHERE `id` = %s;',
			GetSQLValueString($FormData['Nombre'], 'varchar') ,
			GetSQLValueString($FormData['Valor'], 'varchar'),
			GetSQLValueString($FormData['Tipo'], 'varchar'),
			GetSQLValueString($FormData['Categor_ia'], 'int'),
			GetSQLValueString($FormData['Referencia'], 'varchar'),
			GetSQLValueString($FormData['Class'], 'varchar'),
			GetSQLValueString($FormData['Code'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaConfiguraci_onActualizaValor($Key, $Value){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaConfiguraci_on
								 SET `Valor` = %s
								 WHERE `id` = %s;',
			GetSQLValueString($Value, 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaConfiguraci_onQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaConfiguraci_on  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaConfiguraci_on  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>