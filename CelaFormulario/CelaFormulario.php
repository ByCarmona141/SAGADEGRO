<?php
	function CelaFormularioCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									 CelaFormulario
									 ( `id`, `Nombre`, `Descripci_on`, `Ruta`)
								 VALUES
								 	 ( %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Ruta'], 'varchar')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaFormulario    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaFormulario;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaFormularioEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaFormulario WHERE `id` = %s;',
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

	function CelaFormularioLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaFormulario',
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
					'ColumName' => 'Ruta',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'LinkMenu'
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

	function CelaFormularioActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaFormulario
								 SET `Nombre` = %s, `Descripci_on` = %s, `Ruta` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Ruta'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaFormularioQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaFormulario  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaFormulario  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>