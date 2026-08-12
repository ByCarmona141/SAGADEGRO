<?php
	function CelaPrivilegioCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
										CelaPrivilegio
										( `id`, `Nombre`, `Descripci_on`, `Acci_on`)
									 VALUES ( %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Acci_on'], 'varchar')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaPrivilegio    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaPrivilegio;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaPrivilegioEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaPrivilegio WHERE `id` = %;',
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

	function CelaPrivilegioLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaPrivilegio',
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
					'ColumName' => 'Descripci_on',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Acci_on',
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

	function CelaPrivilegioActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaPrivilegio
								 SET `Nombre` = %s, `Descripci_on` = %s, `Acci_on` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Acci_on'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaPrivilegioQueryCombo($InText = false, $Access = false, $Origin = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre`, `Descripci_on` FROM CelaPrivilegio  ORDER BY `Nombre` ASC');
		}elseif($InText == 'InPrivilege'){
			$Query =    sprintf('SELECT `id`, `Nombre`, `Descripci_on`
								 FROM CelaPrivilegio
							     WHERE id IN	(select Tupla
							     				 from CelaPrivilegios
						                         where
						                         	`TuplaAcceso` = %s AND
					                             	`Origen` = %s
							     				)
							     ORDER BY `Nombre` ASC;',
							GetSQLValueString(($Access === false ? 1:$Access), 'int'),
							GetSQLValueString(($Origin === false ? 1:$Origin), 'int')
						);
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre`, `Descripci_on` FROM CelaPrivilegio  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>