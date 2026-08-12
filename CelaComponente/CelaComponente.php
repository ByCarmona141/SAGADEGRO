<?php
	function CelaComponenteCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaComponente ( `id`, `Componente`, `Acci_on`, `FechaSolicitud`, `Descripci_on`, `Solicitante`, `FechaRealizado`, `Reviso`, `Autorizo`, `Conclusi_on`, `TipoDeComponente` )
								 VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Componente'], 'varchar'),
							GetSQLValueString($FormData['Acci_on'], 'varchar'),
							GetSQLValueString($FormData['FechaSolicitud'], 'date'),
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Solicitante'], 'int'),
							GetSQLValueString($FormData['FechaRealizado'], 'date'),
							GetSQLValueString($FormData['Reviso'], 'int'),
							GetSQLValueString($FormData['Autorizo'], 'int'),
							GetSQLValueString($FormData['Conclusi_on'], 'varchar'),
							GetSQLValueString($FormData['TipoDeComponente'], 'int')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaComponente    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaComponente;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaComponenteEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM CelaComponente WHERE `id` = %s;',
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

	function CelaComponenteLeer($Params = null){
		global $Privileges;
		$Condition = '';
		if(isset($Params['Component']) && $Params['Component'] != ''){
			$Condition .=   sprintf(' `Componente` = %s AND ',
								GetSQLValueString($Params['Component'], 'varchar')
							);
		}

		$Condition .= ' 1=1 ';

		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaComponente',
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
					'ColumName' => '(select NombreCompleto from CelaUsuario where CelaUsuario.id = CelaComponente.Solicitante)',
					'Alias'     => 'Solicitante',
					'Extra'     => '',
					'Render'    => ''
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'Descripci_on',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'FechaSolicitud',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'FechaHora'
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => '(select Nombre from CelaTipoComponente where CelaTipoComponente.id = CelaComponente.TipoDeComponente)',
					'Alias'     => 'TipoDeComponente',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Trazabilidad',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Finalizar',
					'Render'    => ''
				),
				7 => array(
					'Type'      => 0,
					'ColumName' => 'FechaRealizado',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => $Condition,
			'Group'         => '',
			'Order'         => ' FechaSolicitud ASC ',
			'RenderRow'     => 'Finaly',
			'Privileges'    => $Privileges,
			'Debug'         => 1
		);

		return $ServerQuery;
	}

	function CelaComponenteActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaComponente
								 SET `FechaSolicitud` = %s, `Descripci_on` = %s, `Solicitante` = %s, `TipoDeComponente` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['FechaSolicitud'], 'datetime'),
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Solicitante'], 'int'),
							GetSQLValueString($FormData['TipoDeComponente'], 'int'), GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaComponenteFinalizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaComponente
								 SET `Conclusi_on` = %s, `Autorizo` = %s, `Reviso` = %s, `FechaRealizado` = %s
								 WHERE `id` = %s;',
			GetSQLValueString($FormData['Conclusi_on'], 'varchar'),
			GetSQLValueString($FormData['Autorizo'], 'int'),
			GetSQLValueString($FormData['Reviso'], 'int'),
			GetSQLValueString($FormData['FechaRealizado'], 'datetime'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaComponenteQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaComponente  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaComponente  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>