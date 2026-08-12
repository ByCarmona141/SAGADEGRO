<?php
	function CelaRolCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaRol
									(`id`, `Nombre`, `Siglas`, `Descripci_on`, `Status`, `Grupo`, `Tema`)
								 VALUES
									(%s, %s, %s, %s, %s, %s, %s);',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Siglas'], 'varchar') ,
							GetSQLValueString($FormData['Descripci_on'], 'varchar') ,
							GetSQLValueString($FormData['Status'], 'int'),
							GetSQLValueString($FormData['Grupo'], 'int'),
							GetSQLValueString($FormData['Tema'], 'int')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaRol    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaRol;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaRolEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('UPDATE CelaRol
									 SET `Status` = %s
									 WHERE `id` = %s;',
								GetSQLValueString(2, 'int'), GetSQLValueString($Key, 'int'));
		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		
		return $Data;
	}

	function CelaRolLeer($Params){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaRol',
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
					'ColumName' => 'Siglas',
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
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Privilege',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 0,
					'ColumName' => 'Status',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 0,
					'ColumName' => '2',
					'Alias'     => 'Origen',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => ' Grupo IN ( ' . $Params['Group'] . ' ) ',
			'Group'         => '',
			'Order'         => ' Nombre ASC ',
			'RenderRow'     => 'Status',
			'Privileges'    => $Privileges,
			'Debug'         => 1
		);
		
		return $ServerQuery;
	}

	function CelaRolActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaRol
								 SET `Nombre` = %s, `Siglas` = %s, `Descripci_on` = %s, `Status` = %s, `Grupo` = %s, `Tema` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Siglas'], 'varchar'),
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Status'], 'int'),
							GetSQLValueString($FormData['Grupo'], 'int'),
							GetSQLValueString($FormData['Tema'], 'int'), GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaRolObtenGrupos($CurrentGroup, $Order = 'desc'){
		global $Connection;

		if($Order == 'desc'){
			$Group = array();
			/*Se Buscan todos los grupos superiores al actual*/
			while(true){
				$GroupData =    GetValue(
					sprintf('SELECT `id`, `Grupo` FROM CelaRol WHERE `id` = %s;',
						GetSQLValueString($CurrentGroup, 'int')
					)
				);

				$CurrentGroup   = $GroupData['Grupo'];
				$Group[]        = $GroupData['Grupo'];

				if($GroupData['Grupo'] == $GroupData['id'])
					break;
			}
		}elseif($Order == 'asc'){
			$Group = $CurrentGroup . ', ';
			$GroupQuery =   sprintf('SELECT `id`, `Grupo` FROM CelaRol WHERE `Grupo` = %s AND `id` != %s;',
								GetSQLValueString($CurrentGroup, 'int'),
								GetSQLValueString($CurrentGroup, 'int')
							);

			$GroupResult = $Connection -> query($GroupQuery);
			while($GroupRecord = $GroupResult -> fetch_assoc()){
				$Group .= CelaRolObtenGrupos($GroupRecord['id'], 'asc') . ', ';
			}

			$Group = substr_replace($Group, '', -2);
		}

		return $Group;
	}

	function CelaRolComboQuery($InText = false, $Group = false, $NotMe = ''){
		if($InText === true){
			if($Group === false){
				$Query =    sprintf('SELECT `Nombre`, CONCAT(`Nombre`, %s, `Siglas`, %s) FROM CelaRol ORDER BY `Nombre` ASC;',
								GetSQLValueString(' (', 'varchar'),
								GetSQLValueString(')', 'varchar')
							);
			}else{
				$Query =    sprintf('SELECT `Nombre`, CONCAT(`Nombre`, %s, `Siglas`, %s) FROM CelaRol WHERE `Grupo` IN ( %s ) ORDER BY `Nombre` ASC;',
								GetSQLValueString(' (', 'varchar'),
								GetSQLValueString(')', 'varchar'),
								$Group
							);
			}
		}elseif($InText === false){
			if($Group === false){
				$Query =    sprintf('SELECT `id`, CONCAT(`Nombre`, %s, `Siglas`, %s) FROM CelaRol ORDER BY `Nombre` ASC;',
								GetSQLValueString(' (', 'varchar'),
								GetSQLValueString(')', 'varchar')
							);
			}else{
				$Query =    sprintf('SELECT `id`, CONCAT(`Nombre`, %s, `Siglas`, %s) FROM CelaRol WHERE `Grupo` IN ( %s ) ORDER BY `Nombre` ASC;',
								GetSQLValueString(' (', 'varchar'),
								GetSQLValueString(')', 'varchar'),
								$Group
							);
			}
		}elseif($InText == 'NotMe'){
			$Query =    sprintf('SELECT `id`, CONCAT(`Nombre`, %s, `Siglas`, %s) FROM CelaRol WHERE `Grupo` IN ( %s ) AND `id` NOT IN (%s) ORDER BY `Nombre` ASC;',
				GetSQLValueString(' (', 'varchar'),
				GetSQLValueString(')', 'varchar'),
				$Group,
				$NotMe
			);
		}

		return $Query;
	}

	/*Deprecate*/
	/*
	function CelaRolGrupoCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaRolGrupo
									( id, RolDeAcceso, RolAccedido)
								 VALUES ( %s, %s, %s );',
			GetSQLValueString(NULL, 'int'),
			GetSQLValueString($FormData['RolDeAcceso'], 'varchar') ,
			GetSQLValueString($FormData['RolAccedido'], 'varchar')
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

	function CelaRolGrupoEliminar($Config){
		global $Connection;
		$DeleteQuery =  sprintf('DELETE FROM CelaRolGrupo
								 WHERE RolDeAcceso = %s;',
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
?>