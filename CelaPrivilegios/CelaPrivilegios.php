<?php
	function CelaPrivilegiosCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									CelaPrivilegios ( `id`, `Privilegio`, `Origen`, `Tupla`, `TuplaAcceso` )
								 VALUES ( %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Privilegio'], 'int'),
							GetSQLValueString($FormData['Origen'], 'int'),
							GetSQLValueString($FormData['Tupla'], 'int'),
							GetSQLValueString($FormData['TuplaAcceso'], 'int')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaPrivilegios    = $Connection -> insert_id;
			$Data['Status']             = 'OK';
			$Data['idRecord']           = $idRecordCelaPrivilegios;
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaPrivilegiosEliminar($Key, $Origin = false, $Access = false, $OnlyAccess = false){
		global $Connection;
		if($Origin === false){
			$ConsultaElimina =  sprintf('DELETE FROM CelaPrivilegios WHERE `id` = %s;',
									GetSQLValueString($Key, 'int')
								);
		}else{
			if($Access === false){
				$ConsultaElimina =  sprintf('DELETE FROM CelaPrivilegios WHERE `Tupla` = %s AND `Origen` = %s;',
										GetSQLValueString($Key, 'int'),
										GetSQLValueString($Origin, 'int')
									);
			}else{
				$ConsultaElimina =  sprintf('DELETE FROM CelaPrivilegios WHERE `TuplaAcceso` = %s AND `Origen` = %s;',
										GetSQLValueString($Access, 'int'),
										GetSQLValueString($Origin, 'int')
									);
			}
		}

		if($OnlyAccess !== false){
			/*Se elimina solo los privilegios en la lista*/
			$ConsultaElimina =  sprintf('DELETE FROM CelaPrivilegios WHERE `TuplaAcceso` IN (%s) AND `Origen` = %s AND `Tupla` = %s;',
				$OnlyAccess,
				GetSQLValueString($Origin, 'int'),
				GetSQLValueString($Key, 'int')
			);
		}

		//print $ConsultaElimina;

		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaPrivilegiosLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaPrivilegios',
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
					'ColumName' => '(select Nombre from CelaPrivilegio where CelaPrivilegio.id = CelaPrivilegios.Privilegio)',
					'Alias'     => 'Privilegio',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => '(select Nombre from CelaOrigen where CelaOrigen.id = CelaPrivilegios.Origen)',
					'Alias'     => 'Origen',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Tupla',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'TuplaAcceso',
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

	function CelaPrivilegiosActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaPrivilegios
								 SET `Privilegio` = %s, `Origen` = %s, `Tupla` = %s, `TuplaAcceso` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Privilegio'], 'varchar'),
							GetSQLValueString($FormData['Origen'], 'varchar'),
							GetSQLValueString($FormData['Tupla'], 'varchar'),
							GetSQLValueString($FormData['TuplaAcceso'], 'varchar'), GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaPrivilegiosGetDataTable($Sorting){
		return  array(
					'sDom' => '<"row"<"col-md-12"r>><"row" <"col-md-12 overflow table-responsive" t>><"row" <"col-md-12"<"col-md-6 text-left"i><"col-md-6 text-right"p>>>',
					'sPaginationType'   => 'bootstrap',
					'iDisplayLength'    => '-1',
					'oLanguage'         => array(
						'sZeroRecords'      => 'No se encontrarón registros',
						'sInfo'             => 'Mostrando _START_ a _END_ de _TOTAL_ registros',
						'sInfoEmpty'        => 'Mostrando 0 a 0 de 0 registros',
						'sInfoFiltered'     => '(filtrado de _MAX_ registros totales)',
						'sEmptyTable'       => 'No se encontrarón registros',
						'sLoadingRecords'   => 'Cargando...',
						'sProcessing'       => '<strong>Procesando . . .</strong>',
						'sSearch'           => 'Buscar:&nbsp;',
						'oPaginate'         => array(
							'sFirst'            => '1&ordf; Pag.',
							'sLast'             => 'Ultima.',
							'sNext'             => 'Sig. &raquo;',
							'sPrevious'         => '&laquo; Ant.'
						)
					),
					'sScrollY'          => '480px',
					'sScrollX'          => '640px',
					'bScrollCollapse'   => true,
					'bPaginate'         => false,
					'aoColumns'         => $Sorting,
					'order'             => array()
				);
		/*
		 * ,
					'fixedColumns'      => array(
						'leftColumns'       => 4
					)*/
	}
	function CelaPrivilegiosClonePrivileges($Access, $NewAccess, $Origin){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
								 	CelaPrivilegios ( `id`, `Privilegio`, `Origen`, `Tupla`, `TuplaAcceso` )
								 (SELECT
								 	 NULL, `Privilegio`, `Origen`, `Tupla`, %s
							      FROM
							      	 CelaPrivilegios
							      WHERE
							      	 `TuplaAcceso` = %s AND
							      	 `Origen` IN (%s)
						         );',
							$NewAccess,
							GetSQLValueString($Access, 'int'),
							GetSQLValueString($Origin, 'int')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaPrivilegios    = $Connection -> insert_id;
			$Data['Status']             = 'OK';
			$Data['idRecord']           = $idRecordCelaPrivilegios;
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	/** /
	function CelaPrivilegiosQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaPrivilegios  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaPrivilegios  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
	/* */
?>