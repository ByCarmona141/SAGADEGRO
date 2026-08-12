<?php
	function CelaMen_uCrear($FormData) {
		global $Connection;

		$InsertQuery =  sprintf('INSERT INTO
								    CelaMen_u
									( `id`, `Nombre`, `Descripci_on`, `Referencia`, `Icono`, `TipoDeElemento`, `Categor_ia`, `Prioridad`, `Orientaci_on`)
								VALUES
								    ( %s, %s, %s, %s, %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Descripci_on'], 'varchar') ,
							GetSQLValueString($FormData['Referencia'], 'varchar') ,
							GetSQLValueString($FormData['Icono'], 'int') ,
							GetSQLValueString($FormData['TipoDeElemento'], 'int') ,
							GetSQLValueString($FormData['Categor_ia'], 'int') ,
							GetSQLValueString($FormData['Prioridad'], 'int') ,
							GetSQLValueString($FormData['Orientaci_on'], 'int')
						);

		if($InsertResult = $Connection -> query($InsertQuery)) {
			$idRecordCelaMen_u  = $Connection -> insert_id;

			if($FormData['Categor_ia'] == '') {
				$UpdateQuery =  sprintf('UPDATE CelaMen_u SET `Categor_ia` = %s WHERE `id` = %s;',
									GetSQLValueString($idRecordCelaMen_u, 'int'),
									GetSQLValueString($idRecordCelaMen_u, 'int')
								);

				if($UpdateResult = $Connection -> query($UpdateQuery)) {
					$Data['Status']     = 'OK';
					$Data['idRecord']   = $idRecordCelaMen_u;
				} else {
					$Data['Status'] = 'ERROR';
					$Data['Error']  = $Connection -> error;
				}
			} else {
				$Data['Status']     = 'OK';
				$Data['idRecord']   = $idRecordCelaMen_u;
			}
		} else {
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaMen_uEliminar($Key) {
		global $Connection;

		$ConsultaElimina =  sprintf('	DELETE FROM CelaMen_u
										WHERE `id` = %s',
								GetSQLValueString($Key, 'int')
							);
		if($ResultadoElimina = $Connection -> query($ConsultaElimina)) {
			$Data['Status'] = 'OK';
		} else {
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaMen_uLeer($Params = '') {
		global $Privileges;

		if($Params['GroupId'] != 1) {
			$Table =    sprintf(    '(SELECT cu.id as id, cu.Nombre as Nombre,  cu.Descripci_on as Descripci_on, cu.Referencia as Referencia, cu.Prioridad as Prioridad, IF(cu.Orientaci_on=1, \'VERTICAL\', \'HORIZONTAL\' ) as Orientaci_on, c.Nombre as Icono, cu1.Nombre as Categor_ia, c1.Nombre as TipoDeElemento
									 FROM CelaMen_u cu
									     INNER JOIN CelaIcono c ON ( cu.Icono = c.id  )
										 INNER JOIN CelaMen_u cu1 ON ( cu.Categor_ia = cu1.id  )
										 INNER JOIN CelaTipoDeElemento c1 ON ( cu.TipoDeElemento = c1.id  )
									 WHERE cu.id IN (SELECT
														c1.Tupla
													 FROM
													    CelaPrivilegios c1
													 WHERE
													    c1.TuplaAcceso = %s AND
													    c1.Origen = %s
												    ))',
							GetSQLValueString($Params['GroupId'], 'int'),
							GetSQLValueString(1, 'int')
						);
		} else {
			$Table =    sprintf(    '(SELECT cu.id as id, cu.Nombre as Nombre,  cu.Descripci_on as Descripci_on, cu.Referencia as Referencia, cu.Prioridad as Prioridad, IF(cu.Orientaci_on=1, \'VERTICAL\', \'HORIZONTAL\' ) as Orientaci_on, c.Nombre as Icono, cu1.Nombre as Categor_ia, c1.Nombre as TipoDeElemento
									 FROM CelaMen_u cu
									     INNER JOIN CelaIcono c ON ( cu.Icono = c.id  )
										 INNER JOIN CelaMen_u cu1 ON ( cu.Categor_ia = cu1.id  )
										 INNER JOIN CelaTipoDeElemento c1 ON ( cu.TipoDeElemento = c1.id  ))',
				GetSQLValueString(1, 'int')
			);
//$Table =    sprintf(    '(SELECT cu.id as id, cu.Nombre as Nombre,  cu.Descripci_on as Descripci_on, cu.Referencia as Referencia, cu.Prioridad as Prioridad, IF(cu.Orientaci_on=1, \'VERTICAL\', \'HORIZONTAL\' ) as Orientaci_on, c.Nombre as Icono, cu1.Nombre as Categor_ia, c1.Nombre as TipoDeElemento
//									 FROM CelaMen_u cu
//									     INNER JOIN CelaIcono c ON ( cu.Icono = c.id  )
//										 INNER JOIN CelaMen_u cu1 ON ( cu.Categor_ia = cu1.id  )
//										 INNER JOIN CelaTipoDeElemento c1 ON ( cu.TipoDeElemento = c1.id  )
//									 WHERE cu.id IN (SELECT
//														c1.Tupla
//													 FROM
//													    CelaPrivilegios c1
//													 WHERE
//													    c1.Origen = %s
//												    ))',
//				GetSQLValueString(1, 'int')
//			);
		}

		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => $Table,
				'Alias' => 'CelaMen_u'
			),
			'Index'     => array(
				'IndexName' => 'CelaMen_u.id',
				'Alias' => 'id'
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
					'ColumName' => 'CelaMen_u.Nombre',
					'Alias'     => 'Nombre',
					'Extra'     => '',
					'Render'    => ''
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMen_u.Descripci_on',
					'Alias'     => 'Descripci_on',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMen_u.Referencia',
					'Alias'     => 'Referencia',
					'Extra'     => '',
					'Render'    => 'LinkMenu'
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMen_u.Categor_ia',
					'Alias'     => 'Categor_ia',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMen_u.Prioridad',
					'Alias'     => 'Prioridad',
					'Extra'     => '',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMen_u.Icono',
					'Alias'     => 'Icono',
					'Extra'     => '',
					'Render'    => 'Icon'
				),
				7 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMen_u.TipoDeElemento',
					'Alias'     => 'TipoDeElemento',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => '',
			'Group'         => '',
			'Order'         => ' CelaMen_u.Nombre ASC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 1
		);

		return $ServerQuery;
	}

	function CelaMen_uGetData($Key = null, $Filter = '') {
		global $Connection;

		if(is_array($Key)) {
			extract($Key, EXTR_OVERWRITE);
		}

		$CelaMen_uQuery =    sprintf('SELECT * FROM CelaMen_u WHERE %s IN (%s)',
			GetSQLValueString($Filter, 'SQL'),
			$Key
		);

		if($CelaMen_uResult = $Connection -> query($CelaMen_uQuery)) {
			$CelaMen_us = array();

			while($CelaMen_uRecord = $CelaMen_uResult -> fetch_assoc()) {
				$CelaMen_us[] = $CelaMen_uRecord;
			}

			$Data = array(
				'Status' => 'OK',
				'Data' => $CelaMen_us
			);
		} else {
			$Data = array(
				'Status'    => 'ERROR',
				'Error'     => $Connection -> error
			);
		}

		return $Data;
	}

	function CelaMen_uActualizar($Key, $FormData) {
		global $Connection;
		global $Privileges;

		$UpdateQuery = sprintf('UPDATE CelaMen_u
								SET `Nombre` = %s, `Descripci_on` = %s, `Referencia` = %s, `Icono` = %s, `TipoDeElemento` = %s, `Categor_ia` = %s, `Prioridad` = %s, `Orientaci_on` = %s
									WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($FormData['Referencia'], 'varchar'),
							GetSQLValueString($FormData['Icono'], 'int'),
							GetSQLValueString($FormData['TipoDeElemento'], 'int'),
							GetSQLValueString($FormData['Categor_ia'], 'int'),
							GetSQLValueString($FormData['Prioridad'], 'int'),
							GetSQLValueString($FormData['Orientaci_on'], 'int'), GetSQLValueString($Key, 'int'));

		if($UpdateResult = $Connection -> query($UpdateQuery)) {
			$Data['Status']     = 'OK';
		} else {
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaMen_uObtenCategor_iaPadre($CurrentMenu) {
		$Category       = array();

		/*Se Busca la categoria y sub categorias*/
		while(true) {
			$Data = GetValue(
						sprintf('SELECT `id`, `Categor_ia` FROM CelaMen_u WHERE `id` = %s;',
							GetSQLValueString($CurrentMenu, 'varchar')
						)
					);

			if($Data['Categor_ia'] == $Data['id'])
				break;

			$Category[]     = $Data['Categor_ia'];
			$CurrentMenu    = $Data['Categor_ia'];
		}

		return $Category;
	}

	function CelaMen_uQueryCombo($InText = false) {
		if($InText === true) {
			$Query =    sprintf('SELECT cu.`id`, CONCAT(cu.`Nombre`, %s , cu1.`Nombre`, %s)
								 FROM CelaMen_u cu
								 	INNER JOIN CelaMen_u cu1 ON ( cu.`Categor_ia` = cu1.`id` )
							     WHERE cu.`TipoDeElemento` = %s
							     ORDER BY cu1.`Nombre` ASC, cu.`Nombre` ASC',
							GetSQLValueString(' (', 'varchar'),
							GetSQLValueString(' )', 'varchar'),
							GetSQLValueString(1, 'int')
						);
		} else if($InText === false) {
			$Query =    sprintf('SELECT cu.`id`, CONCAT(cu.`Nombre`, %s , cu1.`Nombre`, %s)
								 FROM CelaMen_u cu
								 	INNER JOIN CelaMen_u cu1 ON ( cu.`Categor_ia` = cu1.`id` )
							     WHERE cu.`TipoDeElemento` = %s
							     ORDER BY cu1.`Nombre` ASC, cu.`Nombre` ASC',
							GetSQLValueString(' (', 'varchar'),
							GetSQLValueString(' )', 'varchar'),
							GetSQLValueString(1, 'int')
						);
		}

		return $Query;
	}
?>