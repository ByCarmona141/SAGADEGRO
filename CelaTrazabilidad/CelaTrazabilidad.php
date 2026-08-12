<?php
	function CelaTrazabilidadCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
										CelaTrazabilidad
										( `id`, `Componente`, `Fecha`, `Fase`, `Programador`)
									 VALUES ( %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Componente'], 'int'),
							GetSQLValueString($FormData['Fecha'], 'datetime'),
							GetSQLValueString($FormData['Fase'], 'int'),
							GetSQLValueString($FormData['Programador'], 'int')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaTrazabilidad    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaTrazabilidad;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaTrazabilidadEliminar($Key){
		global $Connection;

		if(is_array($Key)){
			extract($Key, EXTR_OVERWRITE);
		}

		$ConsultaElimina =  sprintf('DELETE FROM CelaTrazabilidad WHERE `id` = %s;',
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

	function CelaTrazabilidadLeer($Params = null){
		global $Privileges;
		$Condition = '';

		if(isset($Params['Component']) && $Params['Component'] != ''){
			$Condition .=   sprintf(' Componente = %s AND ',
								GetSQLValueString($Params['Component'], 'int')
							);
		}

		$Condition .= ' 1=1 ';

		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaTrazabilidad',
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
					'ColumName' => 'Fecha',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'FechaHora'
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => '(select Nombre from CelaFase where CelaFase.id = CelaTrazabilidad.Fase)',
					'Alias'     => 'Fase',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => '(select NombreCompleto from CelaUsuario where CelaUsuario.id = CelaTrazabilidad.Programador)',
					'Alias'     => 'Programador',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => $Condition,
			'Group'         => '',
			'Order'         => ' Fecha DESC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 1
		);

		return $ServerQuery;
	}

	function CelaTrazabilidadActualizar($Key, $FormData = null){
		global $Connection;

		if(is_array($Key)){
			extract($Key, EXTR_OVERWRITE);
		}

		$UpdateQuery =  sprintf('UPDATE CelaTrazabilidad
								 SET `Fecha` = %s, `Fase` = %s, `Programador` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Fecha'], 'datetime'),
							GetSQLValueString($FormData['Fase'], 'int'),
							GetSQLValueString($FormData['Programador'], 'int'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}
?>